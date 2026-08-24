<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../Admin/config/db.php';
}
$plan_categories_bar = $pdo->query('SELECT * FROM plan_categories ORDER BY sort_order, name')->fetchAll();
$active_category = $_GET['category'] ?? '';
?>
<div class="category-bar">
	<div class="auto-container">
		<ul class="category-bar_list">
			<li class="<?php echo $active_category === '' ? 'active' : ''; ?>"><a href="plans.php">House Plans</a></li>
			<?php foreach ($plan_categories_bar as $cat): ?>
				<li class="<?php echo $active_category === $cat['name'] ? 'active' : ''; ?>"><a href="plans.php?category=<?php echo urlencode($cat['name']); ?>"><?php echo htmlspecialchars($cat['name']); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
