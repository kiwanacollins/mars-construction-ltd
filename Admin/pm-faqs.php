<?php
	 require_once __DIR__ . '/config/dz.php';

	 if (!empty($_GET['delete'])) {
		 $pdo->prepare('DELETE FROM pm_faqs WHERE id = ?')->execute([(int) $_GET['delete']]);
		 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'FAQ deleted.'];
		 header('Location: pm-faqs.php');
		 exit;
	 }

	 $faqs = $pdo->query('SELECT * FROM pm_faqs ORDER BY sort_order, id')->fetchAll();

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
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Common Questions (FAQ)</a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="clean-list-header">
					<h2>Common Questions</h2>
					<a href="pm-faq-edit.php" class="clean-list-btn-add"><i class="fa fa-plus"></i>Add Question</a>
				</div>

				<div class="clean-list-card">
					<?php if (!$faqs): ?>
						<div class="clean-list-empty">No questions yet.</div>
					<?php else: ?>
						<div class="table-responsive">
							<table class="clean-list-table">
								<thead>
									<tr>
										<th>Question</th>
										<th>Answer</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($faqs as $f): ?>
										<tr>
											<td><a href="pm-faq-edit.php?id=<?php echo $f['id']; ?>" class="clean-list-title"><?php echo htmlspecialchars($f['question']); ?></a></td>
											<td><?php echo htmlspecialchars(mb_strimwidth($f['answer'] ?? '', 0, 60, '...')); ?></td>
											<td>
												<div class="clean-list-actions">
													<a href="pm-faq-edit.php?id=<?php echo $f['id']; ?>" class="edit-btn" title="Edit"><i class="fa fa-pen"></i></a>
													<a href="pm-faqs.php?delete=<?php echo $f['id']; ?>" class="delete-btn" title="Delete" onclick="return confirm('Delete this question?');"><i class="fa fa-trash"></i></a>
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
