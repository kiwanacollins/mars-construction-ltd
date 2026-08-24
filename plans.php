<?php $page_title = "Plans"; $home = false; ?>
<?php
require_once __DIR__ . '/Admin/config/db.php';

$selected_category = $_GET['category'] ?? '';
$min_beds = isset($_GET['bedrooms']) && $_GET['bedrooms'] !== '' ? (int) $_GET['bedrooms'] : null;
$search = trim($_GET['search'] ?? '');

$where = [];
$params = [];
if ($selected_category !== '') {
    $where[] = 'p.category = ?';
    $params[] = $selected_category;
}
if ($min_beds !== null) {
    $where[] = 'p.bedrooms >= ?';
    $params[] = $min_beds;
}
if ($search !== '') {
    $where[] = 'p.title LIKE ?';
    $params[] = '%' . $search . '%';
}
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$per_page = 8;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $per_page;

$count_stmt = $pdo->prepare("SELECT COUNT(*) c FROM properties p $where_sql");
$count_stmt->execute($params);
$total = (int) $count_stmt->fetch()['c'];
$total_pages = max(1, (int) ceil($total / $per_page));
$page = min($page, $total_pages);
$offset = ($page - 1) * $per_page;

$sql = "SELECT p.*, (SELECT file_path FROM property_files pf WHERE pf.property_id = p.id ORDER BY pf.is_cover DESC, pf.id ASC LIMIT 1) AS cover_image
        FROM properties p $where_sql ORDER BY p.created_at DESC LIMIT $per_page OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$properties = $stmt->fetchAll();

$plan_categories = $pdo->query('SELECT * FROM plan_categories ORDER BY sort_order, name')->fetchAll();
$category_counts = $pdo->query("SELECT category, COUNT(*) c FROM properties WHERE category IS NOT NULL AND category != '' GROUP BY category")->fetchAll(PDO::FETCH_KEY_PAIR);

function filter_url($overrides = []) {
    $params = array_merge($_GET, $overrides);
    if (!isset($overrides['page'])) {
        unset($params['page']);
    }
    $params = array_filter($params, fn($v) => $v !== '' && $v !== null);
    return 'plans.php' . ($params ? '?' . http_build_query($params) : '');
}

function hidden_filters($except = []) {
    foreach ($_GET as $k => $v) {
        if (in_array($k, $except, true) || $v === '') {
            continue;
        }
        echo '<input type="hidden" name="' . htmlspecialchars($k) . '" value="' . htmlspecialchars($v) . '">';
    }
}

$has_filters = $selected_category !== '' || $min_beds !== null || $search !== '';
?>
<?php require_once __DIR__ . '/parts/header.php'; ?>
	<!-- End Main Header -->

	<!-- Sidebar Page Container -->
    <div class="sidebar-page-container">
    	<div class="auto-container">
        	<div class="row clearfix">

				<!-- Sidebar Side -->
                <div class="sidebar-side col-lg-3 col-md-12 col-sm-12 order-lg-1">
                	<aside class="sidebar plans-filter-sidebar">
						<div class="sidebar-inner">

							<!-- Category Widget -->
							<div class="sidebar-widget category-widget plans-filter_widget">
								<div class="widget-content">
									<h5 class="sidebar-widget_title"><i class="fa-solid fa-layer-group"></i>Plan Categories</h5>
									<ul class="plans-filter_list">
										<li class="<?php echo $selected_category === '' ? 'active' : ''; ?>"><a href="<?php echo filter_url(['category' => null]); ?>"><span class="plans-filter_dot"></span>All <span class="plans-filter_count"><?php echo array_sum($category_counts); ?></span></a></li>
										<?php foreach ($plan_categories as $cat): ?>
											<li class="<?php echo $selected_category === $cat['name'] ? 'active' : ''; ?>"><a href="<?php echo filter_url(['category' => $cat['name']]); ?>"><span class="plans-filter_dot"></span><?php echo htmlspecialchars($cat['name']); ?> <span class="plans-filter_count"><?php echo (int) ($category_counts[$cat['name']] ?? 0); ?></span></a></li>
										<?php endforeach; ?>
									</ul>
								</div>
							</div>

							<!-- Bedrooms Widget -->
							<div class="sidebar-widget category-widget plans-filter_widget">
								<div class="widget-content">
									<h5 class="sidebar-widget_title"><i class="fa-solid fa-bed"></i>Bedrooms</h5>
									<form method="get" action="plans.php">
										<?php hidden_filters(['bedrooms']); ?>
										<select name="bedrooms" class="form-control-catalogue" onchange="this.form.submit()">
											<option value="" <?php echo $min_beds === null ? 'selected' : ''; ?>>Any</option>
											<?php foreach ([1, 2, 3, 4, 5] as $bcount): ?>
												<option value="<?php echo $bcount; ?>" <?php echo $min_beds === $bcount ? 'selected' : ''; ?>><?php echo $bcount; ?>+ Beds</option>
											<?php endforeach; ?>
										</select>
									</form>
								</div>
							</div>

							<?php if ($has_filters): ?>
							<a href="plans.php" class="theme-btn btn-style-one catalogue-filter-btn w-100"><span class="btn-wrap"><span class="text-one"><i class="fa-solid fa-rotate-left me-1"></i>Clear All Filters</span><span class="text-two"><i class="fa-solid fa-rotate-left me-1"></i>Clear All Filters</span></span></a>
							<?php endif; ?>

						</div>
					</aside>
				</div>
				<!-- End Sidebar Side -->

				<!-- Content Side -->
                <div class="content-side col-lg-9 col-md-12 col-sm-12 order-lg-2">
					<div class="catalogue-result-count">Showing <?php echo count($properties); ?> of <?php echo $total; ?> plan<?php echo $total === 1 ? '' : 's'; ?><?php echo $selected_category !== '' ? ' in ' . htmlspecialchars($selected_category) : ''; ?></div>
					<div class="row clearfix">
						<?php if (!$properties): ?>
							<div class="col-12"><p>No plans found matching your filters.</p></div>
						<?php endif; ?>
						<?php foreach ($properties as $prop): ?>
							<!-- Property Block One -->
							<div class="property-block_one style-two col-lg-3 col-md-6 col-sm-12">
								<div class="property-block_one-inner">
									<div class="property-block_one-image">
										<?php if ($prop['featured']): ?><div class="property-block_one-title">Featured</div><?php endif; ?>
										<a class="property-block_one-heart" href="plan-detail.php?slug=<?php echo urlencode($prop['slug']); ?>"><i class="flaticon-heart"></i></a>
										<a href="plan-detail.php?slug=<?php echo urlencode($prop['slug']); ?>" class="property-block_one-image-link">
											<img src="<?php echo htmlspecialchars($prop['cover_image'] ? 'Admin/' . $prop['cover_image'] : 'assets/images/resource/property-1.jpg'); ?>" alt="" />
											<div class="property-block_one-image-content">
												<h4 class="property-block_one-heading"><?php echo htmlspecialchars($prop['title']); ?></h4>
												<ul class="property-block_one-info">
													<li><span><img src="assets/images/icons/bed.svg" alt="" /></span><?php echo (int) $prop['bedrooms']; ?> Bed</li>
													<li><span><img src="assets/images/icons/bath.svg" alt="" /></span><?php echo rtrim(rtrim(number_format($prop['bathrooms'], 1), '0'), '.'); ?> Bath</li>
													<li><span><img src="assets/images/icons/square.svg" alt="" /></span><?php echo number_format($prop['area_sqft'], 0); ?> sqft</li>
												</ul>
											</div>
										</a>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>

					<?php if ($total_pages > 1): ?>
					<!-- Styled Pagination -->
					<ul class="styled-pagination text-center">
						<?php if ($page > 1): ?><li><a href="<?php echo filter_url(['page' => $page - 1]); ?>"><span class="fa-solid fa-angle-left fa-fw"></span></a></li><?php endif; ?>
						<?php for ($i = 1; $i <= $total_pages; $i++): ?>
							<li><a href="<?php echo filter_url(['page' => $i]); ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a></li>
						<?php endfor; ?>
						<?php if ($page < $total_pages): ?><li class="next"><a href="<?php echo filter_url(['page' => $page + 1]); ?>"><span class="fa-solid fa-angle-right fa-fw"></span></a></li><?php endif; ?>
					</ul>
					<!-- End Styled Pagination -->
					<?php endif; ?>

				</div>
				<!-- End Content Side -->

			</div>
		</div>
	</div>

	<!-- Main Footer -->
<?php require_once __DIR__ . '/parts/footer.php'; ?>
