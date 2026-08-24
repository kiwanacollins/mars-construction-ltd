<?php
	 require_once __DIR__ . '/config/dz.php';
	 require_once __DIR__ . '/config/helpers.php';

	 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		 $name = trim($_POST['name'] ?? '');
		 $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
		 if ($name !== '') {
			 $image = save_single_upload('image', 'categories', ['jpg', 'jpeg', 'png', 'webp']);
			 if ($id) {
				 $slug = unique_slug($pdo, 'plan_categories', $name, $id);
				 if ($image) {
					 $stmt = $pdo->prepare('UPDATE plan_categories SET name = ?, slug = ?, image = ? WHERE id = ?');
					 $stmt->execute([$name, $slug, $image, $id]);
				 } else {
					 $stmt = $pdo->prepare('UPDATE plan_categories SET name = ?, slug = ? WHERE id = ?');
					 $stmt->execute([$name, $slug, $id]);
				 }
				 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Category updated.'];
			 } else {
				 $slug = unique_slug($pdo, 'plan_categories', $name);
				 $max = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), 0) m FROM plan_categories')->fetch()['m'];
				 $stmt = $pdo->prepare('INSERT INTO plan_categories (name, slug, sort_order, image) VALUES (?, ?, ?, ?)');
				 $stmt->execute([$name, $slug, $max + 1, $image]);
				 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Category added.'];
			 }
		 }
		 header('Location: plan-categories.php');
		 exit;
	 }

	 if (!empty($_GET['delete'])) {
		 $del = $pdo->prepare('DELETE FROM plan_categories WHERE id = ?');
		 $del->execute([(int) $_GET['delete']]);
		 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Category deleted.'];
		 header('Location: plan-categories.php');
		 exit;
	 }

	 $categories = $pdo->query(
		 "SELECT c.*, (SELECT COUNT(*) FROM properties p WHERE p.category = c.name) AS plan_count
		  FROM plan_categories c ORDER BY c.sort_order, c.name"
	 )->fetchAll();

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
						<li class="breadcrumb-item"><a href="property-list.php">Plans</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Plan Categories</a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="clean-list-header">
					<h2>Plan Categories</h2>
					<a href="javascript:void(0)" id="toggle-add-category" class="clean-list-btn-add"><i class="fa fa-plus"></i>Add Category</a>
				</div>

				<div class="clean-list-card mb-3" id="add-category-panel" style="display:none; padding:20px;">
					<form method="post" enctype="multipart/form-data" class="d-flex align-items-end gap-2 flex-wrap">
						<div>
							<label class="form-label">Category Name</label>
							<input type="text" name="name" class="form-control" placeholder="e.g. Villas" required>
						</div>
						<div>
							<label class="form-label">Photo <small class="text-muted">(shown on homepage)</small></label>
							<input type="file" name="image" class="form-control" accept="image/*">
						</div>
						<button type="submit" class="btn btn-primary">Add Category</button>
					</form>
				</div>

				<div class="clean-list-card">
					<?php if (!$categories): ?>
						<div class="clean-list-empty">No categories yet.</div>
					<?php else: ?>
						<div class="table-responsive">
							<table class="clean-list-table">
								<thead>
									<tr>
										<th>Photo</th>
										<th>Name</th>
										<th>Plans</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($categories as $c): ?>
										<tr>
											<td>
												<?php if ($c['image']): ?>
													<img src="<?php echo htmlspecialchars($c['image']); ?>" class="clean-list-thumb">
												<?php else: ?>
													<div class="clean-list-thumb d-flex align-items-center justify-content-center text-muted"><i class="fa fa-image"></i></div>
												<?php endif; ?>
											</td>
											<td>
												<form method="post" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
													<input type="hidden" name="id" value="<?php echo $c['id']; ?>">
													<input type="text" name="name" class="form-control form-control-sm" value="<?php echo htmlspecialchars($c['name']); ?>" style="max-width:180px;">
													<input type="file" name="image" class="form-control form-control-sm" accept="image/*" title="Replace photo" style="max-width:180px;">
													<button type="submit" class="btn btn-sm btn-primary text-nowrap">Save</button>
												</form>
											</td>
											<td><?php echo (int) $c['plan_count']; ?></td>
											<td>
												<div class="clean-list-actions">
													<a href="plan-categories.php?delete=<?php echo $c['id']; ?>" class="delete-btn" title="Delete" onclick="return confirm('Delete this category? Plans using it will keep the old category text.');"><i class="fa fa-trash"></i></a>
												</div>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php endif; ?>
				</div>

				<script>
				document.getElementById('toggle-add-category').addEventListener('click', function () {
					var panel = document.getElementById('add-category-panel');
					panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
				});
				</script>
            </div>
        </div>

        <?php include 'elements/footer.php'; ?>
    </div>

    <?php include 'elements/page-js.php'; ?>

</body>

</html>
