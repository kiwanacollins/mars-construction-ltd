<?php
	 require_once __DIR__ . '/config/dz.php';

	 $stat = null;
	 if (!empty($_GET['id'])) {
		 $stmt = $pdo->prepare('SELECT * FROM construction_stats WHERE id = ?');
		 $stmt->execute([(int) $_GET['id']]);
		 $stat = $stmt->fetch();
	 }
	 $is_edit = (bool) $stat;
	 if (!$stat) {
		 $stat = ['id' => null, 'value' => 0, 'suffix' => '%', 'label' => ''];
	 }

	 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		 $value = (int) ($_POST['value'] ?? 0);
		 $suffix = trim($_POST['suffix'] ?? '%');
		 $label = trim($_POST['label'] ?? '');
		 $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;

		 if ($label === '') {
			 $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'Label is required.'];
			 header('Location: construction-stat-edit.php' . ($id ? "?id={$id}" : ''));
			 exit;
		 }

		 if ($id) {
			 $stmt = $pdo->prepare('UPDATE construction_stats SET value = ?, suffix = ?, label = ? WHERE id = ?');
			 $stmt->execute([$value, $suffix, $label, $id]);
			 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Stat updated.'];
		 } else {
			 $max = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), -1) m FROM construction_stats')->fetch()['m'];
			 $stmt = $pdo->prepare('INSERT INTO construction_stats (value, suffix, label, sort_order) VALUES (?, ?, ?, ?)');
			 $stmt->execute([$value, $suffix, $label, $max + 1]);
			 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Stat added.'];
		 }
		 header('Location: construction-stats.php');
		 exit;
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
						<li class="breadcrumb-item"><a href="construction-stats.php">Project Track Record</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $is_edit ? 'Edit Stat' : 'Add Stat'; ?></a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo $is_edit ? 'Edit Stat' : 'Add Stat'; ?></h4>
                            </div>
                            <div class="card-body">
								<form method="post">
									<?php if ($is_edit): ?><input type="hidden" name="id" value="<?php echo (int) $stat['id']; ?>"><?php endif; ?>
									<div class="row">
										<div class="mb-3 col-lg-6">
											<label class="form-label">Value <span class="text-danger">*</span></label>
											<input type="number" name="value" class="form-control" value="<?php echo (int) $stat['value']; ?>" required>
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Suffix</label>
											<input type="text" name="suffix" class="form-control" placeholder="%" value="<?php echo htmlspecialchars($stat['suffix']); ?>">
										</div>
									</div>
									<div class="mb-3">
										<label class="form-label">Label <span class="text-danger">*</span></label>
										<input type="text" name="label" class="form-control" placeholder="On-Time Delivery" value="<?php echo htmlspecialchars($stat['label']); ?>" required>
									</div>
									<a href="construction-stats.php" class="btn btn-outline-secondary">Cancel</a>
									<button type="submit" class="btn btn-primary"><?php echo $is_edit ? 'Save Changes' : 'Add Stat'; ?></button>
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
