<?php
	 require_once __DIR__ . '/config/dz.php';
	 require_once __DIR__ . '/config/mailer.php';

	 if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'save_settings') {
		 $stmt = $pdo->prepare('INSERT INTO site_settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
		 $stmt->execute(['smtp_enabled', !empty($_POST['smtp_enabled']) ? '1' : '']);
		 foreach (['smtp_host', 'smtp_port', 'smtp_username', 'smtp_encryption', 'smtp_from_email', 'smtp_from_name', 'notify_order_email', 'notify_message_email', 'notify_review_email'] as $key) {
			 $stmt->execute([$key, trim($_POST[$key] ?? '')]);
		 }
		 if (trim($_POST['smtp_password'] ?? '') !== '') {
			 $stmt->execute(['smtp_password', trim($_POST['smtp_password'])]);
		 }
		 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Email settings saved.'];
		 header('Location: email-settings.php');
		 exit;
	 }

	 if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'send_test') {
		 $test_to = trim($_POST['test_email'] ?? '');
		 if ($test_to && filter_var($test_to, FILTER_VALIDATE_EMAIL)) {
			 $result = send_site_email($pdo, $test_to, 'Mars Construction — Test Email', "This is a test email from your Mars Construction admin panel.\n\nIf you received this, your email settings are working correctly.");
			 $_SESSION['form_status_message'] = $result['success']
				 ? ['type' => 'success', 'text' => 'Test email sent to ' . htmlspecialchars($test_to) . '.']
				 : ['type' => 'danger', 'text' => 'Failed to send test email: ' . htmlspecialchars($result['error'])];
		 } else {
			 $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'Enter a valid email address to send a test to.'];
		 }
		 header('Location: email-settings.php');
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

	 function es($settings, $key, $default = '') {
		 return htmlspecialchars($settings[$key] ?? $default);
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
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Email &amp; Notifications</a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo $form_status_message['text']; ?></div>
				<?php endif; ?>

				<div class="row">
					<div class="col-lg-8">
						<div class="card" style="height:auto;">
							<div class="card-header">
								<h4 class="card-title">SMTP Settings</h4>
							</div>
							<div class="card-body">
								<p class="text-muted">By default, this site uses PHP's built-in <code>mail()</code> function, which often fails to deliver on shared hosting or local servers. Configure SMTP below (e.g. Gmail, Outlook, SendGrid, Mailgun) for reliable delivery of order and inquiry notifications.</p>
								<form method="post">
									<input type="hidden" name="form_action" value="save_settings">
									<div class="mb-3">
										<div class="form-check form-switch">
											<input type="checkbox" name="smtp_enabled" class="form-check-input" id="smtp_enabled" value="1" <?php echo !empty($settings['smtp_enabled']) ? 'checked' : ''; ?>>
											<label class="form-check-label" for="smtp_enabled">Use SMTP for sending emails</label>
										</div>
									</div>
									<div class="row">
										<div class="mb-3 col-lg-8">
											<label class="form-label">SMTP Host</label>
											<input type="text" name="smtp_host" class="form-control" placeholder="smtp.gmail.com" value="<?php echo es($settings, 'smtp_host'); ?>">
										</div>
										<div class="mb-3 col-lg-4">
											<label class="form-label">Port</label>
											<input type="number" name="smtp_port" class="form-control" placeholder="587" value="<?php echo es($settings, 'smtp_port', '587'); ?>">
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Username</label>
											<input type="text" name="smtp_username" class="form-control" placeholder="you@example.com" value="<?php echo es($settings, 'smtp_username'); ?>">
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Password</label>
											<input type="password" name="smtp_password" class="form-control" placeholder="<?php echo !empty($settings['smtp_password']) ? '••••••••' : 'App password / SMTP password'; ?>" autocomplete="new-password">
											<small class="text-muted">Leave blank to keep the current password.</small>
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Encryption</label>
											<select name="smtp_encryption" class="form-select">
												<option value="tls" <?php echo ($settings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : ''; ?>>TLS (recommended, port 587)</option>
												<option value="ssl" <?php echo ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : ''; ?>>SSL (port 465)</option>
												<option value="none" <?php echo ($settings['smtp_encryption'] ?? '') === 'none' ? 'selected' : ''; ?>>None</option>
											</select>
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">From Email</label>
											<input type="email" name="smtp_from_email" class="form-control" placeholder="no-reply@marsconstruction.co" value="<?php echo es($settings, 'smtp_from_email'); ?>">
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">From Name</label>
											<input type="text" name="smtp_from_name" class="form-control" value="<?php echo es($settings, 'smtp_from_name', 'Mars Construction'); ?>">
										</div>
									</div>

									<hr class="my-4">
									<h5 class="mb-3">Notification Recipients</h5>
									<p class="text-muted">Where order, message, and review notifications get sent. Leave blank to use the Contact Information email set under CMS &rarr; Footer.</p>
									<div class="row">
										<div class="mb-3 col-lg-4">
											<label class="form-label">New Order Notifications</label>
											<input type="email" name="notify_order_email" class="form-control" placeholder="orders@marsconstruction.co" value="<?php echo es($settings, 'notify_order_email'); ?>">
										</div>
										<div class="mb-3 col-lg-4">
											<label class="form-label">New Message Notifications</label>
											<input type="email" name="notify_message_email" class="form-control" placeholder="hello@marsconstruction.co" value="<?php echo es($settings, 'notify_message_email'); ?>">
										</div>
										<div class="mb-3 col-lg-4">
											<label class="form-label">New Review Notifications</label>
											<input type="email" name="notify_review_email" class="form-control" placeholder="hello@marsconstruction.co" value="<?php echo es($settings, 'notify_review_email'); ?>">
										</div>
									</div>

									<button type="submit" class="btn btn-primary">Save Email Settings</button>
								</form>
							</div>
						</div>

						<div class="card" style="height:auto;">
							<div class="card-header">
								<h4 class="card-title">Send a Test Email</h4>
							</div>
							<div class="card-body">
								<form method="post" class="d-flex gap-2 flex-wrap">
									<input type="hidden" name="form_action" value="send_test">
									<input type="email" name="test_email" class="form-control" style="max-width:320px;" placeholder="you@example.com" required>
									<button type="submit" class="btn btn-outline-primary">Send Test Email</button>
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
