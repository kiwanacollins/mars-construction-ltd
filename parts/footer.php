	<footer class="main-footer">
		<div class="main-footer_pattern" style="background-image: url(assets/images/background/footer-1.png)"></div>
		<!-- Widgets Section -->
		<div class="widgets-section">
			<div class="auto-container">

				<div class="footer-logo footer-logo_centered"><a href="index.php"><img src="assets/images/Mars-Logo.svg" alt="" title=""></a></div>

				<div class="row clearfix">

					<!-- Footer Column -->
					<div class="footer_column col-lg-4 col-md-6 col-sm-12">
						<div class="footer-widget">
							<?php if (!empty($site_settings['footer_col1_title'])): ?><h5 class="footer-title"><?php echo htmlspecialchars($site_settings['footer_col1_title']); ?></h5><?php endif; ?>
							<div class="footer-text"><?php echo htmlspecialchars($site_settings['footer_text'] ?? ''); ?></div>
						</div>
					</div>

					<!-- Footer Column -->
					<div class="footer_column col-lg-4 col-md-6 col-sm-12">
						<div class="footer-widget links-widget">
							<h5 class="footer-title"><?php echo htmlspecialchars($site_settings['footer_col2_heading'] ?? '' ?: 'Discover Cities'); ?></h5>
							<ul class="footer-list">
								<?php
									if (!isset($pdo)) {
										require_once __DIR__ . '/../Admin/config/db.php';
									}
									$footer_menu_items_col2 = $pdo->query("SELECT * FROM footer_menu_items WHERE col_group = 'cities' ORDER BY sort_order, id")->fetchAll();
								?>
								<?php foreach ($footer_menu_items_col2 as $flink): ?>
									<li><a href="<?php echo htmlspecialchars($flink['url']); ?>"><?php echo htmlspecialchars($flink['label']); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>

					<!-- Footer Column -->
					<div class="footer_column col-lg-4 col-md-6 col-sm-12">
						<div class="footer-widget contact-widget">
							<h5 class="footer-title">Contact Information</h5>
							<ul class="footer-contact_list">
								<li>
									<span class="flaticon-pin"></span>
									<?php echo htmlspecialchars($site_settings['footer_address'] ?? ''); ?>
								</li>
								<li>
									<span class="flaticon-telephone"></span>
									<a href="tel:<?php echo htmlspecialchars($site_settings['footer_phone'] ?? ''); ?>"><?php echo htmlspecialchars($site_settings['footer_phone'] ?? ''); ?></a>
								</li>
								<li>
									<span class="flaticon-mail-1"></span>
									<a href="mailto:<?php echo htmlspecialchars($site_settings['footer_email'] ?? ''); ?>"><?php echo htmlspecialchars($site_settings['footer_email'] ?? ''); ?></a>
								</li>
							</ul>
						</div>
					</div>

				</div>

			</div>
		</div>
		<!-- End Widgets Section -->

		<!-- Footer Bottom -->
		<div class="footer-bottom">
			<div class="auto-container">
				<div class="d-flex justify-content-between align-items-center flex-wrap">
					<div class="copyright"><?php echo $site_settings['footer_copyright'] ?? ''; ?></div>

					<!-- Social Box -->
					<div class="footer_socials">
						<?php if (!empty($site_settings['footer_facebook'])): ?><a href="<?php echo htmlspecialchars($site_settings['footer_facebook']); ?>"><i class="fa-brands fa-facebook-f"></i></a><?php endif; ?>
						<?php if (!empty($site_settings['footer_twitter'])): ?><a href="<?php echo htmlspecialchars($site_settings['footer_twitter']); ?>"><i class="fa-brands fa-twitter"></i></a><?php endif; ?>
						<?php if (!empty($site_settings['footer_youtube'])): ?><a href="<?php echo htmlspecialchars($site_settings['footer_youtube']); ?>"><i class="fa-brands fa-youtube"></i></a><?php endif; ?>
						<?php if (!empty($site_settings['footer_instagram'])): ?><a href="<?php echo htmlspecialchars($site_settings['footer_instagram']); ?>"><i class="fa-brands fa-instagram"></i></a><?php endif; ?>
						<?php if (!empty($site_settings['footer_tiktok'])): ?><a href="<?php echo htmlspecialchars($site_settings['footer_tiktok']); ?>"><i class="fa-brands fa-tiktok"></i></a><?php endif; ?>
					</div>

					<ul class="footer-bottom_nav">
						<?php
							$footer_bottom_links = $pdo->query("SELECT * FROM footer_menu_items WHERE col_group = 'bottom' ORDER BY sort_order, id")->fetchAll();
						?>
						<?php foreach ($footer_bottom_links as $blink): ?>
							<li><a href="<?php echo htmlspecialchars($blink['url']); ?>"><?php echo htmlspecialchars($blink['label']); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>

	</footer>
	<!-- End Main Footer -->

	<!-- Search Popup -->
	<div class="search-popup">
		<div class="color-layer"></div>
		<button class="close-search"><span class="flaticon-close"></span></button>
		<form method="get" action="blog.php">
			<div class="form-group">
				<input type="search" name="q" value="" placeholder="Search Here" required="">
				<button class="fa fa-solid fa-magnifying-glass fa-fw" type="submit"></button>
			</div>
		</form>
	</div>
	<!-- End Search Popup -->

	<!-- Sidebar Cart Item -->
	<div class="xs-sidebar-group info-group">
		<div class="xs-overlay xs-bg-black"></div>
		<div class="xs-sidebar-widget">
			<div class="sidebar-widget-container">
				<div class="close-button">
					<span class="fa-solid fa-xmark fa-fw"></span>
				</div>
				<div class="sidebar-textwidget">

					<!-- Sidebar Info Content -->
					<div class="sidebar-info-contents">
						<div class="content-inner">

							<?php
								if (!isset($pdo)) {
									require_once __DIR__ . '/../Admin/config/db.php';
								}
								require_once __DIR__ . '/cart.php';
								$cart_items = cart_lines($pdo);
								$cart_total = 0;
								foreach ($cart_items as $ci) {
									$cart_total += $ci['price'] * $ci['qty'];
								}
							?>

							<!-- Title Box -->
							<div class="title-box">
								<h5>Your <span>Cart</span></h5>
								<?php if ($cart_items): ?>
									<div class="price">Total: $<?php echo number_format($cart_total, 0); ?></div>
								<?php endif; ?>
							</div>

							<!-- Empty Cart Box -->
							<?php if (!$cart_items): ?>
								<div class="empty-cart-box">
									<!-- No Product -->
									<div class="no-cart">
										<span class="icon fa-solid fa-cart-flatbed-suitcase fa-fw"></span>
										No plans in cart.
									</div>
								</div>
							<?php else: ?>
								<div class="lower-box">
									<?php foreach ($cart_items as $ci): ?>
										<!-- Post Block -->
										<div class="post-block">
											<div class="inner-box">
												<div class="image">
													<img src="<?php echo htmlspecialchars($ci['property']['cover_image'] ? 'Admin/' . $ci['property']['cover_image'] : 'assets/images/resource/post-thumb-1.jpg'); ?>" alt="" />
												</div>
												<h6><a href="plan-detail.php?slug=<?php echo urlencode($ci['property']['slug']); ?>"><?php echo htmlspecialchars($ci['property']['title']); ?></a></h6>
												<?php if ($ci['addons']): ?><div class="fs-12"><?php echo htmlspecialchars(implode(', ', array_column($ci['addons'], 'name'))); ?></div><?php endif; ?>
												<div class="price-box">$<?php echo number_format($ci['price'], 0); ?><?php echo $ci['qty'] > 1 ? ' x ' . $ci['qty'] : ''; ?></div>
												<a class="theme-btn bag-btn" href="cart-remove.php?key=<?php echo urlencode($ci['key']); ?>">Remove</a>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
								<a class="cart-checkout-btn" href="checkout.php">Checkout <i class="fa-solid fa-arrow-right"></i></a>
							<?php endif; ?>

							<!-- Lower Box -->
							<div class="lower-box">
								<h5>Popular <span>Suggestions</span></h5>

								<?php
									$sidebar_props = $pdo->query(
										"SELECT p.*, (SELECT file_path FROM property_files pf WHERE pf.property_id = p.id ORDER BY pf.is_cover DESC, pf.id ASC LIMIT 1) AS cover_image
										 FROM properties p ORDER BY p.created_at DESC LIMIT 3"
									)->fetchAll();
								?>
								<?php foreach ($sidebar_props as $sp): ?>
									<!-- Post Block -->
									<div class="post-block">
										<div class="inner-box">
											<div class="image">
												<img src="<?php echo htmlspecialchars($sp['cover_image'] ? 'Admin/' . $sp['cover_image'] : 'assets/images/resource/post-thumb-1.jpg'); ?>" alt="" />
											</div>
											<h6><a href="plan-detail.php?slug=<?php echo urlencode($sp['slug']); ?>"><?php echo htmlspecialchars($sp['title']); ?></a></h6>
											<div class="price-box">$<?php echo number_format($sp['price'], 0); ?></div>
											<a class="theme-btn bag-btn" href="plan-detail.php?slug=<?php echo urlencode($sp['slug']); ?>">View</a>
										</div>
									</div>
								<?php endforeach; ?>

							</div>

						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

</div>
<!-- End PageWrapper -->

<div class="progress-wrap">
	<svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
		<path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
	</svg>
</div>

<script src="assets/js/jquery.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/appear.js"></script>
<script src="assets/js/parallax.min.js"></script>
<script src="assets/js/tilt.jquery.min.js"></script>
<script src="assets/js/jquery.paroller.min.js"></script>
<script src="assets/js/wow.js"></script>
<script src="assets/js/swiper.min.js"></script>
<script src="assets/js/backtotop.js"></script>
<script src="assets/js/odometer.js"></script>
<script src="assets/js/parallax-scroll.js"></script>

<script src="assets/js/gsap.min.js"></script>
<script src="assets/js/SplitText.min.js"></script>
<script src="assets/js/ScrollTrigger.min.js"></script>
<script src="assets/js/ScrollToPlugin.min.js"></script>
<script src="assets/js/ScrollSmoother.min.js"></script>

<script src="assets/js/magnific-popup.min.js"></script>
<script src="assets/js/jquery.meanmenu.min.js"></script>
<script src="assets/js/nav-tool.js"></script>
<script src="assets/js/jquery-ui.js"></script>
<script src="assets/js/jquery.countdown.js"></script>
<script src="assets/js/element-in-view.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/sticky-category-bar.js"></script>

</body>
</html>
