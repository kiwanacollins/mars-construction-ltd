<?php
require_once __DIR__ . '/config/dz.php';

$id = (int) ($_GET['id'] ?? 0);
$current_id = (int) (current_admin()['id'] ?? 0);

if ($id && $id !== $current_id) {
    $del = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $del->execute([$id]);
    $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'User deleted.'];
} elseif ($id === $current_id) {
    $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'You cannot delete your own account.'];
}

header('Location: all-users.php');
exit;
