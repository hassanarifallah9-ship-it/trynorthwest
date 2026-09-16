<?php
if (!isset($u)) $u = current_user();
$role = $u['role'];
$title = isset($title) ? $title : 'Dashboard';
$nav = array();
if ($role === 'admin' || $role === 'staff') {
	$prefix = $APP_BASE . '/admin/';
	$nav = array(
		'index.php' => 'Dashboard',
		'leads.php' => 'Leads',
		'projects.php' => 'Projects',
		'inspections.php' => 'Inspections',
		'reports.php' => 'Reports / QC',
		'inspectors.php' => 'Inspectors',
		'coverage.php' => 'Coverage ZIPs',
		'invoices.php' => 'Invoices',
		'companies.php' => 'Companies',
		'applications.php' => 'Applications',
		'blog.php' => 'Blog',
		'messages.php' => 'Messages',
	);
	if ($role === 'admin') {
		$nav['users.php'] = 'Users';
	}
} elseif ($role === 'lender') {
	$prefix = $APP_BASE . '/lender/';
	$nav = array(
		'index.php' => 'Dashboard',
		'projects.php' => 'My Projects',
		'inspections.php' => 'Inspections',
		'reports.php' => 'Reports',
		'invoices.php' => 'Invoices',
		'messages.php' => 'Messages',
	);
} else {
	$prefix = $APP_BASE . '/inspector/';
	$nav = array(
		'index.php' => 'Dashboard',
		'jobs.php' => 'My Jobs',
		'reports.php' => 'My Reports',
		'profile.php' => 'Profile',
	);
}
$file = basename($_SERVER['PHP_SELF']);
$flash = flash_get();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo e($title); ?> | NWCC</title>
	<link rel="icon" type="image/png" href="<?php echo $APP_BASE; ?>/images/sites/ncc/NWCC-favicon.png">
	<link rel="stylesheet" href="<?php echo $APP_BASE; ?>/portal/assets/portal.css?v=layout5">
</head>
<body>
<div class="backdrop" id="navBackdrop"></div>
<div class="shell">
	<aside class="side" id="sideNav">
		<a class="brand" href="<?php echo $prefix; ?>index.php">NWCC <span>Portal</span></a>
		<nav>
			<?php foreach ($nav as $href => $label): ?>
			<a class="<?php echo $file === $href ? 'active' : ''; ?>" href="<?php echo $prefix . $href; ?>"><?php echo e($label); ?></a>
			<?php endforeach; ?>
			<a href="<?php echo $APP_BASE; ?>/">Public website</a>
			<a href="<?php echo $APP_BASE; ?>/logout.php">Log out</a>
		</nav>
		<div class="who"><?php echo e($u['first_name'] . ' ' . $u['last_name']); ?><br><?php echo e(status_label($u['role'])); ?><?php echo $u['company_name'] ? '<br>' . e($u['company_name']) : ''; ?></div>
	</aside>
	<div class="main">
		<div class="topbar">
			<button class="menu-toggle" id="menuToggle" type="button" aria-label="Open menu">☰</button>
			<h2><?php echo e($title); ?></h2>
			<span class="email"><?php echo e($u['email']); ?></span>
		</div>
		<div class="content">
			<?php if ($flash): ?><div class="alert <?php echo e($flash['type'] === 'error' ? 'error' : 'success'); ?>"><?php echo e($flash['msg']); ?></div><?php endif; ?>
