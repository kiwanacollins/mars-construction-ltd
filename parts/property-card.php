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
        "SELECT p.*, (SELECT file_path FROM property_files pf WHERE pf.property_id = p.id ORDER BY pf.is_cover DESC, pf.id ASC LIMIT 1) AS cover_image
         FROM properties p ORDER BY p.created_at DESC, p.id DESC LIMIT ? OFFSET ?"
    );
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
