<?php
	 require_once __DIR__ . '/config/dz.php';
	 require_once __DIR__ . '/config/helpers.php';

	 $card = null;
	 if (!empty($_GET['id'])) {
		 $stmt = $pdo->prepare('SELECT * FROM home_service_cards WHERE id = ?');
		 $stmt->execute([(int) $_GET['id']]);
		 $card = $stmt->fetch();
	 }
	 $is_edit = (bool) $card;
	 if (!$card) {
		 $card = ['id' => null, 'icon_class' => '', 'title' => '', 'description' => '', 'link_url' => '', 'image' => ''];
	 }

	 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		 $title = trim($_POST['title'] ?? '');
		 $icon_class = trim($_POST['icon_class'] ?? '');
		 $description = trim($_POST['description'] ?? '');
		 $link_url = trim($_POST['link_url'] ?? '');
		 $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;

		 if ($title === '') {
			 $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'Title is required.'];
			 header('Location: service-card-edit.php' . ($id ? "?id={$id}" : ''));
			 exit;
		 }

		 $uploaded_image = save_single_upload('image', 'branding', ['jpg', 'jpeg', 'png', 'webp']);
		 $image = $uploaded_image ?: trim($_POST['existing_image'] ?? '');

		 if ($id) {
			 $stmt = $pdo->prepare('UPDATE home_service_cards SET icon_class = ?, title = ?, description = ?, link_url = ?, image = ? WHERE id = ?');
			 $stmt->execute([$icon_class, $title, $description, $link_url, $image, $id]);
			 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Service card updated.'];
		 } else {
			 $max = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), -1) m FROM home_service_cards')->fetch()['m'];
			 $stmt = $pdo->prepare('INSERT INTO home_service_cards (icon_class, title, description, link_url, image, sort_order) VALUES (?, ?, ?, ?, ?, ?)');
			 $stmt->execute([$icon_class, $title, $description, $link_url, $image, $max + 1]);
			 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Service card added.'];
		 }
		 header('Location: service-cards.php');
		 exit;
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
						<li class="breadcrumb-item"><a href="service-cards.php">Services</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $is_edit ? 'Edit Service' : 'Add Service'; ?></a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo $is_edit ? 'Edit Service' : 'Add Service'; ?></h4>
                            </div>
                            <div class="card-body">
								<form method="post" enctype="multipart/form-data">
									<?php if ($is_edit): ?><input type="hidden" name="id" value="<?php echo (int) $card['id']; ?>"><?php endif; ?>
									<div class="mb-3">
										<label class="form-label">Title <span class="text-danger">*</span></label>
										<input type="text" name="title" class="form-control" placeholder="Building Construction" value="<?php echo htmlspecialchars($card['title']); ?>" required>
									</div>
									<div class="mb-3">
										<label class="form-label">Icon Class <small class="text-muted">(Flaticon or Font Awesome class name)</small></label>
										<input type="text" name="icon_class" class="form-control" placeholder="flaticon-building" value="<?php echo htmlspecialchars($card['icon_class']); ?>">
										<?php if ($card['icon_class']): ?><div class="mt-2" style="font-size:24px; color:#1C9DB2;"><i class="<?php echo htmlspecialchars($card['icon_class']); ?>"></i> <small class="text-muted">preview</small></div><?php endif; ?>
									</div>
									<div class="mb-3">
										<label class="form-label">Description</label>
										<textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($card['description']); ?></textarea>
									</div>
									<div class="mb-3">
										<label class="form-label">Links To <small class="text-muted">(e.g. construction.php)</small></label>
										<input type="text" name="link_url" class="form-control" placeholder="construction.php" value="<?php echo htmlspecialchars($card['link_url']); ?>">
									</div>
									<div class="mb-3">
										<label class="form-label">Photo</label>
										<input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($card['image'] ?? ''); ?>">
										<?php if (!empty($card['image'])): ?><div class="mb-2"><img src="<?php echo htmlspecialchars($card['image']); ?>" style="max-height:100px; border-radius:8px;"></div><?php endif; ?>
										<input type="file" name="image" class="form-control" accept="image/*">
										<small class="text-muted">Leave blank to keep the current photo.</small>
									</div>
									<a href="service-cards.php" class="btn btn-outline-secondary">Cancel</a>
									<button type="submit" class="btn btn-primary"><?php echo $is_edit ? 'Save Changes' : 'Add Service'; ?></button>
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
