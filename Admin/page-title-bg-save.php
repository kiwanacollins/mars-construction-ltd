<?php
require_once __DIR__ . '/config/dz.php';
require_once __DIR__ . '/config/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: hero-slides.php');
    exit;
}

$page_key = trim($_POST['page_key'] ?? '');
$path = save_single_upload('page_title_bg_upload', 'branding', ['jpg', 'jpeg', 'png', 'webp']);

if ($path && $page_key !== '') {
    $stmt = $pdo->prepare('INSERT INTO page_title_backgrounds (page_key, image) VALUES (?, ?) ON DUPLICATE KEY UPDATE image = VALUES(image)');
    $stmt->execute([$page_key, $path]);
    $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Page title background updated.'];
} else {
    $_SESSION['form_status_message'] = ['type' => 'warning', 'text' => 'No image uploaded, nothing changed.'];
}

header('Location: hero-slides.php');
exit;
