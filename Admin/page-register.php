<?php 
	 require_once __DIR__ . '/config/dz.php';
?>
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
   <!-- PAGE TITLE HERE -->
	<title><?php echo $DexignZoneSettings['site_level']['site_title'] ?></title>
	<?php include 'elements/meta.php';?>
	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="<?php echo $DexignZoneSettings['site_level']['favicon']?>">
	<?php include 'elements/page-css.php'; ?>
	
</head>

<body class="h-100">
<div class="fix-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-6">
	 
                    <div class="card mb-0 h-auto">
													
												   
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <a href="index.php"><img class="logo-auth" src="assets/images/Mars-Logo.svg" alt=""></a>
                            </div>
                            <h4 class="text-center mb-4">Sign up your account</h4>
                            <form action="index.php">
                                <div class="form-group mb-4">
                                    <label class="form-label" for="username">Username</label>
                                    <input type="text" class="form-control" placeholder="Enter username" id="username">
                                </div>
                                <div class="form-group mb-4">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" class="form-control" placeholder="hello@example.com" id="email">
                                </div>
                                <div class="form-group mb-sm-4 mb-3">
									<label class="form-label">Password</label>
									<div class="position-relative">
										<input type="password" id="dz-password" class="form-control" value="123456">
										<span class="show-pass eye">
											<i class="fa fa-eye-slash"></i>
											<i class="fa fa-eye"></i>
										</span>
									</div>
								</div>
											  
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-block">Sign up</button>
									  
                                </div>
                            </form>
                            <div class="new-account mt-3">
                                <p>Already have an account? <a class="text-primary" href="page-login.php">Sign in</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!--**********************************
	Scripts
***********************************-->
<!-- Required vendors -->
<?php include 'elements/page-js.php'; ?>

</body>
</html>