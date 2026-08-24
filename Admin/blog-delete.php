<?php
require_once __DIR__ . '/config/dz.php';

$id = (int) ($_GET['id'] ?? 0);
if ($id) {
    $stmt = $pdo->prepare('SELECT featured_image FROM blog_posts WHERE id = ?');
    $stmt->execute([$id]);
    $post = $stmt->fetch();
    if ($post && $post['featured_image']) {
        $full = __DIR__ . '/' . $post['featured_image'];
        if (is_file($full)) {
            @unlink($full);
        }
    }
    $del = $pdo->prepare('DELETE FROM blog_posts WHERE id = ?');
    $del->execute([$id]);
    $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Post deleted.'];
}

header('Location: blog.php');
exit;
