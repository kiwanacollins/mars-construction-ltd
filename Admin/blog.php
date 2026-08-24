<?php
	 require_once __DIR__ . '/config/dz.php';

	 $posts = $pdo->query('SELECT * FROM blog_posts ORDER BY created_at DESC')->fetchAll();

	 if (isset($_SESSION['form_status_message'])) {
		 $form_status_message = $_SESSION['form_status_message'];
		 unset($_SESSION['form_status_message']);
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
                <div class="page-titles">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="javascript:void(0)">CMS</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Blog</a></li>
					</ol>
                </div>
                <!-- Row -->
				<div class="row">
					<div class="col-xl-12">
						<div class="filter cm-content-box box-primary">
							<div class="content-title SlideToolHeader">
								<div class="cpa">
									<i class="fa-sharp fa-solid fa-filter me-2"></i>Filter
								</div>
								<div class="tools">
									<a href="javascript:void(0);" class="expand handle"><i class="fal fa-angle-down"></i></a>
								</div>
							</div>
							<div class="cm-content-body form excerpt">
								<div class="card-body">
									<div class="row">
										<div class="col-xl-3 col-sm-6">
											<label class="form-label">Title</label>
											<input type="text" class="form-control mb-xl-0 mb-3" id="exampleFormControlInput1" placeholder="Title">
										</div>
										<div class="col-xl-3  col-sm-6 mb-3 mb-xl-0">
											<label class="form-label">Status</label>
											<select class="form-control default-select h-auto wide" aria-label="Default select example">
												<option selected>Select Status</option>
												<option value="1">Published</option>
												<option value="2">Draft</option>
												<option value="3">Trash</option>
												<option value="4">Private</option>
												<option value="5">Pending</option>
											</select> 
										</div>
										<div class="col-xl-3 col-sm-6">
											<label class="form-label">Date</label>
											<div class="input-hasicon mb-sm-0 mb-3">
												<input  name="datepicker" class="form-control bt-datepicker">
												<div class="icon"><i class="far fa-calendar"></i></div>
											</div>
										</div>
										<div class="col-xl-3 col-sm-6 align-self-end">
											<div>
												<button class="btn btn-primary rounded-sm me-2" title="Click here to Search" type="button"><i class="fa fa-search me-1"></i>Filter</button>
												<button class="btn btn-danger rounded-sm light" title="Click here to remove filter" type="button">Remove Filter</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="mb-4 pb-3">
							<ul class="d-flex align-items-center flex-wrap">
								<li><a href="add-blog.php" class="btn btn-primary btn-sm">Add Blog</a></li>
							</ul>
						</div>
						<div class="filter cm-content-box box-primary">
							<div class="content-title SlideToolHeader">
								<div class="cpa">
									<i class="fa-solid fa-file-lines me-1"></i>Blogs List
								</div>
								<div class="tools">
									<a href="javascript:void(0);" class="expand handle"><i class="fal fa-angle-down"></i></a>
								</div>
							</div>
							<div class="cm-content-body form excerpt">
								<div class="card-body pb-4">
									<div class="table-responsive">
										<table class="table">
											<thead>
												<tr>
													<th>S.No</th>
													<th>Title</th>
													<th>Status</th>
													<th>Modified</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
											<?php if (!$posts): ?>
												<tr><td colspan="5" class="text-center">No blog posts yet. <a href="add-blog.php">Write your first post</a>.</td></tr>
											<?php endif; ?>
											<?php foreach ($posts as $i => $post): ?>
												<tr>
													<td><?php echo $i + 1; ?></td>
													<td><?php echo htmlspecialchars($post['title']); ?></td>
													<td><?php echo htmlspecialchars(ucfirst($post['status'])); ?></td>
													<td><?php echo htmlspecialchars(date('d M, Y', strtotime($post['updated_at']))); ?></td>
													<td class="text-nowrap">
														<a href="add-blog.php?id=<?php echo $post['id']; ?>" class="btn btn-warning btn-sm content-icon">
															<i class="fa-solid fa-pen-to-square"></i>
														</a>
														<a href="blog-delete.php?id=<?php echo $post['id']; ?>" class="btn btn-danger btn-sm content-icon" onclick="return confirm('Delete this post?');">
															<i class="fa-solid fa-trash"></i>
														</a>
													</td>
												</tr>
											<?php endforeach; ?>
												
											</tbody>
										</table>
										<div class="d-flex align-items-center justify-content-between flex-wrap">
											<p class="mb-2 me-3">Page 1 of 5, showing 2 records out of 8 total, starting on record 1, ending on 2</p>
											<nav aria-label="Page navigation example mb-2">
											  <ul class="pagination mb-2 mb-sm-0">
												<li class="page-item"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-angle-left"></i></a></li>
												<li class="page-item"><a class="page-link" href="javascript:void(0);">1</a></li>
												<li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
												<li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
												<li class="page-item"><a class="page-link " href="javascript:void(0);"><i class="fa-solid fa-angle-right"></i></a></li>
											  </ul>
											</nav>
										</div>
									</div>
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
	
	<!--removeIf(production)-->
        
    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
   <?php include 'elements/page-js.php'; ?>

		
</body>

</html>