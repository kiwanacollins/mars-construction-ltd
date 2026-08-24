<?php
	 require_once __DIR__ . '/config/dz.php';

	 $checks = [];

	 $base_dir = __DIR__ . '/uploads';
	 $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
	 $admin_dir_url = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
	 $public_base_url = $scheme . '://' . $_SERVER['HTTP_HOST'] . $admin_dir_url . '/uploads';

	 // 1) Does Admin/uploads exist at all?
	 $checks[] = [
		 'label' => 'Admin/uploads folder exists',
		 'pass' => is_dir($base_dir),
		 'detail' => 'Resolved path: ' . $base_dir,
	 ];

	 // 2) Is it writable by PHP?
	 $writable = is_dir($base_dir) && is_writable($base_dir);
	 $checks[] = [
		 'label' => 'Admin/uploads is writable by PHP',
		 'pass' => $writable,
		 'detail' => $writable ? 'PHP can create new files/folders here.' : 'PHP cannot write here — new uploads will silently fail. Fix folder permissions/ownership on the server (commonly chmod 755, owned by the web server user).',
	 ];

	 // 3) Try an actual write + read + delete round-trip.
	 $test_ok = false;
	 $test_error = '';
	 if (is_dir($base_dir) || @mkdir($base_dir, 0755, true)) {
		 $test_file = $base_dir . '/_write_test_' . bin2hex(random_bytes(4)) . '.txt';
		 if (@file_put_contents($test_file, 'test') !== false) {
			 $test_ok = is_readable($test_file);
			 @unlink($test_file);
		 } else {
			 $test_error = 'file_put_contents() failed — PHP process cannot write to this folder.';
		 }
	 } else {
		 $test_error = 'mkdir() failed — the uploads folder does not exist and PHP could not create it.';
	 }
	 $checks[] = [
		 'label' => 'Live write + read test',
		 'pass' => $test_ok,
		 'detail' => $test_ok ? 'Successfully created, read, and deleted a test file.' : ($test_error ?: 'Could not read back the test file after writing it.'),
	 ];

	 // 4) Find an existing uploaded file (any subfolder) and check it's reachable over HTTP.
	 $sample_path = null;
	 if (is_dir($base_dir)) {
		 $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base_dir, FilesystemIterator::SKIP_DOTS));
		 foreach ($iterator as $file) {
			 if ($file->isFile() && !str_starts_with($file->getFilename(), '_write_test_')) {
				 $sample_path = str_replace('\\', '/', substr($file->getPathname(), strlen($base_dir) + 1));
				 break;
			 }
		 }
	 }
	 if ($sample_path) {
		 $sample_url = $public_base_url . '/' . $sample_path;
		 $http_code = null;
		 $ch = curl_init($sample_url);
		 curl_setopt_array($ch, [CURLOPT_NOBODY => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10, CURLOPT_SSL_VERIFYPEER => false]);
		 curl_exec($ch);
		 $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		 curl_close($ch);
		 $checks[] = [
			 'label' => 'Existing upload reachable over HTTP',
			 'pass' => $http_code === 200,
			 'detail' => "Tested: <code>" . htmlspecialchars($sample_url) . "</code> — server responded HTTP $http_code." . ($http_code !== 200 ? ' If write/read tests above pass but this fails, something (web server config, .htaccess, security module) is blocking public access to this folder.' : ''),
		 ];
	 } else {
		 $checks[] = [
			 'label' => 'Existing upload reachable over HTTP',
			 'pass' => null,
			 'detail' => 'No uploaded files found yet to test with. Upload an image anywhere in the admin, then reload this page.',
		 ];
	 }

	 // 5) PHP upload limits.
	 $checks[] = [
		 'label' => 'PHP upload limits',
		 'pass' => null,
		 'detail' => 'upload_max_filesize: <code>' . ini_get('upload_max_filesize') . '</code> &nbsp; post_max_size: <code>' . ini_get('post_max_size') . '</code> &nbsp; file_uploads: <code>' . (ini_get('file_uploads') ? 'On' : 'Off') . '</code>',
	 ];

	 // 6) open_basedir restriction (common on shared hosting, can block writes).
	 $open_basedir = ini_get('open_basedir');
	 $checks[] = [
		 'label' => 'open_basedir restriction',
		 'pass' => $open_basedir === '' ? true : null,
		 'detail' => $open_basedir === '' ? 'Not restricted.' : 'Restricted to: <code>' . htmlspecialchars($open_basedir) . '</code> — make sure the uploads path falls inside this list.',
	 ];
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
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Uploads Health Check</a></li>
					</ol>
                </div>

				<div class="row">
					<div class="col-lg-9">
						<div class="card" style="height:auto;">
							<div class="card-header">
								<h4 class="card-title">Uploads Health Check</h4>
							</div>
							<div class="card-body">
								<p class="text-muted">Diagnoses why uploaded images might not show up on this server. Reload this page any time — it re-runs live.</p>
								<ul class="list-unstyled">
									<?php foreach ($checks as $c): ?>
										<li class="mb-3">
											<?php if ($c['pass'] === true): ?>
												<span class="text-success"><i class="fa fa-circle-check"></i></span>
											<?php elseif ($c['pass'] === false): ?>
												<span class="text-danger"><i class="fa fa-circle-xmark"></i></span>
											<?php else: ?>
												<span class="text-muted"><i class="fa fa-circle-info"></i></span>
											<?php endif; ?>
											<strong><?php echo htmlspecialchars($c['label']); ?></strong>
											<div class="text-muted small mt-1"><?php echo $c['detail']; ?></div>
										</li>
									<?php endforeach; ?>
								</ul>
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
