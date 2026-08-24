<?php
	 require_once __DIR__ . '/config/dz.php';

	 $page_options = [
		 'about.php' => 'About Us',
		 'services.php' => 'Our Services',
		 'service-detail.php' => 'Service Detail',
		 'construction.php' => 'Construction',
		 'property-management.php' => 'Property Management',
		 'plans.php' => 'Plans',
		 'blog.php' => 'Blog',
		 'contact.php' => 'Contact Us',
	 ];

	 $selected_page = $_GET['page'] ?? '';
	 if (!isset($page_options[$selected_page])) {
		 header('Location: hero-slides.php');
		 exit;
	 }

	 $pbstmt = $pdo->prepare('SELECT image FROM page_title_backgrounds WHERE page_key = ?');
	 $pbstmt->execute([$selected_page]);
	 $current_page_bg = $pbstmt->fetchColumn();

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
						<li class="breadcrumb-item"><a href="hero-slides.php">Hero / Slides</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo htmlspecialchars($page_options[$selected_page]); ?></a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Page Title Background &mdash; <?php echo htmlspecialchars($page_options[$selected_page]); ?></h4>
                            </div>
                            <div class="card-body">
								<form method="post" action="page-title-bg-save.php" enctype="multipart/form-data">
									<input type="hidden" name="page_key" value="<?php echo htmlspecialchars($selected_page); ?>">
									<div class="row">
										<div class="mb-3 col-lg-4">
											<label class="form-label">Current Background</label>
											<div>
												<?php if ($current_page_bg): ?>
													<img src="<?php echo htmlspecialchars($current_page_bg); ?>" alt="Page title background preview" style="max-height:120px; max-width:100%; border-radius:6px;">
												<?php else: ?>
													<span class="text-muted">No image set (default light background)</span>
												<?php endif; ?>
											</div>
										</div>
										<div class="mb-3 col-lg-8">
											<label class="form-label">Replace Background Image <small class="text-muted">(leave blank to keep current)</small></label>
											<input type="file" name="page_title_bg_upload" class="form-control" accept="image/*">
										</div>
									</div>
									<a href="hero-slides.php" class="btn btn-outline-secondary">Cancel</a>
									<button type="submit" class="btn btn-primary">Save Background</button>
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
