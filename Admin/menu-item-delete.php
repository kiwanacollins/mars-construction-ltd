<?php
require_once __DIR__ . '/config/dz.php';

$id = (int) ($_GET['id'] ?? 0);
if ($id) {
    $del = $pdo->prepare('DELETE FROM menu_items WHERE id = ?');
    $del->execute([$id]);
    $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Menu item deleted.'];
}

header('Location: menu-items.php');
exit;
