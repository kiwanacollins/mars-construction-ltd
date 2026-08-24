<?php
	 require_once __DIR__ . '/config/dz.php';

	 $registry = require __DIR__ . '/config/section_registry.php';

	 $page_key = $_GET['page'] ?? '';
	 $section_key = $_GET['key'] ?? '';

	 if (!isset($registry[$page_key]['sections'][$section_key])) {
		 header('Location: sections.php');
		 exit;
	 }

	 $section_def = $registry[$page_key]['sections'][$section_key];

	 $stmt = $pdo->prepare('SELECT * FROM page_sections WHERE page_key = ? AND section_key = ?');
	 $stmt->execute([$page_key, $section_key]);
	 $row = $stmt->fetch();
	 if (!$row) {
		 $row = ['heading' => '', 'subheading' => '', 'body' => '', 'image' => '', 'image2' => '', 'check1' => '', 'check2' => '', 'list1' => '', 'list2' => '', 'button_text' => '', 'button_link' => ''];
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
						<li class="breadcrumb-item"><a href="sections.php?page=<?php echo urlencode($page_key); ?>">Sections</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo htmlspecialchars($section_def['label']); ?></a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo htmlspecialchars($registry[$page_key]['label']); ?> &mdash; <?php echo htmlspecialchars($section_def['label']); ?></h4>
                            </div>
                            <div class="card-body">
								<form method="post" action="section-save.php" enctype="multipart/form-data">
									<input type="hidden" name="page_key" value="<?php echo htmlspecialchars($page_key); ?>">
									<input type="hidden" name="section_key" value="<?php echo htmlspecialchars($section_key); ?>">

									<?php if (in_array('heading', $section_def['fields'], true)): ?>
										<div class="mb-3">
											<label class="form-label">Heading</label>
											<input type="text" name="heading" class="form-control" value="<?php echo htmlspecialchars($row['heading']); ?>">
										</div>
									<?php endif; ?>

									<?php if (in_array('subheading', $section_def['fields'], true)): ?>
										<div class="mb-3">
											<label class="form-label">Tagline <small class="text-muted">(small text above the heading)</small></label>
											<input type="text" name="subheading" class="form-control" value="<?php echo htmlspecialchars($row['subheading']); ?>">
										</div>
									<?php endif; ?>

									<?php if (in_array('body', $section_def['fields'], true)): ?>
										<div class="mb-3">
											<label class="form-label">Body Text</label>
											<textarea name="body" class="form-control" rows="5"><?php echo htmlspecialchars($row['body']); ?></textarea>
										</div>
									<?php endif; ?>

									<?php if (in_array('check1', $section_def['fields'], true) || in_array('check2', $section_def['fields'], true)): ?>
										<div class="row">
											<?php if (in_array('check1', $section_def['fields'], true)): ?>
												<div class="mb-3 col-lg-6">
													<label class="form-label">Checkmark Line 1</label>
													<input type="text" name="check1" class="form-control" value="<?php echo htmlspecialchars($row['check1'] ?? ''); ?>">
												</div>
											<?php endif; ?>
											<?php if (in_array('check2', $section_def['fields'], true)): ?>
												<div class="mb-3 col-lg-6">
													<label class="form-label">Checkmark Line 2</label>
													<input type="text" name="check2" class="form-control" value="<?php echo htmlspecialchars($row['check2'] ?? ''); ?>">
												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>

									<?php if (in_array('list1', $section_def['fields'], true) || in_array('list2', $section_def['fields'], true)): ?>
										<div class="row">
											<?php if (in_array('list1', $section_def['fields'], true)): ?>
												<div class="mb-3 col-lg-6">
													<label class="form-label">Checklist Item 1</label>
													<input type="text" name="list1" class="form-control" value="<?php echo htmlspecialchars($row['list1'] ?? ''); ?>">
												</div>
											<?php endif; ?>
											<?php if (in_array('list2', $section_def['fields'], true)): ?>
												<div class="mb-3 col-lg-6">
													<label class="form-label">Checklist Item 2</label>
													<input type="text" name="list2" class="form-control" value="<?php echo htmlspecialchars($row['list2'] ?? ''); ?>">
												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>

									<?php if (in_array('button_text', $section_def['fields'], true) || in_array('button_link', $section_def['fields'], true)): ?>
										<div class="row">
											<?php if (in_array('button_text', $section_def['fields'], true)): ?>
												<div class="mb-3 col-lg-6">
													<label class="form-label">Button Text</label>
													<input type="text" name="button_text" class="form-control" value="<?php echo htmlspecialchars($row['button_text'] ?? ''); ?>">
												</div>
											<?php endif; ?>
											<?php if (in_array('button_link', $section_def['fields'], true)): ?>
												<div class="mb-3 col-lg-6">
													<label class="form-label">Button Link</label>
													<input type="text" name="button_link" class="form-control" value="<?php echo htmlspecialchars($row['button_link'] ?? ''); ?>">
												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>

									<?php if (in_array('image', $section_def['fields'], true) || in_array('image2', $section_def['fields'], true)): ?>
										<div class="row">
											<?php if (in_array('image', $section_def['fields'], true)): ?>
												<div class="mb-3 col-lg-6">
													<label class="form-label">Photo 1</label>
													<input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($row['image'] ?? ''); ?>">
													<?php if (!empty($row['image'])): ?><div class="mb-2"><img src="<?php echo htmlspecialchars($row['image']); ?>" style="max-height:100px; border-radius:8px;"></div><?php endif; ?>
													<input type="file" name="image" class="form-control" accept="image/*">
												</div>
											<?php endif; ?>
											<?php if (in_array('image2', $section_def['fields'], true)): ?>
												<div class="mb-3 col-lg-6">
													<label class="form-label">Photo 2</label>
													<input type="hidden" name="existing_image2" value="<?php echo htmlspecialchars($row['image2'] ?? ''); ?>">
													<?php if (!empty($row['image2'])): ?><div class="mb-2"><img src="<?php echo htmlspecialchars($row['image2']); ?>" style="max-height:100px; border-radius:8px;"></div><?php endif; ?>
													<input type="file" name="image2" class="form-control" accept="image/*">
												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>

									<small class="text-muted d-block mb-3">Leave text fields blank to fall back to the page's default content. Leave photo uploads blank to keep the current photo.</small>

									<a href="sections.php?page=<?php echo urlencode($page_key); ?>" class="btn btn-outline-secondary">Cancel</a>
									<button type="submit" class="btn btn-primary">Save Section</button>
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
