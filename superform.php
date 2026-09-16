<?php
require_once __DIR__ . '/includes/app.php';
header('Content-Type: text/html; charset=utf-8');

function field($keys) {
	foreach ((array)$keys as $k) {
		if (isset($_POST[$k]) && $_POST[$k] !== '') return trim($_POST[$k]);
	}
	return '';
}

$first = field(array('First Name', 'first_name', 'first'));
$last = field(array('Last Name', 'last_name', 'last'));
$company = field(array('Company Name', 'company'));
$service = field(array('Select Service of Interest', 'service'));
$phone = field(array('Phone', 'phone'));
$email = field(array('Email', 'email'));
$message = field(array('message', 'Message'));

if ($first === '' || $email === '') {
	echo '<div class="form-success"><h3>Please include name and email.</h3></div>';
	exit;
}

try {
	db()->prepare('INSERT INTO leads (first_name, last_name, company, service, phone, email, message, source, status) VALUES (?,?,?,?,?,?,?,?, "new")')
		->execute(array($first, $last, $company, $service, $phone, $email, $message, 'website'));
	$admins = db()->query("SELECT id FROM users WHERE role IN ('admin','staff') AND status='active'")->fetchAll();
	foreach ($admins as $a) {
		notify($a['id'], 'New website lead', $first . ' ' . $last, '/admin/leads.php');
	}
	log_activity('lead', 'leads', db()->lastInsertId(), $email);
} catch (Exception $e) {
	echo '<div class="form-success"><h3>Sorry, we could not save your request.</h3><p>Please try again or call (800) 698-3986.</p></div>';
	exit;
}
?>
<div class="form-success">
	<h3>Thank you<?php echo $first ? ', ' . htmlspecialchars($first) : ''; ?>.</h3>
	<p>We received your request and will be in touch within one business day. Nationwide draw inspection turnaround is two business days.</p>
</div>
