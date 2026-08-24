<?php
	 require_once __DIR__ . '/config/dz.php';

	 if (!empty($_GET['delete'])) {
		 $pdo->prepare('DELETE FROM pm_stats WHERE id = ?')->execute([(int) $_GET['delete']]);
		 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Stat deleted.'];
		 header('Location: pm-stats.php');
		 exit;
	 }

	 $stats = $pdo->query('SELECT * FROM pm_stats ORDER BY sort_order, id')->fetchAll();

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
						<li class="breadcrumb-item"><a href="sections.php?page=property-management">Sections</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Why Owners Choose Us</a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="clean-list-header">
					<h2>Why Owners Choose Us</h2>
					<a href="pm-stat-edit.php" class="clean-list-btn-add"><i class="fa fa-plus"></i>Add Stat</a>
				</div>

				<div class="clean-list-card">
					<?php if (!$stats): ?>
						<div class="clean-list-empty">No stats yet.</div>
					<?php else: ?>
						<div class="table-responsive">
							<table class="clean-list-table">
								<thead>
									<tr>
										<th>Value</th>
										<th>Label</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($stats as $s): ?>
										<tr>
											<td><strong><?php echo (int) $s['value']; ?><?php echo htmlspecialchars($s['suffix']); ?></strong></td>
											<td><a href="pm-stat-edit.php?id=<?php echo $s['id']; ?>" class="clean-list-title"><?php echo htmlspecialchars($s['label']); ?></a></td>
											<td>
												<div class="clean-list-actions">
													<a href="pm-stat-edit.php?id=<?php echo $s['id']; ?>" class="edit-btn" title="Edit"><i class="fa fa-pen"></i></a>
													<a href="pm-stats.php?delete=<?php echo $s['id']; ?>" class="delete-btn" title="Delete" onclick="return confirm('Delete this stat?');"><i class="fa fa-trash"></i></a>
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
