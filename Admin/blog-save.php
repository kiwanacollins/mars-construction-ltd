<?php
require_once __DIR__ . '/config/dz.php';
require_once __DIR__ . '/config/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: blog.php');
    exit;
}

$id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
$title = trim($_POST['title'] ?? '');

if ($title === '') {
    $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'Title is required.'];
    header('Location: add-blog.php' . ($id ? "?id={$id}" : ''));
    exit;
}

$slug_input = trim($_POST['slug'] ?? '');
$slug_base = $slug_input !== '' ? $slug_input : $title;

$data = [
    'title' => $title,
    'excerpt' => trim($_POST['excerpt'] ?? ''),
    'body' => $_POST['body'] ?? '',
    'category_id' => !empty($_POST['category_id']) ? (int) $_POST['category_id'] : null,
    'author_id' => !empty($_POST['author_id']) ? (int) $_POST['author_id'] : null,
    'status' => in_array($_POST['status'] ?? '', ['published', 'draft', 'pending'], true) ? $_POST['status'] : 'draft',
    'published_at' => !empty($_POST['published_at']) ? $_POST['published_at'] : null,
];

$image_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$uploaded = save_uploaded_files('featured_image', 'blog', $image_ext);

if ($id) {
    $data['slug'] = unique_slug($pdo, 'blog_posts', $slug_base, $id);
    $data['id'] = $id;
    $sql = 'UPDATE blog_posts SET title=:title, slug=:slug, excerpt=:excerpt, body=:body, category_id=:category_id,
            author_id=:author_id, status=:status, published_at=:published_at';
    if ($uploaded) {
        $sql .= ', featured_image=:featured_image';
        $data['featured_image'] = $uploaded[0]['path'];
    }
    $sql .= ' WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
} else {
    $data['slug'] = unique_slug($pdo, 'blog_posts', $slug_base);
    $data['featured_image'] = $uploaded ? $uploaded[0]['path'] : null;
    $sql = 'INSERT INTO blog_posts (title, slug, excerpt, body, category_id, author_id, status, published_at, featured_image)
            VALUES (:title, :slug, :excerpt, :body, :category_id, :author_id, :status, :published_at, :featured_image)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
}

$_SESSION['form_status_message'] = ['type' => 'success', 'text' => $id ? 'Post updated.' : 'Post created.'];
header('Location: blog.php');
exit;
