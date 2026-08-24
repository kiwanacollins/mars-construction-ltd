<?php
	 require_once __DIR__ . '/config/dz.php';
	 require_once __DIR__ . '/config/helpers.php';

	 $member = null;
	 if (!empty($_GET['id'])) {
		 $stmt = $pdo->prepare('SELECT * FROM team_members WHERE id = ?');
		 $stmt->execute([(int) $_GET['id']]);
		 $member = $stmt->fetch();
	 }
	 $is_edit = (bool) $member;
	 if (!$member) {
		 $member = ['id' => null, 'name' => '', 'designation' => '', 'image' => '', 'facebook_url' => '', 'instagram_url' => '', 'twitter_url' => '', 'youtube_url' => ''];
	 }

	 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		 $name = trim($_POST['name'] ?? '');
		 $designation = trim($_POST['designation'] ?? '');
		 $facebook_url = trim($_POST['facebook_url'] ?? '');
		 $instagram_url = trim($_POST['instagram_url'] ?? '');
		 $twitter_url = trim($_POST['twitter_url'] ?? '');
		 $youtube_url = trim($_POST['youtube_url'] ?? '');
		 $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;

		 if ($name === '') {
			 $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'Name is required.'];
			 header('Location: team-edit.php' . ($id ? "?id={$id}" : ''));
			 exit;
		 }

		 $uploaded_image = save_single_upload('image', 'branding', ['jpg', 'jpeg', 'png', 'webp']);
		 $image = $uploaded_image ?: trim($_POST['existing_image'] ?? '');

		 if ($id) {
			 $stmt = $pdo->prepare('UPDATE team_members SET name = ?, designation = ?, image = ?, facebook_url = ?, instagram_url = ?, twitter_url = ?, youtube_url = ? WHERE id = ?');
			 $stmt->execute([$name, $designation, $image, $facebook_url, $instagram_url, $twitter_url, $youtube_url, $id]);
			 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Team member updated.'];
		 } else {
			 $max = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), -1) m FROM team_members')->fetch()['m'];
			 $stmt = $pdo->prepare('INSERT INTO team_members (name, designation, image, facebook_url, instagram_url, twitter_url, youtube_url, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
			 $stmt->execute([$name, $designation, $image, $facebook_url, $instagram_url, $twitter_url, $youtube_url, $max + 1]);
			 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Team member added.'];
		 }
		 header('Location: team.php');
		 exit;
	 }

	 $preview_src = null;
	 if (!empty($member['image'])) {
		 $preview_src = strpos($member['image'], 'uploads/') === 0 ? $member['image'] : '../' . $member['image'];
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
						<li class="breadcrumb-item"><a href="team.php">Team</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $is_edit ? 'Edit Team Member' : 'Add Team Member'; ?></a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo $is_edit ? 'Edit Team Member' : 'Add Team Member'; ?></h4>
                            </div>
                            <div class="card-body">
								<form method="post" enctype="multipart/form-data">
									<?php if ($is_edit): ?><input type="hidden" name="id" value="<?php echo (int) $member['id']; ?>"><?php endif; ?>
									<div class="mb-3">
										<label class="form-label">Name <span class="text-danger">*</span></label>
										<input type="text" name="name" class="form-control" placeholder="Leslie Alexander" value="<?php echo htmlspecialchars($member['name']); ?>" required>
									</div>
									<div class="mb-3">
										<label class="form-label">Designation</label>
										<input type="text" name="designation" class="form-control" placeholder="Sr. Director" value="<?php echo htmlspecialchars($member['designation']); ?>">
									</div>
									<div class="mb-3">
										<label class="form-label">Photo</label>
										<input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($member['image'] ?? ''); ?>">
										<?php if ($preview_src): ?><div class="mb-2"><img src="<?php echo htmlspecialchars($preview_src); ?>" style="max-height:100px; border-radius:8px;"></div><?php endif; ?>
										<input type="file" name="image" class="form-control" accept="image/*">
										<small class="text-muted">Leave blank to keep the current photo.</small>
									</div>
									<div class="row">
										<div class="mb-3 col-lg-6">
											<label class="form-label">Facebook URL</label>
											<input type="text" name="facebook_url" class="form-control" placeholder="https://facebook.com/..." value="<?php echo htmlspecialchars($member['facebook_url']); ?>">
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Instagram URL</label>
											<input type="text" name="instagram_url" class="form-control" placeholder="https://instagram.com/..." value="<?php echo htmlspecialchars($member['instagram_url']); ?>">
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Twitter / X URL</label>
											<input type="text" name="twitter_url" class="form-control" placeholder="https://x.com/..." value="<?php echo htmlspecialchars($member['twitter_url']); ?>">
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">YouTube URL</label>
											<input type="text" name="youtube_url" class="form-control" placeholder="https://youtube.com/..." value="<?php echo htmlspecialchars($member['youtube_url']); ?>">
										</div>
									</div>
									<a href="team.php" class="btn btn-outline-secondary">Cancel</a>
									<button type="submit" class="btn btn-primary"><?php echo $is_edit ? 'Save Changes' : 'Add Team Member'; ?></button>
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
