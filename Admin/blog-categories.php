<?php
	 require_once __DIR__ . '/config/dz.php';

	 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		 $name = trim($_POST['name'] ?? '');
		 $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
		 if ($name !== '') {
			 if ($id) {
				 $stmt = $pdo->prepare('UPDATE blog_categories SET name = ? WHERE id = ?');
				 $stmt->execute([$name, $id]);
				 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Category updated.'];
			 } else {
				 $stmt = $pdo->prepare('INSERT IGNORE INTO blog_categories (name) VALUES (?)');
				 $stmt->execute([$name]);
				 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Category added.'];
			 }
		 }
		 header('Location: blog-categories.php');
		 exit;
	 }

	 if (!empty($_GET['delete'])) {
		 $del = $pdo->prepare('DELETE FROM blog_categories WHERE id = ?');
		 $del->execute([(int) $_GET['delete']]);
		 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Category deleted.'];
		 header('Location: blog-categories.php');
		 exit;
	 }

	 $categories = $pdo->query(
		 "SELECT c.*, (SELECT COUNT(*) FROM blog_posts p WHERE p.category_id = c.id) AS post_count
		  FROM blog_categories c ORDER BY c.name"
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
						<li class="breadcrumb-item"><a href="javascript:void(0)">Forms</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Categories</a></li>
					</ol>
                </div>
                <div class="row">
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Add Category</h4>
                            </div>
                            <div class="card-body">
								<?php if (!empty($form_status_message)): ?>
									<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
								<?php endif; ?>
								<form method="post">
									<div class="mb-3">
										<label class="form-label">Category Name</label>
										<input type="text" name="name" class="form-control" placeholder="e.g. Buying" required>
									</div>
									<button type="submit" class="btn btn-primary">Add Category</button>
								</form>
							</div>
                        </div>
                    </div>
					<div class="col-lg-7">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">All Categories</h4>
                            </div>
                            <div class="card-body">
								<div class="table-responsive">
									<table class="table">
										<thead>
											<tr>
												<th>Name</th>
												<th>Posts</th>
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!$categories): ?>
												<tr><td colspan="3" class="text-center">No categories yet.</td></tr>
											<?php endif; ?>
											<?php foreach ($categories as $c): ?>
												<tr>
													<td>
														<form method="post" class="d-flex align-items-center gap-2">
															<input type="hidden" name="id" value="<?php echo $c['id']; ?>">
															<input type="text" name="name" class="form-control form-control-sm" value="<?php echo htmlspecialchars($c['name']); ?>">
															<button type="submit" class="btn btn-sm btn-primary">Save</button>
														</form>
													</td>
													<td><?php echo (int) $c['post_count']; ?></td>
													<td>
														<a href="blog-categories.php?delete=<?php echo $c['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this category? Posts using it will become uncategorized.');">Delete</a>
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
