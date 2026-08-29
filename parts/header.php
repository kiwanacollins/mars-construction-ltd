<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if (!isset($pdo)) {
    require_once __DIR__ . '/../Admin/config/db.php';
}
require_once __DIR__ . '/cart.php';

$site_settings = [];
foreach ($pdo->query('SELECT `key`, `value` FROM site_settings') as $row) {
    $site_settings[$row['key']] = $row['value'];
}

$menu_rows = $pdo->query('SELECT * FROM menu_items ORDER BY sort_order, id')->fetchAll();
$menu_by_parent = [];
foreach ($menu_rows as $row) {
    $menu_by_parent[$row['parent_id'] ?? 0][] = $row;
}

function render_menu_branch($menu_by_parent, $parent_id, $depth = 0) {
    $items = $menu_by_parent[$parent_id] ?? [];
    if (!$items) {
        return;
    }
    foreach ($items as $item) {
        $children = $menu_by_parent[$item['id']] ?? [];
        $has_children = !empty($children);
        $icon = !empty($item['icon']) ? '<i class="' . htmlspecialchars($item['icon']) . '" style="margin-right:6px;"></i>' : '';
        echo '<li' . ($has_children ? ' class="dropdown"' : '') . '>';
        echo '<a href="' . htmlspecialchars($item['url']) . '">' . $icon . htmlspecialchars($item['label']) . '</a>';
        if ($has_children) {
            echo '<ul>';
            render_menu_branch($menu_by_parent, $item['id'], $depth + 1);
            echo '</ul>';
        }
        echo '</li>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php
	if (!empty($home)) {
		echo 'Mars Construction';
	} elseif (isset($page_title)) {
		echo htmlspecialchars($page_title) . ' | Mars Construction';
	} else {
		echo 'Mars Construction';
	}
?></title>

<!-- Critical CSS: nav bar shape/color, inlined so it paints correctly on first render
     instead of flashing unstyled while style.css (which @imports header.css, global.css, etc.)
     and brand-overrides.css finish loading. Keep in sync with brand-overrides.css if those
     selectors change. -->
<style>
:root {
	--main-color: rgb(28,157,178);
	--main-color-rgb: 28,157,178;
	--secondary-color: rgb(143,35,29);
	--color-two: rgb(23,27,42);
	--color-three: rgb(245,245,245);
	--white-color: rgb(255,255,255);
}
.main-header .header-lower { background-color: var(--white-color); }
.main-header .header-lower .inner-container > .d-flex { position: relative; }
.main-header .header-lower .nav-outer { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); }
.category-bar { background-color: transparent; position: relative; z-index: 2; padding: 14px 0 26px; }
.category-bar_inner { display: flex; align-items: center; justify-content: center; background-color: var(--main-color); border-radius: 50px; padding: 8px 8px 8px 0; max-width: 100%; }
.category-bar_list { list-style: none; margin: 0; padding: 0; display: flex; align-items: center; flex-wrap: wrap; justify-content: center; }
.category-bar_list li { margin: 0; position: relative; }
.category-bar_list li a { display: flex; align-items: center; gap: 8px; padding: 13px 18px; color: rgba(255,255,255,0.92); font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; white-space: nowrap; }
.category-bar_home a { background-color: var(--secondary-color); border-radius: 50px; padding: 13px 24px 13px 34px; position: relative; }
.category-bar_home a i { color: var(--white-color); }
.category-bar_quote { display: flex; align-items: center; gap: 10px; flex-shrink: 0; margin-left: 10px; padding: 14px 26px; background-color: var(--white-color); color: var(--color-two); font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; border-radius: 50px; white-space: nowrap; }
.header-quicklinks { display: flex; align-items: center; flex-wrap: wrap; list-style: none; margin: 0; padding: 10px 28px; background-color: var(--color-three); border-radius: 50px; }
</style>

<!-- Stylesheets -->
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
<link href="assets/css/responsive.css" rel="stylesheet">
<link href="assets/css/brand-overrides.css?v=<?php echo filemtime(__DIR__ . '/../assets/css/brand-overrides.css'); ?>" rel="stylesheet">

<?php
$current_page_key = basename($_SERVER['SCRIPT_NAME']);
$pbstmt = $pdo->prepare('SELECT image FROM page_title_backgrounds WHERE page_key = ?');
$pbstmt->execute([$current_page_key]);
$page_title_bg = $pbstmt->fetchColumn();
?>
<?php if ($page_title_bg): ?>
<style>.page-title{ background-image:url('<?php echo htmlspecialchars('Admin/' . $page_title_bg); ?>'); background-size:cover; background-position:center; }</style>
<?php endif; ?>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="shortcut icon" href="assets/images/Mars-Logo.png" type="image/x-icon">
<link rel="icon" href="assets/images/Mars-Logo.png" type="image/x-icon">

<!-- Responsive -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

<!--[if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script><![endif]-->
<!--[if lt IE 9]><script src="js/respond.js"></script><![endif]-->
</head>

<body>

<div class="page-wrapper">

	<!-- Cursor -->
	<div class="cursor"></div>
	<div class="cursor-follower"></div>
	<!-- Cursor End -->

	<!-- Main Header -->
	<header class="main-header<?php echo empty($home) ? ' header-style-four' : ''; ?>">

		<!-- Header Lower -->
		<div class="header-lower">
			<div class="auto-container">
				<div class="inner-container">
					<div class="d-flex justify-content-between align-items-center flex-wrap">

						<!-- Logo Box -->
						<div class="logo-box">
							<div class="logo"><a href="index.php"><img src="<?php echo !empty($site_settings['header_logo']) ? htmlspecialchars('Admin/' . $site_settings['header_logo']) : 'assets/images/Mars-web-Logo.svg'; ?>" alt="" title=""></a></div>
						</div>
						<!-- End Logo Box -->

						<!-- Nav Outer -->
						<div class="nav-outer d-flex align-items-center flex-wrap">
							<!-- Main Menu -->
							<nav class="main-menu navbar-expand-md">
								<div class="navbar-header">
									<!-- Toggle Button -->
									<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
									</button>
								</div>

								<div class="navbar-collapse collapse clearfix" id="navbarSupportedContent">
									<ul class="navigation clearfix header-quicklinks">
										<li><a href="plans.php">House Plans</a></li>
										<li><a href="construction.php">General Construction</a></li>
										<li><a href="property-management.php">Property Management</a></li>
									</ul>
								</div>
							</nav>
						</div>
						<!-- End Nav Outer -->

						<!-- Outer Box -->
						<div class="outer-box d-flex align-items-center flex-wrap">

							<!-- Header Options Box -->
							<div class="header-options_box d-flex align-items-center">

								<!-- Contact Icons -->
								<?php if (!empty($site_settings['header_phone'])): ?>
									<a href="tel:<?php echo htmlspecialchars($site_settings['header_phone']); ?>" class="header-contact-icon" title="Call Us"><i class="fa-solid fa-phone"></i></a>
								<?php endif; ?>
								<?php if (!empty($site_settings['header_video_url'])): ?>
									<a href="<?php echo htmlspecialchars($site_settings['header_video_url']); ?>" class="header-contact-icon" title="Video Call" target="_blank" rel="noopener"><i class="fa-solid fa-video"></i></a>
								<?php endif; ?>
								<?php if (!empty($site_settings['header_whatsapp_url'])): ?>
									<a href="<?php echo htmlspecialchars($site_settings['header_whatsapp_url']); ?>" class="header-contact-icon header-contact-icon--whatsapp" title="WhatsApp" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i></a>
								<?php endif; ?>

								<!-- Search Btn -->
								<div class="search-box-btn search-box-outer"><span class="icon"><img src="assets/images/icons/search.svg" alt="" /></span></div>

								<!-- Nav Btn -->
								<div class="nav-btn navSidebar-button">
									<i class="fa-solid fa-cart-shopping"></i>
									<?php if (cart_count() > 0): ?><span class="cart-count-badge"><?php echo cart_count(); ?></span><?php endif; ?>
								</div>

							</div>

							<!-- Mobile Navigation Toggler -->
							<div class="mobile-nav-toggler"><span class="icon flaticon-menu"></span></div>
						</div>
						<!-- End Outer Box -->

					</div>
				</div>
			</div>
		</div>
		<!-- End Header Lower -->

		<!-- Mobile Menu  -->
		<div class="mobile-menu">
			<div class="menu-backdrop"></div>
			<div class="close-btn"><span class="icon flaticon-close-1"></span></div>

			<nav class="menu-box">
				<div class="nav-logo"><a href="index.php"><img src="assets/images/Mars-Logo.svg" alt="" title=""></a></div>
				<div class="menu-outer"><!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header--></div>
			</nav>
		</div>
		<!-- End Mobile Menu -->

	</header>
	<?php require_once __DIR__ . '/category-bar.php'; ?>
