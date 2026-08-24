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
			 (SELECT file_path FROM project_files pf WHERE pf.project_id = p.id ORDER BY pf.is_cover DESC, pf.id ASC LIMIT 1) AS cover_image
		  FROM projects p
		  $where_sql
		  ORDER BY p.created_at DESC"
	 );
	 $stmt->execute($params);
	 $projects = $stmt->fetchAll();

	 $project_categories = $pdo->query("SELECT DISTINCT category FROM projects WHERE category IS NOT NULL AND category != '' ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

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
						<li class="breadcrumb-item"><a href="javascript:void(0)">Projects</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Projects</a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="clean-list-header">
					<h2>Projects</h2>
					<a href="add-project.php" class="clean-list-btn-add"><i class="fa fa-plus"></i>Add Project</a>
				</div>

				<div class="clean-list-card">
					<form method="get" class="clean-list-filters">
						<input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search project title...">
						<select name="category" onchange="this.form.submit()">
							<option value="">All Categories</option>
							<?php foreach ($project_categories as $cat): ?>
								<option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category_filter === $cat ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
							<?php endforeach; ?>
						</select>
						<button type="submit" class="btn btn-primary btn-sm">Filter</button>
						<?php if ($search !== '' || $category_filter !== ''): ?>
							<a href="project-list.php" class="btn btn-outline-secondary btn-sm">Clear</a>
						<?php endif; ?>
					</form>

					<?php if (!$projects): ?>
						<div class="clean-list-empty">No projects found. <a href="add-project.php">Add your first project</a>.</div>
					<?php else: ?>
						<div class="table-responsive">
							<table class="clean-list-table">
								<thead>
									<tr>
										<th>Photo</th>
										<th>Title</th>
										<th>Category</th>
										<th>Location</th>
										<th>Client</th>
										<th>Status</th>
										<th>Added</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($projects as $p): ?>
										<tr>
											<td><img src="<?php echo htmlspecialchars($p['cover_image'] ?: 'assets/images/property/1.jpg'); ?>" alt="" class="clean-list-thumb"></td>
											<td><a href="add-project.php?id=<?php echo $p['id']; ?>" class="clean-list-title"><?php echo htmlspecialchars($p['title']); ?></a></td>
											<td><?php echo htmlspecialchars($p['category'] ?: 'Uncategorized'); ?></td>
											<td><?php echo htmlspecialchars($p['location'] ?: '—'); ?></td>
											<td><?php echo htmlspecialchars($p['client_name'] ?: '—'); ?></td>
											<td><span class="clean-list-pill <?php echo $p['featured'] ? 'is-featured' : 'is-standard'; ?>"><?php echo $p['featured'] ? 'Featured' : 'Standard'; ?></span></td>
											<td><?php echo htmlspecialchars(date('Y-m-d', strtotime($p['created_at']))); ?></td>
											<td>
												<div class="clean-list-actions">
													<a href="add-project.php?id=<?php echo $p['id']; ?>" class="edit-btn" title="Edit"><i class="fa fa-pen"></i></a>
													<a href="project-delete.php?id=<?php echo $p['id']; ?>" class="delete-btn" title="Delete" onclick="return confirm('Delete this project?');"><i class="fa fa-trash"></i></a>
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

        <?php include 'elements/footer.php'; ?>
    </div>

    <?php include 'elements/page-js.php'; ?>

</body>

</html>
