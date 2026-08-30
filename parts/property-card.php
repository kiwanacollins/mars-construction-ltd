<?php
function render_property_card($plan, $column_class = 'col-lg-3 col-md-6 col-sm-12') {
    $cover = $plan['cover_image'] ? 'Admin/' . $plan['cover_image'] : 'assets/images/resource/property-1.jpg';
    $bathrooms = rtrim(rtrim(number_format($plan['bathrooms'], 1), '0'), '.');
    ob_start();
    ?>
    <div class="property-block_one style-two <?php echo htmlspecialchars($column_class); ?>">
        <div class="property-block_one-inner">
            <div class="property-block_one-image">
                <?php if ($plan['featured']): ?><div class="property-block_one-title">Featured</div><?php endif; ?>
                <a class="property-block_one-heart" href="plan-detail.php?slug=<?php echo urlencode($plan['slug']); ?>"><i class="flaticon-heart"></i></a>
                <a href="plan-detail.php?slug=<?php echo urlencode($plan['slug']); ?>" class="property-block_one-image-link">
                    <img src="<?php echo htmlspecialchars($cover); ?>" alt="" />
                    <div class="property-block_one-image-content">
                        <h4 class="property-block_one-heading"><?php echo htmlspecialchars($plan['title']); ?></h4>
                        <ul class="property-block_one-info">
                            <li><span><img src="assets/images/icons/bed.svg" alt="" /></span><?php echo (int) $plan['bedrooms']; ?> Beds</li>
                            <li><span><img src="assets/images/icons/bath.svg" alt="" /></span><?php echo $bathrooms; ?> Bathrooms</li>
                            <li><span><img src="assets/images/icons/square.svg" alt="" /></span><?php echo number_format($plan['area_sqft']); ?> sqft</li>
                        </ul>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function fetch_properties_page($pdo, $offset, $limit) {
    $stmt = $pdo->prepare(
        "SELECT p.*, (SELECT file_path FROM property_files pf WHERE pf.property_id = p.id ORDER BY pf.is_cover DESC, pf.id ASC LIMIT 1) AS cover_image,
         (SELECT COUNT(*) FROM reviews r WHERE r.reviewable_type = 'plan' AND r.reviewable_id = p.id AND r.status = 'approved') AS comments_count
         FROM properties p ORDER BY p.created_at DESC, p.id DESC LIMIT ? OFFSET ?"
    );
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function format_compact_number($value) {
    $value = (float) $value;
    if ($value >= 1000000) {
        return rtrim(rtrim(number_format($value / 1000000, 1), '0'), '.') . 'M';
    }
    if ($value >= 1000) {
        return rtrim(rtrim(number_format($value / 1000, 1), '0'), '.') . 'k';
    }
    return number_format($value);
}

function render_feed_card($plan) {
    $cover = $plan['cover_image'] ? 'Admin/' . $plan['cover_image'] : 'assets/images/resource/property-1.jpg';
    $bathrooms = rtrim(rtrim(number_format($plan['bathrooms'], 1), '0'), '.');
    $detail_url = 'plan-detail.php?slug=' . urlencode($plan['slug']);
    $comments_count = isset($plan['comments_count']) ? (int) $plan['comments_count'] : 0;
    ob_start();
    ?>
    <div class="col-12">
        <div class="feed-card">
            <a href="<?php echo htmlspecialchars($detail_url); ?>" class="feed-card_image-link">
                <img src="<?php echo htmlspecialchars($cover); ?>" alt="<?php echo htmlspecialchars($plan['title']); ?>" />
                <?php if (!empty($plan['plan_number'])): ?>
                    <div class="feed-card_id-badge"><?php echo htmlspecialchars($plan['plan_number']); ?></div>
                <?php endif; ?>
                <div class="feed-card_views-badge"><i class="flaticon-eye"></i><?php echo format_compact_number($plan['views']); ?> views</div>
            </a>
            <div class="feed-card_body">
                <div class="feed-card_meta">
                    <span><?php echo (int) $plan['bedrooms']; ?> Beds</span>
                    <span class="feed-card_dot">|</span>
                    <span><?php echo $bathrooms; ?> Baths</span>
                    <span class="feed-card_dot">|</span>
                    <span><?php echo number_format($plan['area_sqft']); ?> sqft</span>
                </div>
                <a href="<?php echo htmlspecialchars($detail_url); ?>" class="feed-card_title"><?php echo htmlspecialchars($plan['title']); ?></a>
                <div class="feed-card_footer">
                    <div class="feed-card_price">
                        $<?php echo number_format($plan['price'], 0); ?>
                        <span class="feed-card_price-label">Architectural drawings</span>
                    </div>
                    <div class="feed-card_actions">
                        <span class="feed-card_action"><i class="flaticon-heart"></i><?php echo format_compact_number($plan['likes']); ?></span>
                        <span class="feed-card_action"><i class="flaticon-share"></i><?php echo format_compact_number($plan['shares']); ?></span>
                        <span class="feed-card_action"><i class="flaticon-comment"></i><?php echo format_compact_number($comments_count); ?></span>
                        <a href="<?php echo htmlspecialchars($detail_url); ?>" class="feed-card_cta">View Plans</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
