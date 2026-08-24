<?php
require_once __DIR__ . '/config/dz.php';
require_once __DIR__ . '/config/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: project-list.php');
    exit;
}

$id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
$title = trim($_POST['title'] ?? '');

if ($title === '') {
    $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'Project title is required.'];
    header('Location: add-project.php' . ($id ? "?id={$id}" : ''));
    exit;
}

$data = [
    'title' => $title,
    'category' => trim($_POST['category'] ?? ''),
    'location' => trim($_POST['location'] ?? ''),
    'client_name' => trim($_POST['client_name'] ?? ''),
    'completed_date' => $_POST['completed_date'] !== '' ? $_POST['completed_date'] : null,
    'story' => trim($_POST['story'] ?? ''),
    'featured' => isset($_POST['featured']) ? 1 : 0,
];

if ($id) {
    $data['slug'] = unique_slug($pdo, 'projects', $title, $id);
    $sql = 'UPDATE projects SET title=:title, slug=:slug, category=:category, location=:location,
            client_name=:client_name, completed_date=:completed_date, story=:story, featured=:featured
            WHERE id=:id';
    $data['id'] = $id;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
    $project_id = $id;
} else {
    $data['slug'] = unique_slug($pdo, 'projects', $title);
    $sql = 'INSERT INTO projects (title, slug, category, location, client_name, completed_date, story, featured)
            VALUES (:title, :slug, :category, :location, :client_name, :completed_date, :story, :featured)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
    $project_id = (int) $pdo->lastInsertId();
}

$image_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$images = save_uploaded_files('images', 'projects', $image_ext);

$has_cover_stmt = $pdo->prepare('SELECT COUNT(*) c FROM project_files WHERE project_id = ?');
$has_cover_stmt->execute([$project_id]);
$has_existing_images = (int) $has_cover_stmt->fetch()['c'] > 0;

$insert_file = $pdo->prepare('INSERT INTO project_files (project_id, file_path, original_name, is_cover) VALUES (?, ?, ?, ?)');
foreach ($images as $i => $img) {
    $is_cover = (!$has_existing_images && $i === 0) ? 1 : 0;
    $insert_file->execute([$project_id, $img['path'], $img['original_name'], $is_cover]);
}

$_SESSION['form_status_message'] = ['type' => 'success', 'text' => $id ? 'Project updated.' : 'Project created.'];
header('Location: project-list.php');
exit;
