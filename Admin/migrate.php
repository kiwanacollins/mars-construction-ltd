<?php
	 require_once __DIR__ . '/config/dz.php';

	 $log = [];

	 function col_exists($pdo, $table, $column) {
		 $stmt = $pdo->prepare(
			 "SELECT COUNT(*) c FROM information_schema.COLUMNS
			  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?"
		 );
		 $stmt->execute([$table, $column]);
		 return (int) $stmt->fetch()['c'] > 0;
	 }

	 function table_exists($pdo, $table) {
		 $stmt = $pdo->prepare(
			 "SELECT COUNT(*) c FROM information_schema.TABLES
			  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?"
		 );
		 $stmt->execute([$table]);
		 return (int) $stmt->fetch()['c'] > 0;
	 }

	 function add_column($pdo, $table, $column, $definition, &$log) {
		 if (!table_exists($pdo, $table)) {
			 $log[] = ["skip", "$table.$column — table \"$table\" doesn't exist yet, will be created below if it's a new-table migration."];
			 return;
		 }
		 if (col_exists($pdo, $table, $column)) {
			 $log[] = ["ok", "$table.$column already exists."];
			 return;
		 }
		 $pdo->exec("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
		 $log[] = ["added", "$table.$column added."];
	 }

	 function run_sql_file($pdo, $path, &$log) {
		 if (!file_exists($path)) {
			 $log[] = ["skip", basename($path) . " not found, skipped."];
			 return;
		 }
		 $sql = file_get_contents($path);
		 try {
			 $pdo->exec($sql);
			 $log[] = ["ran", basename($path) . " applied."];
		 } catch (PDOException $e) {
			 $log[] = ["error", basename($path) . ": " . $e->getMessage()];
		 }
	 }

	 if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['confirm'] ?? '') === 'yes') {

		 // 1) Run the versioned schema files from v9 onward (each uses CREATE TABLE
		 //    IF NOT EXISTS, so re-running is always safe). v2-v8 predate this
		 //    round of work and are skipped here since they've long since been
		 //    applied everywhere and can contain now-stale one-off ALTERs.
		 $schema_files = glob(__DIR__ . '/config/schema_v*.sql');
		 natsort($schema_files);
		 foreach ($schema_files as $file) {
			 if (preg_match('/schema_v(\d+)/', basename($file), $m) && (int) $m[1] >= 9) {
				 run_sql_file($pdo, $file, $log);
			 }
		 }

		 // 2) Columns added ad-hoc during development that were never saved to a
		 //    versioned .sql file — checked individually so this is safe to run
		 //    against a database in any partial state.
		 add_column($pdo, 'page_sections', 'image2', "VARCHAR(255) DEFAULT NULL", $log);
		 add_column($pdo, 'page_sections', 'check1', "VARCHAR(150) DEFAULT NULL", $log);
		 add_column($pdo, 'page_sections', 'check2', "VARCHAR(150) DEFAULT NULL", $log);
		 add_column($pdo, 'page_sections', 'list1', "VARCHAR(255) DEFAULT NULL", $log);
		 add_column($pdo, 'page_sections', 'list2', "VARCHAR(255) DEFAULT NULL", $log);
		 add_column($pdo, 'page_sections', 'button_text', "VARCHAR(100) DEFAULT NULL", $log);
		 add_column($pdo, 'page_sections', 'button_link', "VARCHAR(255) DEFAULT NULL", $log);

		 add_column($pdo, 'footer_menu_items', 'col_group', "VARCHAR(20) NOT NULL DEFAULT 'company'", $log);

		 add_column($pdo, 'home_service_cards', 'image', "VARCHAR(255) DEFAULT NULL", $log);

		 add_column($pdo, 'messages', 'project_id', "INT DEFAULT NULL", $log);

		 add_column($pdo, 'hero_slides', 'button_style', "ENUM('solid','outline') DEFAULT 'solid'", $log);
		 add_column($pdo, 'hero_slides', 'bg_type', "ENUM('image','video') DEFAULT 'image'", $log);
		 add_column($pdo, 'hero_slides', 'video_url', "VARCHAR(500) DEFAULT NULL", $log);
		 add_column($pdo, 'hero_slides', 'button2_text', "VARCHAR(100) DEFAULT NULL", $log);
		 add_column($pdo, 'hero_slides', 'button2_link', "VARCHAR(255) DEFAULT NULL", $log);

		 add_column($pdo, 'orders', 'payment_status', "ENUM('unpaid','paid','failed') DEFAULT 'unpaid'", $log);
		 add_column($pdo, 'orders', 'payment_method', "VARCHAR(50) DEFAULT NULL", $log);
		 add_column($pdo, 'orders', 'pesapal_tracking_id', "VARCHAR(100) DEFAULT NULL", $log);
		 add_column($pdo, 'orders', 'pesapal_merchant_ref', "VARCHAR(100) DEFAULT NULL", $log);

		 if (table_exists($pdo, 'messages') && !col_exists($pdo, 'messages', 'project_id')) {
			 $log[] = ["error", "messages.project_id still missing after attempted add — check manually."];
		 }

		 $migration_ran = true;
	 } else {
		 $migration_ran = false;
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
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Database Migration</a></li>
					</ol>
                </div>

				<div class="row">
					<div class="col-lg-9">
						<div class="card" style="height:auto;">
							<div class="card-header">
								<h4 class="card-title">Catch Up the Database Schema</h4>
							</div>
							<div class="card-body">
								<p class="text-muted">Every table/column this admin panel needs, checked one at a time and only added if it's missing. Safe to run more than once — anything already present is left untouched. Use this after deploying new code to a site whose database hasn't been updated yet (this is exactly what causes a 500 error: the code expects a table or column the database doesn't have).</p>

								<?php if ($migration_ran): ?>
									<hr>
									<h5>Result</h5>
									<ul class="list-unstyled">
										<?php foreach ($log as [$type, $msg]): ?>
											<li class="mb-1">
												<?php if ($type === 'added' || $type === 'ran'): ?>
													<span class="text-success"><i class="fa fa-check-circle"></i></span>
												<?php elseif ($type === 'ok'): ?>
													<span class="text-muted"><i class="fa fa-circle-check"></i></span>
												<?php elseif ($type === 'skip'): ?>
													<span class="text-warning"><i class="fa fa-triangle-exclamation"></i></span>
												<?php else: ?>
													<span class="text-danger"><i class="fa fa-circle-xmark"></i></span>
												<?php endif; ?>
												<?php echo htmlspecialchars($msg); ?>
											</li>
										<?php endforeach; ?>
									</ul>
									<div class="alert alert-success">Migration complete. Reload the site's pages to confirm the 500 errors are gone.</div>
								<?php else: ?>
									<form method="post">
										<input type="hidden" name="confirm" value="yes">
										<button type="submit" class="btn btn-primary">Run Migration Now</button>
									</form>
								<?php endif; ?>
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
