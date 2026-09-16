<?php
require_once __DIR__ . '/includes/app.php';

$cfg = require __DIR__ . '/config/database.php';
$ok = false;
$error = '';
$log = array();

if (is_post()) {
	try {
		$pdo = new PDO('mysql:host=' . $cfg['host'] . ';charset=utf8mb4', $cfg['user'], $cfg['pass'], array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
		));
		$dbName = preg_replace('/[^a-z0-9_]/i', '', $cfg['name']);
		$pdo->exec('CREATE DATABASE IF NOT EXISTS `' . $dbName . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
		$pdo->exec('USE `' . $dbName . '`');
		$sql = file_get_contents(__DIR__ . '/sql/schema.sql');
		$sql = preg_replace('/^--.*$/m', '', $sql);
		$sql = preg_replace('/CREATE DATABASE.*?;/is', '', $sql);
		$sql = preg_replace('/USE\s+`?nwcc`?\s*;/i', '', $sql);
		$parts = array_filter(array_map('trim', explode(';', $sql)));
		foreach ($parts as $part) {
			if ($part === '') continue;
			$pdo->exec($part);
		}
		$log[] = 'Database and tables ready.';

		$count = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
		if ($count === 0) {
			$pdo->exec("INSERT INTO companies (name, type, phone, email, city, state, zip) VALUES
				('1st Security Bank', 'bank', '(800) 555-0101', 'lending@1stsecurity.example', 'Seattle', 'WA', '98101'),
				('Level Capital', 'private_lender', '(800) 555-0102', 'ops@level.example', 'Denver', 'CO', '80202'),
				('Heritage Credit Union', 'credit_union', '(800) 555-0103', 'loans@heritage.example', 'Portland', 'OR', '97201')");

			$adminH = password_hash('Admin@123', PASSWORD_DEFAULT);
			$lenderH = password_hash('Lender@123', PASSWORD_DEFAULT);
			$inspH = password_hash('Inspector@123', PASSWORD_DEFAULT);
			$staffH = password_hash('Staff@123', PASSWORD_DEFAULT);

			$ins = $pdo->prepare('INSERT INTO users (company_id, role, first_name, last_name, email, phone, password_hash, status) VALUES (?,?,?,?,?,?,?,?)');
			$ins->execute(array(null, 'admin', 'Keith', 'Admin', 'admin@trynorthwest.com', '(800) 698-3986', $adminH, 'active'));
			$ins->execute(array(null, 'staff', 'Jordan', 'QC', 'staff@trynorthwest.com', '(800) 698-3986', $staffH, 'active'));
			$ins->execute(array(1, 'lender', 'Sharon', 'Lee', 'lender@trynorthwest.com', '(206) 555-0144', $lenderH, 'active'));
			$ins->execute(array(null, 'inspector', 'Marcus', 'Field', 'inspector@trynorthwest.com', '(425) 555-0199', $inspH, 'active'));

			$pdo->exec("INSERT INTO inspector_profiles (user_id, certifications, experience_years, coverage_notes, status) VALUES
				(4, 'Certified Home Inspector, Licensed Contractor', 12, 'WA, OR, ID, CA', 'approved')");

			$pdo->exec("INSERT INTO coverage_zips (zip, city, state, status, inspector_user_id) VALUES
				('98101', 'Seattle', 'WA', 'covered', 4),
				('98104', 'Seattle', 'WA', 'covered', 4),
				('97201', 'Portland', 'OR', 'covered', 4),
				('80202', 'Denver', 'CO', 'covered', 4),
				('10001', 'New York', 'NY', 'nearby', NULL),
				('00000', 'International', 'XX', 'none', NULL)");

			$pdo->exec("INSERT INTO projects (company_id, lender_user_id, name, address, city, state, zip, project_type, budget, contractor_name, status, notes) VALUES
				(1, 3, 'Lakeview Custom Home', '412 Maple Ave', 'Seattle', 'WA', '98101', 'residential', 1850000, 'Puget Builders', 'active', 'Custom home draw monitoring'),
				(1, 3, 'Ballard Mixed-Use', '88 NW Market St', 'Seattle', 'WA', '98107', 'commercial', 9200000, 'Cascade Commercial', 'active', 'Retail + apartments')");

			$pdo->exec("INSERT INTO inspections (project_id, inspector_id, type, draw_number, requested_by, scheduled_date, due_date, status, notes) VALUES
				(1, 4, 'Residential Draw Inspection', 3, 3, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 2 DAY), 'assigned', 'Framing complete check'),
				(1, 4, 'Residential Draw Inspection', 2, 3, DATE_SUB(CURDATE(), INTERVAL 14 DAY), DATE_SUB(CURDATE(), INTERVAL 12 DAY), 'released', 'Foundation + slab'),
				(2, NULL, 'Commercial Draw Inspection', 1, 3, NULL, DATE_ADD(CURDATE(), INTERVAL 2 DAY), 'requested', 'First commercial draw')");

			$pdo->exec("INSERT INTO reports (inspection_id, summary, percent_complete, findings, qc_status, qc_by, released_at) VALUES
				(2, 'Foundation and slab work verified. Photos attached.', 28.00, 'Work matches draw request. No standing water.', 'approved', 2, DATE_SUB(NOW(), INTERVAL 12 DAY))");

			$pdo->exec("INSERT INTO invoices (company_id, project_id, inspection_id, amount, status, due_date, paid_at) VALUES
				(1, 1, 2, 375.00, 'paid', DATE_SUB(CURDATE(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY)),
				(1, 1, 1, 375.00, 'sent', DATE_ADD(CURDATE(), INTERVAL 15 DAY), NULL)");

			$pdo->exec("INSERT INTO leads (first_name, last_name, company, service, phone, email, message, source, status) VALUES
				('Angela', 'Nguyen', 'The Legacy Group', 'Residential Progress Inspections', '(425) 555-0110', 'angela@legacy.example', 'Need nationwide coverage for flips.', 'website', 'qualified')");

			$pdo->exec("INSERT INTO blog_posts (title, slug, excerpt, body, status, published_at) VALUES
				('What Lenders Should Look for in a Construction Lending Inspection Partner', 'inspection-partner', 'Funds should be released based on verified progress.', 'Construction lending is built on verified progress. Draw inspections protect collateral and keep disbursements aligned with work completed.', 'published', '2026-07-16'),
				('How Construction Draw Inspections Keep Projects on Track', 'draw-inspections-on-track', 'Site visits verify that requested funds match completed work.', 'A construction draw inspection is a site visit during construction to verify the draw request matches work completed.', 'published', '2025-10-20')");

			$pdo->exec("INSERT INTO settings (k, v) VALUES
				('company_name', 'NorthWest Construction Control'),
				('support_phone', '(800) 698-3986'),
				('support_email', 'info@trynorthwest.com')");

			$pdo->exec("INSERT INTO notifications (user_id, title, body, link) VALUES
				(3, 'Inspection assigned', 'Draw #3 on Lakeview Custom Home is assigned.', '/lender/inspections.php'),
				(4, 'New job assigned', 'Lakeview Custom Home — Draw 3', '/inspector/jobs.php')");

			$log[] = 'Demo users, projects, inspections, invoices, and coverage ZIP codes loaded.';
		} else {
			$log[] = 'Existing users left unchanged.';
		}
		$ok = true;
	} catch (Exception $e) {
		$error = $e->getMessage();
	}
}
?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>NWCC Setup</title>
	<link rel="stylesheet" href="<?php echo $APP_BASE; ?>/portal/assets/portal.css">
</head>
<body class="auth-body">
<div class="auth-card">
	<h1>Install NWCC backend</h1>
	<p class="muted">Creates MySQL database <strong>nwcc</strong> and demo data.</p>
	<?php if ($error): ?><div class="alert error"><?php echo e($error); ?></div><?php endif; ?>
	<?php foreach ($log as $line): ?><div class="alert success"><?php echo e($line); ?></div><?php endforeach; ?>
	<?php if ($ok): ?>
		<p><a class="btn" href="<?php echo $APP_BASE; ?>/login.php">Go to login</a></p>
	<?php else: ?>
		<form method="post"><button class="btn" type="submit">Create database &amp; seed</button></form>
	<?php endif; ?>
</div>
</body>
</html>
