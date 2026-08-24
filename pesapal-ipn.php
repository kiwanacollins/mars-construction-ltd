<?php
require_once __DIR__ . '/Admin/config/db.php';
require_once __DIR__ . '/Admin/config/pesapal.php';

header('Content-Type: application/json');

$tracking_id = $_GET['OrderTrackingId'] ?? '';
$merchant_ref = $_GET['OrderMerchantReference'] ?? '';
$notification_type = $_GET['OrderNotificationType'] ?? 'IPNCHANGE';

$respond = function ($status_code) use ($tracking_id, $merchant_ref, $notification_type) {
    echo json_encode([
        'orderNotificationType' => $notification_type,
        'orderTrackingId' => $tracking_id,
        'orderMerchantReference' => $merchant_ref,
        'status' => $status_code,
    ]);
    exit;
};

if (!$tracking_id) {
    $respond(500);
}

$stmt = $pdo->prepare('SELECT * FROM orders WHERE pesapal_tracking_id = ?');
$stmt->execute([$tracking_id]);
$order = $stmt->fetch();

if (!$order) {
    $respond(500);
}

$settings = pesapal_settings($pdo);
$token_result = pesapal_get_token($settings);

if (empty($token_result['token'])) {
    $respond(500);
}

$status_result = pesapal_get_transaction_status($settings, $token_result['token'], $tracking_id);

if (empty($status_result['status'])) {
    $respond(500);
}

$payment_status = $status_result['status'] === 'completed' ? 'paid' : ($status_result['status'] === 'failed' ? 'failed' : 'unpaid');
$order_status = $payment_status === 'paid' ? 'processing' : $order['status'];
$pdo->prepare('UPDATE orders SET payment_status = ?, status = ? WHERE id = ?')
    ->execute([$payment_status, $order_status, $order['id']]);

$respond(200);
