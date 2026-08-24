<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/Admin/config/db.php';
require_once __DIR__ . '/Admin/config/pesapal.php';

$order_id = (int) ($_GET['order_id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order || !isset($_SESSION['pesapal_pending_order']) || (int) $_SESSION['pesapal_pending_order'] !== $order_id) {
    header('Location: checkout.php');
    exit;
}

$settings = pesapal_settings($pdo);

if (!pesapal_is_configured($settings)) {
    // Payments aren't set up yet — fall back to the "we'll follow up" flow.
    unset($_SESSION['pesapal_pending_order']);
    $_SESSION['order_status'] = 'placed';
    $_SESSION['last_order_id'] = $order['id'];
    header('Location: checkout.php');
    exit;
}

$token_result = pesapal_get_token($settings);
if (empty($token_result['token'])) {
    $_SESSION['order_status'] = 'placed';
    $_SESSION['last_order_id'] = $order['id'];
    $_SESSION['order_status_note'] = 'We could not reach the payment provider, but your order was recorded — our team will follow up by email.';
    unset($_SESSION['pesapal_pending_order']);
    header('Location: checkout.php');
    exit;
}

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$callback_url = $scheme . '://' . $_SERVER['HTTP_HOST'] . '/pesapal-callback.php';
$merchant_ref = 'MARS-' . $order['id'] . '-' . time();

$submit_result = pesapal_submit_order($settings, $token_result['token'], [
    'merchant_reference' => $merchant_ref,
    'currency' => 'USD',
    'amount' => (float) $order['total'],
    'description' => 'Mars Construction order #' . $order['id'],
    'callback_url' => $callback_url,
    'name' => $order['name'],
    'email' => $order['email'],
    'phone' => $order['phone'],
]);

if (empty($submit_result['redirect_url'])) {
    $_SESSION['order_status'] = 'placed';
    $_SESSION['last_order_id'] = $order['id'];
    $_SESSION['order_status_note'] = 'We could not start the online payment, but your order was recorded — our team will follow up by email.';
    unset($_SESSION['pesapal_pending_order']);
    header('Location: checkout.php');
    exit;
}

$pdo->prepare('UPDATE orders SET pesapal_tracking_id = ?, pesapal_merchant_ref = ?, payment_method = ? WHERE id = ?')
    ->execute([$submit_result['order_tracking_id'], $merchant_ref, 'pesapal', $order['id']]);

unset($_SESSION['pesapal_pending_order']);
header('Location: ' . $submit_result['redirect_url']);
exit;
