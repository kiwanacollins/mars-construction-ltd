<?php $page_title = "Mars Construction"; $home = true; ?>
<?php require_once __DIR__ . '/parts/header.php'; ?>
	<!-- End Main Header -->

	<?php
		$hero_slides = $pdo->query('SELECT * FROM hero_slides ORDER BY sort_order, id')->fetchAll();
		if (!$hero_slides) {
			$hero_slides = [[
				'heading' => 'Let’s Unlock Dream Home here',
				'subheading' => 'real estate',
				'description' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna. Pellentesque sit amet sapien fringilla, mattis ligula consectetur, ultrices mauris.',
				'button_text' => '',
				'button_link' => '',
				'image' => 'assets/images/main-slider/2.jpg',
			]];
		}
		function hero_slide_image($path) {
			if (!$path) {
				return 'assets/images/main-slider/2.jpg';
			}
			return strpos($path, 'uploads/') === 0 ? 'Admin/' . $path : $path;
		}
		function hero_slide_video_embed($url) {
			$url = trim((string) $url);
			if ($url === '') {
				return null;
			}
			if (preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/)([a-zA-Z0-9_-]{6,})#', $url, $m)) {
				$id = $m[1];
				return ['type' => 'youtube', 'src' => 'https://www.youtube.com/embed/' . $id . '?autoplay=1&mute=1&loop=1&playlist=' . $id . '&controls=0&showinfo=0&modestbranding=1&rel=0&iv_load_policy=3&playsinline=1'];
			}
			if (preg_match('#\.(mp4|webm|ogg)(\?.*)?$#i', $url)) {
				return ['type' => 'file', 'src' => $url];
			}
			return ['type' => 'iframe', 'src' => $url];
		}
	?>

	<!-- Banner Two -->
	<section class="banner-two">
		<div class="banner-two_info"><a href="mailto:support@palace.com">support@palace.com</a> <span><a href="tel:+815-804-8928">815-804-8928</a></span></div>
		<div class="banner-two_socials">
			<a class="fa-brands fa-facebook-f fa-fw" href="#"></a>
			<a class="fa-brands fa-instagram fa-fw" href="#"></a>
			<a class="fa-brands fa-twitter fa-fw" href="#"></a>
			<a class="fa-brands fa-youtube fa-fw" href="#"></a>
		</div>

		<div class="banner-two_slider swiper-container">
			<div class="swiper-wrapper">
				<?php foreach ($hero_slides as $slide): ?>
					<?php
						$is_video_slide = ($slide['bg_type'] ?? 'image') === 'video' && !empty($slide['video_url']);
						$video_embed = $is_video_slide ? hero_slide_video_embed($slide['video_url']) : null;
					?>
					<div class="swiper-slide">
						<?php if ($video_embed): ?>
							<div class="banner-two_image banner-two_image-video">
								<div class="hero-video-embed">
									<?php if ($video_embed['type'] === 'file'): ?>
										<video autoplay muted loop playsinline><source src="<?php echo htmlspecialchars($video_embed['src']); ?>"></video>
									<?php else: ?>
										<iframe src="<?php echo htmlspecialchars($video_embed['src']); ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
									<?php endif; ?>
								</div>
								<div class="hero-video-overlay"></div>
							</div>
						<?php else: ?>
							<div class="banner-two_image" style="background-image:url(<?php echo htmlspecialchars(hero_slide_image($slide['image'])); ?>)"></div>
						<?php endif; ?>
						<div class="auto-container">
							<!-- Content Column -->
							<div class="banner-two_content">
								<div class="banner-two_content-inner">
									<?php if (!empty($slide['subheading'])): ?><div class="banner-two_title"><?php echo htmlspecialchars($slide['subheading']); ?></div><?php endif; ?>
									<h1 class="banner-two_heading"><?php echo htmlspecialchars($slide['heading']); ?></h1>
									<?php if (!empty($slide['description'])): ?><div class="banner-two_text"><?php echo htmlspecialchars($slide['description']); ?></div><?php endif; ?>
									<?php if (!empty($slide['button_text']) || !empty($slide['button2_text'])): ?>
										<div class="banner-two_buttons">
											<?php if (!empty($slide['button_text'])): ?>
												<a href="<?php echo htmlspecialchars($slide['button_link'] ?: '#'); ?>" class="theme-btn btn-style-one"><span class="btn-wrap"><span class="text-one"><?php echo htmlspecialchars($slide['button_text']); ?></span><span class="text-two"><?php echo htmlspecialchars($slide['button_text']); ?></span></span></a>
											<?php endif; ?>
											<?php if (!empty($slide['button2_text'])): ?>
												<a href="<?php echo htmlspecialchars($slide['button2_link'] ?: '#'); ?>" class="theme-btn btn-style-outline"><span class="btn-wrap"><span class="text-one"><?php echo htmlspecialchars($slide['button2_text']); ?></span></span></a>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if (count($hero_slides) > 1): ?>
			<div class="banner-two_slider-pagination"></div>
			<?php endif; ?>
		</div>
	</section>
	<!-- End Banner Two -->

	<script>
	(function () {
		function setHeroOffset() {
			var header = document.querySelector('.main-header');
			var categoryBar = document.querySelector('.category-bar');
			var offset = (header ? header.offsetHeight : 0) + (categoryBar ? categoryBar.offsetHeight : 0);
			document.documentElement.style.setProperty('--hero-offset', offset + 'px');
		}
		setHeroOffset();
		window.addEventListener('resize', setHeroOffset);
		window.addEventListener('load', setHeroOffset);
	})();
	</script>

	<?php if (count($hero_slides) > 1): ?>
	<script>
	document.addEventListener('DOMContentLoaded', function () {
		new Swiper('.banner-two_slider', {
			slidesPerView: 1,
			loop: true,
			effect: 'fade',
			fadeEffect: { crossFade: true },
			speed: 1400,
			autoplay: { delay: 6000, disableOnInteraction: false },
			pagination: { el: '.banner-two_slider-pagination', clickable: true }
		});
	});
	</script>
	<?php endif; ?>

	<!-- Modern Villas -->
	<?php
		$modern_villas = $pdo->query(
			"SELECT p.*, (SELECT file_path FROM property_files pf WHERE pf.property_id = p.id ORDER BY pf.is_cover DESC, pf.id ASC LIMIT 1) AS cover_image
			 FROM properties p WHERE p.category = 'Modern Villas' ORDER BY p.featured DESC, p.created_at DESC"
		)->fetchAll();
	?>
	<?php if ($modern_villas): ?>
	<section class="property-one style-two modern-villas-one">
		<div class="auto-container">
			<!-- Sec Title -->
			<div class="sec-title">
				<div class="sec-title_title">New Collection</div>
				<h2 class="sec-title_heading">Modern Villas</h2>
			</div>
			<div class="row clearfix">
				<?php foreach ($modern_villas as $plan): ?>
					<!-- Property Block One / Style Two -->
					<div class="property-block_one style-two col-lg-3 col-md-6 col-sm-12">
						<div class="property-block_one-inner">
							<div class="property-block_one-image">
								<?php if ($plan['featured']): ?><div class="property-block_one-title">Featured</div><?php endif; ?>
								<a class="property-block_one-heart" href="plan-detail.php?slug=<?php echo urlencode($plan['slug']); ?>"><i class="flaticon-heart"></i></a>
								<a href="plan-detail.php?slug=<?php echo urlencode($plan['slug']); ?>" class="property-block_one-image-link">
									<img src="<?php echo htmlspecialchars($plan['cover_image'] ? 'Admin/' . $plan['cover_image'] : 'assets/images/resource/property-1.jpg'); ?>" alt="" />
									<div class="property-block_one-image-content">
										<h4 class="property-block_one-heading"><?php echo htmlspecialchars($plan['title']); ?></h4>
										<ul class="property-block_one-info">
											<li><span><img src="assets/images/icons/bed.svg" alt="" /></span><?php echo (int) $plan['bedrooms']; ?> Bed</li>
											<li><span><img src="assets/images/icons/bath.svg" alt="" /></span><?php echo rtrim(rtrim(number_format($plan['bathrooms'], 1), '0'), '.'); ?> Bath</li>
											<li><span><img src="assets/images/icons/square.svg" alt="" /></span><?php echo number_format($plan['area_sqft']); ?> sqft</li>
										</ul>
									</div>
								</a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<!-- End Modern Villas -->

	<!-- Other Categories -->
	<?php
		$home_other_categories = [
			'Villas' => 'Hand-Picked',
			'Apartments' => 'City Living',
			'Residential' => 'Everyday Comfort',
			'Hotels' => 'Hospitality',
			'Country Homes' => 'Rural Retreats',
		];
		foreach ($home_other_categories as $category_name => $category_eyebrow):
			$category_plans = $pdo->prepare(
				"SELECT p.*, (SELECT file_path FROM property_files pf WHERE pf.property_id = p.id ORDER BY pf.is_cover DESC, pf.id ASC LIMIT 1) AS cover_image
				 FROM properties p WHERE p.category = ? ORDER BY p.featured DESC, p.created_at DESC"
			);
			$category_plans->execute([$category_name]);
			$category_plans = $category_plans->fetchAll();
			if (!$category_plans) {
				continue;
			}
	?>
	<section class="property-one style-two">
		<div class="auto-container">
			<!-- Sec Title -->
			<div class="sec-title d-flex justify-content-between align-items-end flex-wrap">
				<div>
					<div class="sec-title_title"><?php echo htmlspecialchars($category_eyebrow); ?></div>
					<h2 class="sec-title_heading"><?php echo htmlspecialchars($category_name); ?></h2>
				</div>
				<a href="plans.php?category=<?php echo urlencode($category_name); ?>" class="theme-btn btn-style-two"><span class="btn-wrap"><span class="text-one">View All <?php echo htmlspecialchars($category_name); ?></span><span class="text-two">View All <?php echo htmlspecialchars($category_name); ?></span></span></a>
			</div>
			<div class="row clearfix">
				<?php foreach ($category_plans as $plan): ?>
					<!-- Property Block One / Style Two -->
					<div class="property-block_one style-two col-lg-3 col-md-6 col-sm-12">
						<div class="property-block_one-inner">
							<div class="property-block_one-image">
								<?php if ($plan['featured']): ?><div class="property-block_one-title">Featured</div><?php endif; ?>
								<a class="property-block_one-heart" href="plan-detail.php?slug=<?php echo urlencode($plan['slug']); ?>"><i class="flaticon-heart"></i></a>
								<a href="plan-detail.php?slug=<?php echo urlencode($plan['slug']); ?>" class="property-block_one-image-link">
									<img src="<?php echo htmlspecialchars($plan['cover_image'] ? 'Admin/' . $plan['cover_image'] : 'assets/images/resource/property-1.jpg'); ?>" alt="" />
									<div class="property-block_one-image-content">
										<h4 class="property-block_one-heading"><?php echo htmlspecialchars($plan['title']); ?></h4>
										<ul class="property-block_one-info">
											<li><span><img src="assets/images/icons/bed.svg" alt="" /></span><?php echo (int) $plan['bedrooms']; ?> Bed</li>
											<li><span><img src="assets/images/icons/bath.svg" alt="" /></span><?php echo rtrim(rtrim(number_format($plan['bathrooms'], 1), '0'), '.'); ?> Bath</li>
											<li><span><img src="assets/images/icons/square.svg" alt="" /></span><?php echo number_format($plan['area_sqft']); ?> sqft</li>
										</ul>
									</div>
								</a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endforeach; ?>
	<!-- End Other Categories -->

	<!-- Property One -->
	<section class="property-one style-two">
		<div class="auto-container">
			<!-- Sec Title -->
			<div class="sec-title">
				<div class="sec-title_title">Latest Plans</div>
				<h2 class="sec-title_heading">Explore Featured Plans</h2>
			</div>
			<?php
				$home_plans = $pdo->query(
					"SELECT p.*, (SELECT file_path FROM property_files pf WHERE pf.property_id = p.id ORDER BY pf.is_cover DESC, pf.id ASC LIMIT 1) AS cover_image
					 FROM properties p ORDER BY p.featured DESC, p.created_at DESC LIMIT 6"
				)->fetchAll();
			?>
			<div class="four-items_slider swiper-container">
				<div class="swiper-wrapper">

					<?php foreach ($home_plans as $plan): ?>
					<!-- Slide -->
					<div class="swiper-slide">
						<!-- Property Block One / Style Two -->
						<div class="property-block_one style-two">
							<div class="property-block_one-inner">
								<div class="property-block_one-image">
									<?php if ($plan['featured']): ?><div class="property-block_one-title">Featured</div><?php endif; ?>
									<a class="property-block_one-heart" href="plan-detail.php?slug=<?php echo urlencode($plan['slug']); ?>"><i class="flaticon-heart"></i></a>
									<a href="plan-detail.php?slug=<?php echo urlencode($plan['slug']); ?>" class="property-block_one-image-link">
										<img src="<?php echo htmlspecialchars($plan['cover_image'] ? 'Admin/' . $plan['cover_image'] : 'assets/images/resource/property-1.jpg'); ?>" alt="" />
										<div class="property-block_one-image-content">
											<h4 class="property-block_one-heading"><?php echo htmlspecialchars($plan['title']); ?></h4>
											<ul class="property-block_one-info">
												<li><span><img src="assets/images/icons/bed.svg" alt="" /></span><?php echo (int) $plan['bedrooms']; ?> Beds</li>
												<li><span><img src="assets/images/icons/bath.svg" alt="" /></span><?php echo rtrim(rtrim(number_format($plan['bathrooms'], 1), '0'), '.'); ?> Bathrooms</li>
												<li><span><img src="assets/images/icons/square.svg" alt="" /></span><?php echo number_format($plan['area_sqft']); ?> sqft</li>
											</ul>
										</div>
									</a>
								</div>
							</div>
						</div>
					</div>
					<?php endforeach; ?>

				</div>

				<!-- If we need pagination -->
				<div class="four-items_slider-pagination"></div>

				<!-- If we need navigation buttons -->
				<div class="four-items_slider-prev"><img src="assets/images/icons/prev-arrow.png" alt="" /></div>
				<div class="four-items_slider-next"><img src="assets/images/icons/next-arrow.png" alt="" /></div>

			</div>

		</div>
	</section>
	<!-- End Property One -->

	<!-- Latest Plans (infinite scroll) -->
	<?php
		require_once __DIR__ . '/parts/property-card.php';
		$latest_plans_page_size = 8;
		$home_latest_plans = fetch_properties_page($pdo, 0, $latest_plans_page_size);
	?>
	<?php if ($home_latest_plans): ?>
	<section class="latest-plans-one">
		<div class="auto-container">
			<!-- Sec Title -->
			<div class="sec-title">
				<div class="sec-title_title">Fresh Off The Press</div>
				<h2 class="sec-title_heading">Latest Plans</h2>
			</div>
			<div class="row clearfix" id="latest-plans-feed">
				<?php foreach ($home_latest_plans as $plan) { echo render_feed_card($plan); } ?>
			</div>
			<div id="latest-plans-sentinel"></div>
			<p id="latest-plans-loading" class="text-center" style="display:none;">Loading more plans&hellip;</p>
			<p id="latest-plans-end" class="text-center" style="display:none;"></p>
		</div>
	</section>
	<script>
	document.addEventListener('DOMContentLoaded', function () {
		var feed = document.getElementById('latest-plans-feed');
		var sentinel = document.getElementById('latest-plans-sentinel');
		var loadingEl = document.getElementById('latest-plans-loading');
		var endEl = document.getElementById('latest-plans-end');
		if (!feed || !sentinel) {
			return;
		}

		var offset = <?php echo (int) $latest_plans_page_size; ?>;
		var pageSize = <?php echo (int) $latest_plans_page_size; ?>;
		var loading = false;
		var hasMore = true;

		function initFeedCarousels(container) {
			container.querySelectorAll('[data-feed-carousel]:not([data-carousel-ready])').forEach(function (el) {
				el.setAttribute('data-carousel-ready', '1');
				var slides = el.querySelectorAll('.feed-card_slide');
				var dots = el.querySelectorAll('.feed-card_dot-indicator');
				if (slides.length < 2) {
					return;
				}
				var current = 0;
				setInterval(function () {
					slides[current].classList.remove('is-active');
					if (dots[current]) { dots[current].classList.remove('is-active'); }
					current = (current + 1) % slides.length;
					slides[current].classList.add('is-active');
					if (dots[current]) { dots[current].classList.add('is-active'); }
				}, 3500);
			});
		}

		initFeedCarousels(feed);

		var observer = new IntersectionObserver(function (entries) {
			if (entries[0].isIntersecting && hasMore && !loading) {
				loadMore();
			}
		}, { rootMargin: '400px' });
		observer.observe(sentinel);

		function loadMore() {
			loading = true;
			loadingEl.style.display = '';
			fetch('plans-feed.php?offset=' + offset)
				.then(function (res) { return res.text(); })
				.then(function (html) {
					loadingEl.style.display = 'none';
					if (!html.trim()) {
						hasMore = false;
						observer.disconnect();
						endEl.textContent = 'All plans loaded';
						endEl.style.display = '';
						return;
					}
					feed.insertAdjacentHTML('beforeend', html);
					initFeedCarousels(feed);
					offset += pageSize;
					loading = false;
				})
				.catch(function () {
					loadingEl.style.display = 'none';
					loading = false;
				});
		}
	});
	</script>
	<?php endif; ?>
	<!-- End Latest Plans -->

	<!-- Buy Ready Plans Banner -->
	<section class="plans-cta-one">
		<div class="auto-container">
			<a href="plans.php" class="plans-cta-one_inner">
				<div class="plans-cta-one_icon"><i class="flaticon-up-right-arrow"></i></div>
				<div class="plans-cta-one_content">
					<div class="plans-cta-one_label">Ready To Build</div>
					<h3 class="plans-cta-one_heading">Buy Ready House Plans</h3>
					<div class="plans-cta-one_text">Curated, architect-approved designs available now</div>
				</div>
				<div class="plans-cta-one_arrow"><i class="flaticon-up-right-arrow"></i></div>
			</a>
		</div>
	</section>
	<!-- End Buy Ready Plans Banner -->

	<!-- Services One -->
	<section class="services-one">
		<div class="auto-container">
			<!-- Sec Title -->
			<div class="sec-title">
				<div class="sec-title_title">Our Services</div>
				<h2 class="sec-title_heading">Take a stroll around surroundings.</h2>
			</div>
			<?php $home_service_cards = $pdo->query('SELECT * FROM home_service_cards ORDER BY sort_order, id')->fetchAll(); ?>
			<div class="three-items_slider swiper-container">
				<div class="swiper-wrapper">

					<?php foreach ($home_service_cards as $sc): ?>
						<!-- Slide -->
						<div class="swiper-slide">
							<!-- Service Block One -->
							<div class="service-block_one">
								<div class="service-block_one-inner">
									<div class="service-block_one_image" style="background-image:url(<?php echo htmlspecialchars($sc['image'] ? 'Admin/' . $sc['image'] : 'assets/images/resource/services-1.jpg'); ?>)"></div>
									<div class="service-block_one-icon">
										<i class="<?php echo htmlspecialchars($sc['icon_class'] ?: 'flaticon-building'); ?>"></i>
									</div>
									<h4 class="service-block_one-heading"><a href="<?php echo htmlspecialchars($sc['link_url'] ?: '#'); ?>"><?php echo htmlspecialchars($sc['title']); ?></a></h4>
									<div class="service-block_one-text"><?php echo htmlspecialchars($sc['description']); ?></div>
									<a class="service-block_one-more" href="<?php echo htmlspecialchars($sc['link_url'] ?: '#'); ?>">Read More <i class="flaticon-next-1"></i></a>
								</div>
							</div>
						</div>
					<?php endforeach; ?>

				</div>

				<!-- If we need pagination -->
				<div class="three-items_slider-pagination"></div>

			</div>
		</div>
	</section>
	<!-- End Services One -->

	<!-- Projects One -->
	<?php
		$home_projects = $pdo->query(
			"SELECT p.*, (SELECT file_path FROM project_files pf WHERE pf.project_id = p.id ORDER BY pf.is_cover DESC, pf.id ASC LIMIT 1) AS cover_image
			 FROM projects p ORDER BY p.featured DESC, p.created_at DESC LIMIT 6"
		)->fetchAll();
	?>
	<?php if ($home_projects): ?>
	<section class="projects-one">
		<div class="auto-container">
			<!-- Sec Title -->
			<div class="sec-title centered">
				<div class="sec-title_title">Our Portfolio</div>
				<h2 class="sec-title_heading">From Blueprint To Reality</h2>
			</div>
			<div class="projects-items_slider swiper-container">
				<div class="swiper-wrapper">
					<?php foreach ($home_projects as $proj): ?>
						<div class="swiper-slide">
							<a href="project-detail.php?slug=<?php echo urlencode($proj['slug']); ?>" class="construction-project-card">
								<div class="construction-project-card_image">
									<img src="<?php echo htmlspecialchars($proj['cover_image'] ? 'Admin/' . $proj['cover_image'] : 'assets/images/resource/property-1.jpg'); ?>" alt="">
								</div>
								<div class="construction-project-card_body">
									<h6><?php echo htmlspecialchars($proj['title']); ?></h6>
									<?php if ($proj['category'] || $proj['location']): ?>
										<div class="construction-project-card_meta"><?php echo htmlspecialchars(trim($proj['category'] . ($proj['category'] && $proj['location'] ? ' · ' : '') . $proj['location'])); ?></div>
									<?php endif; ?>
								</div>
							</a>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="projects-items_slider-pagination"></div>
			</div>
		</div>
	</section>
	<script>
	document.addEventListener('DOMContentLoaded', function () {
		new Swiper('.projects-items_slider', {
			slidesPerView: 3,
			spaceBetween: 30,
			loop: true,
			autoplay: { delay: 6000 },
			pagination: { el: '.projects-items_slider-pagination', clickable: true },
			breakpoints: {
				992: { slidesPerView: 3 },
				576: { slidesPerView: 2 },
				0: { slidesPerView: 1 }
			}
		});
	});
	</script>
	<?php endif; ?>
	<!-- End Projects One -->

	<!-- Cta One -->
	<section class="cta-one">
		<div class="auto-container">
			<div class="cta-one_inner-container d-flex justify-content-between align-items-center flex-wrap">
				<h1 class="cta-one_title end">let's talk <i><img src="assets/images/icons/arrow.png" alt="" /></i></h1>
				<div class="cta-one_btn">
					<a class="theme-btn" href="contact.php">Contact Us Now</a>
				</div>
			</div>
		</div>
	</section>
	<!-- End Cta One -->
	
	<!-- Main Footer -->
<?php require_once __DIR__ . '/parts/footer.php'; ?>
