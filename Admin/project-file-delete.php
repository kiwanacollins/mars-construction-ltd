<?php
require_once __DIR__ . '/config/dz.php';

$id = (int) ($_GET['id'] ?? 0);
$project_id = (int) ($_GET['project_id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM project_files WHERE id = ?');
$stmt->execute([$id]);
$file = $stmt->fetch();

if ($file) {
    $full_path = __DIR__ . '/' . $file['file_path'];
    if (is_file($full_path)) {
        @unlink($full_path);
    }
    $del = $pdo->prepare('DELETE FROM project_files WHERE id = ?');
    $del->execute([$id]);
}

header('Location: add-project.php?id=' . $project_id);
exit;
