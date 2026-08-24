<?php
	 require_once __DIR__ . '/config/dz.php';
	 require_once __DIR__ . '/config/pesapal.php';

	 if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'save_settings') {
		 $stmt = $pdo->prepare('INSERT INTO site_settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
		 foreach (['pesapal_consumer_key', 'pesapal_consumer_secret', 'pesapal_environment'] as $key) {
			 $stmt->execute([$key, trim($_POST[$key] ?? '')]);
		 }
		 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'PesaPal settings saved.'];
		 header('Location: payment-settings.php');
		 exit;
	 }

	 $ipn_result = null;
	 if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'register_ipn') {
		 $settings = pesapal_settings($pdo);
		 $token_result = pesapal_get_token($settings);
		 if (!empty($token_result['token'])) {
			 $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
			 $ipn_url = $scheme . '://' . $_SERVER['HTTP_HOST'] . '/pesapal-ipn.php';
			 $reg = pesapal_register_ipn($settings, $token_result['token'], $ipn_url);
			 if (!empty($reg['ipn_id'])) {
				 $stmt = $pdo->prepare('INSERT INTO site_settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
				 $stmt->execute(['pesapal_ipn_id', $reg['ipn_id']]);
				 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'IPN registered successfully (ID: ' . htmlspecialchars($reg['ipn_id']) . ').'];
			 } else {
				 $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'IPN registration failed: ' . htmlspecialchars($reg['error'] ?? 'Unknown error')];
			 }
		 } else {
			 $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'Could not authenticate with PesaPal: ' . htmlspecialchars($token_result['error'] ?? 'Unknown error')];
		 }
		 header('Location: payment-settings.php');
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
						<li class="breadcrumb-item"><a href="javascript:void(0)">Settings</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Payments (PesaPal)</a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo $form_status_message['text']; ?></div>
				<?php endif; ?>

				<div class="row">
					<div class="col-lg-8">
						<div class="card" style="height:auto;">
							<div class="card-header">
								<h4 class="card-title">PesaPal API Credentials</h4>
							</div>
							<div class="card-body">
								<p class="text-muted">Get your Consumer Key and Consumer Secret from your <a href="https://developer.pesapal.com/" target="_blank" rel="noopener">PesaPal developer dashboard</a>. Use Sandbox while testing, then switch to Live once you're ready to accept real payments.</p>
								<form method="post">
									<input type="hidden" name="form_action" value="save_settings">
									<div class="mb-3">
										<label class="form-label">Environment</label>
										<select name="pesapal_environment" class="form-select">
											<option value="sandbox" <?php echo ($settings['pesapal_environment'] ?? 'sandbox') === 'sandbox' ? 'selected' : ''; ?>>Sandbox (testing)</option>
											<option value="live" <?php echo ($settings['pesapal_environment'] ?? '') === 'live' ? 'selected' : ''; ?>>Live (real payments)</option>
										</select>
									</div>
									<div class="mb-3">
										<label class="form-label">Consumer Key</label>
										<input type="text" name="pesapal_consumer_key" class="form-control" value="<?php echo ss($settings, 'pesapal_consumer_key'); ?>">
									</div>
									<div class="mb-3">
										<label class="form-label">Consumer Secret</label>
										<input type="password" name="pesapal_consumer_secret" class="form-control" value="<?php echo ss($settings, 'pesapal_consumer_secret'); ?>" autocomplete="new-password">
									</div>
									<button type="submit" class="btn btn-primary">Save Credentials</button>
								</form>
							</div>
						</div>

						<div class="card" style="height:auto;">
							<div class="card-header">
								<h4 class="card-title">IPN (Payment Notification) URL</h4>
							</div>
							<div class="card-body">
								<p class="text-muted">PesaPal needs to notify this site whenever a payment completes. Register the notification URL below once your Consumer Key/Secret are saved — this only needs to be done once per environment.</p>
								<p><strong>Notification URL:</strong> <code><?php echo htmlspecialchars((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http'); ?>://<?php echo htmlspecialchars($_SERVER['HTTP_HOST']); ?>/pesapal-ipn.php</code></p>
								<?php if (!empty($settings['pesapal_ipn_id'])): ?>
									<p class="text-success"><i class="fa fa-check-circle"></i> Registered — IPN ID: <code><?php echo htmlspecialchars($settings['pesapal_ipn_id']); ?></code></p>
								<?php else: ?>
									<p class="text-warning"><i class="fa fa-triangle-exclamation"></i> Not registered yet.</p>
								<?php endif; ?>
								<form method="post">
									<input type="hidden" name="form_action" value="register_ipn">
									<button type="submit" class="btn btn-outline-primary">Register IPN URL</button>
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
