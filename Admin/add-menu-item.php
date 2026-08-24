<?php
	 require_once __DIR__ . '/config/dz.php';

	 $item = null;
	 if (!empty($_GET['id'])) {
		 $stmt = $pdo->prepare('SELECT * FROM menu_items WHERE id = ?');
		 $stmt->execute([(int) $_GET['id']]);
		 $item = $stmt->fetch();
	 }

	 $all_items = $pdo->query('SELECT * FROM menu_items ORDER BY parent_id IS NULL DESC, parent_id, sort_order, id')->fetchAll();
	 $by_parent = [];
	 foreach ($all_items as $row) {
		 $by_parent[$row['parent_id'] ?? 0][] = $row;
	 }

	 $parent_options = [];
	 function collect_parent_options($by_parent, $parent_id, $depth, $exclude_id, &$out) {
		 foreach ($by_parent[$parent_id] ?? [] as $row) {
			 if ($row['id'] == $exclude_id) {
				 continue;
			 }
			 $out[] = ['id' => $row['id'], 'label' => str_repeat('— ', $depth) . $row['label']];
			 collect_parent_options($by_parent, $row['id'], $depth + 1, $exclude_id, $out);
		 }
	 }
	 collect_parent_options($by_parent, 0, 0, $item['id'] ?? 0, $parent_options);

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
						<li class="breadcrumb-item"><a href="menu-items.php">Menu Items</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $item ? 'Edit Menu Item' : 'Add Menu Item'; ?></a></li>
					</ol>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo $item ? 'Edit Menu Item' : 'Add Menu Item'; ?></h4>
                            </div>
                            <div class="card-body">
								<?php if (!empty($form_status_message)): ?>
									<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
								<?php endif; ?>
								<form action="menu-item-save.php" method="post">
									<?php if ($item): ?>
										<input type="hidden" name="id" value="<?php echo (int) $item['id']; ?>">
									<?php endif; ?>
									<div class="row">
										<div class="mb-3 col-lg-6">
											<label class="form-label">Label</label>
											<input type="text" name="label" class="form-control" value="<?php echo htmlspecialchars($item['label'] ?? ''); ?>" required>
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">URL <small class="text-muted">(use # for a dropdown-only parent)</small></label>
											<input type="text" name="url" class="form-control" value="<?php echo htmlspecialchars($item['url'] ?? '#'); ?>">
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Icon <small class="text-muted">(Font Awesome class, e.g. <code>fa-solid fa-house</code> &mdash; leave blank for none)</small></label>
											<input type="text" name="icon" class="form-control" placeholder="fa-solid fa-house" value="<?php echo htmlspecialchars($item['icon'] ?? ''); ?>">
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Parent Item</label>
											<select name="parent_id" class="default-select form-control wide">
												<option value="">-- Top Level --</option>
												<?php foreach ($parent_options as $opt): ?>
													<option value="<?php echo $opt['id']; ?>" <?php echo (int) ($item['parent_id'] ?? 0) === (int) $opt['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($opt['label']); ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Sort Order</label>
											<input type="number" name="sort_order" class="form-control" value="<?php echo (int) ($item['sort_order'] ?? 0); ?>">
										</div>
									</div>
									<button type="submit" class="btn btn-primary"><?php echo $item ? 'Update' : 'Create'; ?></button>
									<a href="menu-items.php" class="btn btn-danger light">Cancel</a>
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
