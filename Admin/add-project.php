<?php
	 require_once __DIR__ . '/config/dz.php';

	 $project = null;
	 $project_files = [];
	 if (!empty($_GET['id'])) {
		 $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = ?');
		 $stmt->execute([(int) $_GET['id']]);
		 $project = $stmt->fetch();
		 if ($project) {
			 $fstmt = $pdo->prepare('SELECT * FROM project_files WHERE project_id = ? ORDER BY sort_order, id');
			 $fstmt->execute([$project['id']]);
			 $project_files = $fstmt->fetchAll();
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
						<li class="breadcrumb-item"><a href="project-list.php">Projects</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $project ? 'Edit Project' : 'Create Project'; ?></a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<form action="project-save.php" method="post" enctype="multipart/form-data" id="project-wizard-form">
					<?php if ($project): ?>
						<input type="hidden" name="id" value="<?php echo (int) $project['id']; ?>">
					<?php endif; ?>

					<div class="wizard-card">
						<ul class="wizard-steps">
							<li class="wizard-step-item active" data-step="1"><span class="wizard-step-num">1</span>Details</li>
							<li class="wizard-step-item" data-step="2"><span class="wizard-step-num">2</span>Story</li>
							<li class="wizard-step-item" data-step="3"><span class="wizard-step-num">3</span>Photos</li>
						</ul>

						<!-- Step 1: Details -->
						<div class="wizard-pane" data-pane="1">
							<h5 class="wizard-pane-title">Project Details</h5>
							<p class="wizard-pane-hint">The basics — what the project is and who it was for.</p>
							<div class="row">
								<div class="mb-3 col-lg-6 col-md-6">
									<label class="form-label">Project Title <span class="text-danger">*</span></label>
									<input type="text" name="title" class="form-control" placeholder="Riverside Family Residence" value="<?php echo htmlspecialchars($project['title'] ?? ''); ?>" required>
								</div>
								<div class="mb-3 col-lg-6 col-md-6">
									<label class="form-label">Construction Type</label>
									<input type="text" name="category" class="form-control" placeholder="Residential, Commercial, Renovation, Villa..." value="<?php echo htmlspecialchars($project['category'] ?? ''); ?>">
								</div>
								<div class="mb-3 col-lg-4 col-md-6">
									<label class="form-label">Location</label>
									<input type="text" name="location" class="form-control" placeholder="Austin, TX" value="<?php echo htmlspecialchars($project['location'] ?? ''); ?>">
								</div>
								<div class="mb-3 col-lg-4 col-md-6">
									<label class="form-label">Client Name</label>
									<input type="text" name="client_name" class="form-control" placeholder="The Johnson Family" value="<?php echo htmlspecialchars($project['client_name'] ?? ''); ?>">
								</div>
								<div class="mb-3 col-lg-4 col-md-6">
									<label class="form-label">Completion Date</label>
									<input type="date" name="completed_date" class="form-control" value="<?php echo htmlspecialchars($project['completed_date'] ?? ''); ?>">
								</div>
								<div class="mb-3 col-12">
									<div class="form-check">
										<input type="checkbox" name="featured" value="1" class="form-check-input" id="featured" <?php echo !empty($project['featured']) ? 'checked' : ''; ?>>
										<label class="form-check-label" for="featured">Feature this project on the homepage</label>
									</div>
								</div>
							</div>
						</div>

						<!-- Step 2: Story -->
						<div class="wizard-pane" data-pane="2" style="display:none;">
							<h5 class="wizard-pane-title">Project Story</h5>
							<p class="wizard-pane-hint">The brief, the challenges, the approach, and the outcome.</p>
							<textarea name="story" class="form-control" rows="12" placeholder="Describe the brief, challenges, approach, and outcome of this project..."><?php echo htmlspecialchars($project['story'] ?? ''); ?></textarea>
						</div>

						<!-- Step 3: Photos -->
						<div class="wizard-pane" data-pane="3" style="display:none;">
							<h5 class="wizard-pane-title">Project Photos</h5>
							<p class="wizard-pane-hint">The first photo (or the one marked cover) becomes the project thumbnail.</p>
							<div class="mb-3">
								<label class="form-label">Upload Photos</label>
								<input type="file" name="images[]" class="form-control" accept="image/*" multiple>
							</div>

							<?php if ($project_files): ?>
								<div class="mb-3">
									<label class="form-label d-block">Existing Photos</label>
									<div class="row g-2">
										<?php foreach ($project_files as $pf): ?>
											<div class="col-auto text-center">
												<img src="<?php echo htmlspecialchars($pf['file_path']); ?>" style="width:100px;height:80px;object-fit:cover;" class="rounded d-block mb-1">
												<small class="d-block text-truncate" style="max-width:100px;"><?php echo htmlspecialchars($pf['original_name']); ?></small>
												<a href="project-file-delete.php?id=<?php echo $pf['id']; ?>&project_id=<?php echo $project['id']; ?>" class="text-danger" onclick="return confirm('Remove this photo?');">Remove</a>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endif; ?>
						</div>

						<div class="wizard-nav">
							<div>
								<a href="project-list.php" class="btn btn-danger light">Cancel</a>
							</div>
							<div class="d-flex gap-2">
								<button type="button" id="wizard-prev" class="btn btn-outline-secondary" style="display:none;">Back</button>
								<button type="button" id="wizard-next" class="btn btn-primary">Next</button>
								<button type="submit" id="wizard-submit" class="btn btn-primary" style="display:none;"><?php echo $project ? 'Update Project' : 'Create Project'; ?></button>
							</div>
						</div>
					</div>
				</form>
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

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <?php include 'elements/page-js.php'; ?>

    <script>
    (function () {
        var totalSteps = 3;
        var current = 1;
        var steps = document.querySelectorAll('.wizard-step-item');
        var panes = document.querySelectorAll('.wizard-pane');
        var prevBtn = document.getElementById('wizard-prev');
        var nextBtn = document.getElementById('wizard-next');
        var submitBtn = document.getElementById('wizard-submit');

        function goToStep(n) {
            current = n;
            panes.forEach(function (p) {
                p.style.display = (parseInt(p.getAttribute('data-pane'), 10) === n) ? 'block' : 'none';
            });
            steps.forEach(function (s) {
                var stepNum = parseInt(s.getAttribute('data-step'), 10);
                s.classList.remove('active', 'done');
                if (stepNum === n) {
                    s.classList.add('active');
                } else if (stepNum < n) {
                    s.classList.add('done');
                }
            });
            prevBtn.style.display = n === 1 ? 'none' : 'inline-block';
            nextBtn.style.display = n === totalSteps ? 'none' : 'inline-block';
            submitBtn.style.display = n === totalSteps ? 'inline-block' : 'none';
            window.scrollTo({ top: document.querySelector('.wizard-card').offsetTop - 20, behavior: 'smooth' });
        }

        steps.forEach(function (s) {
            s.addEventListener('click', function () {
                goToStep(parseInt(s.getAttribute('data-step'), 10));
            });
        });

        nextBtn.addEventListener('click', function () {
            if (current < totalSteps) { goToStep(current + 1); }
        });
        prevBtn.addEventListener('click', function () {
            if (current > 1) { goToStep(current - 1); }
        });
    })();
    </script>

</body>

</html>
