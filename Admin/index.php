<?php
	 require_once __DIR__ . '/config/dz.php';

	 $total_plans = (int) $pdo->query('SELECT COUNT(*) c FROM properties')->fetch()['c'];
	 $total_projects = (int) $pdo->query('SELECT COUNT(*) c FROM projects')->fetch()['c'];
	 $total_blog_posts = (int) $pdo->query("SELECT COUNT(*) c FROM blog_posts WHERE status = 'published'")->fetch()['c'];
	 $total_messages = (int) $pdo->query('SELECT COUNT(*) c FROM messages')->fetch()['c'];
	 $unread_messages = (int) $pdo->query('SELECT COUNT(*) c FROM messages WHERE is_read = 0')->fetch()['c'];
	 $total_categories = (int) $pdo->query('SELECT COUNT(*) c FROM plan_categories')->fetch()['c'];

	 $recent_messages = $pdo->query('SELECT * FROM messages ORDER BY created_at DESC LIMIT 5')->fetchAll();
	 $recent_plans = $pdo->query(
		 "SELECT p.*, (SELECT file_path FROM property_files pf WHERE pf.property_id = p.id ORDER BY pf.is_cover DESC, pf.id ASC LIMIT 1) AS cover_image
		  FROM properties p ORDER BY p.created_at DESC LIMIT 5"
	 )->fetchAll();

	 $category_breakdown = $pdo->query(
		 "SELECT c.name, (SELECT COUNT(*) FROM properties p WHERE p.category = c.name) AS plan_count
		  FROM plan_categories c ORDER BY c.sort_order, c.name"
	 )->fetchAll();
	 $category_max = 1;
	 foreach ($category_breakdown as $cb) {
		 $category_max = max($category_max, (int) $cb['plan_count']);
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

    <!--*******************
        Preloader start
    ********************-->
    <?php include 'elements/pre-loader.php'; ?>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <?php include 'elements/nav-header.php'; ?>
        <!--**********************************
            Nav header end
        ***********************************-->

		<!--**********************************
            Chat box start
        ***********************************-->
		<?php include 'elements/chatbox.php'; ?>
		<!--**********************************
            Chat box End
        ***********************************-->

		<!--**********************************
            Header start
        ***********************************-->
        <?php include 'elements/header.php'; ?>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <?php include 'elements/sidebar.php'; ?>
        <!--**********************************
            Sidebar end
        ***********************************-->

		<!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                <div class="form-head d-md-flex mb-sm-4 mb-3 align-items-start">
					<div class="me-auto d-lg-block">
						<h2 class="text-black font-w600">Dashboard</h2>
						<p class="mb-0">Welcome to Mars Construction Admin</p>
					</div>
					<a href="index.php" class="btn btn-primary rounded light me-3">Refresh</a>
				</div>

				<!-- Stat Cards -->
				<div class="row">
					<div class="col-xl-3 col-md-6 col-sm-6">
						<a href="property-list.php" class="card text-decoration-none">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<span class="rounded me-3 bg-primary p-3"><i class="fa-solid fa-house text-white fs-20"></i></span>
									<div>
										<p class="fs-14 mb-1">Total Plans</p>
										<span class="fs-24 text-black font-w700"><?php echo number_format($total_plans); ?></span>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-xl-3 col-md-6 col-sm-6">
						<a href="project-list.php" class="card text-decoration-none">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<span class="rounded me-3 bg-success p-3"><i class="fa-solid fa-diagram-project text-white fs-20"></i></span>
									<div>
										<p class="fs-14 mb-1">Total Projects</p>
										<span class="fs-24 text-black font-w700"><?php echo number_format($total_projects); ?></span>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-xl-3 col-md-6 col-sm-6">
						<a href="blog.php" class="card text-decoration-none">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<span class="rounded me-3 bg-warning p-3"><i class="fa-solid fa-file-lines text-white fs-20"></i></span>
									<div>
										<p class="fs-14 mb-1">Blog Posts</p>
										<span class="fs-24 text-black font-w700"><?php echo number_format($total_blog_posts); ?></span>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-xl-3 col-md-6 col-sm-6">
						<a href="notifications.php?tab=messages" class="card text-decoration-none">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<span class="rounded me-3 bg-danger p-3"><i class="fa-solid fa-envelope text-white fs-20"></i></span>
									<div>
										<p class="fs-14 mb-1">Messages <?php if ($unread_messages > 0): ?><span class="badge badge-danger"><?php echo $unread_messages; ?> new</span><?php endif; ?></p>
										<span class="fs-24 text-black font-w700"><?php echo number_format($total_messages); ?></span>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>

				<div class="row">
					<!-- Recent Messages -->
					<div class="col-xl-6">
						<div class="card">
							<div class="card-header border-0 pb-0">
								<h3 class="fs-20 text-black">Recent Messages</h3>
								<a href="notifications.php?tab=messages" class="ms-auto fs-14">View All</a>
							</div>
							<div class="card-body pt-2">
								<?php if (!$recent_messages): ?>
									<p class="text-muted mb-0">No messages yet.</p>
								<?php endif; ?>
								<?php foreach ($recent_messages as $msg): ?>
									<div class="d-flex align-items-start pb-3 mb-3 border-bottom">
										<div>
											<h6 class="fs-15 text-black font-w600 mb-1">
												<?php echo htmlspecialchars($msg['name']); ?>
												<?php if (!$msg['is_read']): ?><span class="badge badge-danger ms-1">New</span><?php endif; ?>
											</h6>
											<p class="fs-13 mb-1"><?php echo htmlspecialchars($msg['subject'] ?: 'General inquiry'); ?></p>
											<span class="fs-12 text-muted"><?php echo htmlspecialchars(date('M d, Y g:i A', strtotime($msg['created_at']))); ?></span>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>

					<!-- Recent Plans -->
					<div class="col-xl-6">
						<div class="card">
							<div class="card-header border-0 pb-0">
								<h3 class="fs-20 text-black">Recent Plans</h3>
								<a href="property-list.php" class="ms-auto fs-14">View All</a>
							</div>
							<div class="card-body pt-2">
								<?php if (!$recent_plans): ?>
									<p class="text-muted mb-0">No plans yet.</p>
								<?php endif; ?>
								<?php foreach ($recent_plans as $plan): ?>
									<div class="d-flex align-items-center pb-3 mb-3 border-bottom">
										<img src="<?php echo htmlspecialchars($plan['cover_image'] ?: 'assets/images/property/1.jpg'); ?>" alt="" class="rounded me-3" style="width:52px;height:52px;object-fit:cover;">
										<div>
											<h6 class="fs-15 text-black font-w600 mb-1"><a href="add-property.php?id=<?php echo $plan['id']; ?>" class="text-black"><?php echo htmlspecialchars($plan['title']); ?></a></h6>
											<span class="fs-13 text-muted"><?php echo htmlspecialchars($plan['category'] ?: 'Uncategorized'); ?> &middot; $<?php echo number_format($plan['price'], 0); ?></span>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<!-- Plans by Category -->
					<div class="col-xl-6">
						<div class="card">
							<div class="card-header border-0 pb-0">
								<h3 class="fs-20 text-black">Plans by Category</h3>
								<a href="plan-categories.php" class="ms-auto fs-14">Manage</a>
							</div>
							<div class="card-body pt-2">
								<?php if (!$category_breakdown): ?>
									<p class="text-muted mb-0">No categories yet.</p>
								<?php endif; ?>
								<?php foreach ($category_breakdown as $cb): ?>
									<p class="mb-2 d-flex fs-15 text-black font-w500"><?php echo htmlspecialchars($cb['name']); ?>
										<span class="ms-auto text-dark fs-14"><?php echo (int) $cb['plan_count']; ?></span>
									</p>
									<div class="progress mb-3" style="height:8px">
										<div class="progress-bar bg-primary" style="width:<?php echo round(((int) $cb['plan_count'] / $category_max) * 100); ?>%; height:8px;" role="progressbar"></div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>

					<!-- Quick Links -->
					<div class="col-xl-6">
						<div class="card">
							<div class="card-header border-0 pb-0">
								<h3 class="fs-20 text-black">Quick Actions</h3>
							</div>
							<div class="card-body pt-2">
								<div class="row">
									<div class="col-6 mb-3"><a href="add-property.php" class="btn btn-outline-primary w-100"><i class="fa-solid fa-plus me-1"></i>Add Plan</a></div>
									<div class="col-6 mb-3"><a href="add-project.php" class="btn btn-outline-primary w-100"><i class="fa-solid fa-plus me-1"></i>Add Project</a></div>
									<div class="col-6 mb-3"><a href="add-blog.php" class="btn btn-outline-primary w-100"><i class="fa-solid fa-plus me-1"></i>Add Blog Post</a></div>
									<div class="col-6 mb-3"><a href="notifications.php?tab=messages" class="btn btn-outline-primary w-100"><i class="fa-solid fa-envelope me-1"></i>View Messages</a></div>
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <!--**********************************
            Footer start
        ***********************************-->
        <?php include 'elements/footer.php'; ?>
        <!--**********************************
            Footer end
        ***********************************-->

		<!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <?php include 'elements/page-js.php'; ?>

</body>
</html>
