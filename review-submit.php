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

$type = ($_POST['reviewable_type'] ?? '') === 'project' ? 'project' : 'plan';
$id = (int) ($_POST['reviewable_id'] ?? 0);
$name = trim(strip_tags($_POST['name'] ?? ''));
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$rating = max(1, min(5, (int) ($_POST['rating'] ?? 5)));
$comment = trim(strip_tags($_POST['comment'] ?? ''));

if (!$id || !$name || !$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['review_status'] = 'error';
    header('Location: ' . $redirect);
    exit;
}

$table = $type === 'project' ? 'projects' : 'properties';
$exists = $pdo->prepare("SELECT id FROM {$table} WHERE id = ?");
$exists->execute([$id]);
if (!$exists->fetch()) {
    $_SESSION['review_status'] = 'error';
    header('Location: ' . $redirect);
    exit;
}

$insert = $pdo->prepare(
    "INSERT INTO reviews (reviewable_type, reviewable_id, name, email, rating, comment, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')"
);
$insert->execute([$type, $id, $name, $email, $rating, $comment]);

$admin_settings = [];
foreach ($pdo->query('SELECT `key`, `value` FROM site_settings') as $row) {
    $admin_settings[$row['key']] = $row['value'];
}
$notify_email = $admin_settings['notify_review_email'] ?: ($admin_settings['footer_email'] ?? null);
if ($notify_email && filter_var($notify_email, FILTER_VALIDATE_EMAIL)) {
    $item_stmt = $pdo->prepare("SELECT title FROM {$table} WHERE id = ?");
    $item_stmt->execute([$id]);
    $item_title = $item_stmt->fetchColumn() ?: '(unknown)';
    $body = "A new review is awaiting approval.\n\n"
        . "On: {$item_title} (" . ucfirst($type) . ")\n"
        . "From: {$name} ({$email})\n"
        . "Rating: {$rating}/5\n"
        . ($comment ? "Comment:\n{$comment}\n\n" : "\n")
        . "Review it in admin: notifications.php?tab=reviews";
    send_site_email($pdo, $notify_email, 'New review — Mars Construction', $body, $email);
}

$_SESSION['review_status'] = 'success';
header('Location: ' . $redirect);
exit;
