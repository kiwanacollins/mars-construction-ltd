<?php
require_once __DIR__ . '/config/dz.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: menu-items.php');
    exit;
}

$id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
$label = trim($_POST['label'] ?? '');
$url = trim($_POST['url'] ?? '#') ?: '#';
$icon = trim($_POST['icon'] ?? '');
$parent_id = !empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;
$sort_order = (int) ($_POST['sort_order'] ?? 0);

if ($label === '') {
    $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'Label is required.'];
    header('Location: add-menu-item.php' . ($id ? "?id={$id}" : ''));
    exit;
}

if ($id && $parent_id === $id) {
    $parent_id = null;
}

if ($id) {
    $stmt = $pdo->prepare('UPDATE menu_items SET label=?, url=?, icon=?, parent_id=?, sort_order=? WHERE id=?');
    $stmt->execute([$label, $url, $icon ?: null, $parent_id, $sort_order, $id]);
} else {
    $stmt = $pdo->prepare('INSERT INTO menu_items (label, url, icon, parent_id, sort_order) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$label, $url, $icon ?: null, $parent_id, $sort_order]);
}

$_SESSION['form_status_message'] = ['type' => 'success', 'text' => $id ? 'Menu item updated.' : 'Menu item created.'];
header('Location: menu-items.php');
exit;
