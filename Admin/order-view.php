<?php
	 require_once __DIR__ . '/config/dz.php';

	 $id = (int) ($_GET['id'] ?? 0);
	 $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
	 $stmt->execute([$id]);
	 $order = $stmt->fetch();

	 if (!$order) {
		 header('Location: orders.php');
		 exit;
	 }

	 if (!$order['is_read']) {
		 $pdo->prepare('UPDATE orders SET is_read = 1 WHERE id = ?')->execute([$id]);
		 $order['is_read'] = 1;
	 }

	 if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'update_status') {
		 $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
		 $stmt->execute([$_POST['status'], $id]);
		 $_SESSION['form_status_message'] = ['type' => 'success', 'text' => 'Order status updated.'];
		 header('Location: order-view.php?id=' . $id);
		 exit;
	 }

	 $items_stmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ?');
	 $items_stmt->execute([$id]);
	 $items = $items_stmt->fetchAll();

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
						<li class="breadcrumb-item"><a href="orders.php">Orders</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Order #<?php echo $order['id']; ?></a></li>
					</ol>
                </div>

				<?php if (!empty($form_status_message)): ?>
					<div class="alert alert-<?php echo $form_status_message['type']; ?>"><?php echo htmlspecialchars($form_status_message['text']); ?></div>
				<?php endif; ?>

				<div class="row">
					<div class="col-lg-8">
						<div class="card" style="height:auto;">
							<div class="card-header">
								<h4 class="card-title">Order Items</h4>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table">
										<thead><tr><th>Plan</th><th>Add-ons</th><th>Price</th><th>Qty</th><th>Line Total</th></tr></thead>
										<tbody>
											<?php foreach ($items as $item): ?>
												<tr>
													<td><?php echo htmlspecialchars($item['plan_title']); ?></td>
													<td><?php echo htmlspecialchars($item['addon_names'] ?: '—'); ?></td>
													<td>$<?php echo number_format($item['price'], 2); ?></td>
													<td><?php echo (int) $item['qty']; ?></td>
													<td>$<?php echo number_format($item['price'] * $item['qty'], 2); ?></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
										<tfoot>
											<tr><th colspan="4" class="text-end">Total</th><th>$<?php echo number_format($order['total'], 2); ?></th></tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>

						<div class="card" style="height:auto;">
							<div class="card-header">
								<h4 class="card-title">Payment</h4>
							</div>
							<div class="card-body">
								<p><strong>Method:</strong> <?php echo htmlspecialchars(ucfirst($order['payment_method'] ?? 'Not set')); ?></p>
								<p><strong>Payment Status:</strong>
									<span class="badge badge-<?php echo $order['payment_status'] === 'paid' ? 'success' : ($order['payment_status'] === 'failed' ? 'danger' : 'warning'); ?>">
										<?php echo ucfirst($order['payment_status'] ?? 'unpaid'); ?>
									</span>
								</p>
								<?php if ($order['pesapal_tracking_id']): ?><p><strong>PesaPal Tracking ID:</strong> <code><?php echo htmlspecialchars($order['pesapal_tracking_id']); ?></code></p><?php endif; ?>
								<?php if ($order['pesapal_merchant_ref']): ?><p class="mb-0"><strong>Merchant Reference:</strong> <code><?php echo htmlspecialchars($order['pesapal_merchant_ref']); ?></code></p><?php endif; ?>
							</div>
						</div>
					</div>

					<div class="col-lg-4">
						<div class="card" style="height:auto;">
							<div class="card-header">
								<h4 class="card-title">Customer</h4>
							</div>
							<div class="card-body">
								<p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
								<p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($order['email']); ?>"><?php echo htmlspecialchars($order['email']); ?></a></p>
								<p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone'] ?: '—'); ?></p>
								<p class="mb-0"><strong>Placed:</strong> <?php echo htmlspecialchars(date('d M, Y H:i', strtotime($order['created_at']))); ?></p>
							</div>
						</div>

						<div class="card" style="height:auto;">
							<div class="card-header">
								<h4 class="card-title">Order Status</h4>
							</div>
							<div class="card-body">
								<form method="post">
									<input type="hidden" name="form_action" value="update_status">
									<div class="mb-3">
										<select name="status" class="form-select" onchange="this.form.submit()">
											<?php foreach (['new', 'processing', 'completed', 'cancelled'] as $st): ?>
												<option value="<?php echo $st; ?>" <?php echo $order['status'] === $st ? 'selected' : ''; ?>><?php echo ucfirst($st); ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</form>
								<a href="orders.php?delete=<?php echo $order['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this order?');">Delete Order</a>
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
