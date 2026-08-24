<?php $page_title = "Blog"; $home = false; ?>
<?php
require_once __DIR__ . '/Admin/config/db.php';
$blog_posts = $pdo->query("SELECT * FROM blog_posts WHERE status = 'published' ORDER BY published_at DESC, created_at DESC")->fetchAll();
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
			<h2>blog-grid</h2>
        </div>
    </section>
    <!-- End Page Title -->
	
	<!-- Blog Three -->
	<section class="blog-three">
		<div class="auto-container">
			<div class="row clearfix">
				<?php if (!$blog_posts): ?>
					<div class="col-12"><p>No blog posts published yet.</p></div>
				<?php endif; ?>
				<?php foreach ($blog_posts as $post): ?>
					<!-- News Block One -->
					<div class="news-block_one style-two col-lg-4 col-md-6 col-sm-12">
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
			<ul class="styled-pagination text-center">
				<li><a href="#" class="active">1</a></li>
				<li><a href="#">2</a></li>
				<li><a href="#">3</a></li>
				<li class="next"><a href="#"><span class="fa-solid fa-angle-right fa-fw"></span></a></li>
			</ul>
			<!-- End Styled Pagination -->
			
		</div>
	</section>
	<!-- End Blog Three -->
	
	<!-- Main Footer -->
<?php require_once __DIR__ . '/parts/footer.php'; ?>
