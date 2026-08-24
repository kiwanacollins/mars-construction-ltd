<?php
	 require_once __DIR__ . '/config/dz.php';

	 $users = $pdo->query('SELECT * FROM users ORDER BY created_at DESC')->fetchAll();

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
						<li class="breadcrumb-item"><a href="javascript:void(0)">Users</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">All Users</a></li>
					</ol>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Admin Users</h4>
								<a href="add-user.php" class="btn btn-primary btn-sm"><i class="fa fa-plus me-1"></i>Add User</a>
                            </div>
                            <div class="card-body">
								<?php if (!empty($form_status_message)): ?>
									<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
								<?php endif; ?>
								<div class="table-responsive">
									<table class="table">
										<thead>
											<tr>
												<th>Name</th>
												<th>Email</th>
												<th>Role</th>
												<th>Added</th>
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!$users): ?>
												<tr><td colspan="5" class="text-center">No users yet.</td></tr>
											<?php endif; ?>
											<?php foreach ($users as $u): ?>
												<tr>
													<td><?php echo htmlspecialchars($u['name']); ?><?php echo (int) $u['id'] === (int) (current_admin()['id'] ?? 0) ? ' <span class="badge badge-primary">You</span>' : ''; ?></td>
													<td><?php echo htmlspecialchars($u['email']); ?></td>
													<td><span class="badge badge-<?php echo $u['role'] === 'admin' ? 'primary' : 'secondary'; ?>"><?php echo htmlspecialchars(ucfirst($u['role'])); ?></span></td>
													<td><?php echo htmlspecialchars(date('d M, Y', strtotime($u['created_at']))); ?></td>
													<td class="text-nowrap">
														<a href="add-user.php?id=<?php echo $u['id']; ?>" class="btn btn-warning btn-sm content-icon"><i class="fa-solid fa-pen-to-square"></i></a>
														<?php if ((int) $u['id'] !== (int) (current_admin()['id'] ?? 0)): ?>
															<a href="user-delete.php?id=<?php echo $u['id']; ?>" class="btn btn-danger btn-sm content-icon" onclick="return confirm('Delete this user?');"><i class="fa-solid fa-trash"></i></a>
														<?php endif; ?>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
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
