<?php
	 require_once __DIR__ . '/config/dz.php';

	 $search = trim($_GET['search'] ?? '');
	 $category_filter = trim($_GET['category'] ?? '');

	 $where = [];
	 $params = [];
	 if ($search !== '') {
		 $where[] = 'p.title LIKE ?';
		 $params[] = '%' . $search . '%';
	 }
	 if ($category_filter !== '') {
		 $where[] = 'p.category = ?';
		 $params[] = $category_filter;
	 }
	 $where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

	 $stmt = $pdo->prepare(
		 "SELECT p.*,
			 (SELECT file_path FROM property_files pf WHERE pf.property_id = p.id ORDER BY pf.is_cover DESC, pf.id ASC LIMIT 1) AS cover_image
		  FROM properties p
		  $where_sql
		  ORDER BY p.created_at DESC"
	 );
	 $stmt->execute($params);
	 $properties = $stmt->fetchAll();

	 $plan_categories_filter = $pdo->query('SELECT * FROM plan_categories ORDER BY sort_order, name')->fetchAll();

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
						<li class="breadcrumb-item"><a href="javascript:void(0)">Plans</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Plans</a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="clean-list-header">
					<h2>Plans</h2>
					<a href="add-property.php" class="clean-list-btn-add"><i class="fa fa-plus"></i>Add Plan</a>
				</div>

				<div class="clean-list-card">
					<form method="get" class="clean-list-filters">
						<input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search plan title...">
						<select name="category" onchange="this.form.submit()">
							<option value="">All Categories</option>
							<?php foreach ($plan_categories_filter as $cat): ?>
								<option value="<?php echo htmlspecialchars($cat['name']); ?>" <?php echo $category_filter === $cat['name'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
							<?php endforeach; ?>
						</select>
						<button type="submit" class="btn btn-primary btn-sm">Filter</button>
						<?php if ($search !== '' || $category_filter !== ''): ?>
							<a href="property-list.php" class="btn btn-outline-secondary btn-sm">Clear</a>
						<?php endif; ?>
					</form>

					<?php if (!$properties): ?>
						<div class="clean-list-empty">No plans found. <a href="add-property.php">Add your first plan</a>.</div>
					<?php else: ?>
						<div class="table-responsive">
							<table class="clean-list-table">
								<thead>
									<tr>
										<th>Photo</th>
										<th>Title</th>
										<th>Category</th>
										<th>Price</th>
										<th>Status</th>
										<th>Added</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($properties as $p): ?>
										<tr>
											<td><img src="<?php echo htmlspecialchars($p['cover_image'] ?: 'assets/images/property/1.jpg'); ?>" alt="" class="clean-list-thumb"></td>
											<td><a href="add-property.php?id=<?php echo $p['id']; ?>" class="clean-list-title"><?php echo htmlspecialchars($p['title']); ?></a><?php if ($p['plan_number']): ?><div class="text-muted" style="font-size:12px;">#<?php echo htmlspecialchars($p['plan_number']); ?></div><?php endif; ?></td>
											<td><?php echo htmlspecialchars($p['category'] ?: 'Uncategorized'); ?></td>
											<td>$<?php echo number_format($p['price'], 0); ?></td>
											<td><span class="clean-list-pill <?php echo $p['featured'] ? 'is-featured' : 'is-standard'; ?>"><?php echo $p['featured'] ? 'Featured' : 'Standard'; ?></span></td>
											<td><?php echo htmlspecialchars(date('Y-m-d', strtotime($p['created_at']))); ?></td>
											<td>
												<div class="clean-list-actions">
													<a href="add-property.php?id=<?php echo $p['id']; ?>" class="edit-btn" title="Edit"><i class="fa fa-pen"></i></a>
													<a href="property-delete.php?id=<?php echo $p['id']; ?>" class="delete-btn" title="Delete" onclick="return confirm('Delete this plan?');"><i class="fa fa-trash"></i></a>
												</div>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php endif; ?>
				</div>
            </div>
        </div>

        <div class="footer">
            <div class="copyright">
                <p>Copyright © Mars Construction <?php echo date("Y"); ?>. All Rights Reserved</p>
            </div>
        </div>
    </div>

    <?php include 'elements/page-js.php'; ?>

</body>

</html>
