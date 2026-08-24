<?php
	 require_once __DIR__ . '/config/dz.php';

	 if (!empty($_GET['delete'])) {
		 $pdo->prepare('DELETE FROM team_members WHERE id = ?')->execute([(int) $_GET['delete']]);
		 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Team member deleted.'];
		 header('Location: team.php');
		 exit;
	 }

	 $members = $pdo->query('SELECT * FROM team_members ORDER BY sort_order, id')->fetchAll();

	 function team_image_src($path) {
		 if (empty($path)) {
			 return null;
		 }
		 return strpos($path, 'uploads/') === 0 ? 'Admin/' . $path : '../' . $path;
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
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Team</a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="clean-list-header">
					<h2>Meet Our Team</h2>
					<a href="team-edit.php" class="clean-list-btn-add"><i class="fa fa-plus"></i>Add Team Member</a>
				</div>

				<div class="clean-list-card">
					<?php if (!$members): ?>
						<div class="clean-list-empty">No team members yet.</div>
					<?php else: ?>
						<div class="table-responsive">
							<table class="clean-list-table">
								<thead>
									<tr>
										<th>Photo</th>
										<th>Name</th>
										<th>Designation</th>
										<th>Socials</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($members as $m): $img = team_image_src($m['image']); ?>
										<tr>
											<td>
												<?php if ($img): ?>
													<img src="<?php echo htmlspecialchars($img); ?>" class="clean-list-thumb" alt="">
												<?php else: ?>
													<div class="clean-list-thumb d-flex align-items-center justify-content-center"><i class="fa fa-user text-muted"></i></div>
												<?php endif; ?>
											</td>
											<td><a href="team-edit.php?id=<?php echo $m['id']; ?>" class="clean-list-title"><?php echo htmlspecialchars($m['name']); ?></a></td>
											<td><?php echo htmlspecialchars($m['designation'] ?: '—'); ?></td>
											<td>
												<?php
													$social_count = count(array_filter([$m['facebook_url'], $m['instagram_url'], $m['twitter_url'], $m['youtube_url']]));
												?>
												<?php echo $social_count ? $social_count . ' linked' : '<span class="text-muted">—</span>'; ?>
											</td>
											<td>
												<div class="clean-list-actions">
													<a href="team-edit.php?id=<?php echo $m['id']; ?>" class="edit-btn" title="Edit"><i class="fa fa-pen"></i></a>
													<a href="team.php?delete=<?php echo $m['id']; ?>" class="delete-btn" title="Delete" onclick="return confirm('Delete this team member?');"><i class="fa fa-trash"></i></a>
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
