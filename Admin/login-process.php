<?php
require_once __DIR__ . '/config/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: page-login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$redirect = $_POST['redirect'] ?? 'index.php';
if (!preg_match('/^[a-zA-Z0-9_.\-]+\.php$/', $redirect)) {
    $redirect = 'index.php';
}

$stmt = $pdo->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
    session_regenerate_id(true);
    $_SESSION['admin'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'],
    ];
    header('Location: ' . $redirect);
    exit;
}

$_SESSION['login_error'] = 'Invalid email or password.';
header('Location: page-login.php');
exit;
