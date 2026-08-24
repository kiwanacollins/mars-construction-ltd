<?php
	 require_once __DIR__ . '/config/dz.php';
	 require_once __DIR__ . '/config/helpers.php';

	 $handle = null;
	 if (!empty($_GET['id'])) {
		 $stmt = $pdo->prepare('SELECT * FROM construction_handles WHERE id = ?');
		 $stmt->execute([(int) $_GET['id']]);
		 $handle = $stmt->fetch();
	 }
	 $is_edit = (bool) $handle;
	 if (!$handle) {
		 $handle = ['id' => null, 'title' => '', 'image' => ''];
	 }

	 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		 $title = trim($_POST['title'] ?? '');
		 $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;

		 if ($title === '') {
			 $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'Title is required.'];
			 header('Location: construction-handle-edit.php' . ($id ? "?id={$id}" : ''));
			 exit;
		 }

		 $uploaded_image = save_single_upload('image', 'branding', ['jpg', 'jpeg', 'png', 'webp']);
		 $image = $uploaded_image ?: trim($_POST['existing_image'] ?? '');

		 if ($id) {
			 $stmt = $pdo->prepare('UPDATE construction_handles SET title = ?, image = ? WHERE id = ?');
			 $stmt->execute([$title, $image, $id]);
			 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Item updated.'];
		 } else {
			 $max = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), -1) m FROM construction_handles')->fetch()['m'];
			 $stmt = $pdo->prepare('INSERT INTO construction_handles (title, image, sort_order) VALUES (?, ?, ?)');
			 $stmt->execute([$title, $image, $max + 1]);
			 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Item added.'];
		 }
		 header('Location: construction-handles.php');
		 exit;
	 }

	 $preview_src = null;
	 if (!empty($handle['image'])) {
		 $preview_src = strpos($handle['image'], 'uploads/') === 0 ? $handle['image'] : '../' . $handle['image'];
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
						<li class="breadcrumb-item"><a href="construction-handles.php">What We Handle</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $is_edit ? 'Edit Item' : 'Add Item'; ?></a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo $is_edit ? 'Edit Item' : 'Add Item'; ?></h4>
                            </div>
                            <div class="card-body">
								<form method="post" enctype="multipart/form-data">
									<?php if ($is_edit): ?><input type="hidden" name="id" value="<?php echo (int) $handle['id']; ?>"><?php endif; ?>
									<div class="mb-3">
										<label class="form-label">Title <span class="text-danger">*</span></label>
										<input type="text" name="title" class="form-control" placeholder="Site preparation and foundation work" value="<?php echo htmlspecialchars($handle['title']); ?>" required>
									</div>
									<div class="mb-3">
										<label class="form-label">Photo</label>
										<input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($handle['image'] ?? ''); ?>">
										<?php if ($preview_src): ?><div class="mb-2"><img src="<?php echo htmlspecialchars($preview_src); ?>" style="max-height:100px; border-radius:8px;"></div><?php endif; ?>
										<input type="file" name="image" class="form-control" accept="image/*">
										<small class="text-muted">Leave blank to keep the current photo.</small>
									</div>
									<a href="construction-handles.php" class="btn btn-outline-secondary">Cancel</a>
									<button type="submit" class="btn btn-primary"><?php echo $is_edit ? 'Save Changes' : 'Add Item'; ?></button>
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
