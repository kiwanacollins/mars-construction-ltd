<?php
require_once __DIR__ . '/config/dz.php';

$id = (int) ($_GET['id'] ?? 0);
if ($id) {
    $stmt = $pdo->prepare('SELECT file_path FROM property_files WHERE property_id = ?');
    $stmt->execute([$id]);
    foreach ($stmt->fetchAll() as $f) {
        $full = __DIR__ . '/' . $f['file_path'];
        if (is_file($full)) {
            @unlink($full);
        }
    }
    $del = $pdo->prepare('DELETE FROM properties WHERE id = ?');
    $del->execute([$id]);
    $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Plan deleted.'];
}

header('Location: property-list.php');
exit;
