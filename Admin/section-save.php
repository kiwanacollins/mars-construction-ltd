<?php
require_once __DIR__ . '/config/dz.php';
require_once __DIR__ . '/config/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: sections.php');
    exit;
}

$registry = require __DIR__ . '/config/section_registry.php';

$page_key = $_POST['page_key'] ?? '';
$section_key = $_POST['section_key'] ?? '';

if (!isset($registry[$page_key]['sections'][$section_key])) {
    header('Location: sections.php');
    exit;
}

$image_ext = ['jpg', 'jpeg', 'png', 'webp'];
$uploaded_image = save_single_upload('image', 'branding', $image_ext);
$image = $uploaded_image ?: trim($_POST['existing_image'] ?? '');

$uploaded_image2 = save_single_upload('image2', 'branding', $image_ext);
$image2 = $uploaded_image2 ?: trim($_POST['existing_image2'] ?? '');

$data = [
    'page_key' => $page_key,
    'section_key' => $section_key,
    'heading' => trim($_POST['heading'] ?? ''),
    'subheading' => trim($_POST['subheading'] ?? ''),
    'body' => trim($_POST['body'] ?? ''),
    'image' => $image,
    'image2' => $image2,
    'check1' => trim($_POST['check1'] ?? ''),
    'check2' => trim($_POST['check2'] ?? ''),
    'list1' => trim($_POST['list1'] ?? ''),
    'list2' => trim($_POST['list2'] ?? ''),
    'button_text' => trim($_POST['button_text'] ?? ''),
    'button_link' => trim($_POST['button_link'] ?? ''),
];

$stmt = $pdo->prepare(
    'INSERT INTO page_sections (page_key, section_key, heading, subheading, body, image, image2, check1, check2, list1, list2, button_text, button_link)
     VALUES (:page_key, :section_key, :heading, :subheading, :body, :image, :image2, :check1, :check2, :list1, :list2, :button_text, :button_link)
     ON DUPLICATE KEY UPDATE heading = VALUES(heading), subheading = VALUES(subheading), body = VALUES(body),
        image = VALUES(image), image2 = VALUES(image2), check1 = VALUES(check1), check2 = VALUES(check2),
        list1 = VALUES(list1), list2 = VALUES(list2), button_text = VALUES(button_text), button_link = VALUES(button_link)'
);
$stmt->execute($data);

$_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Section updated.'];
header('Location: sections.php?page=' . urlencode($page_key));
exit;
