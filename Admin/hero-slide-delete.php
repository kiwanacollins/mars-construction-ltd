<?php
require_once __DIR__ . '/config/dz.php';

if (!empty($_GET['id'])) {
    $stmt = $pdo->prepare('DELETE FROM hero_slides WHERE id = ?');
    $stmt->execute([(int) $_GET['id']]);
    $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Slide deleted.'];
}

header('Location: hero-slides.php?tab=slides');
exit;
