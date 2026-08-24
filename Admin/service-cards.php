<?php
	 require_once __DIR__ . '/config/dz.php';

	 if (!empty($_GET['delete'])) {
		 $pdo->prepare('DELETE FROM home_service_cards WHERE id = ?')->execute([(int) $_GET['delete']]);
		 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Service card deleted.'];
		 header('Location: service-cards.php');
		 exit;
	 }

	 $cards = $pdo->query('SELECT * FROM home_service_cards ORDER BY sort_order, id')->fetchAll();

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
						<li class="breadcrumb-item"><a href="sections.php?page=home">Sections</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Services</a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="clean-list-header">
					<h2>Homepage Services</h2>
					<a href="service-card-edit.php" class="clean-list-btn-add"><i class="fa fa-plus"></i>Add Service</a>
				</div>

				<div class="clean-list-card">
					<?php if (!$cards): ?>
						<div class="clean-list-empty">No service cards yet.</div>
					<?php else: ?>
						<div class="table-responsive">
							<table class="clean-list-table">
								<thead>
									<tr>
										<th>Photo</th>
										<th>Icon</th>
										<th>Title</th>
										<th>Description</th>
										<th>Links To</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($cards as $c): ?>
										<tr>
											<td>
												<?php if (!empty($c['image'])): ?>
													<img src="<?php echo htmlspecialchars($c['image']); ?>" class="clean-list-thumb" alt="">
												<?php else: ?>
													<div class="clean-list-thumb d-flex align-items-center justify-content-center"><i class="fa fa-image text-muted"></i></div>
												<?php endif; ?>
											</td>
											<td><div class="d-flex align-items-center justify-content-center" style="font-size:20px; color:#1C9DB2;"><i class="<?php echo htmlspecialchars($c['icon_class'] ?: 'fa fa-image'); ?>"></i></div></td>
											<td><a href="service-card-edit.php?id=<?php echo $c['id']; ?>" class="clean-list-title"><?php echo htmlspecialchars($c['title']); ?></a></td>
											<td><?php echo htmlspecialchars(mb_strimwidth($c['description'] ?? '', 0, 60, '...')); ?></td>
											<td><?php echo htmlspecialchars($c['link_url'] ?: '—'); ?></td>
											<td>
												<div class="clean-list-actions">
													<a href="service-card-edit.php?id=<?php echo $c['id']; ?>" class="edit-btn" title="Edit"><i class="fa fa-pen"></i></a>
													<a href="service-cards.php?delete=<?php echo $c['id']; ?>" class="delete-btn" title="Delete" onclick="return confirm('Delete this service card?');"><i class="fa fa-trash"></i></a>
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

        <?php include 'elements/footer.php'; ?>
    </div>

    <?php include 'elements/page-js.php'; ?>

</body>

</html>
