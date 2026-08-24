<?php
require_once __DIR__ . '/config/dz.php';

$id = (int) ($_GET['id'] ?? 0);
$property_id = (int) ($_GET['property_id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM property_files WHERE id = ?');
$stmt->execute([$id]);
$file = $stmt->fetch();

if ($file) {
    $full_path = __DIR__ . '/' . $file['file_path'];
    if (is_file($full_path)) {
        @unlink($full_path);
    }
    $del = $pdo->prepare('DELETE FROM property_files WHERE id = ?');
    $del->execute([$id]);
}

header('Location: add-property.php?id=' . $property_id);
exit;
