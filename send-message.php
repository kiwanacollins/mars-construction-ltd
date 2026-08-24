<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/Admin/config/db.php';
require_once __DIR__ . '/Admin/config/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (
    !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    http_response_code(403);
    exit('Invalid CSRF token');
}

$redirect = $_POST['redirect'] ?? 'index.php';
if (!preg_match('#^[a-zA-Z0-9_\-]+\.php(\?[a-zA-Z0-9_=&%.\-]*)?$#', $redirect)) {
    $redirect = 'index.php';
}

$name = trim(strip_tags($_POST['name'] ?? $_POST['username'] ?? ''));
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$phone = trim(strip_tags($_POST['phone'] ?? ''));
$subject = trim(strip_tags($_POST['subject'] ?? ''));
$services = trim(strip_tags($_POST['country'] ?? $_POST['services'] ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));
$property_id = !empty($_POST['property_id']) ? (int) $_POST['property_id'] : null;
$project_id = !empty($_POST['project_id']) ? (int) $_POST['project_id'] : null;

if (!$name || !$email || !$message || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['form_status'] = 'error';
    header('Location: ' . $redirect);
    exit;
}

$insert = $pdo->prepare(
    'INSERT INTO messages (name, email, phone, subject, services, property_id, project_id, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
);
$insert->execute([$name, $email, $phone, $subject, $services, $property_id, $project_id, $message]);

$admin_settings = [];
foreach ($pdo->query('SELECT `key`, `value` FROM site_settings') as $row) {
    $admin_settings[$row['key']] = $row['value'];
}
$notify_email = $admin_settings['notify_message_email'] ?: ($admin_settings['footer_email'] ?? null);
if ($notify_email && filter_var($notify_email, FILTER_VALIDATE_EMAIL)) {
    $body = "A new inquiry has been submitted.\n\n"
        . "Name: {$name}\n"
        . "Email: {$email}\n"
        . ($phone ? "Phone: {$phone}\n" : '')
        . ($subject ? "Subject: {$subject}\n" : '')
        . "\nMessage:\n{$message}\n\n"
        . "View in admin: notifications.php?tab=messages";
    send_site_email($pdo, $notify_email, 'New inquiry — Mars Construction', $body, $email);
}

$_SESSION['form_status'] = 'success';
header('Location: ' . $redirect);
exit;
