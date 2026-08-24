<?php
	 require_once __DIR__ . '/config/dz.php';

	 $active_tab = in_array($_GET['tab'] ?? '', ['reviews', 'messages', 'orders'], true) ? $_GET['tab'] : 'reviews';

	 // Reviews actions
	 if (!empty($_GET['approve_review'])) {
		 $pdo->prepare("UPDATE reviews SET status = 'approved' WHERE id = ?")->execute([(int) $_GET['approve_review']]);
		 header('Location: notifications.php?tab=reviews');
		 exit;
	 }
	 if (!empty($_GET['delete_review'])) {
		 $pdo->prepare('DELETE FROM reviews WHERE id = ?')->execute([(int) $_GET['delete_review']]);
		 header('Location: notifications.php?tab=reviews');
		 exit;
	 }

	 // Messages actions
	 if (!empty($_GET['delete_message'])) {
		 $pdo->prepare('DELETE FROM messages WHERE id = ?')->execute([(int) $_GET['delete_message']]);
		 header('Location: notifications.php?tab=messages');
		 exit;
	 }
	 if (!empty($_GET['read_message'])) {
		 $pdo->prepare('UPDATE messages SET is_read = 1 WHERE id = ?')->execute([(int) $_GET['read_message']]);
		 header('Location: notifications.php?tab=messages');
		 exit;
	 }

	 // Orders actions
	 if (!empty($_GET['read_order'])) {
		 $pdo->prepare('UPDATE orders SET is_read = 1 WHERE id = ?')->execute([(int) $_GET['read_order']]);
		 header('Location: notifications.php?tab=orders');
		 exit;
	 }
	 if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'update_order_status') {
		 $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
		 $stmt->execute([$_POST['status'], (int) $_POST['order_id']]);
		 header('Location: notifications.php?tab=orders');
		 exit;
	 }
	 if (!empty($_GET['delete_order'])) {
		 $pdo->prepare('DELETE FROM orders WHERE id = ?')->execute([(int) $_GET['delete_order']]);
		 header('Location: notifications.php?tab=orders');
		 exit;
	 }

	 $reviews = $pdo->query(
		 "SELECT r.*,
			CASE WHEN r.reviewable_type = 'plan' THEN (SELECT title FROM properties WHERE id = r.reviewable_id)
				 ELSE (SELECT title FROM projects WHERE id = r.reviewable_id) END AS item_title
		  FROM reviews r ORDER BY r.status = 'pending' DESC, r.created_at DESC"
	 )->fetchAll();

	 $messages = $pdo->query(
		 "SELECT m.*, p.title AS property_title, pr.title AS project_title FROM messages m
		  LEFT JOIN properties p ON p.id = m.property_id
		  LEFT JOIN projects pr ON pr.id = m.project_id
		  ORDER BY m.is_read ASC, m.created_at DESC"
	 )->fetchAll();

	 $orders = $pdo->query('SELECT * FROM orders ORDER BY is_read ASC, created_at DESC')->fetchAll();
	 $order_items_by_order = [];
	 if ($orders) {
		 $order_ids = array_column($orders, 'id');
		 $in = implode(',', array_fill(0, count($order_ids), '?'));
		 $items_stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id IN ($in)");
		 $items_stmt->execute($order_ids);
		 foreach ($items_stmt->fetchAll() as $item) {
			 $order_items_by_order[$item['order_id']][] = $item;
		 }
	 }

	 $pending_review_count = count(array_filter($reviews, fn($r) => $r['status'] === 'pending'));
	 $unread_message_count = count(array_filter($messages, fn($m) => !$m['is_read']));
	 $unread_order_count = count(array_filter($orders, fn($o) => !$o['is_read']));
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

	<style>
	.star-rating { color: #f5b301; letter-spacing: 2px; }
	</style>

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
						<li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Notifications</a></li>
					</ol>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
								<ul class="nav nav-tabs card-header-tabs" id="notifTabs" role="tablist">
									<li class="nav-item" role="presentation">
										<button class="nav-link <?php echo $active_tab === 'reviews' ? 'active' : ''; ?>" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-pane" type="button" role="tab">Reviews <?php if ($pending_review_count): ?><span class="badge badge-danger ms-1"><?php echo $pending_review_count; ?></span><?php endif; ?></button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link <?php echo $active_tab === 'messages' ? 'active' : ''; ?>" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages-pane" type="button" role="tab">Messages <?php if ($unread_message_count): ?><span class="badge badge-danger ms-1"><?php echo $unread_message_count; ?></span><?php endif; ?></button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link <?php echo $active_tab === 'orders' ? 'active' : ''; ?>" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders-pane" type="button" role="tab">Orders <?php if ($unread_order_count): ?><span class="badge badge-danger ms-1"><?php echo $unread_order_count; ?></span><?php endif; ?></button>
									</li>
								</ul>
                            </div>
                            <div class="card-body">
								<div class="tab-content" id="notifTabsContent">

									<!-- Reviews Tab -->
									<div class="tab-pane fade <?php echo $active_tab === 'reviews' ? 'show active' : ''; ?>" id="reviews-pane" role="tabpanel">
										<div class="table-responsive">
											<table class="table">
												<thead>
													<tr>
														<th>Reviewer</th>
														<th>On</th>
														<th>Rating</th>
														<th>Comment</th>
														<th>Status</th>
														<th>Date</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
													<?php if (!$reviews): ?>
														<tr><td colspan="7" class="text-center">No reviews yet.</td></tr>
													<?php endif; ?>
													<?php foreach ($reviews as $r): ?>
														<tr style="<?php echo $r['status'] === 'pending' ? 'font-weight:bold;' : ''; ?>">
															<td><?php echo htmlspecialchars($r['name']); ?><br><small class="text-muted"><?php echo htmlspecialchars($r['email']); ?></small></td>
															<td><?php echo htmlspecialchars($r['item_title'] ?? '—'); ?> <small class="text-muted">(<?php echo htmlspecialchars(ucfirst($r['reviewable_type'])); ?>)</small></td>
															<td class="star-rating"><?php echo str_repeat('★', (int) $r['rating']) . str_repeat('☆', 5 - (int) $r['rating']); ?></td>
															<td><?php echo htmlspecialchars(mb_strimwidth($r['comment'] ?? '', 0, 80, '...')); ?></td>
															<td><span class="badge badge-<?php echo $r['status'] === 'approved' ? 'success' : 'warning'; ?>"><?php echo htmlspecialchars(ucfirst($r['status'])); ?></span></td>
															<td><?php echo htmlspecialchars(date('d M, Y', strtotime($r['created_at']))); ?></td>
															<td class="text-nowrap">
																<?php if ($r['status'] === 'pending'): ?>
																	<a href="notifications.php?approve_review=<?php echo $r['id']; ?>" class="btn btn-success btn-sm">Approve</a>
																<?php endif; ?>
																<a href="notifications.php?delete_review=<?php echo $r['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this review?');">Delete</a>
															</td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>

									<!-- Messages Tab -->
									<div class="tab-pane fade <?php echo $active_tab === 'messages' ? 'show active' : ''; ?>" id="messages-pane" role="tabpanel">
										<div class="table-responsive">
											<table class="table">
												<thead>
													<tr>
														<th>From</th>
														<th>Contact</th>
														<th>Subject</th>
														<th>Related To</th>
														<th>Message</th>
														<th>Received</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
													<?php if (!$messages): ?>
														<tr><td colspan="7" class="text-center">No messages yet.</td></tr>
													<?php endif; ?>
													<?php foreach ($messages as $m): ?>
														<tr style="<?php echo $m['is_read'] ? '' : 'font-weight:bold;'; ?>">
															<td><?php echo htmlspecialchars($m['name']); ?></td>
															<td><?php echo htmlspecialchars($m['email']); ?><?php echo $m['phone'] ? '<br>' . htmlspecialchars($m['phone']) : ''; ?></td>
															<td><?php echo htmlspecialchars($m['subject'] ?: $m['services'] ?: '—'); ?></td>
															<td><?php echo htmlspecialchars($m['property_title'] ?? $m['project_title'] ?? '—'); ?></td>
															<td><?php echo htmlspecialchars(mb_strimwidth($m['message'], 0, 80, '...')); ?></td>
															<td><?php echo htmlspecialchars(date('d M, Y H:i', strtotime($m['created_at']))); ?></td>
															<td class="text-nowrap">
																<?php if (!$m['is_read']): ?>
																	<a href="notifications.php?read_message=<?php echo $m['id']; ?>" class="btn btn-primary btn-sm">Mark Read</a>
																<?php endif; ?>
																<a href="notifications.php?delete_message=<?php echo $m['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this message?');">Delete</a>
															</td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>

									<!-- Orders Tab -->
									<div class="tab-pane fade <?php echo $active_tab === 'orders' ? 'show active' : ''; ?>" id="orders-pane" role="tabpanel">
										<div class="text-end mb-3"><a href="orders.php" class="btn btn-outline-primary btn-sm">Open Full Orders List &rarr;</a></div>
										<?php if (!$orders): ?>
											<p class="text-center mb-0">No orders yet.</p>
										<?php endif; ?>
										<?php foreach ($orders as $o): ?>
											<div class="card mb-3" style="<?php echo $o['is_read'] ? '' : 'border-color:#dc3545;'; ?>">
												<div class="card-body">
													<div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
														<div>
															<h6 class="mb-1"><?php echo htmlspecialchars($o['name']); ?> <?php if (!$o['is_read']): ?><span class="badge badge-danger">New</span><?php endif; ?></h6>
															<small class="text-muted"><?php echo htmlspecialchars($o['email']); ?><?php echo $o['phone'] ? ' · ' . htmlspecialchars($o['phone']) : ''; ?></small><br>
															<small class="text-muted"><?php echo htmlspecialchars(date('d M, Y H:i', strtotime($o['created_at']))); ?></small>
														</div>
														<div class="text-end">
															<div class="fs-18 font-w600">$<?php echo number_format($o['total'], 0); ?></div>
															<form method="post" class="d-flex gap-2 mt-1">
																<input type="hidden" name="form_action" value="update_order_status">
																<input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
																<select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
																	<?php foreach (['new', 'processing', 'completed', 'cancelled'] as $st): ?>
																		<option value="<?php echo $st; ?>" <?php echo $o['status'] === $st ? 'selected' : ''; ?>><?php echo ucfirst($st); ?></option>
																	<?php endforeach; ?>
																</select>
															</form>
														</div>
													</div>
													<table class="table table-sm mb-2">
														<thead><tr><th>Plan</th><th>Add-ons</th><th>Price</th><th>Qty</th></tr></thead>
														<tbody>
															<?php foreach ($order_items_by_order[$o['id']] ?? [] as $item): ?>
																<tr>
																	<td><?php echo htmlspecialchars($item['plan_title']); ?></td>
																	<td><?php echo htmlspecialchars($item['addon_names'] ?: '—'); ?></td>
																	<td>$<?php echo number_format($item['price'], 0); ?></td>
																	<td><?php echo (int) $item['qty']; ?></td>
																</tr>
															<?php endforeach; ?>
														</tbody>
													</table>
													<div class="text-nowrap">
														<?php if (!$o['is_read']): ?>
															<a href="notifications.php?read_order=<?php echo $o['id']; ?>&tab=orders" class="btn btn-primary btn-sm">Mark Read</a>
														<?php endif; ?>
														<a href="notifications.php?delete_order=<?php echo $o['id']; ?>&tab=orders" class="btn btn-danger btn-sm" onclick="return confirm('Delete this order?');">Delete</a>
													</div>
												</div>
											</div>
										<?php endforeach; ?>
									</div>

								</div>
                            </div>
                        </div>
					</div>
				</div>
            </div>
        </div>

        <div class="footer">
            <div class="copyright">
                <p>Copyright © Mars Construction <?php echo date("Y"); ?>. All Rights Reserved</p>
            </div>
        </div>
    </div>

    <?php include 'elements/page-js.php'; ?>

    <script>
    var params = new URLSearchParams(window.location.search);
    var tab = params.get('tab');
    if (tab) {
        var trigger = document.getElementById(tab + '-tab');
        if (trigger) { new bootstrap.Tab(trigger).show(); }
    }
    </script>

</body>

</html>
