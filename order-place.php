<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/Admin/config/db.php';
require_once __DIR__ . '/Admin/config/mailer.php';
require_once __DIR__ . '/parts/cart.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit;
}

if (
    !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    http_response_code(403);
    exit('Invalid CSRF token');
}

$name = trim(strip_tags($_POST['name'] ?? ''));
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$phone = trim(strip_tags($_POST['phone'] ?? ''));

$cart_items = cart_lines($pdo);

if (!$name || !$email || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$cart_items) {
    header('Location: checkout.php');
    exit;
}

$total = 0;
foreach ($cart_items as $ci) {
    $total += $ci['price'] * $ci['qty'];
}

$insert_order = $pdo->prepare('INSERT INTO orders (name, email, phone, total, status) VALUES (?, ?, ?, ?, ?)');
$insert_order->execute([$name, $email, $phone, $total, 'new']);
$order_id = (int) $pdo->lastInsertId();

$insert_item = $pdo->prepare('INSERT INTO order_items (order_id, property_id, plan_title, addon_names, price, qty) VALUES (?, ?, ?, ?, ?, ?)');
$summary_lines = [];
foreach ($cart_items as $ci) {
    $addon_names = $ci['addons'] ? implode(', ', array_column($ci['addons'], 'name')) : '';
    $insert_item->execute([$order_id, $ci['property']['id'], $ci['property']['title'], $addon_names, $ci['price'], $ci['qty']]);
    $summary_lines[] = "- {$ci['property']['title']}" . ($addon_names ? " ({$addon_names})" : '') . " x{$ci['qty']} - $" . number_format($ci['price'], 0);
}

// Notify the admin by email
$admin_settings = [];
foreach ($pdo->query('SELECT `key`, `value` FROM site_settings') as $row) {
    $admin_settings[$row['key']] = $row['value'];
}
$admin_email = $admin_settings['notify_order_email'] ?: ($admin_settings['footer_email'] ?? null);
if ($admin_email && filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
    $subject = "New order #{$order_id} - Mars Construction";
    $body = "A new order has been placed.\n\n"
        . "Order #: {$order_id}\n"
        . "Customer: {$name}\n"
        . "Email: {$email}\n"
        . "Phone: {$phone}\n"
        . "Total: $" . number_format($total, 0) . "\n\n"
        . "Items:\n" . implode("\n", $summary_lines) . "\n\n"
        . "View in admin: order-view.php?id={$order_id}";
    send_site_email($pdo, $admin_email, $subject, $body, $email);
}

// Clear the cart now that the order has been recorded
unset($_SESSION['cart']);
$_SESSION['pesapal_pending_order'] = $order_id;
header('Location: pesapal-pay.php?order_id=' . $order_id);
exit;
