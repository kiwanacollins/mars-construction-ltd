<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function cart_key($property_id, $addon_ids) {
    $variant = $addon_ids ? implode('_', $addon_ids) : 'base';
    return $property_id . '-' . $variant;
}

function cart_count() {
    $total = 0;
    foreach ($_SESSION['cart'] as $line) {
        $total += (int) $line['qty'];
    }
    return $total;
}

function cart_add($property_id, $addon_ids, $price, $qty = 1) {
    $addon_ids = array_values(array_unique(array_map('intval', (array) $addon_ids)));
    sort($addon_ids);
    $key = cart_key($property_id, $addon_ids);
    if (isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key]['qty'] += $qty;
    } else {
        $_SESSION['cart'][$key] = ['property_id' => $property_id, 'addon_ids' => $addon_ids, 'price' => $price, 'qty' => $qty];
    }
}

function cart_remove($key) {
    unset($_SESSION['cart'][$key]);
}

function cart_lines($pdo) {
    $lines = [];
    foreach ($_SESSION['cart'] as $key => $line) {
        $stmt = $pdo->prepare(
            "SELECT p.id, p.title, p.slug, p.price,
                (SELECT file_path FROM property_files pf WHERE pf.property_id = p.id ORDER BY pf.is_cover DESC, pf.id ASC LIMIT 1) AS cover_image
             FROM properties p WHERE p.id = ?"
        );
        $stmt->execute([$line['property_id']]);
        $property = $stmt->fetch();
        if (!$property) {
            continue;
        }
        $addons = [];
        if (!empty($line['addon_ids'])) {
            $in = implode(',', array_fill(0, count($line['addon_ids']), '?'));
            $astmt = $pdo->prepare("SELECT * FROM plan_addons WHERE id IN ($in)");
            $astmt->execute($line['addon_ids']);
            $addons = $astmt->fetchAll();
        }
        $lines[] = [
            'key' => $key,
            'property' => $property,
            'addons' => $addons,
            'qty' => $line['qty'],
            'price' => $line['price'],
        ];
    }
    return $lines;
}
