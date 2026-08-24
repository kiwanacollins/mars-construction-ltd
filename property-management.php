<?php $page_title = "Property Management"; $home = false; ?>
<?php require_once __DIR__ . '/parts/header.php'; ?>
	<!-- End Main Header -->

	<!-- Page Title -->
    <section class="page-title">
		<div class="page-title_cloud" style="background-image:url(assets/images/icons/cloud.png)"></div>
		<div class="page-title_cloud-two" style="background-image:url(assets/images/icons/cloud-1.png)"></div>
		<div class="page-title_pattern" style="background-image:url(assets/images/background/pattern-3.png)"></div>
		<div class="page-title_gradient"></div>
        <div class="auto-container">
			<h2>Property Management</h2>
        </div>
    </section>
    <!-- End Page Title -->

	<!-- Property Management Hero -->
	<?php
		require_once __DIR__ . '/parts/sections.php';
		$pm_intro = get_page_section($pdo, 'property-management', 'intro');
		$pm_image = !empty($pm_intro['image']) ? 'Admin/' . $pm_intro['image'] : 'assets/images/resource/property-1.jpg';
		$pm_image2 = !empty($pm_intro['image2']) ? 'Admin/' . $pm_intro['image2'] : 'assets/images/resource/property-2.jpg';
	?>
	<section class="story-three construction-hero pm-hero">
		<div class="auto-container">
			<div class="row clearfix">

				<!-- Image Column -->
				<div class="story-three_image-column col-lg-6 col-md-12 col-sm-12">
					<div class="story-three_image-outer">
						<div class="story-three_pattern" style="background-image:url(assets/images/background/pattern-1.png)"></div>
						<span class="story-three_color pm-hero_color"></span>
						<div class="row clearfix">
							<div class="column col-lg-7 col-md-6 col-sm-6">
								<div class="story-three_image">
									<img src="<?php echo htmlspecialchars($pm_image); ?>" alt="" />
								</div>
							</div>
							<div class="column col-lg-5 col-md-6 col-sm-6">
								<div class="story-three_image">
									<img src="<?php echo htmlspecialchars($pm_image2); ?>" alt="" />
								</div>
								<div class="story-three_clients pm-hero_badge">
									<div class="story-three_inner">
										<i class="flaticon-house"></i>
										Protecting Your Investment
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
							<div class="sec-title_title"><?php echo htmlspecialchars(($pm_intro['subheading'] ?? '') ?: 'Property Management'); ?></div>
							<h2 class="sec-title_heading"><?php echo htmlspecialchars(($pm_intro['heading'] ?? '') ?: 'Property Management'); ?></h2>
						</div>
						<?php if (!empty($pm_intro['body'])): ?>
							<?php foreach (explode("\n", $pm_intro['body']) as $para): ?>
								<?php if (trim($para) !== ''): ?><div class="construction-hero_text"><?php echo htmlspecialchars(trim($para)); ?></div><?php endif; ?>
							<?php endforeach; ?>
						<?php else: ?>
							<div class="construction-hero_text">Once your home is built, Mars Construction can stay on as your property manager. We handle the day-to-day upkeep, coordinate repairs, and keep your investment in top condition, whether it's your primary residence, a second home, or a rental property.</div>
							<div class="construction-hero_text">Our team acts as a single point of contact for maintenance, vendor coordination, and inspections, so you're not chasing down contractors on your own.</div>
						<?php endif; ?>
						<?php if (!empty($pm_intro['check1']) || !empty($pm_intro['check2'])): ?>
							<ul class="story-three_checklist">
								<?php if (!empty($pm_intro['check1'])): ?><li><i class="flaticon-checked"></i> <?php echo htmlspecialchars($pm_intro['check1']); ?></li><?php endif; ?>
								<?php if (!empty($pm_intro['check2'])): ?><li><i class="flaticon-checked"></i> <?php echo htmlspecialchars($pm_intro['check2']); ?></li><?php endif; ?>
							</ul>
						<?php else: ?>
							<ul class="story-three_checklist">
								<li><i class="flaticon-checked"></i> One point of contact for maintenance and vendors</li>
								<li><i class="flaticon-checked"></i> Fast response on repairs and inspections</li>
							</ul>
						<?php endif; ?>
						<a href="<?php echo htmlspecialchars(($pm_intro['button_link'] ?? '') ?: 'contact.php'); ?>" class="btn-style-one construction-hero_btn"><span class="txt"><?php echo htmlspecialchars(($pm_intro['button_text'] ?? '') ?: 'Get Started'); ?></span></a>
					</div>
				</div>

			</div>
		</div>
	</section>
	<!-- End Property Management Hero -->

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
								<?php $pm_handles = $pdo->query('SELECT * FROM pm_handles ORDER BY sort_order, id')->fetchAll(); ?>
								<?php if ($pm_handles): ?>
								<div class="pm-handle_wrap">
									<div class="three-items_slider swiper-container handle-slider">
										<div class="swiper-wrapper">
											<?php foreach ($pm_handles as $h): ?>
												<div class="swiper-slide">
													<div class="plan-category-card">
														<div class="plan-category-card_image">
															<img src="<?php echo htmlspecialchars($h['image'] ? 'Admin/' . $h['image'] : 'assets/images/resource/services.jpg'); ?>" alt="">
														</div>
														<div class="plan-category-card_name"><?php echo htmlspecialchars($h['title']); ?></div>
													</div>
												</div>
											<?php endforeach; ?>
										</div>
										<div class="three-items_slider-pagination"></div>
									</div>
								</div>
								<?php endif; ?>

								<div class="sec-title centered">
									<div class="sec-title_title">By The Numbers</div>
									<h4 class="service-detail_subheading">Why Owners Choose Us</h4>
								</div>
								<?php $pm_stats = $pdo->query('SELECT * FROM pm_stats ORDER BY sort_order, id')->fetchAll(); ?>
								<?php if ($pm_stats): ?>
								<div class="stat-row">
									<?php foreach ($pm_stats as $s): ?>
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


								<div class="sec-title centered">
									<div class="sec-title_title">FAQ</div>
									<h4 class="service-detail_subheading">Common Questions</h4>
								</div>
								<?php $pm_faqs = $pdo->query('SELECT * FROM pm_faqs ORDER BY sort_order, id')->fetchAll(); ?>
								<?php if ($pm_faqs): ?>
								<div class="pm-faq_wrap">
									<ul class="accordion-box">
										<?php foreach ($pm_faqs as $i => $f): ?>
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
								</div>
								<?php endif; ?>

								<div class="sec-title centered">
									<div class="sec-title_title">Get In Touch</div>
									<h4 class="service-detail_subheading">Inquire About Property Management</h4>
								</div>
								<div class="message-form pm-inquiry_form">
									<form method="post" action="send-message.php">
										<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
										<input type="hidden" name="subject" value="Property management inquiry">
										<input type="hidden" name="redirect" value="property-management.php">
										<div class="row clearfix">
											<div class="form-group col-lg-6 col-md-6 col-sm-12">
												<input type="text" name="name" value="" placeholder="Name*" required>
											</div>
											<div class="form-group col-lg-6 col-md-6 col-sm-12">
												<input type="email" name="email" value="" placeholder="Email*" required>
											</div>
											<div class="form-group col-12">
												<input type="tel" name="phone" value="" placeholder="Phone Number*" required>
											</div>
											<div class="form-group col-12">
												<textarea class="" name="message" placeholder="Tell us about your property - address, type, and what you need help with." required></textarea>
											</div>
											<div class="form-group col-12">
												<button type="submit" class="template-btn btn-style-one">
													<span class="btn-wrap">
														<span class="text-one">submit now</span>
														<span class="text-two">submit now</span>
													</span>
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- Main Footer -->
<?php require_once __DIR__ . '/parts/footer.php'; ?>
