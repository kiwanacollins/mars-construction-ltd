<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

function current_admin() {
    return $_SESSION['admin'] ?? null;
}

function require_login() {
    if (!current_admin()) {
        $self = basename($_SERVER['PHP_SELF']);
        header('Location: page-login.php?redirect=' . urlencode($self));
        exit;
    }
}
