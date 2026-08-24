<?php
	 require_once __DIR__ . '/config/dz.php';
	 require_once __DIR__ . '/config/helpers.php';

	 $testimonial = null;
	 if (!empty($_GET['id'])) {
		 $stmt = $pdo->prepare('SELECT * FROM testimonials WHERE id = ?');
		 $stmt->execute([(int) $_GET['id']]);
		 $testimonial = $stmt->fetch();
	 }
	 $is_edit = (bool) $testimonial;
	 if (!$testimonial) {
		 $testimonial = ['id' => null, 'name' => '', 'role' => '', 'rating' => 5, 'testimonial' => '', 'image' => ''];
	 }

	 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		 $name = trim($_POST['name'] ?? '');
		 $role = trim($_POST['role'] ?? '');
		 $rating = max(1, min(5, (int) ($_POST['rating'] ?? 5)));
		 $text = trim($_POST['testimonial'] ?? '');
		 $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;

		 if ($name === '') {
			 $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'Name is required.'];
			 header('Location: testimonial-edit.php' . ($id ? "?id={$id}" : ''));
			 exit;
		 }

		 $uploaded_image = save_single_upload('image', 'branding', ['jpg', 'jpeg', 'png', 'webp']);
		 $image = $uploaded_image ?: trim($_POST['existing_image'] ?? '');

		 if ($id) {
			 $stmt = $pdo->prepare('UPDATE testimonials SET name = ?, role = ?, rating = ?, testimonial = ?, image = ? WHERE id = ?');
			 $stmt->execute([$name, $role, $rating, $text, $image, $id]);
			 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Testimonial updated.'];
		 } else {
			 $max = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), -1) m FROM testimonials')->fetch()['m'];
			 $stmt = $pdo->prepare('INSERT INTO testimonials (name, role, rating, testimonial, image, sort_order) VALUES (?, ?, ?, ?, ?, ?)');
			 $stmt->execute([$name, $role, $rating, $text, $image, $max + 1]);
			 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Testimonial added.'];
		 }
		 header('Location: testimonials.php');
		 exit;
	 }

	 $preview_src = null;
	 if (!empty($testimonial['image'])) {
		 $preview_src = strpos($testimonial['image'], 'uploads/') === 0 ? $testimonial['image'] : '../' . $testimonial['image'];
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
						<li class="breadcrumb-item"><a href="testimonials.php">Client Testimonials</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $is_edit ? 'Edit Testimonial' : 'Add Testimonial'; ?></a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo $is_edit ? 'Edit Testimonial' : 'Add Testimonial'; ?></h4>
                            </div>
                            <div class="card-body">
								<form method="post" enctype="multipart/form-data">
									<?php if ($is_edit): ?><input type="hidden" name="id" value="<?php echo (int) $testimonial['id']; ?>"><?php endif; ?>
									<div class="row">
										<div class="mb-3 col-lg-6">
											<label class="form-label">Name <span class="text-danger">*</span></label>
											<input type="text" name="name" class="form-control" placeholder="Leslie Alexander" value="<?php echo htmlspecialchars($testimonial['name']); ?>" required>
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Role <small class="text-muted">(e.g. Online Broker)</small></label>
											<input type="text" name="role" class="form-control" value="<?php echo htmlspecialchars($testimonial['role']); ?>">
										</div>
									</div>
									<div class="mb-3">
										<label class="form-label">Rating</label>
										<select name="rating" class="form-select" style="max-width:150px;">
											<?php for ($i = 5; $i >= 1; $i--): ?>
												<option value="<?php echo $i; ?>" <?php echo (int) $testimonial['rating'] === $i ? 'selected' : ''; ?>><?php echo str_repeat('★', $i); ?> (<?php echo $i; ?>)</option>
											<?php endfor; ?>
										</select>
									</div>
									<div class="mb-3">
										<label class="form-label">Testimonial Text</label>
										<textarea name="testimonial" class="form-control" rows="4"><?php echo htmlspecialchars($testimonial['testimonial']); ?></textarea>
									</div>
									<div class="mb-3">
										<label class="form-label">Photo</label>
										<input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($testimonial['image'] ?? ''); ?>">
										<?php if ($preview_src): ?><div class="mb-2"><img src="<?php echo htmlspecialchars($preview_src); ?>" style="max-height:80px; border-radius:50%;"></div><?php endif; ?>
										<input type="file" name="image" class="form-control" accept="image/*">
										<small class="text-muted">Leave blank to keep the current photo.</small>
									</div>
									<a href="testimonials.php" class="btn btn-outline-secondary">Cancel</a>
									<button type="submit" class="btn btn-primary"><?php echo $is_edit ? 'Save Changes' : 'Add Testimonial'; ?></button>
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
