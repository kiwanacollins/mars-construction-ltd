<?php $page_title = "Blog Detail"; $home = false; ?>
<?php
require_once __DIR__ . '/Admin/config/db.php';

$post = null;
if (!empty($_GET['slug'])) {
    $stmt = $pdo->prepare(
        "SELECT bp.*, u.name AS author_name FROM blog_posts bp
         LEFT JOIN users u ON u.id = bp.author_id
         WHERE bp.slug = ?"
    );
    $stmt->execute([$_GET['slug']]);
    $post = $stmt->fetch();
}

$recent_posts = $pdo->query("SELECT * FROM blog_posts WHERE status = 'published' ORDER BY created_at DESC LIMIT 3")->fetchAll();
?>
<?php require_once __DIR__ . '/parts/header.php'; ?>
	<!-- End Main Header -->
	
	<!-- Page Title -->
    <section class="page-title">
		<div class="page-title_cloud" style="background-image:url(assets/images/icons/cloud.png)"></div>
		<div class="page-title_cloud-two" style="background-image:url(assets/images/icons/cloud-1.png)"></div>
		<div class="page-title_pattern" style="background-image:url(assets/images/background/pattern-3.png)"></div>
		<div class="page-title_gradient"></div>
        <div class="auto-container">
			<h2>Blog Detail</h2>
        </div>
    </section>
    <!-- End Page Title -->
	
	<!-- Sidebar Page Container -->
    <div class="sidebar-page-container">
    	<div class="auto-container">
        	<div class="row clearfix">
				
				<!-- Content Side -->
                <div class="content-side col-lg-8 col-md-12 col-sm-12">
					<div class="blog-detail">
						<div class="blog-detail_inner">
						<?php if (!$post): ?>
							<div class="blog-detail_content"><p>Post not found. <a href="blog.php">Back to blog</a>.</p></div>
						<?php else: ?>
							<div class="blog-detail_image">
								<img src="<?php echo htmlspecialchars($post['featured_image'] ?: 'assets/images/resource/news-10.jpg'); ?>" alt="" />
							</div>
							<div class="blog-detail_content">
								<div class="blog-detail_author-outer d-flex align-items-center flex-wrap">
									<div class="blog-detail-author d-flex align-items-center flex-wrap">
										By <?php echo htmlspecialchars($post['author_name'] ?: 'Admin'); ?>
									</div>
									<ul class="blog-detail-meta d-flex align-items-center flex-wrap">
										<li><span class="icon fa-regular fa-calendar fa-fw"></span><?php echo htmlspecialchars(date('d M, Y', strtotime($post['published_at'] ?: $post['created_at']))); ?></li>
									</ul>
								</div>
								<h3 class="blog-detail_heading"><?php echo htmlspecialchars($post['title']); ?></h3>
								<?php if ($post['excerpt']): ?><p><em><?php echo htmlspecialchars($post['excerpt']); ?></em></p><?php endif; ?>
								<p><?php echo nl2br(htmlspecialchars($post['body'] ?: '')); ?></p>
						<?php endif; ?>

								<!-- Comment Form -->

								<!-- Comment Form -->
								<div class="comment-form_outer">
									<div class="group-title">
										<h3>Leave a comment</h3>
									</div>
									<!-- Comment Form -->
									<div class="comment-form">
										<form method="post" action="blog.php">
											<div class="row clearfix">
												
												<div class="col-lg-6 col-md-6 col-sm-12 form-group">
													<i class="flaticon-user"></i>
													<input type="text" name="username" placeholder="Full Name" required="">
												</div>
												
												<div class="col-lg-6 col-md-6 col-sm-12 form-group">
													<i class="flaticon-mail"></i>
													<input type="text" name="email" placeholder="Email Address" required="">
												</div>
												
												<div class="col-lg-12 col-md-12 col-sm-12 form-group">
													<textarea class="" name="message" placeholder="Type Here..."></textarea>
												</div>
												
												<div class="col-lg-12 col-md-12 col-sm-12 form-group">
													<!-- Button Box -->
													<button type="submit" class="theme-btn btn-style-one">
														<span class="btn-wrap">
															<span class="text-one">Comment Now</span>
															<span class="text-two">Comment Now</span>
														</span>
													</button>
												</div>
												
											</div>
										</form>
									</div>
									<!-- End Comment Form -->
								</div>
								<!--End Comment Form -->

							</div>
						</div>
					</div>
				</div>
				
				<!-- Sidebar Side -->
                <div class="sidebar-side col-lg-4 col-md-12 col-sm-12">
                	<aside class="sidebar">
						<div class="sidebar-inner">
							
							<!-- Search Widget -->
							<div class="sidebar-widget search-box">
								<div class="widget-content">
									<h5 class="sidebar-widget_title">Search Here</h5>
									<form method="post" action="contact.php">
										<div class="form-group">
											<input type="search" name="search-field" value="" placeholder="Search..." required>
											<button type="submit"><span class="icon fa fa-search"></span></button>
										</div>
									</form>
								</div>
							</div>
							
							<!-- Post Widget -->
							<div class="sidebar-widget post-widget">
								<div class="widget-content">
									<h5 class="sidebar-widget_title">Popular Post</h5>

									<?php foreach ($recent_posts as $rp): ?>
									<div class="post">
										<div class="thumb"><a href="blog-detail.php?slug=<?php echo urlencode($rp['slug']); ?>"><img src="<?php echo htmlspecialchars($rp['featured_image'] ?: 'assets/images/resource/post-thumb-4.jpg'); ?>" alt=""></a></div>
										<h6><a href="blog-detail.php?slug=<?php echo urlencode($rp['slug']); ?>"><?php echo htmlspecialchars($rp['title']); ?></a></h6>
										<div class="post-date"><i><img src="assets/images/icons/calendar.svg" alt="" /></i> <?php echo htmlspecialchars(date('M d, Y', strtotime($rp['published_at'] ?: $rp['created_at']))); ?></div>
									</div>
									<?php endforeach; ?>

								</div>
							</div>
							
							<!-- Service Widget -->
							<div class="sidebar-widget category-widget">
								<div class="widget-content">
									<h5 class="sidebar-widget_title">Categories</h5>
									<ul class="service-list">
										<li><a href="#">Buying <span>(03)</span></a></li>
										<li><a href="#">Neighborhoods <span>(04)</span></a></li>
										<li><a href="#">Trends <span>(02)</span></a></li>
										<li><a href="#">Renovation <span>(05)</span></a></li>
										<li><a href="#">Selling <span>(02)</span></a></li>
									</ul>
								</div>
							</div>
							
							<!-- Popular Tags -->
							<div class="sidebar-widget popular-tags">
								<div class="widget-content">
									<h5 class="sidebar-widget_title">Popular Tags</h5>
									<a href="#">Investing</a>
									<a href="#">Legal</a>
									<a href="#">Design</a>
									<a href="#">Sustainability</a>
								</div>
							</div>
							
						</div>
					</aside>
				</div>
				
			</div>
		</div>
	</div>
	
	<!-- Main Footer -->
<?php require_once __DIR__ . '/parts/footer.php'; ?>
