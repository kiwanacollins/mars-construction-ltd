<?php
	 require_once __DIR__ . '/config/dz.php';

	 $stmt = $pdo->query('SELECT * FROM about_story_tabs WHERE id = 1');
	 $row = $stmt->fetch();
	 if (!$row) {
		 $row = ['badge_text' => '', 'mission_text' => '', 'mission_check1' => '', 'mission_check2' => '', 'vission_text' => '', 'vission_check1' => '', 'vission_check2' => '', 'goal_text' => '', 'goal_check1' => '', 'goal_check2' => ''];
	 }

	 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		 $data = [
			 'badge_text' => trim($_POST['badge_text'] ?? ''),
			 'mission_text' => trim($_POST['mission_text'] ?? ''),
			 'mission_check1' => trim($_POST['mission_check1'] ?? ''),
			 'mission_check2' => trim($_POST['mission_check2'] ?? ''),
			 'vission_text' => trim($_POST['vission_text'] ?? ''),
			 'vission_check1' => trim($_POST['vission_check1'] ?? ''),
			 'vission_check2' => trim($_POST['vission_check2'] ?? ''),
			 'goal_text' => trim($_POST['goal_text'] ?? ''),
			 'goal_check1' => trim($_POST['goal_check1'] ?? ''),
			 'goal_check2' => trim($_POST['goal_check2'] ?? ''),
		 ];
		 $stmt = $pdo->prepare(
			 'INSERT INTO about_story_tabs (id, badge_text, mission_text, mission_check1, mission_check2, vission_text, vission_check1, vission_check2, goal_text, goal_check1, goal_check2)
			  VALUES (1, :badge_text, :mission_text, :mission_check1, :mission_check2, :vission_text, :vission_check1, :vission_check2, :goal_text, :goal_check1, :goal_check2)
			  ON DUPLICATE KEY UPDATE badge_text = VALUES(badge_text), mission_text = VALUES(mission_text),
				 mission_check1 = VALUES(mission_check1), mission_check2 = VALUES(mission_check2),
				 vission_text = VALUES(vission_text), vission_check1 = VALUES(vission_check1), vission_check2 = VALUES(vission_check2),
				 goal_text = VALUES(goal_text), goal_check1 = VALUES(goal_check1), goal_check2 = VALUES(goal_check2)'
		 );
		 $stmt->execute($data);
		 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Story tabs updated.'];
		 header('Location: sections.php?page=about');
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
						<li class="breadcrumb-item"><a href="sections.php?page=about">Sections</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Story Tabs</a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="row">
                    <div class="col-lg-9">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Story Tabs (Mission / Vission / Goal)</h4>
                            </div>
                            <div class="card-body">
								<form method="post">
									<div class="mb-3">
										<label class="form-label">Badge Text <small class="text-muted">(shown over the second photo)</small></label>
										<input type="text" name="badge_text" class="form-control" placeholder="Client Centric Approach" value="<?php echo htmlspecialchars($row['badge_text'] ?? ''); ?>">
									</div>

									<hr class="my-4">
									<h5>Mission Tab</h5>
									<div class="mb-3">
										<label class="form-label">Text</label>
										<textarea name="mission_text" class="form-control" rows="3"><?php echo htmlspecialchars($row['mission_text'] ?? ''); ?></textarea>
									</div>
									<div class="row">
										<div class="mb-3 col-lg-6">
											<label class="form-label">Checklist Item 1</label>
											<input type="text" name="mission_check1" class="form-control" value="<?php echo htmlspecialchars($row['mission_check1'] ?? ''); ?>">
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Checklist Item 2</label>
											<input type="text" name="mission_check2" class="form-control" value="<?php echo htmlspecialchars($row['mission_check2'] ?? ''); ?>">
										</div>
									</div>

									<hr class="my-4">
									<h5>Vission Tab</h5>
									<div class="mb-3">
										<label class="form-label">Text</label>
										<textarea name="vission_text" class="form-control" rows="3"><?php echo htmlspecialchars($row['vission_text'] ?? ''); ?></textarea>
									</div>
									<div class="row">
										<div class="mb-3 col-lg-6">
											<label class="form-label">Checklist Item 1</label>
											<input type="text" name="vission_check1" class="form-control" value="<?php echo htmlspecialchars($row['vission_check1'] ?? ''); ?>">
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Checklist Item 2</label>
											<input type="text" name="vission_check2" class="form-control" value="<?php echo htmlspecialchars($row['vission_check2'] ?? ''); ?>">
										</div>
									</div>

									<hr class="my-4">
									<h5>Goal Tab</h5>
									<div class="mb-3">
										<label class="form-label">Text</label>
										<textarea name="goal_text" class="form-control" rows="3"><?php echo htmlspecialchars($row['goal_text'] ?? ''); ?></textarea>
									</div>
									<div class="row">
										<div class="mb-3 col-lg-6">
											<label class="form-label">Checklist Item 1</label>
											<input type="text" name="goal_check1" class="form-control" value="<?php echo htmlspecialchars($row['goal_check1'] ?? ''); ?>">
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Checklist Item 2</label>
											<input type="text" name="goal_check2" class="form-control" value="<?php echo htmlspecialchars($row['goal_check2'] ?? ''); ?>">
										</div>
									</div>

									<a href="sections.php?page=about" class="btn btn-outline-secondary">Cancel</a>
									<button type="submit" class="btn btn-primary">Save Changes</button>
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
