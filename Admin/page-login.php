<?php
	 require_once __DIR__ . '/config/dz.php';
	 require_once __DIR__ . '/config/auth.php';

	 $login_error = '';
	 if (current_admin()) {
		 header('Location: index.php');
		 exit;
	 }
	 if (isset($_SESSION['login_error'])) {
		 $login_error = $_SESSION['login_error'];
		 unset($_SESSION['login_error']);
	 }
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
                            <h4 class="text-center mb-4">Sign in your account</h4>
                            <?php if ($login_error): ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($login_error); ?></div>
                            <?php endif; ?>
                            <form action="login-process.php" method="post">
                                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_GET['redirect'] ?? 'index.php'); ?>">
                                <div class="form-group mb-4">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="Enter email" id="email" required>
                                </div>
                               <div class="form-group mb-3 mb-sm-4">
									<label class="form-label">Password</label>
									<div class="position-relative">
										<input type="password" name="password" id="dz-password" class="form-control" placeholder="Password" required>
										<span class="show-pass eye">
											<i class="fa fa-eye-slash"></i>
											<i class="fa fa-eye"></i>
										</span>
									</div>
								</div>
                                <div class="form-row d-flex flex-wrap justify-content-between align-items-baseline mb-2">
                                    <div class="form-group mb-sm-4 mb-1">
                                        <div class="form-check custom-checkbox ms-1">
                                            <input type="checkbox" class="form-check-input" id="basic_checkbox_1">
                                            <label class="form-check-label" for="basic_checkbox_1">Remember my preference</label>
                                        </div>
											
                                    </div>
                                    <div class="form-group ms-2">
                                        <a href="page-forgot-password.php">Forgot Password?</a>
										
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                                </div>
                            </form>
                            <div class="new-account mt-3">
                                <p>Don't have an account? <a class="text-primary" href="page-register.php">Sign up</a></p>
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