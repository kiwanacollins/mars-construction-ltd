<?php $page_title = "Project Detail"; $home = false; ?>
<?php
require_once __DIR__ . '/Admin/config/db.php';

$project = null;
if (!empty($_GET['slug'])) {
    $stmt = $pdo->prepare('SELECT * FROM projects WHERE slug = ?');
    $stmt->execute([$_GET['slug']]);
    $project = $stmt->fetch();
}

$project_files = [];
if ($project) {
    $fstmt = $pdo->prepare('SELECT * FROM project_files WHERE project_id = ? ORDER BY is_cover DESC, sort_order, id');
    $fstmt->execute([$project['id']]);
    $project_files = $fstmt->fetchAll();
}
$cover_image = $project_files ? 'Admin/' . $project_files[0]['file_path'] : 'assets/images/resource/news-10.jpg';

$other_projects = $pdo->query(
    'SELECT p.*, (SELECT file_path FROM project_files pf WHERE pf.project_id = p.id ORDER BY pf.is_cover DESC, pf.id ASC LIMIT 1) AS cover_image
     FROM projects p ' . ($project ? 'WHERE p.id != ' . (int) $project['id'] . ' ' : '') . 'ORDER BY p.created_at DESC LIMIT 3'
)->fetchAll();
?>
<?php require_once __DIR__ . '/parts/header.php'; ?>
	<!-- End Main Header -->

	<!-- Sidebar Page Container -->
    <div class="sidebar-page-container">
    	<div class="auto-container">
        	<div class="row clearfix">

				<!-- Content Side -->
                <div class="content-side col-lg-8 col-md-12 col-sm-12">
					<div class="blog-detail">
						<div class="blog-detail_inner">
						<?php if (!$project): ?>
							<div class="blog-detail_content"><p>Project not found. <a href="construction.php">Back to Construction</a>.</p></div>
						<?php else: ?>
							<div class="blog-detail_image">
								<img src="<?php echo htmlspecialchars($cover_image); ?>" alt="" />
							</div>
							<div class="blog-detail_content">
								<ul class="project-meta_pills d-flex align-items-center flex-wrap">
									<?php if ($project['category']): ?><li class="project-meta_pill is-category"><i class="fa-solid fa-tag fa-fw"></i><?php echo htmlspecialchars($project['category']); ?></li><?php endif; ?>
									<?php if ($project['location']): ?><li class="project-meta_pill"><i class="fa-solid fa-location-dot fa-fw"></i><?php echo htmlspecialchars($project['location']); ?></li><?php endif; ?>
									<?php if ($project['completed_date']): ?><li class="project-meta_pill"><i class="fa-regular fa-calendar fa-fw"></i><?php echo htmlspecialchars(date('M Y', strtotime($project['completed_date']))); ?></li><?php endif; ?>
								</ul>
								<h3 class="blog-detail_heading"><?php echo htmlspecialchars($project['title']); ?></h3>
								<div class="detail-heading_row">
									<?php if ($project['client_name']): ?><div class="project-client_chip"><i class="fa-solid fa-user-tie fa-fw"></i>Client — <?php echo htmlspecialchars($project['client_name']); ?></div><?php endif; ?>
									<?php require_once __DIR__ . '/parts/share.php'; render_share_buttons($project['title']); ?>
								</div>
								<p class="project-detail_text"><?php echo nl2br(htmlspecialchars($project['story'] ?: '')); ?></p>

								<?php if (count($project_files) > 1): ?>
									<div class="project-detail_divider"></div>
									<h4 class="service-detail_subheading">Project Gallery</h4>
									<div class="row clearfix">
										<?php foreach (array_slice($project_files, 1) as $pf): ?>
											<div class="col-lg-4 col-md-6 col-sm-12 mb-3">
												<img src="<?php echo htmlspecialchars('Admin/' . $pf['file_path']); ?>" alt="" style="width:100%; border-radius:8px;">
											</div>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>

								<div class="project-detail_divider"></div>
								<?php
									require_once __DIR__ . '/parts/reviews.php';
									render_reviews_section($pdo, 'project', (int) $project['id'], 'project-detail.php?slug=' . urlencode($project['slug']));
								?>
						<?php endif; ?>
						</div>
					</div>
				</div>
				</div>

				<!-- Sidebar Side -->
                <div class="sidebar-side col-lg-4 col-md-12 col-sm-12">
                	<aside class="sidebar">
						<div class="sidebar-inner">

							<!-- Message Widget -->
							<div class="sidebar-widget message-widget">
								<div class="widget-content">
									<h5 class="sidebar-widget_title">Inquire About This Project</h5>
									<div class="message-form">
										<form method="post" action="send-message.php">
											<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
											<input type="hidden" name="project_id" value="<?php echo (int) ($project['id'] ?? 0); ?>">
											<input type="hidden" name="subject" value="Project inquiry: <?php echo htmlspecialchars($project['title'] ?? ''); ?>">
											<input type="hidden" name="redirect" value="project-detail.php?slug=<?php echo urlencode($project['slug'] ?? ''); ?>">

											<!-- Form Group -->
											<div class="form-group">
												<input type="text" name="name" value="" placeholder="Name*" required>
											</div>

											<!--Form Group-->
											<div class="form-group">
												<input type="email" name="email" value="" placeholder="Email*" required>
											</div>

											<!--Form Group-->
											<div class="form-group">
												<input type="tel" name="phone" value="" placeholder="Phone Number*" required>
											</div>

											<div class="form-group">
												<textarea class="" name="message" placeholder="Tell us more - project type, budget, timeline, etc." required></textarea>
											</div>

											<div class="form-group">
												<button type="submit" class="template-btn btn-style-one">
													<span class="btn-wrap">
														<span class="text-one">submit now</span>
														<span class="text-two">submit now</span>
													</span>
												</button>
											</div>

										</form>
									</div>
								</div>
							</div>

							<!-- Post Widget -->
							<div class="sidebar-widget post-widget">
								<div class="widget-content">
									<h5 class="sidebar-widget_title">Other Projects</h5>
									<?php foreach ($other_projects as $op): ?>
										<div class="post">
											<div class="thumb"><a href="project-detail.php?slug=<?php echo urlencode($op['slug']); ?>"><img src="<?php echo htmlspecialchars($op['cover_image'] ? 'Admin/' . $op['cover_image'] : 'assets/images/resource/post-thumb-4.jpg'); ?>" alt=""></a></div>
											<h6><a href="project-detail.php?slug=<?php echo urlencode($op['slug']); ?>"><?php echo htmlspecialchars($op['title']); ?></a></h6>
											<?php if ($op['category']): ?><div class="fs-12"><?php echo htmlspecialchars($op['category']); ?></div><?php endif; ?>
										</div>
									<?php endforeach; ?>
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
