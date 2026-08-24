<?php
require_once __DIR__ . '/config/dz.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: all-users.php');
    exit;
}

$id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$role = in_array($_POST['role'] ?? '', ['admin', 'editor'], true) ? $_POST['role'] : 'admin';
$password = $_POST['password'] ?? '';

if ($name === '' || $email === '' || (!$id && $password === '')) {
    $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'Name, email, and password are required.'];
    header('Location: add-user.php' . ($id ? "?id={$id}" : ''));
    exit;
}

$dupe = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
$dupe->execute([$email, $id ?? 0]);
if ($dupe->fetch()) {
    $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'That email is already in use by another user.'];
    header('Location: add-user.php' . ($id ? "?id={$id}" : ''));
    exit;
}

if ($id) {
    if ($password !== '') {
        $stmt = $pdo->prepare('UPDATE users SET name=?, email=?, role=?, password_hash=? WHERE id=?');
        $stmt->execute([$name, $email, $role, password_hash($password, PASSWORD_DEFAULT), $id]);
    } else {
        $stmt = $pdo->prepare('UPDATE users SET name=?, email=?, role=? WHERE id=?');
        $stmt->execute([$name, $email, $role, $id]);
    }
} else {
    $stmt = $pdo->prepare('INSERT INTO users (name, email, role, password_hash) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $email, $role, password_hash($password, PASSWORD_DEFAULT)]);
}

$_SESSION['form_status_message'] = ['type' => 'success', 'text' => $id ? 'User updated.' : 'User created.'];
header('Location: all-users.php');
exit;
