<?php $page_title = "Construction"; $home = false; ?>
<?php require_once __DIR__ . '/parts/header.php'; ?>
	<!-- End Main Header -->

	<!-- Page Title -->
    <section class="page-title">
		<div class="page-title_cloud" style="background-image:url(assets/images/icons/cloud.png)"></div>
		<div class="page-title_cloud-two" style="background-image:url(assets/images/icons/cloud-1.png)"></div>
		<div class="page-title_pattern" style="background-image:url(assets/images/background/pattern-3.png)"></div>
		<div class="page-title_gradient"></div>
        <div class="auto-container">
			<h2>Construction</h2>
        </div>
    </section>
    <!-- End Page Title -->

	<!-- Construction Hero -->
	<?php
		require_once __DIR__ . '/parts/sections.php';
		$con_intro = get_page_section($pdo, 'construction', 'intro');
		$con_image = !empty($con_intro['image']) ? 'Admin/' . $con_intro['image'] : 'assets/images/resource/story-1.jpg';
		$con_image2 = !empty($con_intro['image2']) ? 'Admin/' . $con_intro['image2'] : 'assets/images/resource/services-2.jpg';
	?>
	<section class="story-three construction-hero">
		<div class="auto-container">
			<div class="row clearfix">

				<!-- Image Column -->
				<div class="story-three_image-column col-lg-6 col-md-12 col-sm-12">
					<div class="story-three_image-outer">
						<div class="story-three_pattern" style="background-image:url(assets/images/background/pattern-1.png)"></div>
						<span class="story-three_color construction-hero_color"></span>
						<div class="row clearfix">
							<div class="column col-lg-7 col-md-6 col-sm-6">
								<div class="story-three_image">
									<img src="<?php echo htmlspecialchars($con_image); ?>" alt="" />
								</div>
							</div>
							<div class="column col-lg-5 col-md-6 col-sm-6">
								<div class="story-three_image">
									<img src="<?php echo htmlspecialchars($con_image2); ?>" alt="" />
								</div>
								<div class="story-three_clients construction-hero_badge">
									<div class="story-three_inner">
										<i class="flaticon-building"></i>
										Built On Trust
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Content Column -->
				<div class="story-three_content-column col-lg-6 col-md-12 col-sm-12">
					<div class="story-three_content-outer">
						<div class="sec-title">
							<div class="sec-title_title"><?php echo htmlspecialchars(($con_intro['subheading'] ?? '') ?: 'General Construction'); ?></div>
							<h2 class="sec-title_heading"><?php echo htmlspecialchars(($con_intro['heading'] ?? '') ?: 'Construction'); ?></h2>
						</div>
						<?php if (!empty($con_intro['body'])): ?>
							<?php foreach (explode("\n", $con_intro['body']) as $para): ?>
								<?php if (trim($para) !== ''): ?><div class="construction-hero_text"><?php echo htmlspecialchars(trim($para)); ?></div><?php endif; ?>
							<?php endforeach; ?>
						<?php else: ?>
							<div class="construction-hero_text">Mars Construction handles every stage of the build, from breaking ground to handing over the keys. Our in-house teams manage site preparation, framing, structural work, and finishing so you deal with one accountable partner instead of a patchwork of subcontractors.</div>
							<div class="construction-hero_text">Whether you're building from one of our house plans or bringing your own architectural drawings, we scope the project, hold to the schedule, and keep you informed at every milestone.</div>
						<?php endif; ?>
						<?php if (!empty($con_intro['check1']) || !empty($con_intro['check2'])): ?>
							<ul class="story-three_checklist">
								<?php if (!empty($con_intro['check1'])): ?><li><i class="flaticon-checked"></i> <?php echo htmlspecialchars($con_intro['check1']); ?></li><?php endif; ?>
								<?php if (!empty($con_intro['check2'])): ?><li><i class="flaticon-checked"></i> <?php echo htmlspecialchars($con_intro['check2']); ?></li><?php endif; ?>
							</ul>
						<?php else: ?>
							<ul class="story-three_checklist">
								<li><i class="flaticon-checked"></i> One accountable team from groundbreaking to handover</li>
								<li><i class="flaticon-checked"></i> Transparent scheduling and milestone updates</li>
							</ul>
						<?php endif; ?>
						<a href="<?php echo htmlspecialchars(($con_intro['button_link'] ?? '') ?: 'contact.php'); ?>" class="btn-style-one construction-hero_btn"><span class="txt"><?php echo htmlspecialchars(($con_intro['button_text'] ?? '') ?: 'Request A Quote'); ?></span></a>
					</div>
				</div>

			</div>
		</div>
	</section>
	<!-- End Construction Hero -->

	<!-- Sidebar Page Container -->
    <div class="sidebar-page-container style-two">
    	<div class="auto-container">
        	<div class="row clearfix">

				<!-- Content Side -->
                <div class="content-side col-lg-12 col-md-12 col-sm-12">
					<div class="service-detail">
						<div class="service-detail_inner">
							<div class="service-detail_content">
								<div class="sec-title centered">
									<div class="sec-title_title">Our Capabilities</div>
									<h4 class="service-detail_subheading">What We Handle</h4>
								</div>
								<?php $construction_handles = $pdo->query('SELECT * FROM construction_handles ORDER BY sort_order, id')->fetchAll(); ?>
								<?php if ($construction_handles): ?>
								<div class="four-items_slider swiper-container handle-slider">
									<div class="swiper-wrapper">
										<?php foreach ($construction_handles as $h): ?>
											<div class="swiper-slide">
												<div class="plan-category-card">
													<div class="plan-category-card_image">
														<img src="<?php echo htmlspecialchars($h['image'] ? 'Admin/' . $h['image'] : 'assets/images/resource/services-1.jpg'); ?>" alt="">
													</div>
													<div class="plan-category-card_name"><?php echo htmlspecialchars($h['title']); ?></div>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
									<div class="four-items_slider-pagination"></div>
								</div>
								<?php endif; ?>
								<div class="sec-title centered">
									<div class="sec-title_title">By The Numbers</div>
									<h4 class="service-detail_subheading">Project Track Record</h4>
								</div>
								<?php $construction_stats = $pdo->query('SELECT * FROM construction_stats ORDER BY sort_order, id')->fetchAll(); ?>
								<?php if ($construction_stats): ?>
								<div class="stats-card stat-row">
									<?php foreach ($construction_stats as $s): ?>
										<div class="stat-item">
											<div class="stat-item_value"><span class="stat-counter" data-count="<?php echo (int) $s['value']; ?>">0</span><?php echo htmlspecialchars($s['suffix']); ?></div>
											<div class="stat-item_label"><?php echo htmlspecialchars($s['label']); ?></div>
										</div>
									<?php endforeach; ?>
								</div>
								<script>
								document.addEventListener('DOMContentLoaded', function () {
									var counters = document.querySelectorAll('.stat-counter');
									if (!counters.length) return;
									var animate = function (el) {
										var target = parseInt(el.getAttribute('data-count'), 10) || 0;
										var start = 0;
										var duration = 1200;
										var startTime = null;
										function step(ts) {
											if (!startTime) startTime = ts;
											var progress = Math.min((ts - startTime) / duration, 1);
											el.textContent = Math.floor(start + (target - start) * progress);
											if (progress < 1) requestAnimationFrame(step);
											else el.textContent = target;
										}
										requestAnimationFrame(step);
									};
									if ('IntersectionObserver' in window) {
										var observer = new IntersectionObserver(function (entries) {
											entries.forEach(function (entry) {
												if (entry.isIntersecting) {
													animate(entry.target);
													observer.unobserve(entry.target);
												}
											});
										}, { threshold: 0.4 });
										counters.forEach(function (el) { observer.observe(el); });
									} else {
										counters.forEach(animate);
									}
								});
								</script>
								<?php endif; ?>
								<?php $projects = $pdo->query('SELECT p.*, (SELECT file_path FROM project_files pf WHERE pf.project_id = p.id ORDER BY pf.is_cover DESC, pf.id ASC LIMIT 1) AS cover_image FROM projects p ORDER BY p.featured DESC, p.created_at DESC LIMIT 9')->fetchAll(); ?>
								<?php if ($projects): ?>
								<div class="sec-title centered">
									<div class="sec-title_title">Our Portfolio</div>
									<h4 class="service-detail_subheading">Projects We've Handled</h4>
								</div>
								<div class="construction-projects_slider swiper-container">
									<div class="swiper-wrapper">
										<?php foreach ($projects as $proj): ?>
											<div class="swiper-slide">
												<a href="project-detail.php?slug=<?php echo urlencode($proj['slug']); ?>" class="construction-project-card">
													<div class="construction-project-card_image">
														<img src="<?php echo htmlspecialchars($proj['cover_image'] ? 'Admin/' . $proj['cover_image'] : 'assets/images/resource/property-1.jpg'); ?>" alt="">
														<div class="construction-project-card_overlay"></div>
														<?php if ($proj['category']): ?><span class="construction-project-card_badge"><?php echo htmlspecialchars($proj['category']); ?></span><?php endif; ?>
													</div>
													<div class="construction-project-card_body">
														<h6><?php echo htmlspecialchars($proj['title']); ?></h6>
														<?php if ($proj['location']): ?>
															<div class="construction-project-card_meta"><i class="flaticon-pin"></i><?php echo htmlspecialchars($proj['location']); ?></div>
														<?php endif; ?>
														<span class="construction-project-card_cta">View Project <i class="flaticon-next-1"></i></span>
													</div>
												</a>
											</div>
										<?php endforeach; ?>
									</div>
									<div class="construction-projects_slider-pagination"></div>
									<div class="construction-projects_slider-prev"><i class="fa fa-angle-left"></i></div>
									<div class="construction-projects_slider-next"><i class="fa fa-angle-right"></i></div>
								</div>
								<script>
								document.addEventListener('DOMContentLoaded', function () {
									new Swiper('.construction-projects_slider', {
										slidesPerView: 3,
										spaceBetween: 24,
										loop: true,
										autoplay: { delay: 6000 },
										navigation: {
											nextEl: '.construction-projects_slider-next',
											prevEl: '.construction-projects_slider-prev'
										},
										pagination: { el: '.construction-projects_slider-pagination', clickable: true },
										breakpoints: {
											992: { slidesPerView: 3 },
											768: { slidesPerView: 2 },
											0: { slidesPerView: 1 }
										}
									});
								});
								</script>
								<?php endif; ?>

								<div class="sec-title centered">
									<div class="sec-title_title">FAQ</div>
									<h4 class="service-detail_subheading">Common Questions</h4>
								</div>
								<?php $construction_faqs = $pdo->query('SELECT * FROM construction_faqs ORDER BY sort_order, id')->fetchAll(); ?>
								<?php if ($construction_faqs): ?>
								<!-- Accordion Box -->
								<ul class="accordion-box">
									<?php foreach ($construction_faqs as $i => $f): ?>
										<!-- Block -->
										<li class="accordion block<?php echo $i === 0 ? ' active-block' : ''; ?>">
											<div class="acc-btn<?php echo $i === 0 ? ' active' : ''; ?>"><div class="icon-outer"><span class="icon icon-plus flaticon-plus"></span></div><?php echo htmlspecialchars($f['question']); ?></div>
											<div class="acc-content<?php echo $i === 0 ? ' current' : ''; ?>">
												<div class="content">
													<div class="text"><?php echo htmlspecialchars($f['answer']); ?></div>
												</div>
											</div>
										</li>
									<?php endforeach; ?>
								</ul>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- Main Footer -->
<?php require_once __DIR__ . '/parts/footer.php'; ?>
