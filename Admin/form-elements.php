<?php
	 require_once __DIR__ . '/config/dz.php';
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
						<li class="breadcrumb-item"><a href="javascript:void(0)">Forms</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Form Elements</a></li>
					</ol>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Basic Form Elements</h4>
                            </div>
                            <div class="card-body">
								<form>
									<div class="row">
										<div class="mb-3 col-lg-6">
											<label class="form-label">Text Input</label>
											<input type="text" class="form-control" placeholder="Text">
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Email Input</label>
											<input type="email" class="form-control" placeholder="name@example.com">
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Number Input</label>
											<input type="number" class="form-control" placeholder="0">
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Date Input</label>
											<input type="date" class="form-control">
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">Select</label>
											<select class="default-select form-control wide">
												<option>Option One</option>
												<option>Option Two</option>
												<option>Option Three</option>
											</select>
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label">File Upload</label>
											<input type="file" class="form-control">
										</div>
										<div class="mb-3 col-12">
											<label class="form-label">Textarea</label>
											<textarea class="form-control" rows="4" placeholder="Message"></textarea>
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label d-block">Checkboxes</label>
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="check1">
												<label class="form-check-label" for="check1">Option A</label>
											</div>
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="check2">
												<label class="form-check-label" for="check2">Option B</label>
											</div>
										</div>
										<div class="mb-3 col-lg-6">
											<label class="form-label d-block">Radio Buttons</label>
											<div class="form-check form-check-inline">
												<input type="radio" name="radio-demo" class="form-check-input" id="radio1">
												<label class="form-check-label" for="radio1">Option A</label>
											</div>
											<div class="form-check form-check-inline">
												<input type="radio" name="radio-demo" class="form-check-input" id="radio2">
												<label class="form-check-label" for="radio2">Option B</label>
											</div>
										</div>
										<div class="col-12">
											<button type="button" class="btn btn-primary">Submit</button>
											<button type="button" class="btn btn-danger light">Cancel</button>
										</div>
									</div>
								</form>
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
