<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../Admin/config/db.php';
}
$plan_categories_bar = $pdo->query('SELECT * FROM plan_categories ORDER BY sort_order, name')->fetchAll();
$active_category = $_GET['category'] ?? '';
?>
<div class="category-bar">
	<div class="auto-container">
		<div class="category-bar_inner">
			<ul class="category-bar_list">
				<li class="category-bar_home<?php echo $current_page_key === 'index.php' ? ' active' : ''; ?>"><a href="index.php"><i class="fa-solid fa-house"></i>Home</a></li>
				<li class="<?php echo ($current_page_key === 'plans.php' && $active_category === '') ? 'active' : ''; ?>"><a href="plans.php"><i class="fa-solid fa-swatchbook"></i>House Plans</a></li>
				<?php foreach ($plan_categories_bar as $cat): ?>
					<li class="<?php echo ($current_page_key === 'plans.php' && $active_category === $cat['name']) ? 'active' : ''; ?>"><a href="plans.php?category=<?php echo urlencode($cat['name']); ?>"><i class="fa-solid fa-building"></i><?php echo htmlspecialchars($cat['name']); ?></a></li>
				<?php endforeach; ?>
			</ul>
			<a href="contact.php" class="category-bar_quote"><i class="fa-regular fa-calendar-check"></i>Get A Quote</a>
		</div>
	</div>
</div>
