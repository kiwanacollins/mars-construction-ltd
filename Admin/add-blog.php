<?php
	 require_once __DIR__ . '/config/dz.php';

	 $post = null;
	 if (!empty($_GET['id'])) {
		 $stmt = $pdo->prepare('SELECT * FROM blog_posts WHERE id = ?');
		 $stmt->execute([(int) $_GET['id']]);
		 $post = $stmt->fetch();
	 }
	 $categories = $pdo->query('SELECT id, name FROM blog_categories ORDER BY name')->fetchAll();
	 $authors = $pdo->query('SELECT id, name, email FROM users ORDER BY name')->fetchAll();

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

    <!--*******************
        Preloader start
    ********************-->
    <?php include 'elements/pre-loader.php'; ?>
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <?php include 'elements/nav-header.php'; ?>
        <!--**********************************
            Nav header end
        ***********************************-->
		
		<!--**********************************
            Chat box start
        ***********************************-->
		<?php include 'elements/chatbox.php'; ?>
		<!--**********************************
            Chat box End
        ***********************************-->
		
		<!--**********************************
            Header start
        ***********************************-->
        <?php include 'elements/header.php'; ?>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <?php include 'elements/sidebar.php'; ?>
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                <div class="page-titles">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="javascript:void(0)">CMS</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Add Blog</a></li>
					</ol>
                </div>
                <!-- Row -->
				<div class="row">
					<div class="col-xl-12">
						<div class="mb-4">
							<ul class="d-flex align-items-center flex-wrap">
								<li><a href="blog.php" class="btn btn-primary btn-sm">Blog List</a></li>
							</ul>
						</div>
						<?php if (!empty($form_status_message)): ?>
							<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
						<?php endif; ?>
						<form action="blog-save.php" method="post" enctype="multipart/form-data">
							<?php if ($post): ?>
								<input type="hidden" name="id" value="<?php echo (int) $post['id']; ?>">
							<?php endif; ?>
							<div class="row">
								<div class="col-xl-8">
									<div class="card h-auto">
										<div class="card-body">
											<div class="mb-3">
												<label class="form-label">Title</label>
												<input type="text" name="title" class="form-control" placeholder="Title" value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>" required>
											</div>
											<label class="form-label">Body</label>
											<textarea name="body" id="ckeditor" class="form-control" rows="12"><?php echo htmlspecialchars($post['body'] ?? ''); ?></textarea>
										</div>
									</div>

									<div class="filter cm-content-box box-primary">
										<div class="content-title SlideToolHeader">
											<div class="cpa">Excerpt</div>
											<div class="tools"><a href="javascript:void(0);" class="expand handle"><i class="fal fa-angle-down"></i></a></div>
										</div>
										<div class="cm-content-body publish-content form excerpt">
											<div class="card-body">
												<div class="mb-3">
												  <label class="form-label">Excerpt</label>
												  <textarea name="excerpt" class="form-control" rows="3"><?php echo htmlspecialchars($post['excerpt'] ?? ''); ?></textarea>
												  <div class="form-text">Optional short summary shown on blog listing pages.</div>
												</div>
											</div>
										</div>
									</div>
									<div class="filter cm-content-box box-primary">
										<div class="content-title SlideToolHeader">
											<div class="cpa">Slug</div>
											<div class="tools"><a href="javascript:void(0);" class="expand handle"><i class="fal fa-angle-down"></i></a></div>
										</div>
										<div class="cm-content-body form excerpt">
											<div class="card-body">
												<label class="form-label">Slug (leave blank to auto-generate)</label>
												<input type="text" name="slug" class="form-control" value="<?php echo htmlspecialchars($post['slug'] ?? ''); ?>">
											</div>
										</div>
									</div>
									<div class="filter cm-content-box box-primary">
										<div class="content-title SlideToolHeader">
											<div class="cpa">Author</div>
											<div class="tools"><a href="javascript:void(0);" class="expand handle"><i class="fal fa-angle-down"></i></a></div>
										</div>
										<div class="cm-content-body form excerpt">
											<div class="card-body">
												<label class="form-label">User</label>
												<select name="author_id" class="form-control default-select h-auto wide">
													<option value="">-- Select --</option>
													<?php foreach ($authors as $a): ?>
														<option value="<?php echo $a['id']; ?>" <?php echo (int) ($post['author_id'] ?? 0) === (int) $a['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($a['name']); ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="col-xl-4">
									<div class="right-sidebar-sticky">
										<div class="filter cm-content-box box-primary">
											<div class="content-title SlideToolHeader">
												<div class="cpa">Publish</div>
												<div class="tools"><a href="javascript:void(0);" class="expand handle"><i class="fal fa-angle-down"></i></a></div>
											</div>
											<div class="cm-content-body publish-content form excerpt">
												<div class="card-body py-3">
													<div class="mb-3">
														<label class="form-label w-100">Status</label>
														<select name="status" class="form-control solid default-select">
															<option value="draft" <?php echo ($post['status'] ?? 'draft') === 'draft' ? 'selected' : ''; ?>>Draft</option>
															<option value="published" <?php echo ($post['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
															<option value="pending" <?php echo ($post['status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
														</select>
													</div>
													<div class="mb-3">
														<label class="form-label w-100">Published Date</label>
														<input type="date" name="published_at" class="form-control bt-datepicker solid" value="<?php echo htmlspecialchars($post['published_at'] ?? date('Y-m-d')); ?>">
													</div>
												</div>
												<div class="card-footer border-top text-end py-3">
													<button type="submit" class="btn btn-primary btn-sm"><?php echo $post ? 'Update' : 'Publish'; ?></button>
												</div>
											</div>
										</div>
										<div class="filter cm-content-box box-primary">
											<div class="content-title SlideToolHeader">
												<div class="cpa">Category</div>
												<div class="tools"><a href="javascript:void(0);" class="expand handle"><i class="fal fa-angle-down"></i></a></div>
											</div>
											<div class="cm-content-body publish-content form excerpt">
												<div class="card-body">
													<select name="category_id" class="form-control default-select h-auto wide">
														<option value="">-- None --</option>
														<?php foreach ($categories as $c): ?>
															<option value="<?php echo $c['id']; ?>" <?php echo (int) ($post['category_id'] ?? 0) === (int) $c['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['name']); ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											</div>
										</div>
										<div class="filter cm-content-box box-primary">
											<div class="content-title SlideToolHeader">
												<div class="cpa">Featured Image</div>
												<div class="tools"><a href="javascript:void(0);" class="expand handle"><i class="fal fa-angle-down"></i></a></div>
											</div>
											<div class="cm-content-body publish-content form excerpt">
												<div class="card-body">
													<?php if (!empty($post['featured_image'])): ?>
														<img src="<?php echo htmlspecialchars($post['featured_image']); ?>" style="width:100%;max-height:160px;object-fit:cover;" class="rounded mb-2">
													<?php endif; ?>
													<input type="file" name="featured_image" class="form-control" accept=".png,.jpg,.jpeg,.webp">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
        <!--**********************************
            Content body end
        ***********************************-->

        <!--**********************************
            Footer start
        ***********************************-->
		<div class="footer">
            <div class="copyright">
                <p>Copyright © Mars Construction <?php echo date("Y"); ?>. All Rights Reserved</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->
	
	<!--removeIf(production)-->
        
    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
   <?php include 'elements/page-js.php'; ?>
	<script>
		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$('#imagePreview').css('background-image', 'url('+e.target.result +')');
					$('#imagePreview').hide();
					$('#imagePreview').fadeIn(650);
				}
				reader.readAsDataURL(input.files[0]);
			}
		}
		$("#imageUpload").on('change',function() {
			
			readURL(this);
		});
			$('.remove-img').on('click', function() {
				var imageUrl = "images/no-img-avatar.png";
				$('.avatar-preview, #imagePreview').removeAttr('style');
				$('#imagePreview').css('background-image', 'url(' + imageUrl + ')');
			});
	</script>
</body>

</html>