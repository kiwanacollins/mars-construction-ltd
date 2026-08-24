<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/Admin/config/db.php';
require_once __DIR__ . '/Admin/config/pesapal.php';

$tracking_id = $_GET['OrderTrackingId'] ?? '';

if (!$tracking_id) {
    header('Location: checkout.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM orders WHERE pesapal_tracking_id = ?');
$stmt->execute([$tracking_id]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: checkout.php');
    exit;
}

$settings = pesapal_settings($pdo);
$token_result = pesapal_get_token($settings);

if (!empty($token_result['token'])) {
    $status_result = pesapal_get_transaction_status($settings, $token_result['token'], $tracking_id);
    if (!empty($status_result['status'])) {
        $payment_status = $status_result['status'] === 'completed' ? 'paid' : ($status_result['status'] === 'failed' ? 'failed' : 'unpaid');
        $order_status = $payment_status === 'paid' ? 'processing' : $order['status'];
        $pdo->prepare('UPDATE orders SET payment_status = ?, status = ? WHERE id = ?')
            ->execute([$payment_status, $order_status, $order['id']]);
    }
}

$_SESSION['order_status'] = 'placed';
$_SESSION['last_order_id'] = $order['id'];
header('Location: checkout.php');
exit;
