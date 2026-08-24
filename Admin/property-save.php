<?php
require_once __DIR__ . '/config/dz.php';
require_once __DIR__ . '/config/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: property-list.php');
    exit;
}

$id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
$title = trim($_POST['title'] ?? '');
$price = (float) ($_POST['price'] ?? 0);

if ($title === '') {
    $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'Plan title is required.'];
    header('Location: add-property.php' . ($id ? "?id={$id}" : ''));
    exit;
}

$data = [
    'title' => $title,
    'price' => $price,
    'plan_number' => trim($_POST['plan_number'] ?? ''),
    'bedrooms' => (int) ($_POST['bedrooms'] ?? 0),
    'bathrooms' => (float) ($_POST['bathrooms'] ?? 0),
    'stories' => (int) ($_POST['stories'] ?? 1),
    'garage_bays' => (int) ($_POST['garage_bays'] ?? 0),
    'area_sqft' => (float) ($_POST['area_sqft'] ?? 0),
    'width_ft' => $_POST['width_ft'] !== '' ? (float) $_POST['width_ft'] : null,
    'depth_ft' => $_POST['depth_ft'] !== '' ? (float) $_POST['depth_ft'] : null,
    'foundation_type' => trim($_POST['foundation_type'] ?? ''),
    'roof_type' => trim($_POST['roof_type'] ?? ''),
    'roof_pitch' => trim($_POST['roof_pitch'] ?? ''),
    'exterior_material' => trim($_POST['exterior_material'] ?? ''),
    'category' => trim($_POST['category'] ?? ''),
    'video_url' => trim($_POST['video_url'] ?? ''),
    'featured' => isset($_POST['featured']) ? 1 : 0,
    'description' => trim($_POST['description'] ?? ''),
    'features' => json_encode($_POST['features'] ?? []),
];

if ($id) {
    $data['slug'] = unique_slug($pdo, 'properties', $title, $id);
    $sql = 'UPDATE properties SET title=:title, slug=:slug, price=:price, plan_number=:plan_number,
            bedrooms=:bedrooms, bathrooms=:bathrooms, stories=:stories, garage_bays=:garage_bays,
            area_sqft=:area_sqft, width_ft=:width_ft, depth_ft=:depth_ft, foundation_type=:foundation_type,
            roof_type=:roof_type, roof_pitch=:roof_pitch, exterior_material=:exterior_material,
            category=:category, video_url=:video_url, featured=:featured, description=:description,
            features=:features WHERE id=:id';
    $data['id'] = $id;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
    $property_id = $id;
} else {
    $data['slug'] = unique_slug($pdo, 'properties', $title);
    $sql = 'INSERT INTO properties (title, slug, price, plan_number, bedrooms, bathrooms, stories, garage_bays,
            area_sqft, width_ft, depth_ft, foundation_type, roof_type, roof_pitch, exterior_material,
            category, video_url, featured, description, features)
            VALUES (:title, :slug, :price, :plan_number, :bedrooms, :bathrooms, :stories, :garage_bays,
            :area_sqft, :width_ft, :depth_ft, :foundation_type, :roof_type, :roof_pitch, :exterior_material,
            :category, :video_url, :featured, :description, :features)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
    $property_id = (int) $pdo->lastInsertId();
}

// Replace pricing tiers
$del_tiers = $pdo->prepare('DELETE FROM plan_pricing WHERE property_id = ?');
$del_tiers->execute([$property_id]);

$tier_names = $_POST['tier_name'] ?? [];
$tier_prices = $_POST['tier_price'] ?? [];
$tier_descriptions = $_POST['tier_description'] ?? [];
$existing_tier_files = $_POST['existing_tier_file'] ?? [];
$tier_file_ext = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'dwg', 'dxf'];
$insert_tier = $pdo->prepare('INSERT INTO plan_pricing (property_id, tier_name, price, description, file_path, sort_order) VALUES (?, ?, ?, ?, ?, ?)');
foreach ($tier_names as $i => $name) {
    $name = trim($name);
    $tprice = $tier_prices[$i] ?? '';
    if ($name === '' || $tprice === '') {
        continue;
    }
    $uploaded_tier_file = save_indexed_upload('tier_file', $i, 'properties', $tier_file_ext);
    $tier_file = $uploaded_tier_file ?: trim($existing_tier_files[$i] ?? '');
    $insert_tier->execute([$property_id, $name, (float) $tprice, trim($tier_descriptions[$i] ?? ''), $tier_file, $i]);
}

// Replace customizable add-ons
$del_addons = $pdo->prepare('DELETE FROM plan_addons WHERE property_id = ?');
$del_addons->execute([$property_id]);

$addon_names = $_POST['addon_name'] ?? [];
$addon_descriptions = $_POST['addon_description'] ?? [];
$addon_prices = $_POST['addon_price'] ?? [];
$addon_types = $_POST['addon_type'] ?? [];
$insert_addon = $pdo->prepare('INSERT INTO plan_addons (property_id, name, description, price, price_type, sort_order) VALUES (?, ?, ?, ?, ?, ?)');
foreach ($addon_names as $i => $name) {
    $name = trim($name);
    $aprice = $addon_prices[$i] ?? '';
    if ($name === '' || $aprice === '') {
        continue;
    }
    $atype = ($addon_types[$i] ?? 'flat') === 'percent' ? 'percent' : 'flat';
    $insert_addon->execute([$property_id, $name, trim($addon_descriptions[$i] ?? ''), (float) $aprice, $atype, $i]);
}

$image_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$doc_ext = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'dwg', 'dxf', 'txt'];

$images = save_uploaded_files('images', 'properties', $image_ext);
$docs = save_uploaded_files('documents', 'properties', $doc_ext);

$has_cover_stmt = $pdo->prepare("SELECT COUNT(*) c FROM property_files WHERE property_id = ? AND file_type = 'image'");
$has_cover_stmt->execute([$property_id]);
$has_existing_images = (int) $has_cover_stmt->fetch()['c'] > 0;

$insert_file = $pdo->prepare('INSERT INTO property_files (property_id, file_path, original_name, file_type, is_cover) VALUES (?, ?, ?, ?, ?)');
foreach ($images as $i => $img) {
    $is_cover = (!$has_existing_images && $i === 0) ? 1 : 0;
    $insert_file->execute([$property_id, $img['path'], $img['original_name'], 'image', $is_cover]);
}
foreach ($docs as $doc) {
    $insert_file->execute([$property_id, $doc['path'], $doc['original_name'], 'document', 0]);
}

$featured_image = save_single_upload('featured_image', 'properties', $image_ext);
if ($featured_image) {
    $pdo->prepare("UPDATE property_files SET is_cover = 0 WHERE property_id = ? AND file_type = 'image'")->execute([$property_id]);
    $insert_cover = $pdo->prepare('INSERT INTO property_files (property_id, file_path, original_name, file_type, is_cover) VALUES (?, ?, ?, ?, 1)');
    $insert_cover->execute([$property_id, $featured_image, basename($featured_image), 'image']);
}

$_SESSION['form_status_message'] = ['type' => 'success', 'text' => $id ? 'Plan updated.' : 'Plan created.'];
header('Location: property-list.php');
exit;
