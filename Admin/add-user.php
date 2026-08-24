<?php
	 require_once __DIR__ . '/config/dz.php';

	 $edit_user = null;
	 if (!empty($_GET['id'])) {
		 $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
		 $stmt->execute([(int) $_GET['id']]);
		 $edit_user = $stmt->fetch();
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
						<li class="breadcrumb-item"><a href="all-users.php">Users</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $edit_user ? 'Edit User' : 'Add User'; ?></a></li>
					</ol>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo $edit_user ? 'Edit User' : 'Add User'; ?></h4>
                            </div>
                            <div class="card-body">
								<?php if (!empty($form_status_message)): ?>
									<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
								<?php endif; ?>
								<form action="user-save.php" method="post">
									<?php if ($edit_user): ?>
										<input type="hidden" name="id" value="<?php echo (int) $edit_user['id']; ?>">
									<?php endif; ?>
									<div class="row">
										<div class="mb-3 col-lg-6">
											<label class="form-label">Full Name</label>
											<input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($edit_user['name'] ?? ''); ?>" required>
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Email</label>
											<input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($edit_user['email'] ?? ''); ?>" required>
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Role</label>
											<select name="role" class="default-select form-control wide">
												<option value="admin" <?php echo ($edit_user['role'] ?? 'admin') === 'admin' ? 'selected' : ''; ?>>Admin</option>
												<option value="editor" <?php echo ($edit_user['role'] ?? '') === 'editor' ? 'selected' : ''; ?>>Editor</option>
											</select>
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Password <?php echo $edit_user ? '<small class="text-muted">(leave blank to keep current password)</small>' : ''; ?></label>
											<input type="password" name="password" class="form-control" <?php echo $edit_user ? '' : 'required'; ?>>
										</div>
									</div>
									<button type="submit" class="btn btn-primary"><?php echo $edit_user ? 'Update' : 'Create'; ?></button>
									<a href="all-users.php" class="btn btn-danger light">Cancel</a>
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
