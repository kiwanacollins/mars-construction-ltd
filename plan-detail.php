<?php $page_title = "Plan Detail"; $home = false; ?>
<?php
require_once __DIR__ . '/Admin/config/db.php';

$property = null;
if (!empty($_GET['slug'])) {
    $stmt = $pdo->prepare('SELECT * FROM properties WHERE slug = ?');
    $stmt->execute([$_GET['slug']]);
    $property = $stmt->fetch();
} elseif (!empty($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM properties WHERE id = ?');
    $stmt->execute([(int) $_GET['id']]);
    $property = $stmt->fetch();
}

if ($property) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $viewed_key = 'viewed_plan_' . $property['id'];
    if (empty($_SESSION[$viewed_key])) {
        $pdo->prepare('UPDATE properties SET views = views + 1 WHERE id = ?')->execute([$property['id']]);
        $property['views']++;
        $_SESSION[$viewed_key] = true;
    }
}

$property_files = [];
$features = [];
$addons = [];
if ($property) {
    $fstmt = $pdo->prepare('SELECT * FROM property_files WHERE property_id = ? ORDER BY is_cover DESC, id ASC');
    $fstmt->execute([$property['id']]);
    $property_files = $fstmt->fetchAll();

    $astmt = $pdo->prepare('SELECT * FROM plan_addons WHERE property_id = ? ORDER BY sort_order, id');
    $astmt->execute([$property['id']]);
    $addons = $astmt->fetchAll();

    $features = $property['features'] ? json_decode($property['features'], true) : [];
} else {
    $property = ['title' => 'Plan not found', 'slug' => '', 'description' => 'This plan could not be found.', 'price' => 0, 'bedrooms' => 0, 'bathrooms' => 0, 'stories' => 0, 'garage_bays' => 0, 'area_sqft' => 0, 'width_ft' => null, 'depth_ft' => null, 'foundation_type' => '', 'roof_type' => '', 'roof_pitch' => '', 'exterior_material' => '', 'plan_number' => '', 'video_url' => '', 'created_at' => date('Y-m-d')];
}

function bath_display($value) {
    return rtrim(rtrim(number_format((float) $value, 1), '0'), '.');
}

$images = array_values(array_filter($property_files, fn($f) => $f['file_type'] === 'image'));
$documents = array_values(array_filter($property_files, fn($f) => $f['file_type'] === 'document'));
$cover_image = $images ? 'Admin/' . $images[0]['file_path'] : 'assets/images/resource/news-10.jpg';

$recent_plans = $pdo->query(
    "SELECT p.*, (SELECT file_path FROM property_files pf WHERE pf.property_id = p.id ORDER BY pf.is_cover DESC, pf.id ASC LIMIT 1) AS cover_image
     FROM properties p WHERE p.id != " . (int) ($property['id'] ?? 0) . " ORDER BY p.created_at DESC LIMIT 3"
)->fetchAll();

require_once __DIR__ . '/parts/property-card.php';
$related_plans = [];
if (!empty($property['id']) && !empty($property['category'])) {
    $related_stmt = $pdo->prepare(
        "SELECT p.*, (SELECT file_path FROM property_files pf WHERE pf.property_id = p.id ORDER BY pf.is_cover DESC, pf.id ASC LIMIT 1) AS cover_image
         FROM properties p WHERE p.category = ? AND p.id != ? ORDER BY p.featured DESC, p.created_at DESC LIMIT 4"
    );
    $related_stmt->execute([$property['category'], $property['id']]);
    $related_plans = $related_stmt->fetchAll();

    if (count($related_plans) < 4) {
        $exclude_ids = array_merge([$property['id']], array_column($related_plans, 'id'));
        $placeholders = implode(',', array_fill(0, count($exclude_ids), '?'));
        $fallback_stmt = $pdo->prepare(
            "SELECT p.*, (SELECT file_path FROM property_files pf WHERE pf.property_id = p.id ORDER BY pf.is_cover DESC, pf.id ASC LIMIT 1) AS cover_image
             FROM properties p WHERE p.id NOT IN ($placeholders) ORDER BY p.created_at DESC LIMIT ?"
        );
        $fallback_limit = 4 - count($related_plans);
        $bind_values = $exclude_ids;
        $bind_values[] = $fallback_limit;
        foreach ($bind_values as $i => $v) {
            $fallback_stmt->bindValue($i + 1, $v, PDO::PARAM_INT);
        }
        $fallback_stmt->execute();
        $related_plans = array_merge($related_plans, $fallback_stmt->fetchAll());
    }
}
?>
<?php require_once __DIR__ . '/parts/header.php'; ?>
	<!-- End Main Header -->

	<!-- Sidebar Page Container -->
    <div class="sidebar-page-container">
    	<div class="auto-container">
        	<div class="row clearfix">

				<!-- Content Side -->
                <div class="content-side col-lg-8 col-md-12 col-sm-12">
					<!-- Property Detail -->
					<div class="property-detail">
						<div class="property-detail_inner">
							<div class="property-detail_image">
								<img src="<?php echo htmlspecialchars($cover_image); ?>" alt="" />
							</div>
							<div class="property-detail_content">
								<ul class="property-detail_meta d-flex align-items-center flex-wrap">
									<?php if (!empty($property['featured'])): ?><i>Featured</i><?php endif; ?>
									<?php if (!empty($property['plan_number'])): ?><li><span class="icon fa-regular fa-file fa-fw"></span>Plan #<?php echo htmlspecialchars($property['plan_number']); ?></li><?php endif; ?>
									<li><span class="icon fa-regular fa-user fa-fw"></span><?php echo htmlspecialchars(date('d M, Y', strtotime($property['created_at']))); ?></li>
								</ul>
								<h3 class="property-detail_heading"><?php echo htmlspecialchars($property['title']); ?></h3>
								<div class="detail-heading_row">
									<?php if (!empty($property['category'])): ?><div class="property-detail_location"><i class="flaticon-maps-and-flags"></i><?php echo htmlspecialchars($property['category']); ?></div><?php endif; ?>
									<?php require_once __DIR__ . '/parts/share.php'; render_share_buttons($property['title']); ?>
								</div>
								<p><?php echo nl2br(htmlspecialchars($property['description'] ?: 'No description provided yet.')); ?></p>

								<h4 class="property-detail_subheading">Specifications</h4>
								<div class="propert-info">
									<div class="row clearfix">
										<!-- Column -->
										<div class="column col-lg-6 col-md-12 col-sm-12">
											<ul class="propert-info_list">
												<li>Total Square Footage<span><?php echo number_format($property['area_sqft'], 0); ?> sqft</span></li>
												<li>Bedrooms<span><?php echo (int) $property['bedrooms']; ?></span></li>
												<li>Bathrooms<span><?php echo bath_display($property['bathrooms']); ?></span></li>
												<li>Stories<span><?php echo (int) $property['stories']; ?></span></li>
											</ul>
										</div>
										<!-- Column -->
										<div class="column col-lg-6 col-md-12 col-sm-12">
											<ul class="propert-info_list">
												<li>Garage Bays<span><?php echo (int) $property['garage_bays']; ?></span></li>
												<li>Dimensions<span><?php echo ($property['width_ft'] && $property['depth_ft']) ? number_format($property['width_ft'], 0) . "' x " . number_format($property['depth_ft'], 0) . "'" : '—'; ?></span></li>
												<li>Starting Price<span>$<?php echo number_format($property['price'], 0); ?></span></li>
											</ul>
										</div>
									</div>
								</div>

								<?php if (!empty($property['foundation_type']) || !empty($property['roof_type']) || !empty($property['exterior_material'])): ?>
								<h4 class="property-detail_subheading">Construction Details</h4>
								<div class="propert-info">
									<div class="row clearfix">
										<div class="column col-lg-6 col-md-12 col-sm-12">
											<ul class="propert-info_list">
												<?php if (!empty($property['foundation_type'])): ?><li>Foundation<span><?php echo htmlspecialchars($property['foundation_type']); ?></span></li><?php endif; ?>
												<?php if (!empty($property['roof_type'])): ?><li>Roof Type<span><?php echo htmlspecialchars($property['roof_type']); ?></span></li><?php endif; ?>
											</ul>
										</div>
										<div class="column col-lg-6 col-md-12 col-sm-12">
											<ul class="propert-info_list">
												<?php if (!empty($property['roof_pitch'])): ?><li>Roof Pitch<span><?php echo htmlspecialchars($property['roof_pitch']); ?></span></li><?php endif; ?>
												<?php if (!empty($property['exterior_material'])): ?><li>Exterior<span><?php echo htmlspecialchars($property['exterior_material']); ?></span></li><?php endif; ?>
											</ul>
										</div>
									</div>
								</div>
								<?php endif; ?>

								<h4 class="property-detail_subheading">Facts and Features</h4>
								<div class="row clearfix">

									<!-- Property Block Two -->
									<div class="property-block_two col-lg-4 col-md-4 col-sm-6">
										<div class="property-block_two-inner">
											<div class="property-block_two-icon flaticon-double-bed"></div>
											<h6 class="property-block_two-title">Total Bed Count</h6>
											<div class="property-block_two-text"><?php echo (int) $property['bedrooms']; ?> Beds</div>
										</div>
									</div>

									<!-- Property Block Two -->
									<div class="property-block_two col-lg-4 col-md-4 col-sm-6">
										<div class="property-block_two-inner">
											<div class="property-block_two-icon flaticon-bath-tub"></div>
											<h6 class="property-block_two-title">Bathroom for Use</h6>
											<div class="property-block_two-text"><?php echo bath_display($property['bathrooms']); ?> Bathroom</div>
										</div>
									</div>

									<!-- Property Block Two -->
									<div class="property-block_two col-lg-4 col-md-4 col-sm-6">
										<div class="property-block_two-inner">
											<div class="property-block_two-icon flaticon-scale"></div>
											<h6 class="property-block_two-title">Total Area Size</h6>
											<div class="property-block_two-text"><?php echo number_format($property['area_sqft'], 0); ?> sqft</div>
										</div>
									</div>

								</div>

								<?php if ($images): ?>
								<h4 class="property-detail_subheading">Plan Gallery</h4>
								<div class="carousel-box">
									<div class="single-item_slider swiper-container">
										<div class="swiper-wrapper">
											<?php foreach ($images as $img): ?>
												<!-- Slide -->
												<div class="swiper-slide">
													<div class="image">
														<img src="<?php echo htmlspecialchars('Admin/' . $img['file_path']); ?>" alt="" />
													</div>
												</div>
											<?php endforeach; ?>
										</div>
										<div class="single-item_slider-prev fas fa-angle-left fa-fw"></div>
										<div class="single-item_slider-next fas fa-angle-right fa-fw"></div>
									</div>
								</div>
								<?php endif; ?>
								<?php if ($features): ?>
								<h4 class="property-detail_subheading">Plan Amenities</h4>
								<div class="property-detail_checks">
									<div class="row clearfix">
										<div class="column col-lg-12 col-md-12 col-sm-12">
											<ul class="property-detail_checklist">
												<?php foreach ((array) $features as $feat): ?>
													<li><i class="flaticon-checked"></i><?php echo htmlspecialchars($feat); ?></li>
												<?php endforeach; ?>
											</ul>
										</div>
									</div>
								</div>
								<?php endif; ?>
								<?php if ($documents): ?>
								<h4 class="property-detail_subheading">Downloadable Files</h4>
								<ul class="property-detail_checklist">
									<?php foreach ($documents as $doc): ?>
										<li><i class="flaticon-checked"></i><a href="<?php echo htmlspecialchars('Admin/' . $doc['file_path']); ?>" target="_blank"><?php echo htmlspecialchars($doc['original_name']); ?></a></li>
									<?php endforeach; ?>
								</ul>
								<?php endif; ?>
								<?php if ($property['video_url']): ?>
								<h4 class="property-detail_subheading">Plan Video</h4>
								<div class="video-box">
									<img src="assets/images/resource/property-9.jpg" alt="" />
									<a href="<?php echo htmlspecialchars($property['video_url']); ?>" class="lightbox-video overlay-box"><span class="fa-solid fa-play fa-fw"><i class="ripple"></i></span></a>
								</div>
								<?php endif; ?>

								<?php
									require_once __DIR__ . '/parts/reviews.php';
									render_reviews_section($pdo, 'plan', (int) ($property['id'] ?? 0), 'plan-detail.php?slug=' . urlencode($property['slug'] ?? ''));
								?>

							</div>
						</div>
					</div>

				</div>

				<!-- Sidebar Side -->
                <div class="sidebar-side col-lg-4 col-md-12 col-sm-12">
                	<aside class="sidebar">
						<div class="sidebar-inner">

							<?php if ($property['id'] ?? false): ?>
							<!-- Customize Order Widget -->
							<div class="sidebar-widget customize-order-widget sticky-widget">
								<div class="widget-content">
									<h5 class="sidebar-widget_title">Customize Your Order</h5>
									<div class="customize-order_total">
										<span>Total</span>
										<strong id="customize-order-total">$<?php echo number_format($property['price'], 0); ?></strong>
									</div>
									<form method="post" action="cart-add.php" id="customize-order-form">
										<input type="hidden" name="property_id" value="<?php echo (int) $property['id']; ?>">
										<input type="hidden" name="redirect" value="plan-detail.php?slug=<?php echo urlencode($property['slug']); ?>">

										<?php if ($addons): ?>
										<div class="customize-order_options">
											<?php foreach ($addons as $addon): ?>
												<label class="customize-order_option">
													<input type="checkbox" name="addon_ids[]" value="<?php echo (int) $addon['id']; ?>" data-price="<?php echo htmlspecialchars($addon['price']); ?>" data-type="<?php echo htmlspecialchars($addon['price_type']); ?>">
													<span class="customize-order_option-body">
														<span class="customize-order_option-name"><?php echo htmlspecialchars($addon['name']); ?></span>
														<?php if ($addon['description']): ?><span class="customize-order_option-desc"><?php echo htmlspecialchars($addon['description']); ?></span><?php endif; ?>
													</span>
													<span class="customize-order_option-price"><?php echo $addon['price_type'] === 'percent' ? '+' . rtrim(rtrim(number_format($addon['price'], 2), '0'), '.') . '%' : '+$' . number_format($addon['price'], 0); ?></span>
												</label>
											<?php endforeach; ?>
										</div>
										<?php endif; ?>

										<button type="submit" class="theme-btn btn-style-one"><span class="btn-wrap"><span class="text-one">Add to Cart</span><span class="text-two">Add to Cart</span></span></button>
									</form>
								</div>
							</div>
							<?php endif; ?>

							<!-- Message Widget -->
							<div class="sidebar-widget message-widget">
								<div class="widget-content">
									<h5 class="sidebar-widget_title">Inquire About This Plan</h5>
									<div class="message-form">
										<form method="post" action="send-message.php">
											<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
											<input type="hidden" name="property_id" value="<?php echo (int) ($property['id'] ?? 0); ?>">
											<input type="hidden" name="subject" value="Plan inquiry: <?php echo htmlspecialchars($property['title']); ?>">
											<input type="hidden" name="redirect" value="plan-detail.php?slug=<?php echo urlencode($property['slug'] ?? ''); ?>">

											<!-- Form Group -->
											<div class="form-group">
												<input type="text" name="name" value="" placeholder="Name*" required>
											</div>

											<!--Form Group-->
											<div class="form-group">
												<input type="email" name="email" value="" placeholder="Email*" required>
											</div>

											<!--Form Group-->
											<div class="form-group">
												<input type="tel" name="phone" value="" placeholder="Phone Number*" required>
											</div>

											<div class="form-group">
												<textarea class="" name="message" placeholder="Tell us more - preferred start date, site location, customizations, etc." required></textarea>
											</div>

											<div class="form-group">
												<button type="submit" class="template-btn btn-style-one">
													<span class="btn-wrap">
														<span class="text-one">submit now</span>
														<span class="text-two">submit now</span>
													</span>
												</button>
											</div>

										</form>
									</div>
								</div>
							</div>

							<!-- Post Widget -->
							<div class="sidebar-widget post-widget">
								<div class="widget-content">
									<h5 class="sidebar-widget_title">Recent Plans</h5>
									<?php foreach ($recent_plans as $rp): ?>
										<div class="post">
											<div class="thumb"><a href="plan-detail.php?slug=<?php echo urlencode($rp['slug']); ?>"><img src="<?php echo htmlspecialchars($rp['cover_image'] ? 'Admin/' . $rp['cover_image'] : 'assets/images/resource/post-thumb-4.jpg'); ?>" alt=""></a></div>
											<h6><a href="plan-detail.php?slug=<?php echo urlencode($rp['slug']); ?>"><?php echo htmlspecialchars($rp['title']); ?></a></h6>
											<div class="post-date">$<?php echo number_format($rp['price'], 0); ?></div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>

						</div>
					</aside>
				</div>

			</div>
		</div>
	</div>

	<?php if ($related_plans): ?>
	<!-- Related Plans -->
	<section class="property-one style-two">
		<div class="auto-container">
			<!-- Sec Title -->
			<div class="sec-title">
				<div class="sec-title_title">You Might Also Like</div>
				<h2 class="sec-title_heading">Related Plans</h2>
			</div>
			<div class="row clearfix">
				<?php foreach ($related_plans as $plan) { echo render_property_card($plan); } ?>
			</div>
		</div>
	</section>
	<!-- End Related Plans -->
	<?php endif; ?>

	<?php if ($property['id'] ?? false): ?>
	<script>
	(function () {
		var basePrice = <?php echo (float) $property['price']; ?>;
		var form = document.getElementById('customize-order-form');
		var total = document.getElementById('customize-order-total');
		if (!form || !total) { return; }
		function recalc() {
			var flatTotal = 0;
			var percentTotal = 0;
			form.querySelectorAll('input[name="addon_ids[]"]:checked').forEach(function (el) {
				var price = parseFloat(el.getAttribute('data-price')) || 0;
				if (el.getAttribute('data-type') === 'percent') {
					percentTotal += price;
				} else {
					flatTotal += price;
				}
			});
			var grandTotal = basePrice + flatTotal + (flatTotal * percentTotal / 100);
			total.textContent = '$' + grandTotal.toLocaleString(undefined, { maximumFractionDigits: 0 });
		}
		form.addEventListener('change', recalc);
		recalc();
	})();
	</script>
	<?php endif; ?>

	<!-- Main Footer -->
<?php require_once __DIR__ . '/parts/footer.php'; ?>
