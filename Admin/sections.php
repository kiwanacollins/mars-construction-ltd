<?php
	 require_once __DIR__ . '/config/dz.php';

	 $registry = require __DIR__ . '/config/section_registry.php';

	 $selected_page = $_GET['page'] ?? array_key_first($registry);
	 if (!isset($registry[$selected_page])) {
		 $selected_page = array_key_first($registry);
	 }

	 $page_sections = [];
	 foreach ($registry[$selected_page]['sections'] as $section_key => $section_def) {
		 if (empty($section_def['fields'])) {
			 continue;
		 }
		 $stmt = $pdo->prepare('SELECT * FROM page_sections WHERE page_key = ? AND section_key = ?');
		 $stmt->execute([$selected_page, $section_key]);
		 $page_sections[$section_key] = $stmt->fetch();
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
						<li class="breadcrumb-item"><a href="javascript:void(0)">Sections</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo htmlspecialchars($registry[$selected_page]['label']); ?></a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="clean-list-header">
					<h2><?php echo htmlspecialchars($registry[$selected_page]['label']); ?> Sections</h2>
					<form method="get" action="sections.php">
						<select name="page" class="form-select" onchange="this.form.submit()" style="min-width:220px;">
							<?php foreach ($registry as $key => $def): ?>
								<option value="<?php echo htmlspecialchars($key); ?>" <?php echo $selected_page === $key ? 'selected' : ''; ?>><?php echo htmlspecialchars($def['label']); ?></option>
							<?php endforeach; ?>
						</select>
					</form>
				</div>

				<div class="clean-list-card">
					<div class="table-responsive">
						<table class="clean-list-table">
							<thead>
								<tr>
									<th>Section</th>
									<th>Current Content</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($registry[$selected_page]['sections'] as $section_key => $section_def): ?>
									<?php
										$edit_url = $section_def['edit_url'] ?? ('section-edit.php?page=' . urlencode($selected_page) . '&key=' . urlencode($section_key));
										$row = $page_sections[$section_key] ?? null;
										$is_custom = $row && !empty($row['heading']);
									?>
									<tr>
										<td><a href="<?php echo htmlspecialchars($edit_url); ?>" class="clean-list-title"><?php echo htmlspecialchars($section_def['label']); ?></a></td>
										<td>
											<?php if (isset($section_def['fields'])): ?>
												<?php echo $is_custom ? htmlspecialchars($row['heading']) : '<span class="text-muted">Using default page content</span>'; ?>
											<?php else: ?>
												<span class="text-muted">Managed separately &mdash; click to open</span>
											<?php endif; ?>
										</td>
										<td>
											<?php if (isset($section_def['fields'])): ?>
												<span class="clean-list-pill <?php echo $is_custom ? 'is-featured' : 'is-standard'; ?>"><?php echo $is_custom ? 'Customized' : 'Default'; ?></span>
											<?php else: ?>
												<span class="clean-list-pill is-standard">List</span>
											<?php endif; ?>
										</td>
										<td>
											<div class="clean-list-actions">
												<a href="<?php echo htmlspecialchars($edit_url); ?>" class="edit-btn" title="Edit"><i class="fa fa-pen"></i></a>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
            </div>
        </div>

        <?php include 'elements/footer.php'; ?>
    </div>

    <?php include 'elements/page-js.php'; ?>

</body>

</html>
