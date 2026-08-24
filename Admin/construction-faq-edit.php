<?php
	 require_once __DIR__ . '/config/dz.php';

	 $faq = null;
	 if (!empty($_GET['id'])) {
		 $stmt = $pdo->prepare('SELECT * FROM construction_faqs WHERE id = ?');
		 $stmt->execute([(int) $_GET['id']]);
		 $faq = $stmt->fetch();
	 }
	 $is_edit = (bool) $faq;
	 if (!$faq) {
		 $faq = ['id' => null, 'question' => '', 'answer' => ''];
	 }

	 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		 $question = trim($_POST['question'] ?? '');
		 $answer = trim($_POST['answer'] ?? '');
		 $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;

		 if ($question === '') {
			 $_SESSION['form_status_message'] = ['type' => 'danger', 'text' => 'Question is required.'];
			 header('Location: construction-faq-edit.php' . ($id ? "?id={$id}" : ''));
			 exit;
		 }

		 if ($id) {
			 $stmt = $pdo->prepare('UPDATE construction_faqs SET question = ?, answer = ? WHERE id = ?');
			 $stmt->execute([$question, $answer, $id]);
			 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Question updated.'];
		 } else {
			 $max = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), -1) m FROM construction_faqs')->fetch()['m'];
			 $stmt = $pdo->prepare('INSERT INTO construction_faqs (question, answer, sort_order) VALUES (?, ?, ?)');
			 $stmt->execute([$question, $answer, $max + 1]);
			 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Question added.'];
		 }
		 header('Location: construction-faqs.php');
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
						<li class="breadcrumb-item"><a href="construction-faqs.php">Common Questions</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $is_edit ? 'Edit Question' : 'Add Question'; ?></a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo $is_edit ? 'Edit Question' : 'Add Question'; ?></h4>
                            </div>
                            <div class="card-body">
								<form method="post">
									<?php if ($is_edit): ?><input type="hidden" name="id" value="<?php echo (int) $faq['id']; ?>"><?php endif; ?>
									<div class="mb-3">
										<label class="form-label">Question <span class="text-danger">*</span></label>
										<input type="text" name="question" class="form-control" placeholder="Do you handle permits and inspections?" value="<?php echo htmlspecialchars($faq['question']); ?>" required>
									</div>
									<div class="mb-3">
										<label class="form-label">Answer</label>
										<textarea name="answer" class="form-control" rows="4"><?php echo htmlspecialchars($faq['answer']); ?></textarea>
									</div>
									<a href="construction-faqs.php" class="btn btn-outline-secondary">Cancel</a>
									<button type="submit" class="btn btn-primary"><?php echo $is_edit ? 'Save Changes' : 'Add Question'; ?></button>
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
