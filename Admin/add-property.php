<?php
	 require_once __DIR__ . '/config/dz.php';

	 $property = null;
	 $property_files = [];
	 $pricing_tiers = [];
	 $addons = [];
	 $plan_categories = $pdo->query('SELECT * FROM plan_categories ORDER BY sort_order, name')->fetchAll();
	 if (!empty($_GET['id'])) {
		 $stmt = $pdo->prepare('SELECT * FROM properties WHERE id = ?');
		 $stmt->execute([(int) $_GET['id']]);
		 $property = $stmt->fetch();
		 if ($property) {
			 $fstmt = $pdo->prepare('SELECT * FROM property_files WHERE property_id = ? ORDER BY sort_order, id');
			 $fstmt->execute([$property['id']]);
			 $property_files = $fstmt->fetchAll();

			 $pstmt = $pdo->prepare('SELECT * FROM plan_pricing WHERE property_id = ? ORDER BY sort_order, id');
			 $pstmt->execute([$property['id']]);
			 $pricing_tiers = $pstmt->fetchAll();

			 $astmt = $pdo->prepare('SELECT * FROM plan_addons WHERE property_id = ? ORDER BY sort_order, id');
			 $astmt->execute([$property['id']]);
			 $addons = $astmt->fetchAll();
		 }
	 }
	 $cover_file = null;
	 foreach ($property_files as $pf) {
		 if ($pf['file_type'] === 'image' && $pf['is_cover']) {
			 $cover_file = $pf;
			 break;
		 }
	 }

	 $features = $property && $property['features'] ? json_decode($property['features'], true) : [];
	 $feature_options = ['Open Floor Plan', 'Walk-in Closet', 'Kitchen Island', 'Covered Porch', 'Home Office', 'Mudroom', 'Walk-in Pantry', 'Vaulted Ceilings', 'Outdoor Living Space', 'Energy Efficient', 'Bonus Room', 'Split Bedroom Layout'];

	 // Ensure there are always 4 pricing tier rows to fill in, pre-labelled with common options
	 $default_tier_names = ['PDF File Set', 'CAD File', '5 Print Set', 'Reproducible Master'];
	 for ($i = 0; $i < 4; $i++) {
		 if (!isset($pricing_tiers[$i])) {
			 $pricing_tiers[$i] = ['tier_name' => $default_tier_names[$i], 'price' => '', 'description' => '', 'file_path' => ''];
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
						<li class="breadcrumb-item"><a href="property-list.php">Plans</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $property ? 'Edit Plan' : 'Create Plan'; ?></a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<form action="property-save.php" method="post" enctype="multipart/form-data" id="plan-wizard-form">
					<?php if ($property): ?>
						<input type="hidden" name="id" value="<?php echo (int) $property['id']; ?>">
					<?php endif; ?>

					<div class="wizard-card">
						<ul class="wizard-steps">
							<li class="wizard-step-item active" data-step="1"><span class="wizard-step-num">1</span>Basics</li>
							<li class="wizard-step-item" data-step="2"><span class="wizard-step-num">2</span>Specs &amp; Construction</li>
							<li class="wizard-step-item" data-step="3"><span class="wizard-step-num">3</span>Pricing &amp; Add-ons</li>
							<li class="wizard-step-item" data-step="4"><span class="wizard-step-num">4</span>Features &amp; Media</li>
						</ul>

						<!-- Step 1: Basics -->
						<div class="wizard-pane" data-pane="1">
							<h5 class="wizard-pane-title">Basic Information</h5>
							<p class="wizard-pane-hint">The essentials — what the plan is called and what it looks like.</p>
							<div class="row">
								<div class="mb-3 col-lg-6">
									<label class="form-label">Plan Title <span class="text-danger">*</span></label>
									<input type="text" name="title" class="form-control" placeholder="The Sunrise Craftsman" value="<?php echo htmlspecialchars($property['title'] ?? ''); ?>" required>
								</div>
								<div class="mb-3 col-lg-3 col-md-6">
									<label class="form-label">Plan Number / SKU</label>
									<input type="text" name="plan_number" class="form-control" placeholder="MC-1042" value="<?php echo htmlspecialchars($property['plan_number'] ?? ''); ?>">
								</div>
								<div class="mb-3 col-lg-3 col-md-6">
									<label class="form-label">Category</label>
									<select name="category" class="default-select form-control wide">
										<option value="">-- Select --</option>
										<?php foreach ($plan_categories as $cat): ?>
											<option value="<?php echo htmlspecialchars($cat['name']); ?>" <?php echo ($property['category'] ?? '') === $cat['name'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="mb-3 col-12">
									<label class="form-label">Description</label>
									<textarea name="description" class="form-control" rows="4" placeholder="Describe the plan's layout, flow, and highlights..."><?php echo htmlspecialchars($property['description'] ?? ''); ?></textarea>
								</div>
								<div class="mb-3 col-lg-5">
									<label class="form-label">Featured Image <small class="text-muted">(main listing/cover photo)</small></label>
									<?php if ($cover_file): ?>
										<div class="mb-2"><img src="<?php echo htmlspecialchars('../' . $cover_file['file_path']); ?>" style="max-height:100px; border-radius:8px;"></div>
									<?php endif; ?>
									<input type="file" name="featured_image" class="form-control js-file-preview" data-preview-mode="thumb" accept="image/*">
									<div class="js-file-preview-output mt-2 d-flex flex-wrap gap-2"></div>
									<small class="text-muted d-block mt-1">Leave blank to keep the current featured image.</small>
								</div>
								<div class="mb-3 col-12">
									<div class="form-check">
										<input type="checkbox" name="featured" value="1" class="form-check-input" id="featured" <?php echo !empty($property['featured']) ? 'checked' : ''; ?>>
										<label class="form-check-label" for="featured">Feature this plan on the homepage and listings</label>
									</div>
								</div>
							</div>
						</div>

						<!-- Step 2: Specs & Construction -->
						<div class="wizard-pane" data-pane="2" style="display:none;">
							<h5 class="wizard-pane-title">Specifications</h5>
							<p class="wizard-pane-hint">Room counts and overall size.</p>
							<div class="row">
								<div class="mb-3 col-lg-2 col-md-4 col-6">
									<label class="form-label">Bedrooms</label>
									<input type="number" min="0" name="bedrooms" class="form-control" value="<?php echo htmlspecialchars($property['bedrooms'] ?? 0); ?>">
								</div>
								<div class="mb-3 col-lg-2 col-md-4 col-6">
									<label class="form-label">Bathrooms</label>
									<input type="number" min="0" step="0.5" name="bathrooms" class="form-control" placeholder="2.5" value="<?php echo htmlspecialchars($property['bathrooms'] ?? 0); ?>">
								</div>
								<div class="mb-3 col-lg-2 col-md-4 col-6">
									<label class="form-label">Stories</label>
									<input type="number" min="1" name="stories" class="form-control" value="<?php echo htmlspecialchars($property['stories'] ?? 1); ?>">
								</div>
								<div class="mb-3 col-lg-2 col-md-4 col-6">
									<label class="form-label">Garage Bays</label>
									<input type="number" min="0" name="garage_bays" class="form-control" value="<?php echo htmlspecialchars($property['garage_bays'] ?? 0); ?>">
								</div>
								<div class="mb-3 col-lg-2 col-md-4 col-6">
									<label class="form-label">Width (ft)</label>
									<input type="number" step="0.1" name="width_ft" class="form-control" value="<?php echo htmlspecialchars($property['width_ft'] ?? ''); ?>">
								</div>
								<div class="mb-3 col-lg-2 col-md-4 col-6">
									<label class="form-label">Depth (ft)</label>
									<input type="number" step="0.1" name="depth_ft" class="form-control" value="<?php echo htmlspecialchars($property['depth_ft'] ?? ''); ?>">
								</div>
								<div class="mb-3 col-lg-3 col-md-6">
									<label class="form-label">Total Square Footage</label>
									<input type="number" step="0.01" name="area_sqft" class="form-control" placeholder="2450" value="<?php echo htmlspecialchars($property['area_sqft'] ?? ''); ?>">
								</div>
							</div>

							<hr class="my-4">

							<h5 class="wizard-pane-title">Construction Details</h5>
							<p class="wizard-pane-hint">How the plan is built. All optional.</p>
							<div class="row">
								<div class="mb-3 col-lg-3 col-md-6">
									<label class="form-label">Foundation Type</label>
									<select name="foundation_type" class="default-select form-control wide">
										<?php $foundations = ['', 'Slab', 'Crawlspace', 'Basement', 'Walkout Basement', 'Pier'];
										foreach ($foundations as $f): ?>
											<option value="<?php echo htmlspecialchars($f); ?>" <?php echo ($property['foundation_type'] ?? '') === $f ? 'selected' : ''; ?>><?php echo $f === '' ? '-- Select --' : htmlspecialchars($f); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="mb-3 col-lg-3 col-md-6">
									<label class="form-label">Roof Type</label>
									<select name="roof_type" class="default-select form-control wide">
										<?php $roofs = ['', 'Gable', 'Hip', 'Gambrel', 'Mansard', 'Flat', 'Shed'];
										foreach ($roofs as $r): ?>
											<option value="<?php echo htmlspecialchars($r); ?>" <?php echo ($property['roof_type'] ?? '') === $r ? 'selected' : ''; ?>><?php echo $r === '' ? '-- Select --' : htmlspecialchars($r); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="mb-3 col-lg-3 col-md-6">
									<label class="form-label">Roof Pitch</label>
									<input type="text" name="roof_pitch" class="form-control" placeholder="6:12" value="<?php echo htmlspecialchars($property['roof_pitch'] ?? ''); ?>">
								</div>
								<div class="mb-3 col-lg-3 col-md-6">
									<label class="form-label">Exterior Material</label>
									<input type="text" name="exterior_material" class="form-control" placeholder="Brick, Vinyl Siding, Stone..." value="<?php echo htmlspecialchars($property['exterior_material'] ?? ''); ?>">
								</div>
							</div>
						</div>

						<!-- Step 3: Pricing & Add-ons -->
						<div class="wizard-pane" data-pane="3" style="display:none;">
							<h5 class="wizard-pane-title">Pricing</h5>
							<p class="wizard-pane-hint">Set a starting price, then optionally break it into purchase options.</p>
							<div class="mb-3 col-lg-4">
								<label class="form-label">Starting Price <span class="text-danger">*</span> <small class="text-muted">(shown on listing cards)</small></label>
								<input type="number" step="0.01" name="price" class="form-control" placeholder="899" value="<?php echo htmlspecialchars($property['price'] ?? ''); ?>" required>
							</div>

							<label class="form-label d-block mt-4">Purchase Options <small class="text-muted">(leave a row's price blank to skip it)</small></label>
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th style="width:22%">Option Name</th>
											<th style="width:12%">Price ($)</th>
											<th style="width:24%">Description</th>
											<th>Deliverable File</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($pricing_tiers as $i => $tier): ?>
											<tr>
												<td><input type="text" name="tier_name[]" class="form-control" value="<?php echo htmlspecialchars($tier['tier_name']); ?>"></td>
												<td><input type="number" step="0.01" name="tier_price[]" class="form-control" value="<?php echo htmlspecialchars($tier['price']); ?>"></td>
												<td><input type="text" name="tier_description[]" class="form-control" placeholder="Optional note" value="<?php echo htmlspecialchars($tier['description'] ?? ''); ?>"></td>
												<td>
													<input type="hidden" name="existing_tier_file[]" value="<?php echo htmlspecialchars($tier['file_path'] ?? ''); ?>">
													<input type="file" name="tier_file[]" class="form-control form-control-sm js-file-preview" data-preview-mode="name">
													<div class="js-file-preview-output mt-1"></div>
													<?php if (!empty($tier['file_path'])): ?>
														<small class="text-muted d-block mt-1"><i class="fa fa-paperclip me-1"></i><?php echo htmlspecialchars(basename($tier['file_path'])); ?></small>
													<?php endif; ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>

							<hr class="my-4">

							<div class="d-flex justify-content-between align-items-center mb-2">
								<div>
									<h5 class="wizard-pane-title mb-0">Customizable Add-ons</h5>
									<p class="wizard-pane-hint mb-0">Optional checkboxes shown on the plan page, on top of the starting price.</p>
								</div>
								<button type="button" id="add-addon-row" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus me-1"></i>Add Row</button>
							</div>
							<div class="table-responsive mt-3">
								<table class="table" id="addon-rows-table">
									<thead>
										<tr>
											<th style="width:25%">Name</th>
											<th>Description</th>
											<th style="width:15%">Price</th>
											<th style="width:18%">Type</th>
											<th style="width:5%"></th>
										</tr>
									</thead>
									<tbody id="addon-rows-body">
										<?php foreach ($addons as $addon): ?>
											<tr>
												<td><input type="text" name="addon_name[]" class="form-control" value="<?php echo htmlspecialchars($addon['name']); ?>"></td>
												<td><input type="text" name="addon_description[]" class="form-control" placeholder="Optional note" value="<?php echo htmlspecialchars($addon['description'] ?? ''); ?>"></td>
												<td><input type="number" step="0.01" name="addon_price[]" class="form-control" value="<?php echo htmlspecialchars($addon['price']); ?>"></td>
												<td>
													<select name="addon_type[]" class="form-select">
														<option value="flat" <?php echo $addon['price_type'] === 'flat' ? 'selected' : ''; ?>>Flat $</option>
														<option value="percent" <?php echo $addon['price_type'] === 'percent' ? 'selected' : ''; ?>>% of selected flat add-ons</option>
													</select>
												</td>
												<td><button type="button" class="btn btn-danger btn-sm remove-addon-row"><i class="fa fa-trash"></i></button></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>

						<!-- Step 4: Features & Media -->
						<div class="wizard-pane" data-pane="4" style="display:none;">
							<h5 class="wizard-pane-title">Features &amp; Amenities</h5>
							<p class="wizard-pane-hint">Check everything that applies.</p>
							<?php foreach ($feature_options as $i => $opt): ?>
								<div class="form-check form-check-inline">
									<input type="checkbox" name="features[]" class="form-check-input" value="<?php echo htmlspecialchars($opt); ?>" id="feat<?php echo $i; ?>" <?php echo in_array($opt, (array) $features, true) ? 'checked' : ''; ?>>
									<label class="form-check-label" for="feat<?php echo $i; ?>"> <?php echo htmlspecialchars($opt); ?></label>
								</div>
							<?php endforeach; ?>

							<hr class="my-4">

							<h5 class="wizard-pane-title">Media &amp; Downloadable Files</h5>
							<p class="wizard-pane-hint">Renderings for the listing, plus the actual files buyers will download.</p>
							<div class="mb-3">
								<label class="form-label">Renderings / Floor Plan Images</label>
								<input type="file" name="images[]" class="form-control js-file-preview" data-preview-mode="thumb" accept="image/*" multiple>
								<div class="js-file-preview-output mt-2 d-flex flex-wrap gap-2"></div>
								<small class="text-muted">First image (or the one marked cover) is used as the listing photo.</small>
							</div>
							<div class="mb-3">
								<label class="form-label">Downloadable Files (PDF plan set, CAD files, etc.)</label>
								<input type="file" name="documents[]" class="form-control js-file-preview" data-preview-mode="name" multiple>
								<div class="js-file-preview-output mt-2"></div>
							</div>
							<div class="mb-3">
								<label class="form-label">Walkthrough Video URL</label>
								<input type="text" name="video_url" class="form-control" placeholder="https://youtube.com/..." value="<?php echo htmlspecialchars($property['video_url'] ?? ''); ?>">
							</div>

							<?php if ($property_files): ?>
								<div class="mb-3">
									<label class="form-label d-block">Existing Files</label>
									<div class="row g-2">
										<?php foreach ($property_files as $pf): ?>
											<div class="col-auto text-center">
												<?php if ($pf['file_type'] === 'image'): ?>
													<img src="<?php echo htmlspecialchars($pf['file_path']); ?>" style="width:90px;height:70px;object-fit:cover;" class="rounded d-block mb-1">
												<?php else: ?>
													<div class="border rounded d-flex align-items-center justify-content-center mb-1" style="width:90px;height:70px;"><i class="fas fa-file"></i></div>
												<?php endif; ?>
												<small class="d-block text-truncate" style="max-width:90px;"><?php echo htmlspecialchars($pf['original_name']); ?></small>
												<a href="property-file-delete.php?id=<?php echo $pf['id']; ?>&property_id=<?php echo $property['id']; ?>" class="text-danger" onclick="return confirm('Remove this file?');">Remove</a>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endif; ?>
						</div>

						<div class="wizard-nav">
							<div>
								<a href="property-list.php" class="btn btn-danger light">Cancel</a>
							</div>
							<div class="d-flex gap-2">
								<button type="button" id="wizard-prev" class="btn btn-outline-secondary" style="display:none;">Back</button>
								<button type="button" id="wizard-next" class="btn btn-primary">Next</button>
								<button type="submit" id="wizard-submit" class="btn btn-primary" style="display:none;"><?php echo $property ? 'Update Plan' : 'Create Plan'; ?></button>
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
    document.getElementById('add-addon-row').addEventListener('click', function () {
        var tbody = document.getElementById('addon-rows-body');
        var tr = document.createElement('tr');
        tr.innerHTML =
            '<td><input type="text" name="addon_name[]" class="form-control"></td>' +
            '<td><input type="text" name="addon_description[]" class="form-control" placeholder="Optional note"></td>' +
            '<td><input type="number" step="0.01" name="addon_price[]" class="form-control"></td>' +
            '<td><select name="addon_type[]" class="form-select"><option value="flat">Flat $</option><option value="percent">% of selected flat add-ons</option></select></td>' +
            '<td><button type="button" class="btn btn-danger btn-sm remove-addon-row"><i class="fa fa-trash"></i></button></td>';
        tbody.appendChild(tr);
    });

    document.getElementById('addon-rows-body').addEventListener('click', function (e) {
        var btn = e.target.closest('.remove-addon-row');
        if (btn) {
            btn.closest('tr').remove();
        }
    });

    (function () {
        function humanFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            var units = ['KB', 'MB', 'GB'];
            var i = -1;
            do { bytes /= 1024; i++; } while (bytes >= 1024 && i < units.length - 1);
            return bytes.toFixed(1) + ' ' + units[i];
        }

        document.querySelectorAll('.js-file-preview').forEach(function (input) {
            var output = input.parentElement.querySelector('.js-file-preview-output');
            if (!output) return;
            var mode = input.getAttribute('data-preview-mode') || 'name';

            input.addEventListener('change', function () {
                output.innerHTML = '';
                var files = Array.prototype.slice.call(input.files || []);
                if (!files.length) return;

                files.forEach(function (file) {
                    if (mode === 'thumb' && file.type.indexOf('image/') === 0) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var wrap = document.createElement('div');
                            wrap.className = 'text-center';
                            wrap.innerHTML =
                                '<img src="' + e.target.result + '" style="width:90px;height:70px;object-fit:cover;" class="rounded d-block mb-1">' +
                                '<small class="d-block text-truncate" style="max-width:90px;">' + file.name + '</small>';
                            output.appendChild(wrap);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        var chip = document.createElement('small');
                        chip.className = 'text-muted d-block mt-1';
                        chip.innerHTML = '<i class="fa fa-paperclip me-1"></i>' + file.name + ' <span class="text-muted">(' + humanFileSize(file.size) + ')</span>';
                        output.appendChild(chip);
                    }
                });
            });
        });
    })();

    (function () {
        var totalSteps = 4;
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
