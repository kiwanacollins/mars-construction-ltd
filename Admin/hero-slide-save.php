<?php
require_once __DIR__ . '/config/dz.php';
require_once __DIR__ . '/config/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: hero-slides.php?tab=slides');
    exit;
}

$id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
$heading = trim($_POST['heading'] ?? '');

if ($heading === '') {
    $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'Heading is required.'];
    header('Location: hero-slide-edit.php' . ($id ? "?id={$id}" : ''));
    exit;
}

if (!$id) {
    $count = (int) $pdo->query('SELECT COUNT(*) c FROM hero_slides')->fetch()['c'];
    if ($count >= 5) {
        $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'Maximum of 5 slides reached. Delete one before adding another.'];
        header('Location: hero-slides.php?tab=slides');
        exit;
    }
}

$uploaded = save_single_upload('slide_image', 'branding', ['jpg', 'jpeg', 'png', 'webp']);
$image = $uploaded ?: trim($_POST['existing_image'] ?? '');

$bg_type = in_array($_POST['bg_type'] ?? '', ['image', 'video'], true) ? $_POST['bg_type'] : 'image';

$data = [
    'heading' => $heading,
    'subheading' => trim($_POST['subheading'] ?? ''),
    'description' => trim($_POST['description'] ?? ''),
    'button_text' => trim($_POST['button_text'] ?? ''),
    'button_link' => trim($_POST['button_link'] ?? ''),
    'button2_text' => trim($_POST['button2_text'] ?? ''),
    'button2_link' => trim($_POST['button2_link'] ?? ''),
    'image' => $image,
    'bg_type' => $bg_type,
    'video_url' => trim($_POST['video_url'] ?? ''),
];

if ($id) {
    $data['id'] = $id;
    $stmt = $pdo->prepare('UPDATE hero_slides SET heading=:heading, subheading=:subheading, description=:description, button_text=:button_text, button_link=:button_link, button2_text=:button2_text, button2_link=:button2_link, image=:image, bg_type=:bg_type, video_url=:video_url WHERE id=:id');
    $stmt->execute($data);
    $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Slide updated.'];
} else {
    $max = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), -1) m FROM hero_slides')->fetch()['m'];
    $data['sort_order'] = $max + 1;
    $stmt = $pdo->prepare('INSERT INTO hero_slides (heading, subheading, description, button_text, button_link, button2_text, button2_link, image, bg_type, video_url, sort_order) VALUES (:heading, :subheading, :description, :button_text, :button_link, :button2_text, :button2_link, :image, :bg_type, :video_url, :sort_order)');
    $stmt->execute($data);
    $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Slide added.'];
}

header('Location: hero-slides.php?tab=slides');
exit;
