<?php $page_title = "About Us"; $home = false; ?>
<?php require_once __DIR__ . '/parts/header.php'; ?>
	<!-- End Main Header -->
	
	<!-- Page Title -->
    <section class="page-title">
		<div class="page-title_cloud" style="background-image:url(assets/images/icons/cloud.png)"></div>
		<div class="page-title_cloud-two" style="background-image:url(assets/images/icons/cloud-1.png)"></div>
		<div class="page-title_pattern" style="background-image:url(assets/images/background/pattern-3.png)"></div>
		<div class="page-title_gradient"></div>
        <div class="auto-container">
			<h2>About Us</h2>
        </div>
    </section>
    <!-- End Page Title -->
	
	<!-- Story One -->
	<section class="story-three">
		<div class="auto-container">
			<div class="row clearfix">
				
				<?php
					require_once __DIR__ . '/parts/sections.php';
					$about_story = get_page_section($pdo, 'about', 'story');
					$story_image = !empty($about_story['image']) ? 'Admin/' . $about_story['image'] : 'assets/images/resource/story-4.jpg';
					$story_image2 = !empty($about_story['image2']) ? 'Admin/' . $about_story['image2'] : 'assets/images/resource/story-5.jpg';
					$story_tabs = $pdo->query('SELECT * FROM about_story_tabs WHERE id = 1')->fetch() ?: [];
				?>
				<!-- Image Column -->
				<div class="story-three_image-column col-lg-6 col-md-12 col-sm-12">
					<div class="story-three_image-outer">
						<div class="story-three_pattern" style="background-image:url(assets/images/background/pattern-1.png)"></div>
						<span class="story-three_color"></span>
						<div class="row clearfix">
							<div class="column col-lg-7 col-md-6 col-sm-6">
								<div class="story-three_image">
									<img src="<?php echo htmlspecialchars($story_image); ?>" alt="" />
								</div>
							</div>
							<div class="column col-lg-5 col-md-6 col-sm-6">
								<div class="story-three_image">
									<img src="<?php echo htmlspecialchars($story_image2); ?>" alt="" />
								</div>
								<div class="story-three_clients">
									<div class="story-three_inner">
										<i class="flaticon-rating"></i>
										<?php echo htmlspecialchars(($story_tabs['badge_text'] ?? '') ?: 'Client Centric Approach'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Content Column -->
				<div class="story-three_content-column col-lg-6 col-md-12 col-sm-12">
					<div class="story-three_content-outer">
						<!-- Sec Title -->
						<div class="sec-title">
							<div class="sec-title_title"><?php echo htmlspecialchars(($about_story['subheading'] ?? '') ?: 'Discover Our Story'); ?></div>
							<h2 class="sec-title_heading"><?php echo htmlspecialchars(($about_story['heading'] ?? '') ?: 'Building Homes People Are Proud Of'); ?></h2>
							<?php if (!empty($about_story['body'])): ?><div class="sec-title_text"><?php echo htmlspecialchars($about_story['body']); ?></div><?php endif; ?>
						</div>
						
						<!-- Story Tabs -->
						<div class="story-tabs">
							<!-- Story Tabs -->
							<div class="story-tab tabs-box">
								
								<!-- Tab Btns -->
								<ul class="tab-btns tab-buttons clearfix">
									<li data-tab="#prod-mission" class="tab-btn active-btn">Mission</li>
									<li data-tab="#prod-vission" class="tab-btn">Vission</li>
									<li data-tab="#prod-goal" class="tab-btn">Goal</li>
								</ul>
								
								<!-- Tabs Container -->
								<div class="tabs-content">
									
									<!-- Tab -->
									<div class="tab active-tab" id="prod-mission">
										<div class="content">
											<div class="text"><?php echo htmlspecialchars($story_tabs['mission_text'] ?? ''); ?></div>
											<ul class="story-three_checklist">
												<?php if (!empty($story_tabs['mission_check1'])): ?><li><i class="flaticon-checked"></i> <?php echo htmlspecialchars($story_tabs['mission_check1']); ?></li><?php endif; ?>
												<?php if (!empty($story_tabs['mission_check2'])): ?><li><i class="flaticon-checked"></i> <?php echo htmlspecialchars($story_tabs['mission_check2']); ?></li><?php endif; ?>
											</ul>
										</div>
									</div>

									<!-- Tab -->
									<div class="tab" id="prod-vission">
										<div class="content">
											<div class="text"><?php echo htmlspecialchars($story_tabs['vission_text'] ?? ''); ?></div>
											<ul class="story-three_checklist">
												<?php if (!empty($story_tabs['vission_check1'])): ?><li><i class="flaticon-checked"></i> <?php echo htmlspecialchars($story_tabs['vission_check1']); ?></li><?php endif; ?>
												<?php if (!empty($story_tabs['vission_check2'])): ?><li><i class="flaticon-checked"></i> <?php echo htmlspecialchars($story_tabs['vission_check2']); ?></li><?php endif; ?>
											</ul>
										</div>
									</div>

									<!-- Tab -->
									<div class="tab" id="prod-goal">
										<div class="content">
											<div class="text"><?php echo htmlspecialchars($story_tabs['goal_text'] ?? ''); ?></div>
											<ul class="story-three_checklist">
												<?php if (!empty($story_tabs['goal_check1'])): ?><li><i class="flaticon-checked"></i> <?php echo htmlspecialchars($story_tabs['goal_check1']); ?></li><?php endif; ?>
												<?php if (!empty($story_tabs['goal_check2'])): ?><li><i class="flaticon-checked"></i> <?php echo htmlspecialchars($story_tabs['goal_check2']); ?></li><?php endif; ?>
											</ul>
										</div>
									</div>
									
								</div>
							</div>
						</div>
						
					</div>
				</div>
				
			</div>
		</div>
	</section>
	<!-- End Story Three -->
	
	<!-- Services One -->
	<?php $home_service_cards = $pdo->query('SELECT * FROM home_service_cards ORDER BY sort_order, id')->fetchAll(); ?>
	<section class="services-one style-two">
		<div class="auto-container">
			<!-- Sec Title -->
			<div class="sec-title centered">
				<div class="sec-title_title">Our Services</div>
				<h2 class="sec-title_heading">Take a stroll around <br> surroundings.</h2>
			</div>
			<div class="row clearfix">

				<?php foreach ($home_service_cards as $sc): ?>
					<!-- Service Block One -->
					<div class="service-block_one col-lg-4 col-md-6 col-sm-12">
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
				<?php endforeach; ?>

			</div>
		</div>
	</section>
	<!-- End Services One -->
	
	<!-- Team One -->
	<?php
		$team_members = $pdo->query('SELECT * FROM team_members ORDER BY sort_order, id')->fetchAll();
		function about_team_image_src($path) {
			if (empty($path)) {
				return 'assets/images/resource/team-1.png';
			}
			return strpos($path, 'uploads/') === 0 ? 'Admin/' . $path : $path;
		}
	?>
	<section class="team-one">
		<div class="auto-container">
			<!-- Sec Title -->
			<div class="sec-title">
				<div class="sec-title_title">Our teams</div>
				<h2 class="sec-title_heading">Meet  our Real Estates <br> Professional</h2>
			</div>
			<div class="four-items_slider swiper-container">
				<div class="swiper-wrapper">

					<?php foreach ($team_members as $tm): ?>
						<!-- Slide -->
						<div class="swiper-slide">
							<!-- Team Block One -->
							<div class="team-block_one">
								<div class="team-block_one-inner">
									<div class="team-block_one-image_outer">
										<div class="team-block_one-image">
											<img src="<?php echo htmlspecialchars(about_team_image_src($tm['image'])); ?>" alt="" />
											<!-- Socials -->
											<div class="team-block_one-socials">
												<div class="team-block_one-socials-inner">
													<?php if (!empty($tm['facebook_url'])): ?><a class="fa-brands fa-facebook-f fa-fw" href="<?php echo htmlspecialchars($tm['facebook_url']); ?>" target="_blank"></a><?php endif; ?>
													<?php if (!empty($tm['instagram_url'])): ?><a class="fa-brands fa-instagram fa-fw" href="<?php echo htmlspecialchars($tm['instagram_url']); ?>" target="_blank"></a><?php endif; ?>
													<?php if (!empty($tm['twitter_url'])): ?><a class="fa-brands fa-twitter fa-fw" href="<?php echo htmlspecialchars($tm['twitter_url']); ?>" target="_blank"></a><?php endif; ?>
													<?php if (!empty($tm['youtube_url'])): ?><a class="fa-brands fa-youtube fa-fw" href="<?php echo htmlspecialchars($tm['youtube_url']); ?>" target="_blank"></a><?php endif; ?>
												</div>
											</div>
										</div>
									</div>
									<div class="team-block_one-content">
										<div class="d-flex justify-content-between align-items-center flex-wrap">
											<div class="content">
												<h6 class="team-block_one-heading"><a href="about.php"><?php echo htmlspecialchars($tm['name']); ?></a></h6>
												<div class="team-block_one-designation"><?php echo htmlspecialchars($tm['designation'] ?: ''); ?></div>
											</div>
											<div class="team-block_one-icon"><img src="assets/images/icons/chat.svg" alt="" /></div>
										</div>
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
	<!-- End Team One -->
	
	<!-- Video One -->
	<?php
		$about_video = get_page_section($pdo, 'about', 'video');
		$video_bg = !empty($about_video['image']) ? 'Admin/' . $about_video['image'] : 'assets/images/background/3.jpg';
	?>
	<section class="video-one">
		<div class="video-one_bg" style="background-image:url(<?php echo htmlspecialchars($video_bg); ?>)"></div>
		<div class="auto-container">
			<div class="video-one_content">
				<a href="https://www.youtube.com/watch?v=YS3PwmOQ1Fc" class="lightbox-video video-one_play"><span class="fa fa-play"><i class="ripple"></i></span></a>
				<h2 class="video-one_heading"><?php echo htmlspecialchars(($about_video['heading'] ?? '') ?: 'Take a stroll around surroundings.'); ?></h2>
			</div>
		</div>
	</section>
	<!-- End Video One -->
	
	<!-- Testimonial Three -->
	<section class="testimonial-three style-two">
		<div class="auto-container">
			<div class="inner-container">
				<!-- Sec Title -->
				<div class="sec-title">
					<div class="sec-title_title">Explore Cities</div>
					<h2 class="sec-title_heading">Client Testimonials</h2>
				</div>
				<?php
					$testimonials = $pdo->query('SELECT * FROM testimonials ORDER BY sort_order, id')->fetchAll();
					function about_testimonial_image_src($path) {
						if (empty($path)) {
							return 'assets/images/resource/author-4.png';
						}
						return strpos($path, 'uploads/') === 0 ? 'Admin/' . $path : $path;
					}
				?>
				<div class="two-items_slider swiper-container">
					<div class="swiper-wrapper">

						<?php foreach ($testimonials as $t): ?>
							<!-- Slide -->
							<div class="swiper-slide">
								<!-- Testimonial Block Two -->
								<div class="testimonial-block_three">
									<div class="testimonial-block_three-inner">
										<div class="testimonial-block_three-rating">
											<?php for ($i = 0; $i < (int) $t['rating']; $i++): ?><span class="fa fa-star"></span><?php endfor; ?>
										</div>
										<div class="testimonial-block_three-text"><?php echo htmlspecialchars($t['testimonial']); ?></div>
										<div class="testimonial-block_three-author">
											<span><img src="<?php echo htmlspecialchars(about_testimonial_image_src($t['image'])); ?>" alt="" /></span>
											<strong><?php echo htmlspecialchars($t['name']); ?></strong>
											<?php echo htmlspecialchars($t['role'] ?: ''); ?>
										</div>
										<div class="testimonial-block_three-quote"><img src="assets/images/icons/quote-1.svg" alt="" /></div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>

					</div>
					
					<!-- If we need pagination -->
					<div class="two-items_slider-pagination"></div>
					
					<!-- If we need navigation buttons -->
					<div class="two-items_slider-prev"><img src="assets/images/icons/prev-arrow.png" alt="" /></div>
					<div class="two-items_slider-next"><img src="assets/images/icons/next-arrow.png" alt="" /></div>
					
				</div>
			</div>
		</div>
	</section>
	<!-- End Testimonial Three -->
	
	<!-- Clients One -->
	<?php
		$client_logos = $pdo->query('SELECT * FROM client_logos ORDER BY sort_order, id')->fetchAll();
		function about_client_logo_src($path) {
			if (empty($path)) {
				return null;
			}
			return strpos($path, 'uploads/') === 0 ? 'Admin/' . $path : $path;
		}
	?>
	<section class="clients-one style-two">
		<div class="auto-container">
			<div class="title-box">
				Our trusted clients
			</div>
			<div class="inner-container">
				<div class="clients_slider swiper-container">
					<div class="swiper-wrapper">

						<?php foreach ($client_logos as $l): ?>
							<!-- Slide -->
							<div class="swiper-slide">
								<div class="client-image">
									<a href="<?php echo htmlspecialchars($l['link_url'] ?: '#'); ?>"><img src="<?php echo htmlspecialchars(about_client_logo_src($l['image'])); ?>" alt="" /></a>
								</div>
							</div>
						<?php endforeach; ?>

					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Clients One -->
	
	<!-- Main Footer -->
<?php require_once __DIR__ . '/parts/footer.php'; ?>
