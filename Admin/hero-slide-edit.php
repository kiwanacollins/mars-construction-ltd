<?php
	 require_once __DIR__ . '/config/dz.php';

	 $slide = ['id' => null, 'heading' => '', 'subheading' => '', 'description' => '', 'button_text' => '', 'button_link' => '', 'button2_text' => '', 'button2_link' => '', 'image' => '', 'bg_type' => 'image', 'video_url' => ''];
	 $is_edit = false;
	 if (!empty($_GET['id'])) {
		 $stmt = $pdo->prepare('SELECT * FROM hero_slides WHERE id = ?');
		 $stmt->execute([(int) $_GET['id']]);
		 $found = $stmt->fetch();
		 if ($found) {
			 $slide = $found;
			 $is_edit = true;
		 }
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

	<style>
	.hero-slide-card {
		border: none;
		border-radius: 14px;
		box-shadow: 0 2px 14px rgba(28, 157, 178, 0.10);
		overflow: hidden;
		background: #fff;
	}

	.hero-slide-card_header {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 16px 22px;
		background: linear-gradient(120deg, #1C9DB2, #17828f);
	}

	.hero-slide-card_header h5 {
		margin: 0;
		color: #fff;
		font-weight: 600;
	}

	.hero-slide-card_body {
		padding: 24px;
	}

	.hero-slide-image-preview {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 100%;
		aspect-ratio: 16 / 9;
		margin-bottom: 10px;
		border: 1.5px dashed #d7dce3;
		border-radius: 10px;
		background: #f6f8fa;
		overflow: hidden;
		text-align: center;
	}

	.hero-slide-image-preview img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}

	.hero-slide-image-preview span {
		color: #9aa4b2;
		font-size: 12px;
	}
	</style>

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
						<li class="breadcrumb-item"><a href="hero-slides.php?tab=slides">Hero / Slides</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $is_edit ? 'Edit Slide' : 'Add Slide'; ?></a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

                <div class="row">
                    <div class="col-12">
						<div class="hero-slide-card">
							<div class="hero-slide-card_header">
								<h5><?php echo $is_edit ? 'Edit Slide' : 'Add New Slide'; ?></h5>
							</div>
							<div class="hero-slide-card_body">
								<form method="post" action="hero-slide-save.php" enctype="multipart/form-data">
									<input type="hidden" name="id" value="<?php echo htmlspecialchars($slide['id'] ?? ''); ?>">
									<input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($slide['image']); ?>">
									<div class="row">
										<div class="col-lg-8">
											<div class="row">
												<div class="mb-3 col-lg-6">
													<label class="form-label">Heading</label>
													<input type="text" name="heading" class="form-control" value="<?php echo htmlspecialchars($slide['heading']); ?>" required>
												</div>
												<div class="mb-3 col-lg-6">
													<label class="form-label">Tagline <small class="text-muted">(small text above heading)</small></label>
													<input type="text" name="subheading" class="form-control" value="<?php echo htmlspecialchars($slide['subheading']); ?>">
												</div>
												<div class="mb-3 col-12">
													<label class="form-label">Description</label>
													<textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($slide['description']); ?></textarea>
												</div>
												<div class="mb-3 col-lg-6">
													<label class="form-label">Button 1 Text <small class="text-muted">(solid button)</small></label>
													<input type="text" name="button_text" class="form-control" value="<?php echo htmlspecialchars($slide['button_text']); ?>">
												</div>
												<div class="mb-3 col-lg-6">
													<label class="form-label">Button 1 Link</label>
													<input type="text" name="button_link" class="form-control" value="<?php echo htmlspecialchars($slide['button_link']); ?>">
												</div>
												<div class="mb-3 col-lg-6">
													<label class="form-label">Button 2 Text <small class="text-muted">(white outline button)</small></label>
													<input type="text" name="button2_text" class="form-control" value="<?php echo htmlspecialchars($slide['button2_text'] ?? ''); ?>">
												</div>
												<div class="mb-3 col-lg-6">
													<label class="form-label">Button 2 Link</label>
													<input type="text" name="button2_link" class="form-control" value="<?php echo htmlspecialchars($slide['button2_link'] ?? ''); ?>">
												</div>
												<small class="text-muted d-block mb-3">Both buttons appear together on the slide when filled in. Leave Button 2 blank to show only Button 1.</small>
											</div>
										</div>
										<div class="col-lg-4">
											<label class="form-label d-block">Background Type</label>
											<div class="form-check form-check-inline mb-2">
												<input class="form-check-input hero-bg-type-toggle" type="radio" name="bg_type" id="bg_type_image" value="image" <?php echo ($slide['bg_type'] ?? 'image') === 'image' ? 'checked' : ''; ?>>
												<label class="form-check-label" for="bg_type_image">Photo</label>
											</div>
											<div class="form-check form-check-inline mb-2">
												<input class="form-check-input hero-bg-type-toggle" type="radio" name="bg_type" id="bg_type_video" value="video" <?php echo ($slide['bg_type'] ?? 'image') === 'video' ? 'checked' : ''; ?>>
												<label class="form-check-label" for="bg_type_video">Video</label>
											</div>

											<div id="hero-bg-image-fields" style="<?php echo ($slide['bg_type'] ?? 'image') === 'video' ? 'display:none;' : ''; ?>">
												<label class="form-label">Background Image</label>
												<div class="hero-slide-image-preview">
													<?php if ($slide['image']): ?>
														<img src="<?php echo htmlspecialchars('../' . $slide['image']); ?>" alt="">
													<?php else: ?>
														<span><i class="fa fa-image d-block mb-1"></i>No image set</span>
													<?php endif; ?>
												</div>
												<input type="file" name="slide_image" class="form-control form-control-sm" accept="image/*">
												<small class="text-muted d-block mt-1">Leave blank to keep current</small>
											</div>

											<div id="hero-bg-video-fields" style="<?php echo ($slide['bg_type'] ?? 'image') === 'video' ? '' : 'display:none;'; ?>">
												<label class="form-label">Video URL</label>
												<input type="text" name="video_url" class="form-control form-control-sm" placeholder="https://www.youtube.com/watch?v=..." value="<?php echo htmlspecialchars($slide['video_url'] ?? ''); ?>">
												<small class="text-muted d-block mt-1">Paste a YouTube link, or a direct video file URL (.mp4). Video plays muted and on loop as the background.</small>
											</div>
										</div>
									</div>
									<script>
									(function () {
										var toggles = document.querySelectorAll('.hero-bg-type-toggle');
										var imageFields = document.getElementById('hero-bg-image-fields');
										var videoFields = document.getElementById('hero-bg-video-fields');
										toggles.forEach(function (el) {
											el.addEventListener('change', function () {
												var isVideo = document.getElementById('bg_type_video').checked;
												imageFields.style.display = isVideo ? 'none' : '';
												videoFields.style.display = isVideo ? '' : 'none';
											});
										});
									})();
									</script>
									<hr class="my-4">
									<a href="hero-slides.php?tab=slides" class="btn btn-outline-secondary">Cancel</a>
									<button type="submit" class="btn btn-primary"><?php echo $is_edit ? 'Save Changes' : 'Add Slide'; ?></button>
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
