<?php
	 require_once __DIR__ . '/config/dz.php';

	 if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'save_content') {
		 $stmt = $pdo->prepare('INSERT INTO site_settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
		 foreach ($_POST as $key => $value) {
			 if (in_array($key, ['csrf_token', 'form_action'], true)) {
				 continue;
			 }
			 $stmt->execute([$key, trim($value)]);
		 }
		 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Footer content updated.'];
		 header('Location: footer-settings.php');
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
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Footer</a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Footer Content</h4>
                            </div>
                            <div class="card-body">
								<form method="post">
									<input type="hidden" name="form_action" value="save_content">
									<div class="row">
										<div class="mb-3 col-12">
											<label class="form-label">Footer About Heading</label>
											<input type="text" name="footer_col1_title" class="form-control" placeholder="Mars Construction LTD" value="<?php echo ss($settings, 'footer_col1_title'); ?>">
										</div>
										<div class="mb-3 col-12">
											<label class="form-label">Footer About Text</label>
											<textarea name="footer_text" class="form-control" rows="3"><?php echo ss($settings, 'footer_text'); ?></textarea>
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Address</label>
											<input type="text" name="footer_address" class="form-control" value="<?php echo ss($settings, 'footer_address'); ?>">
										</div>
										<div class="mb-3 col-lg-3">
											<label class="form-label">Phone</label>
											<input type="text" name="footer_phone" class="form-control" value="<?php echo ss($settings, 'footer_phone'); ?>">
										</div>
										<div class="mb-3 col-lg-3">
											<label class="form-label">Email</label>
											<input type="text" name="footer_email" class="form-control" value="<?php echo ss($settings, 'footer_email'); ?>">
										</div>
										<div class="mb-3 col-lg-3">
											<label class="form-label">Facebook URL</label>
											<input type="text" name="footer_facebook" class="form-control" value="<?php echo ss($settings, 'footer_facebook'); ?>">
										</div>
										<div class="mb-3 col-lg-3">
											<label class="form-label">Twitter URL</label>
											<input type="text" name="footer_twitter" class="form-control" value="<?php echo ss($settings, 'footer_twitter'); ?>">
										</div>
										<div class="mb-3 col-lg-3">
											<label class="form-label">Youtube URL</label>
											<input type="text" name="footer_youtube" class="form-control" value="<?php echo ss($settings, 'footer_youtube'); ?>">
										</div>
										<div class="mb-3 col-lg-3">
											<label class="form-label">Instagram URL</label>
											<input type="text" name="footer_instagram" class="form-control" value="<?php echo ss($settings, 'footer_instagram'); ?>">
										</div>
										<div class="mb-3 col-lg-3">
											<label class="form-label">TikTok URL</label>
											<input type="text" name="footer_tiktok" class="form-control" value="<?php echo ss($settings, 'footer_tiktok'); ?>">
										</div>
										<div class="mb-3 col-12">
											<label class="form-label">Copyright Line <small class="text-muted">(HTML allowed, e.g. links)</small></label>
											<input type="text" name="footer_copyright" class="form-control" value="<?php echo ss($settings, 'footer_copyright'); ?>">
										</div>
									</div>
									<button type="submit" class="btn btn-primary">Save Footer Content</button>
								</form>
							</div>
                        </div>
					</div>
				</div>

				<p class="text-muted">Looking to edit the footer link columns? Head to <a href="menus.php">CMS &rarr; Menu</a>.</p>
            </div>
        </div>

        <?php include 'elements/footer.php'; ?>
    </div>

    <?php include 'elements/page-js.php'; ?>

</body>

</html>
