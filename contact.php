<?php $page_title = "Contact"; $home = false; ?>
<?php require_once __DIR__ . '/parts/header.php'; ?>
<?php
$contact_status = null;
if (isset($_SESSION['form_status'])) {
    $contact_status = $_SESSION['form_status'];
    unset($_SESSION['form_status']);
}
?>
	<!-- End Main Header -->
	
	<!-- Page Title -->
    <section class="page-title">
		<div class="page-title_cloud" style="background-image:url(assets/images/icons/cloud.png)"></div>
		<div class="page-title_cloud-two" style="background-image:url(assets/images/icons/cloud-1.png)"></div>
		<div class="page-title_pattern" style="background-image:url(assets/images/background/pattern-3.png)"></div>
		<div class="page-title_gradient"></div>
        <div class="auto-container">
			<h2>Contact us</h2>
        </div>
    </section>
    <!-- End Page Title -->
	
	<!-- Contact Three -->
	<section class="contact-three">
		<div class="auto-container">
			<div class="row clearfix">
				<!-- Column -->
				<div class="contact-three_title-column col-lg-6 col-md-12 col-sm-12">
					<!-- Sec Title -->
					<?php
						require_once __DIR__ . '/parts/sections.php';
						$contact_intro = get_page_section($pdo, 'contact', 'intro');
					?>
					<div class="sec-title">
						<div class="sec-title_title"><?php echo htmlspecialchars(($contact_intro['subheading'] ?? '') ?: 'Contact Us'); ?></div>
						<h2 class="sec-title_heading"><?php echo htmlspecialchars(($contact_intro['heading'] ?? '') ?: 'Let\'s Talk About Your Project'); ?></h2>
						<div class="sec-title_text"><?php echo htmlspecialchars(($contact_intro['body'] ?? '') ?: 'Have a question about a plan, a build, or an existing property? Send us a message and our team will get back to you.'); ?></div>
					</div>
					<ul class="contact-three_list">
						<?php if (!empty($site_settings['footer_address'])): ?>
						<li>
							<i><img src="assets/images/icons/map.svg" alt="" /></i>
							Address
							<strong><?php echo htmlspecialchars($site_settings['footer_address']); ?></strong>
						</li>
						<?php endif; ?>
						<?php if (!empty($site_settings['footer_phone'])): ?>
						<li>
							<i><img src="assets/images/icons/phone.svg" alt="" /></i>
							Phone
							<strong><a href="tel:<?php echo htmlspecialchars($site_settings['footer_phone']); ?>"><?php echo htmlspecialchars($site_settings['footer_phone']); ?></a></strong>
						</li>
						<?php endif; ?>
						<?php if (!empty($site_settings['footer_email'])): ?>
						<li>
							<i><img src="assets/images/icons/email.svg" alt="" /></i>
							Email
							<strong><a href="mailto:<?php echo htmlspecialchars($site_settings['footer_email']); ?>"><?php echo htmlspecialchars($site_settings['footer_email']); ?></a></strong>
						</li>
						<?php endif; ?>
					</ul>
				</div>
				<!-- Column -->
				<div class="contact-three_form-column col-lg-6 col-md-12 col-sm-12">
					<div class="contact-three_form-outer">
						<h3>Get a Free Quote</h3>

						<?php if ($contact_status === 'success'): ?>
							<div class="alert alert-success" style="padding:12px 16px;margin-bottom:15px;background:#e6f7ea;color:#1e7e34;border-radius:4px;">Thanks! Your message has been sent — we'll get back to you soon.</div>
						<?php elseif ($contact_status === 'error'): ?>
							<div class="alert alert-danger" style="padding:12px 16px;margin-bottom:15px;background:#fdecea;color:#a12622;border-radius:4px;">Something went wrong — please check your details and try again.</div>
						<?php endif; ?>
						<div class="default-form contact-form">
							<form method="post" action="send-message.php" id="contact-form">
								<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
								<input type="hidden" name="redirect" value="contact.php">
								<div class="row clearfix">
									<!--Form Group-->
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<input type="text" name="name" value="" placeholder="Name" required>
									</div>
									<!--Form Group-->
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<input type="email" name="email" value="" placeholder="Email" required>
									</div>
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<input type="text" name="phone" value="" placeholder="Phone" required>
									</div>
									<!--Form Group-->
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<select name="services" class="custom-select-box">
											<option>Select Service</option>
											<option>Buying</option>
											<option>Selling</option>
											<option>Renting</option>
										</select>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 form-group">
										<textarea class="" name="message" placeholder="Write a Message"></textarea>
									</div>
									<div class="form-group col-lg-12 col-md-12 col-sm-12">
										<button type="submit" class="theme-btn btn-style-one">
											<span class="btn-wrap">
												<span class="text-one">Send Now</span>
												<span class="text-two">Send Now</span>
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
	</section>
	<!-- Contact Three -->

	<!-- Faq One -->
	<section class="faq-one">
		<div class="auto-container">
			<div class="row clearfix">

				<!-- Image Column -->
				<div class="faq-one_accordion-column col-lg-6 col-md-12 col-sm-12">
					<div class="faq-one_accordion-outer">

						<?php $contact_faqs = $pdo->query('SELECT * FROM contact_faqs ORDER BY sort_order, id')->fetchAll(); ?>
						<?php if ($contact_faqs): ?>
						<!-- Accordion Box -->
						<ul class="accordion-box">
							<?php foreach ($contact_faqs as $i => $f): ?>
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

				<!-- Content Column -->
				<?php
					$contact_help = get_page_section($pdo, 'contact', 'help');
					$contact_help_image = !empty($contact_help['image']) ? 'Admin/' . $contact_help['image'] : 'assets/images/resource/faq.jpg';
				?>
				<div class="faq-one_image-column col-lg-6 col-md-12 col-sm-12">
					<div class="faq-one_image-outer">
						<!-- Sec Title -->
						<div class="sec-title">
							<div class="sec-title_title"><?php echo htmlspecialchars(($contact_help['subheading'] ?? '') ?: 'Got Questions?'); ?></div>
							<h2 class="sec-title_heading"><?php echo htmlspecialchars(($contact_help['heading'] ?? '') ?: 'We\'re Here To Help'); ?></h2>
							<div class="sec-title_text"><?php echo htmlspecialchars(($contact_help['body'] ?? '') ?: 'From choosing the right plan to scheduling a build, our team is on hand to walk you through every step. Reach out any time.'); ?></div>
						</div>
						<div class="faq-one_image wow fadeInRight" data-wow-delay="0ms" data-wow-duration="1500ms">
							<img src="<?php echo htmlspecialchars($contact_help_image); ?>" alt="" />
						</div>
						<a href="<?php echo htmlspecialchars(($contact_help['button_link'] ?? '') ?: '#'); ?>" class="faq-one_chat">
							<?php echo htmlspecialchars(($contact_help['button_text'] ?? '') ?: 'Live Chat'); ?> <i><img src="assets/images/icons/chat-1.svg" alt="" /></i>
						</a>
					</div>
				</div>

			</div>
		</div>
	</section>
	<!-- End Faq One -->

	<!-- Map One -->
	<section class="map-one">
		<div class="auto-container">
			<div class="map-one_map">
				<iframe width="820" height="560" id="gmap_canvas" src="https://maps.google.com/maps?q=636+5th+Ave%2C+New+York&t=&z=18&ie=UTF8&iwloc=&output=embed"></iframe>
			</div>
		</div>
	</section>
	<!-- End Map One -->
	
	<!-- Main Footer -->
<?php require_once __DIR__ . '/parts/footer.php'; ?>
