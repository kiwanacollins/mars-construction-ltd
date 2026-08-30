<?php
require_once __DIR__ . '/Admin/config/db.php';
require_once __DIR__ . '/parts/property-card.php';

$offset = isset($_GET['offset']) ? max(0, (int) $_GET['offset']) : 0;
$limit = 8;

$plans = fetch_properties_page($pdo, $offset, $limit);

header('Content-Type: text/html; charset=utf-8');
foreach ($plans as $plan) {
    echo render_property_card($plan);
}
