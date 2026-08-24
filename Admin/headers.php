<?php
	 require_once __DIR__ . '/config/dz.php';
	 require_once __DIR__ . '/config/helpers.php';

	 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		 $stmt = $pdo->prepare('INSERT INTO site_settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
		 foreach ($_POST as $key => $value) {
			 if ($key === 'csrf_token') {
				 continue;
			 }
			 $stmt->execute([$key, trim($value)]);
		 }

		 $logo_path = save_single_upload('header_logo_upload', 'branding', ['jpg', 'jpeg', 'png', 'svg', 'webp']);
		 if ($logo_path) {
			 $stmt->execute(['header_logo', $logo_path]);
		 }

		 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Header settings updated.'];
		 header('Location: headers.php');
		 exit;
	 }

	 $settings = [];
	 foreach ($pdo->query('SELECT `key`, `value` FROM site_settings') as $row) {
		 $settings[$row['key']] = $row['value'];
	 }

	 if (isset($_SESSION['form_status_message'])) {
		 $form_status_message = $_SESSION['form_status_message'];
		 unset($_SESSION['form_status_message']);
	 }

	 function ss($settings, $key) {
		 return htmlspecialchars($settings[$key] ?? '');
	 }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- PAGE TITLE HERE -->
	<title><?php echo $DexignZoneSettings['site_level']['site_title'] ?></title>
	<?php include 'elements/meta.php';?>
	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="<?php echo $DexignZoneSettings['site_level']['favicon']?>">
	<?php include 'elements/page-css.php'; ?>

</head>

<body>

    <?php include 'elements/pre-loader.php'; ?>

    <div id="main-wrapper">
        <?php include 'elements/nav-header.php'; ?>
		<?php include 'elements/chatbox.php'; ?>
        <?php include 'elements/header.php'; ?>
        <?php include 'elements/sidebar.php'; ?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="page-titles">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="javascript:void(0)">CMS</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Headers</a></li>
					</ol>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Header Elements</h4>
								<a href="menu-items.php" class="btn btn-outline-primary btn-sm"><i class="fa fa-bars me-1"></i>Manage Navigation Menu</a>
                            </div>
                            <div class="card-body">
								<?php if (!empty($form_status_message)): ?>
									<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
								<?php endif; ?>
								<form method="post" enctype="multipart/form-data">
									<h5 class="mb-3">Logo</h5>
									<div class="row">
										<div class="mb-3 col-lg-4">
											<label class="form-label">Current Logo</label>
											<div>
												<img src="<?php echo !empty($settings['header_logo']) ? htmlspecialchars($settings['header_logo']) : '../assets/images/Mars-web-Logo.svg'; ?>" alt="Logo preview" style="max-height:70px; background:#eee; padding:8px; border-radius:6px;">
											</div>
										</div>
										<div class="mb-3 col-lg-8">
											<label class="form-label">Replace Logo <small class="text-muted">(shown in the site header; leave blank to keep the current logo)</small></label>
											<input type="file" name="header_logo_upload" class="form-control" accept="image/*">
										</div>
									</div>

									<hr class="my-4">
									<h5 class="mb-3">Header Button</h5>
									<div class="row">
										<div class="mb-3 col-lg-6">
											<label class="form-label">Button Text</label>
											<input type="text" name="header_cta_text" class="form-control" value="<?php echo ss($settings, 'header_cta_text'); ?>">
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Button Link</label>
											<input type="text" name="header_cta_url" class="form-control" value="<?php echo ss($settings, 'header_cta_url'); ?>">
										</div>
									</div>

									<hr class="my-4">
									<h5 class="mb-3">Header Contact Icons</h5>
									<div class="row">
										<div class="mb-3 col-lg-4">
											<label class="form-label">Video Call Link <small class="text-muted">(leave blank to hide the icon)</small></label>
											<input type="text" name="header_video_url" class="form-control" placeholder="https://meet.google.com/..." value="<?php echo ss($settings, 'header_video_url'); ?>">
										</div>
										<div class="mb-3 col-lg-4">
											<label class="form-label">Header Phone Number <small class="text-muted">(leave blank to hide the icon)</small></label>
											<input type="text" name="header_phone" class="form-control" placeholder="815-804-8928" value="<?php echo ss($settings, 'header_phone'); ?>">
										</div>
										<div class="mb-3 col-lg-4">
											<label class="form-label">WhatsApp Link <small class="text-muted">(leave blank to hide the icon)</small></label>
											<input type="text" name="header_whatsapp_url" class="form-control" placeholder="https://wa.me/15551234567" value="<?php echo ss($settings, 'header_whatsapp_url'); ?>">
										</div>
									</div>

									<button type="submit" class="btn btn-primary">Save Header Settings</button>
								</form>
							</div>
                        </div>
					</div>
				</div>
            </div>
        </div>

        <?php include 'elements/footer.php'; ?>
    </div>

    <?php include 'elements/page-js.php'; ?>

</body>

</html>
