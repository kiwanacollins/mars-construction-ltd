<?php
	 require_once __DIR__ . '/config/dz.php';

	 $slides = $pdo->query('SELECT * FROM hero_slides ORDER BY sort_order, id')->fetchAll();

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
	 $all_page_bgs = $pdo->query('SELECT page_key, image FROM page_title_backgrounds')->fetchAll(PDO::FETCH_KEY_PAIR);

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
						<li class="breadcrumb-item"><a href="javascript:void(0)">CMS</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Hero / Slides</a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
								<ul class="nav nav-tabs card-header-tabs" id="heroTabs" role="tablist">
									<li class="nav-item" role="presentation">
										<button class="nav-link active" id="pagetitle-tab" data-bs-toggle="tab" data-bs-target="#pagetitle-pane" type="button" role="tab">Page Title Background</button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link" id="slides-tab" data-bs-toggle="tab" data-bs-target="#slides-pane" type="button" role="tab">Homepage Slides</button>
									</li>
								</ul>
                            </div>
                            <div class="card-body">
								<div class="tab-content" id="heroTabsContent">

									<!-- Page Title Background Tab -->
									<div class="tab-pane fade show active" id="pagetitle-pane" role="tabpanel">
										<div class="clean-list-header">
											<p class="text-muted mb-0">Each inner page's title banner can have its own background image.</p>
										</div>
										<div class="clean-list-card">
											<div class="table-responsive">
												<table class="clean-list-table">
													<thead>
														<tr>
															<th>Background</th>
															<th>Page</th>
															<th>Status</th>
															<th>Actions</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($page_options as $key => $label): ?>
															<?php $bg = $all_page_bgs[$key] ?? null; ?>
															<tr>
																<td>
																	<?php if ($bg): ?>
																		<img src="<?php echo htmlspecialchars($bg); ?>" class="clean-list-thumb">
																	<?php else: ?>
																		<div class="clean-list-thumb d-flex align-items-center justify-content-center text-muted"><i class="fa fa-image"></i></div>
																	<?php endif; ?>
																</td>
																<td><a href="page-title-bg-edit.php?page=<?php echo urlencode($key); ?>" class="clean-list-title"><?php echo htmlspecialchars($label); ?></a></td>
																<td><span class="clean-list-pill <?php echo $bg ? 'is-featured' : 'is-standard'; ?>"><?php echo $bg ? 'Custom Image' : 'Default'; ?></span></td>
																<td>
																	<div class="clean-list-actions">
																		<a href="page-title-bg-edit.php?page=<?php echo urlencode($key); ?>" class="edit-btn" title="Edit"><i class="fa fa-pen"></i></a>
																	</div>
																</td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>

									<!-- Homepage Slides Tab -->
									<div class="tab-pane fade" id="slides-pane" role="tabpanel">
										<div class="clean-list-header">
											<p class="text-muted mb-0">Up to 5 slides for the homepage hero. <?php echo count($slides); ?> of 5 used.</p>
											<?php if (count($slides) < 5): ?>
												<a href="hero-slide-edit.php" class="clean-list-btn-add"><i class="fa fa-plus"></i>Add New Slide</a>
											<?php else: ?>
												<button type="button" class="btn btn-secondary btn-sm" disabled title="Maximum of 5 slides reached">Maximum Reached</button>
											<?php endif; ?>
										</div>

										<div class="clean-list-card">
											<?php if (!$slides): ?>
												<div class="clean-list-empty">No slides yet. Add one to populate the homepage hero.</div>
											<?php else: ?>
												<div class="table-responsive">
													<table class="clean-list-table">
														<thead>
															<tr>
																<th>#</th>
																<th>Image</th>
																<th>Heading</th>
																<th>Tagline</th>
																<th>Actions</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($slides as $i => $slide): ?>
																<tr>
																	<td><?php echo $i + 1; ?></td>
																	<td>
																		<?php if ($slide['image']): ?>
																			<img src="<?php echo htmlspecialchars('../' . $slide['image']); ?>" class="clean-list-thumb">
																		<?php else: ?>
																			<div class="clean-list-thumb d-flex align-items-center justify-content-center text-muted"><i class="fa fa-image"></i></div>
																		<?php endif; ?>
																	</td>
																	<td><a href="hero-slide-edit.php?id=<?php echo $slide['id']; ?>" class="clean-list-title"><?php echo htmlspecialchars($slide['heading'] ?: '(No heading)'); ?></a></td>
																	<td><?php echo htmlspecialchars($slide['subheading'] ?: '—'); ?></td>
																	<td>
																		<div class="clean-list-actions">
																			<a href="hero-slide-edit.php?id=<?php echo $slide['id']; ?>" class="edit-btn" title="Edit"><i class="fa fa-pen"></i></a>
																			<a href="hero-slide-delete.php?id=<?php echo $slide['id']; ?>" class="delete-btn" title="Delete" onclick="return confirm('Delete this slide?');"><i class="fa fa-trash"></i></a>
																		</div>
																	</td>
																</tr>
															<?php endforeach; ?>
														</tbody>
													</table>
												</div>
											<?php endif; ?>
										</div>
									</div>

								</div>
                            </div>
                        </div>
					</div>
				</div>
            </div>
        </div>

        <?php include 'elements/footer.php'; ?>
    </div>

    <?php include 'elements/page-js.php'; ?>

    <script>
    var params = new URLSearchParams(window.location.search);
    if (params.get('tab') === 'slides') {
        var trigger = document.getElementById('slides-tab');
        if (trigger) {
            new bootstrap.Tab(trigger).show();
        }
    }
    </script>

</body>

</html>
