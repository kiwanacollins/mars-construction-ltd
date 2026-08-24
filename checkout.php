<?php $page_title = "Checkout"; $home = false; ?>
<?php
require_once __DIR__ . '/Admin/config/db.php';
require_once __DIR__ . '/parts/cart.php';

$cart_items = cart_lines($pdo);
$cart_total = 0;
foreach ($cart_items as $ci) {
    $cart_total += $ci['price'] * $ci['qty'];
}

function checkout_image_src($path) {
    return $path ? 'Admin/' . $path : 'assets/images/resource/post-thumb-1.jpg';
}

$order_status = $_SESSION['order_status'] ?? null;
$order_status_note = $_SESSION['order_status_note'] ?? null;
unset($_SESSION['order_status'], $_SESSION['order_status_note']);

$placed_order = null;
if ($order_status === 'placed' && !empty($_SESSION['last_order_id'])) {
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
    $stmt->execute([$_SESSION['last_order_id']]);
    $placed_order = $stmt->fetch();
    unset($_SESSION['last_order_id']);
}
?>
<?php require_once __DIR__ . '/parts/header.php'; ?>
	<!-- End Main Header -->

	<!-- Sidebar Page Container -->
    <div class="sidebar-page-container checkout-page">
    	<div class="auto-container">

			<?php if ($order_status === 'placed'): ?>
				<div class="checkout-status_card">
					<?php if ($placed_order && $placed_order['payment_status'] === 'paid'): ?>
						<div class="checkout-status_icon is-success"><i class="fa-solid fa-check"></i></div>
						<h2>Payment Received</h2>
						<p>Thank you — your payment for order #<?php echo (int) $placed_order['id']; ?> was successful. A confirmation has been sent to our team and we'll be in touch shortly.</p>
					<?php elseif ($placed_order && $placed_order['payment_status'] === 'failed'): ?>
						<div class="checkout-status_icon is-warning"><i class="fa-solid fa-triangle-exclamation"></i></div>
						<h2>Payment Not Completed</h2>
						<p>Your order #<?php echo (int) $placed_order['id']; ?> was recorded, but the payment didn't go through. Please contact us or place a new order to try again.</p>
					<?php else: ?>
						<div class="checkout-status_icon is-success"><i class="fa-solid fa-check"></i></div>
						<h2>Order Received</h2>
						<p><?php echo htmlspecialchars($order_status_note ?: 'Thank you — your order has been placed. Our team will review the details and reach out to you by email shortly.'); ?></p>
					<?php endif; ?>
					<a href="plans.php" class="checkout-btn">Browse More Plans</a>
				</div>
			<?php elseif (!$cart_items): ?>
				<div class="checkout-status_card">
					<div class="checkout-status_icon"><i class="fa-solid fa-cart-shopping"></i></div>
					<h2>Your Cart Is Empty</h2>
					<p>Browse our plans and add one to your cart before checking out.</p>
					<a href="plans.php" class="checkout-btn">Browse Plans</a>
				</div>
			<?php else: ?>

				<div class="checkout-header">
					<div class="checkout-header_step is-done"><span>1</span> Cart</div>
					<div class="checkout-header_sep"></div>
					<div class="checkout-header_step is-active"><span>2</span> Checkout</div>
					<div class="checkout-header_sep"></div>
					<div class="checkout-header_step"><span>3</span> Confirmation</div>
				</div>

				<form method="post" action="order-place.php" class="checkout-grid">
					<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

					<!-- Billing Details -->
					<div class="checkout-panel checkout-billing">
						<h3 class="checkout-panel_title">Billing Details</h3>
						<div class="checkout-field">
							<label>Full Name <span>*</span></label>
							<input type="text" name="name" placeholder="Jane Doe" required>
						</div>
						<div class="checkout-field">
							<label>Email Address <span>*</span></label>
							<input type="email" name="email" placeholder="jane@example.com" required>
						</div>
						<div class="checkout-field">
							<label>Phone Number <span>*</span></label>
							<input type="tel" name="phone" placeholder="+1 555 123 4567" required>
						</div>

						<div class="checkout-info_box">
							<i class="fa-solid fa-lock"></i>
							<div>You'll be redirected to PesaPal's secure payment page to complete your payment. Once confirmed, our team will follow up by email with next steps.</div>
						</div>
					</div>

					<!-- Order Summary -->
					<div class="checkout-panel checkout-summary">
						<h3 class="checkout-panel_title">Order Summary</h3>

						<div class="checkout-summary_items">
							<?php foreach ($cart_items as $ci): ?>
								<div class="checkout-summary_item">
									<div class="checkout-summary_item-thumb">
										<img src="<?php echo htmlspecialchars(checkout_image_src($ci['property']['cover_image'])); ?>" alt="">
									</div>
									<div class="checkout-summary_item-body">
										<div class="checkout-summary_item-title"><?php echo htmlspecialchars($ci['property']['title']); ?></div>
										<?php if ($ci['addons']): ?><div class="checkout-summary_item-addons"><?php echo htmlspecialchars(implode(', ', array_column($ci['addons'], 'name'))); ?></div><?php endif; ?>
										<div class="checkout-summary_item-qty">Qty: <?php echo (int) $ci['qty']; ?></div>
									</div>
									<div class="checkout-summary_item-price">$<?php echo number_format($ci['price'] * $ci['qty'], 0); ?></div>
								</div>
							<?php endforeach; ?>
						</div>

						<div class="checkout-summary_totals">
							<div class="checkout-summary_row">
								<span>Subtotal</span>
								<span>$<?php echo number_format($cart_total, 0); ?></span>
							</div>
							<div class="checkout-summary_row is-total">
								<span>Total</span>
								<span>$<?php echo number_format($cart_total, 0); ?></span>
							</div>
						</div>

						<button type="submit" class="checkout-btn checkout-btn_full">Proceed to Payment <i class="fa-solid fa-arrow-right"></i></button>
						<div class="checkout-secure_note"><i class="fa-solid fa-lock"></i> Your details are only used to process this order.</div>
					</div>
				</form>

			<?php endif; ?>

		</div>
	</div>

	<!-- Main Footer -->
<?php require_once __DIR__ . '/parts/footer.php'; ?>
