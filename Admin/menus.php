<?php
	 require_once __DIR__ . '/config/dz.php';

	 $main_count = (int) $pdo->query('SELECT COUNT(*) c FROM menu_items')->fetch()['c'];
	 $col2_count = (int) $pdo->query("SELECT COUNT(*) c FROM footer_menu_items WHERE col_group = 'cities'")->fetch()['c'];
	 $bottom_count = (int) $pdo->query("SELECT COUNT(*) c FROM footer_menu_items WHERE col_group = 'bottom'")->fetch()['c'];

	 $settings = [];
	 foreach ($pdo->query('SELECT `key`, `value` FROM site_settings') as $row) {
		 $settings[$row['key']] = $row['value'];
	 }
	 $col2_heading = $settings['footer_col2_heading'] ?: 'Discover Cities';

	 $menus = [
		 ['name' => 'Main Menu', 'location' => 'Site Header', 'count' => $main_count, 'edit' => 'menu-items.php'],
		 ['name' => $col2_heading, 'location' => 'Footer &middot; Column 2', 'count' => $col2_count, 'edit' => 'footer-menu-edit.php?group=cities'],
		 ['name' => 'Bottom Footer Bar', 'location' => 'Footer &middot; Legal Links', 'count' => $bottom_count, 'edit' => 'footer-menu-edit.php?group=bottom'],
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
						<li class="breadcrumb-item"><a href="javascript:void(0)">CMS</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Menu</a></li>
					</ol>
                </div>

				<div class="clean-list-header">
					<h2>Menu</h2>
				</div>

				<div class="clean-list-card">
					<table class="clean-list-table">
						<thead>
							<tr>
								<th>Menu</th>
								<th>Location</th>
								<th>Items</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($menus as $m): ?>
								<tr>
									<td><span class="clean-list-title"><?php echo htmlspecialchars($m['name']); ?></span></td>
									<td><?php echo $m['location']; ?></td>
									<td><?php echo $m['count']; ?> item<?php echo $m['count'] === 1 ? '' : 's'; ?></td>
									<td>
										<div class="clean-list-actions">
											<a href="<?php echo htmlspecialchars($m['edit']); ?>" class="edit-btn" title="Edit"><i class="fa fa-pen"></i></a>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
            </div>
        </div>

        <?php include 'elements/footer.php'; ?>
    </div>

    <?php include 'elements/page-js.php'; ?>

</body>

</html>
