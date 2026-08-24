<?php
require_once __DIR__ . '/parts/cart.php';
require_once __DIR__ . '/Admin/config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: plans.php');
    exit;
}

$property_id = (int) ($_POST['property_id'] ?? 0);
$addon_ids = array_map('intval', $_POST['addon_ids'] ?? []);
$qty = max(1, (int) ($_POST['qty'] ?? 1));

$stmt = $pdo->prepare('SELECT id, price FROM properties WHERE id = ?');
$stmt->execute([$property_id]);
$property = $stmt->fetch();

if ($property) {
    $selected = [];
    if ($addon_ids) {
        $in = implode(',', array_fill(0, count($addon_ids), '?'));
        $astmt = $pdo->prepare("SELECT * FROM plan_addons WHERE property_id = ? AND id IN ($in)");
        $astmt->execute(array_merge([$property_id], $addon_ids));
        $selected = $astmt->fetchAll();
    }

    $flat_total = 0;
    $percent_total = 0;
    foreach ($selected as $addon) {
        if ($addon['price_type'] === 'percent') {
            $percent_total += (float) $addon['price'];
        } else {
            $flat_total += (float) $addon['price'];
        }
    }

    $price = (float) $property['price'] + $flat_total + ($flat_total * $percent_total / 100);
    cart_add($property_id, array_column($selected, 'id'), $price, $qty);
}

$redirect = $_POST['redirect'] ?? 'plans.php';
if (!preg_match('#^[a-zA-Z0-9_\-]+\.php(\?[a-zA-Z0-9_=&%.\-]*)?$#', $redirect)) {
    $redirect = 'plans.php';
}

header('Location: ' . $redirect);
exit;
