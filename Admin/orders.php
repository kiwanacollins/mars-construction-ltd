<?php
	 require_once __DIR__ . '/config/dz.php';

	 if (!empty($_GET['delete'])) {
		 $id = (int) $_GET['delete'];
		 $pdo->prepare('DELETE FROM order_items WHERE order_id = ?')->execute([$id]);
		 $pdo->prepare('DELETE FROM orders WHERE id = ?')->execute([$id]);
		 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Order deleted.'];
		 header('Location: orders.php');
		 exit;
	 }

	 $status_filter = in_array($_GET['status'] ?? '', ['new', 'processing', 'completed', 'cancelled'], true) ? $_GET['status'] : '';
	 $sql = 'SELECT * FROM orders';
	 $params = [];
	 if ($status_filter) {
		 $sql .= ' WHERE status = ?';
		 $params[] = $status_filter;
	 }
	 $sql .= ' ORDER BY is_read ASC, created_at DESC';
	 $stmt = $pdo->prepare($sql);
	 $stmt->execute($params);
	 $orders = $stmt->fetchAll();

	 $unread_count = (int) $pdo->query('SELECT COUNT(*) c FROM orders WHERE is_read = 0')->fetch()['c'];

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
						<li class="breadcrumb-item"><a href="javascript:void(0)">Sales</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Orders</a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="clean-list-header">
					<h2>Orders <?php if ($unread_count): ?><span class="clean-list-pill is-featured"><?php echo $unread_count; ?> new</span><?php endif; ?></h2>
					<form method="get" action="orders.php">
						<select name="status" class="form-select" onchange="this.form.submit()" style="min-width:180px;">
							<option value="">All Statuses</option>
							<?php foreach (['new', 'processing', 'completed', 'cancelled'] as $st): ?>
								<option value="<?php echo $st; ?>" <?php echo $status_filter === $st ? 'selected' : ''; ?>><?php echo ucfirst($st); ?></option>
							<?php endforeach; ?>
						</select>
					</form>
				</div>

				<div class="clean-list-card">
					<?php if (!$orders): ?>
						<div class="clean-list-empty">No orders yet.</div>
					<?php else: ?>
						<div class="table-responsive">
							<table class="clean-list-table">
								<thead>
									<tr>
										<th>Order</th>
										<th>Customer</th>
										<th>Total</th>
										<th>Payment</th>
										<th>Status</th>
										<th>Placed</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($orders as $o): ?>
										<tr style="<?php echo $o['is_read'] ? '' : 'font-weight:600;'; ?>">
											<td>
												<a href="order-view.php?id=<?php echo $o['id']; ?>" class="clean-list-title">#<?php echo $o['id']; ?></a>
												<?php if (!$o['is_read']): ?><span class="clean-list-pill is-featured">New</span><?php endif; ?>
											</td>
											<td><?php echo htmlspecialchars($o['name']); ?><br><small class="text-muted"><?php echo htmlspecialchars($o['email']); ?></small></td>
											<td>$<?php echo number_format($o['total'], 0); ?></td>
											<td>
												<?php
													$pay_class = ['paid' => 'is-featured', 'failed' => 'is-standard', 'unpaid' => 'is-standard'][$o['payment_status'] ?? 'unpaid'];
												?>
												<span class="clean-list-pill <?php echo $pay_class; ?>"><?php echo ucfirst($o['payment_status'] ?? 'unpaid'); ?></span>
											</td>
											<td><span class="clean-list-pill is-standard"><?php echo ucfirst($o['status']); ?></span></td>
											<td><?php echo htmlspecialchars(date('d M, Y H:i', strtotime($o['created_at']))); ?></td>
											<td>
												<div class="clean-list-actions">
													<a href="order-view.php?id=<?php echo $o['id']; ?>" class="edit-btn" title="View"><i class="fa fa-eye"></i></a>
													<a href="orders.php?delete=<?php echo $o['id']; ?>" class="delete-btn" title="Delete" onclick="return confirm('Delete this order?');"><i class="fa fa-trash"></i></a>
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
