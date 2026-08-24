<?php
	 require_once __DIR__ . '/config/dz.php';

	 if (!empty($_GET['delete'])) {
		 $pdo->prepare('DELETE FROM construction_handles WHERE id = ?')->execute([(int) $_GET['delete']]);
		 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Item deleted.'];
		 header('Location: construction-handles.php');
		 exit;
	 }

	 $handles = $pdo->query('SELECT * FROM construction_handles ORDER BY sort_order, id')->fetchAll();

	 function handle_image_src($path) {
		 if (empty($path)) {
			 return null;
		 }
		 return strpos($path, 'uploads/') === 0 ? 'Admin/' . $path : '../' . $path;
	 }

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
						<li class="breadcrumb-item"><a href="sections.php?page=construction">Sections</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">What We Handle</a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="clean-list-header">
					<h2>What We Handle</h2>
					<a href="construction-handle-edit.php" class="clean-list-btn-add"><i class="fa fa-plus"></i>Add Item</a>
				</div>

				<div class="clean-list-card">
					<?php if (!$handles): ?>
						<div class="clean-list-empty">No items yet.</div>
					<?php else: ?>
						<div class="table-responsive">
							<table class="clean-list-table">
								<thead>
									<tr>
										<th>Photo</th>
										<th>Title</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($handles as $h): $img = handle_image_src($h['image']); ?>
										<tr>
											<td>
												<?php if ($img): ?>
													<img src="<?php echo htmlspecialchars($img); ?>" class="clean-list-thumb" alt="">
												<?php else: ?>
													<div class="clean-list-thumb d-flex align-items-center justify-content-center"><i class="fa fa-image text-muted"></i></div>
												<?php endif; ?>
											</td>
											<td><a href="construction-handle-edit.php?id=<?php echo $h['id']; ?>" class="clean-list-title"><?php echo htmlspecialchars($h['title']); ?></a></td>
											<td>
												<div class="clean-list-actions">
													<a href="construction-handle-edit.php?id=<?php echo $h['id']; ?>" class="edit-btn" title="Edit"><i class="fa fa-pen"></i></a>
													<a href="construction-handles.php?delete=<?php echo $h['id']; ?>" class="delete-btn" title="Delete" onclick="return confirm('Delete this item?');"><i class="fa fa-trash"></i></a>
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
