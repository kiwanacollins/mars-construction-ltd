<?php
	 require_once __DIR__ . '/config/dz.php';

	 $valid_groups = ['company', 'cities', 'bottom'];
	 $group = in_array($_GET['group'] ?? '', $valid_groups, true) ? $_GET['group'] : 'company';
	 $has_heading = $group !== 'bottom';
	 $heading_key = $group === 'cities' ? 'footer_col2_heading' : 'footer_col1_heading';
	 $default_heading = $group === 'cities' ? 'Discover Cities' : ($group === 'bottom' ? 'Bottom Footer Bar' : 'Our Company');

	 if ($has_heading && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'save_heading') {
		 $heading = trim($_POST['heading'] ?? '');
		 $stmt = $pdo->prepare('INSERT INTO site_settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
		 $stmt->execute([$heading_key, $heading]);
		 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Menu title updated.'];
		 header('Location: footer-menu-edit.php?group=' . $group);
		 exit;
	 }

	 if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'save_link') {
		 $label = trim($_POST['label'] ?? '');
		 $url = trim($_POST['url'] ?? '');
		 $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
		 if ($label !== '' && $url !== '') {
			 if ($id) {
				 $stmt = $pdo->prepare('UPDATE footer_menu_items SET label = ?, url = ? WHERE id = ?');
				 $stmt->execute([$label, $url, $id]);
				 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Link updated.'];
			 } else {
				 $max_stmt = $pdo->prepare('SELECT COALESCE(MAX(sort_order), -1) m FROM footer_menu_items WHERE col_group = ?');
				 $max_stmt->execute([$group]);
				 $max = (int) $max_stmt->fetch()['m'];
				 $stmt = $pdo->prepare('INSERT INTO footer_menu_items (label, url, sort_order, col_group) VALUES (?, ?, ?, ?)');
				 $stmt->execute([$label, $url, $max + 1, $group]);
				 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Link added.'];
			 }
		 }
		 header('Location: footer-menu-edit.php?group=' . $group);
		 exit;
	 }

	 if (!empty($_GET['delete_link'])) {
		 $del = $pdo->prepare('DELETE FROM footer_menu_items WHERE id = ? AND col_group = ?');
		 $del->execute([(int) $_GET['delete_link'], $group]);
		 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Link deleted.'];
		 header('Location: footer-menu-edit.php?group=' . $group);
		 exit;
	 }

	 $settings = [];
	 foreach ($pdo->query('SELECT `key`, `value` FROM site_settings') as $row) {
		 $settings[$row['key']] = $row['value'];
	 }
	 $heading = $has_heading ? ($settings[$heading_key] ?: $default_heading) : $default_heading;

	 $links = $pdo->prepare('SELECT * FROM footer_menu_items WHERE col_group = ? ORDER BY sort_order, id');
	 $links->execute([$group]);
	 $links = $links->fetchAll();

	 if (isset($_SESSION['form_status_message'])) {
		 $form_status_message = $_SESSION['form_status_message'];
		 unset($_SESSION['form_status_message']);
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
						<li class="breadcrumb-item"><a href="menus.php">Menu</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo htmlspecialchars($heading); ?></a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<?php if ($has_heading): ?>
				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Menu Title</h4>
							</div>
							<div class="card-body">
								<form method="post" class="d-flex align-items-end gap-2">
									<input type="hidden" name="form_action" value="save_heading">
									<div class="flex-grow-1">
										<label class="form-label">Title shown above this list in the footer</label>
										<input type="text" name="heading" class="form-control" value="<?php echo htmlspecialchars($heading); ?>" required>
									</div>
									<button type="submit" class="btn btn-primary">Save Title</button>
								</form>
							</div>
						</div>
					</div>
				</div>
				<?php else: ?>
				<p class="text-muted">This is the small legal link row at the very bottom of the footer (no heading, just links).</p>
				<?php endif; ?>

				<div class="row">
					<div class="col-lg-5">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Add Link</h4>
							</div>
							<div class="card-body">
								<form method="post">
									<input type="hidden" name="form_action" value="save_link">
									<div class="mb-3">
										<label class="form-label">Link Text</label>
										<input type="text" name="label" class="form-control" placeholder="e.g. Careers" required>
									</div>
									<div class="mb-3">
										<label class="form-label">URL</label>
										<input type="text" name="url" class="form-control" placeholder="e.g. contact.php or https://..." required>
									</div>
									<button type="submit" class="btn btn-primary">Add Link</button>
								</form>
							</div>
						</div>
					</div>
					<div class="col-lg-7">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title"><?php echo htmlspecialchars($heading); ?> Links</h4>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table">
										<thead>
											<tr>
												<th>Text</th>
												<th>URL</th>
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!$links): ?>
												<tr><td colspan="3" class="text-center">No links yet.</td></tr>
											<?php endif; ?>
											<?php foreach ($links as $link): ?>
												<tr>
													<td colspan="2">
														<form method="post" class="d-flex align-items-center gap-2">
															<input type="hidden" name="form_action" value="save_link">
															<input type="hidden" name="id" value="<?php echo $link['id']; ?>">
															<input type="text" name="label" class="form-control form-control-sm" value="<?php echo htmlspecialchars($link['label']); ?>" placeholder="Text">
															<input type="text" name="url" class="form-control form-control-sm" value="<?php echo htmlspecialchars($link['url']); ?>" placeholder="URL">
															<button type="submit" class="btn btn-sm btn-primary text-nowrap">Save</button>
														</form>
													</td>
													<td>
														<a href="footer-menu-edit.php?group=<?php echo $group; ?>&delete_link=<?php echo $link['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this link?');">Delete</a>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
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
