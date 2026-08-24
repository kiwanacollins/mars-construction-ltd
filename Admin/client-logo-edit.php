<?php
	 require_once __DIR__ . '/config/dz.php';
	 require_once __DIR__ . '/config/helpers.php';

	 $logo = null;
	 if (!empty($_GET['id'])) {
		 $stmt = $pdo->prepare('SELECT * FROM client_logos WHERE id = ?');
		 $stmt->execute([(int) $_GET['id']]);
		 $logo = $stmt->fetch();
	 }
	 $is_edit = (bool) $logo;
	 if (!$logo) {
		 $logo = ['id' => null, 'image' => '', 'link_url' => ''];
	 }

	 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		 $link_url = trim($_POST['link_url'] ?? '');
		 $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;

		 $uploaded_image = save_single_upload('image', 'branding', ['jpg', 'jpeg', 'png', 'webp', 'svg']);
		 $image = $uploaded_image ?: trim($_POST['existing_image'] ?? '');

		 if (!$image) {
			 $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'A logo image is required.'];
			 header('Location: client-logo-edit.php' . ($id ? "?id={$id}" : ''));
			 exit;
		 }

		 if ($id) {
			 $stmt = $pdo->prepare('UPDATE client_logos SET image = ?, link_url = ? WHERE id = ?');
			 $stmt->execute([$image, $link_url, $id]);
			 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Client logo updated.'];
		 } else {
			 $max = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), -1) m FROM client_logos')->fetch()['m'];
			 $stmt = $pdo->prepare('INSERT INTO client_logos (image, link_url, sort_order) VALUES (?, ?, ?)');
			 $stmt->execute([$image, $link_url, $max + 1]);
			 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Client logo added.'];
		 }
		 header('Location: client-logos.php');
		 exit;
	 }

	 $preview_src = null;
	 if (!empty($logo['image'])) {
		 $preview_src = strpos($logo['image'], 'uploads/') === 0 ? $logo['image'] : '../' . $logo['image'];
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
						<li class="breadcrumb-item"><a href="client-logos.php">Trusted Client Logos</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $is_edit ? 'Edit Logo' : 'Add Logo'; ?></a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo $is_edit ? 'Edit Logo' : 'Add Logo'; ?></h4>
                            </div>
                            <div class="card-body">
								<form method="post" enctype="multipart/form-data">
									<?php if ($is_edit): ?><input type="hidden" name="id" value="<?php echo (int) $logo['id']; ?>"><?php endif; ?>
									<div class="mb-3">
										<label class="form-label">Logo Image <?php echo $is_edit ? '' : '<span class="text-danger">*</span>'; ?></label>
										<input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($logo['image'] ?? ''); ?>">
										<?php if ($preview_src): ?><div class="mb-2 p-2" style="background:#f8f9fa; display:inline-block;"><img src="<?php echo htmlspecialchars($preview_src); ?>" style="max-height:60px;"></div><?php endif; ?>
										<input type="file" name="image" class="form-control" accept="image/*" <?php echo $is_edit ? '' : 'required'; ?>>
										<small class="text-muted">Leave blank to keep the current logo.</small>
									</div>
									<div class="mb-3">
										<label class="form-label">Links To <small class="text-muted">(optional)</small></label>
										<input type="text" name="link_url" class="form-control" placeholder="https://..." value="<?php echo htmlspecialchars($logo['link_url']); ?>">
									</div>
									<a href="client-logos.php" class="btn btn-outline-secondary">Cancel</a>
									<button type="submit" class="btn btn-primary"><?php echo $is_edit ? 'Save Changes' : 'Add Logo'; ?></button>
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
