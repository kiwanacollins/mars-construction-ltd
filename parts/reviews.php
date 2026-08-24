<?php
function render_reviews_section($pdo, $type, $id, $redirect) {
    $stmt = $pdo->prepare("SELECT * FROM reviews WHERE reviewable_type = ? AND reviewable_id = ? AND status = 'approved' ORDER BY created_at DESC");
    $stmt->execute([$type, $id]);
    $reviews = $stmt->fetchAll();

    $avg = 0;
    if ($reviews) {
        $sum = array_sum(array_column($reviews, 'rating'));
        $avg = round($sum / count($reviews), 1);
    }
    ?>
    <h4 class="property-detail_subheading">
        Reviews <?php if ($reviews): ?><span class="star-rating-inline">(<?php echo $avg; ?>/5 from <?php echo count($reviews); ?> review<?php echo count($reviews) === 1 ? '' : 's'; ?>)</span><?php endif; ?>
    </h4>

    <?php if (!empty($_SESSION['review_status'])): ?>
        <div class="alert alert-<?php echo $_SESSION['review_status'] === 'success' ? 'success' : 'danger'; ?>" style="padding:12px 16px;margin-bottom:15px;border-radius:4px;<?php echo $_SESSION['review_status'] === 'success' ? 'background:#e6f7ea;color:#1e7e34;' : 'background:#fdecea;color:#a12622;'; ?>">
            <?php echo $_SESSION['review_status'] === 'success' ? 'Thanks for your review! It will appear here once approved.' : 'Please fill in your name, email, and a rating.'; ?>
        </div>
        <?php unset($_SESSION['review_status']); ?>
    <?php endif; ?>

    <?php if (!$reviews): ?>
        <p class="text-muted">No reviews yet — be the first to leave one.</p>
    <?php else: ?>
        <ul class="review-list">
            <?php foreach ($reviews as $rv): ?>
                <li class="review-list_item">
                    <div class="review-list_stars"><?php echo str_repeat('★', (int) $rv['rating']) . str_repeat('☆', 5 - (int) $rv['rating']); ?></div>
                    <div class="review-list_name"><?php echo htmlspecialchars($rv['name']); ?> <span class="review-list_date"><?php echo htmlspecialchars(date('M d, Y', strtotime($rv['created_at']))); ?></span></div>
                    <?php if ($rv['comment']): ?><p class="review-list_comment"><?php echo htmlspecialchars($rv['comment']); ?></p><?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h4 class="property-detail_subheading">Leave A Review</h4>
    <div class="default-form review-form">
        <form method="post" action="review-submit.php">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <input type="hidden" name="reviewable_type" value="<?php echo htmlspecialchars($type); ?>">
            <input type="hidden" name="reviewable_id" value="<?php echo (int) $id; ?>">
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
            <div class="review-form_grid">
                <div class="form-group review-form_half">
                    <input type="text" name="name" placeholder="Your Name" required>
                </div>
                <div class="form-group review-form_half">
                    <input type="email" name="email" placeholder="Your Email" required>
                </div>
                <div class="form-group review-form_full">
                    <div class="review-rating-input">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" name="rating" id="rating-<?php echo $i; ?>-<?php echo $type . $id; ?>" value="<?php echo $i; ?>" <?php echo $i === 5 ? 'checked' : ''; ?>>
                            <label for="rating-<?php echo $i; ?>-<?php echo $type . $id; ?>">★</label>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="form-group review-form_full">
                    <textarea name="comment" placeholder="Share your experience..."></textarea>
                </div>
                <div class="form-group review-form_full">
                    <button type="submit" class="theme-btn btn-style-one"><span class="btn-wrap"><span class="text-one">Submit Review</span><span class="text-two">Submit Review</span></span></button>
                </div>
            </div>
        </form>
    </div>
    <?php
}
