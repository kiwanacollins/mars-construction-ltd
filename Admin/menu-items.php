<?php
	 require_once __DIR__ . '/config/dz.php';

	 $rows = $pdo->query('SELECT * FROM menu_items ORDER BY parent_id IS NULL DESC, parent_id, sort_order, id')->fetchAll();
	 $by_parent = [];
	 foreach ($rows as $row) {
		 $by_parent[$row['parent_id'] ?? 0][] = $row;
	 }

	 if (isset($_SESSION['form_status_message'])) {
		 $form_status_message = $_SESSION['form_status_message'];
		 unset($_SESSION['form_status_message']);
	 }

	 function render_menu_rows($by_parent, $parent_id, $depth) {
		 $items = $by_parent[$parent_id] ?? [];
		 foreach ($items as $item) {
			 echo '<tr>';
			 echo '<td>' . str_repeat('&mdash;&nbsp;', $depth) . htmlspecialchars($item['label']) . '</td>';
			 echo '<td>' . htmlspecialchars($item['url']) . '</td>';
			 echo '<td>' . (!empty($item['icon']) ? '<i class="' . htmlspecialchars($item['icon']) . '"></i> <code>' . htmlspecialchars($item['icon']) . '</code>' : '&mdash;') . '</td>';
			 echo '<td>' . (int) $item['sort_order'] . '</td>';
			 echo '<td class="text-nowrap">';
			 echo '<a href="add-menu-item.php?id=' . $item['id'] . '" class="btn btn-warning btn-sm content-icon"><i class="fa-solid fa-pen-to-square"></i></a> ';
			 echo '<a href="menu-item-delete.php?id=' . $item['id'] . '" class="btn btn-danger btn-sm content-icon" onclick="return confirm(\'Delete this menu item and its sub-items?\');"><i class="fa-solid fa-trash"></i></a>';
			 echo '</td>';
			 echo '</tr>';
			 render_menu_rows($by_parent, $item['id'], $depth + 1);
		 }
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
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Main Menu</a></li>
					</ol>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Navigation Menu</h4>
								<a href="add-menu-item.php" class="btn btn-primary btn-sm"><i class="fa fa-plus me-1"></i>Add Menu Item</a>
                            </div>
                            <div class="card-body">
								<?php if (!empty($form_status_message)): ?>
									<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
								<?php endif; ?>
								<div class="table-responsive">
									<table class="table">
										<thead>
											<tr>
												<th>Label</th>
												<th>URL</th>
												<th>Icon</th>
												<th>Order</th>
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!$rows): ?>
												<tr><td colspan="5" class="text-center">No menu items yet.</td></tr>
											<?php endif; ?>
											<?php render_menu_rows($by_parent, 0, 0); ?>
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
