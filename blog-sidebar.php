<?php $page_title = "Blog Sidebar"; $home = false; ?>
<?php
require_once __DIR__ . '/Admin/config/db.php';
$blog_posts = $pdo->query("SELECT * FROM blog_posts WHERE status = 'published' ORDER BY published_at DESC, created_at DESC")->fetchAll();
$recent_posts = array_slice($blog_posts, 0, 3);
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
			<h2>Blog Sidebar</h2>
        </div>
    </section>
    <!-- End Page Title -->
	
	<!-- Sidebar Page Container -->
    <div class="sidebar-page-container">
    	<div class="auto-container">
        	<div class="row clearfix">
				
				<!-- Content Side -->
                <div class="content-side col-lg-8 col-md-12 col-sm-12">
					<div class="row clearfix">
						<?php if (!$blog_posts): ?>
							<p>No blog posts published yet.</p>
						<?php endif; ?>
						<?php foreach ($blog_posts as $post): ?>
							<!-- News Block One -->
							<div class="news-block_one style-two col-lg-6 col-md-6 col-sm-12">
								<div class="news-block_one-inner">
									<div class="news-block_one-image">
										<a href="blog-detail.php?slug=<?php echo urlencode($post['slug']); ?>"><img src="<?php echo htmlspecialchars($post['featured_image'] ?: 'assets/images/resource/news-1.jpg'); ?>" alt="" /></a>
									</div>
									<div class="news-block_one-content">
										<ul class="news-block_one-meta">
											<li>By Admin</li>
											<li><?php echo htmlspecialchars(date('d M, Y', strtotime($post['published_at'] ?: $post['created_at']))); ?></li>
										</ul>
										<h4 class="news-block_one-title"><a href="blog-detail.php?slug=<?php echo urlencode($post['slug']); ?>"><?php echo htmlspecialchars($post['title']); ?></a></h4>
										<a class="news-block_one-more" href="blog-detail.php?slug=<?php echo urlencode($post['slug']); ?>">Read More <i class="flaticon-next-1"></i></a>
									</div>
								</div>
							</div>
						<?php endforeach; ?>

					</div>
					
					<!-- Styled Pagination -->
					<ul class="styled-pagination">
						<li><a href="#" class="active">1</a></li>
						<li><a href="#">2</a></li>
						<li><a href="#">3</a></li>
						<li class="next"><a href="#"><span class="fa-solid fa-angle-right fa-fw"></span></a></li>
					</ul>
					<!-- End Styled Pagination -->
					
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
