<?php
require_once __DIR__ . '/parts/cart.php';

if (!empty($_GET['key'])) {
    cart_remove($_GET['key']);
}

$redirect = $_GET['redirect'] ?? 'plans.php';
if (!preg_match('#^[a-zA-Z0-9_\-]+\.php(\?[a-zA-Z0-9_=&%.\-]*)?$#', $redirect)) {
    $redirect = 'plans.php';
}

header('Location: ' . $redirect);
exit;
