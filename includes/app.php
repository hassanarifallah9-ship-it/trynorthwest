<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

$APP_BASE = '/trynorthwest';
$ROOT = dirname(__DIR__);

function db() {
	static $pdo = null;
	if ($pdo) return $pdo;
	$cfg = require dirname(__DIR__) . '/config/database.php';
	$dsn = 'mysql:host=' . $cfg['host'] . ';dbname=' . $cfg['name'] . ';charset=' . $cfg['charset'];
	$pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	));
	return $pdo;
}

function db_available() {
	try {
		db();
		return true;
	} catch (Exception $e) {
		return false;
	}
}

function e($v) {
	return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function redirect($path) {
	global $APP_BASE;
	if (strpos($path, 'http') === 0) {
		header('Location: ' . $path);
	} else {
		header('Location: ' . $APP_BASE . $path);
	}
	exit;
}

function flash_set($type, $msg) {
	$_SESSION['flash'] = array('type' => $type, 'msg' => $msg);
}

function flash_get() {
	if (empty($_SESSION['flash'])) return null;
	$f = $_SESSION['flash'];
	unset($_SESSION['flash']);
	return $f;
}

function current_user() {
	if (empty($_SESSION['user_id'])) return null;
	static $u = null;
	if ($u) return $u;
	$st = db()->prepare('SELECT u.*, c.name AS company_name FROM users u LEFT JOIN companies c ON c.id = u.company_id WHERE u.id = ?');
	$st->execute(array($_SESSION['user_id']));
	$u = $st->fetch();
	return $u ?: null;
}

function require_login($roles = null) {
	global $APP_BASE;
	$u = current_user();
	if (!$u) {
		$_SESSION['after_login'] = $_SERVER['REQUEST_URI'];
		header('Location: ' . $APP_BASE . '/login.php');
		exit;
	}
	if ($u['status'] !== 'active') {
		$_SESSION['user_id'] = null;
		flash_set('error', 'Your account is not active.');
		header('Location: ' . $APP_BASE . '/login.php');
		exit;
	}
	if ($roles) {
		$roles = (array)$roles;
		if (!in_array($u['role'], $roles, true)) {
			header('HTTP/1.1 403 Forbidden');
			echo 'Access denied.';
			exit;
		}
	}
	return $u;
}

function portal_home($role) {
	if ($role === 'inspector') return '/inspector/index.php';
	if ($role === 'lender') return '/lender/index.php';
	return '/admin/index.php';
}

function log_activity($action, $entity = null, $entity_id = null, $meta = null) {
	try {
		$st = db()->prepare('INSERT INTO activity_log (user_id, action, entity, entity_id, meta) VALUES (?,?,?,?,?)');
		$st->execute(array(
			empty($_SESSION['user_id']) ? null : $_SESSION['user_id'],
			$action, $entity, $entity_id, $meta
		));
	} catch (Exception $e) {}
}

function notify($user_id, $title, $body = '', $link = '') {
	$st = db()->prepare('INSERT INTO notifications (user_id, title, body, link) VALUES (?,?,?,?)');
	$st->execute(array($user_id, $title, $body, $link));
}

function post($key, $default = '') {
	return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}

function getv($key, $default = '') {
	return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
}

function is_post() {
	return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function csrf_token() {
	if (empty($_SESSION['csrf'])) {
		$_SESSION['csrf'] = bin2hex(random_bytes(16));
	}
	return $_SESSION['csrf'];
}

function csrf_field() {
	return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check() {
	if (empty($_POST['csrf']) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
		flash_set('error', 'Invalid session token. Please try again.');
		return false;
	}
	return true;
}

function status_label($s) {
	$map = array(
		'new' => 'New', 'contacted' => 'Contacted', 'qualified' => 'Qualified', 'converted' => 'Converted', 'closed' => 'Closed',
		'requested' => 'Requested', 'assigned' => 'Assigned', 'in_progress' => 'In Progress', 'submitted' => 'Submitted',
		'qc_review' => 'QC Review', 'released' => 'Released', 'cancelled' => 'Cancelled',
		'active' => 'Active', 'on_hold' => 'On Hold', 'completed' => 'Completed',
		'draft' => 'Draft', 'sent' => 'Sent', 'paid' => 'Paid', 'overdue' => 'Overdue',
		'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected',
		'covered' => 'Covered', 'nearby' => 'Nearby', 'none' => 'Not covered',
		'published' => 'Published', 'reviewing' => 'Reviewing', 'accepted' => 'Accepted',
		'bank' => 'Bank', 'credit_union' => 'Credit Union', 'private_lender' => 'Private Lender',
		'residential' => 'Residential', 'commercial' => 'Commercial', 'land development' => 'Land Development',
		'admin' => 'Admin', 'staff' => 'Staff', 'lender' => 'Lender', 'inspector' => 'Inspector',
		'inactive' => 'Inactive',
	);
	return isset($map[$s]) ? $map[$s] : ucfirst(str_replace('_', ' ', (string)$s));
}

function badge($status) {
	$s = strtolower((string)$status);
	$class = 'b-gray';
	if (in_array($s, array('released','paid','converted','approved','accepted','covered','active','published','complete'), true)) $class = 'b-green';
	if (in_array($s, array('requested','new','pending','draft','reviewing'), true)) $class = 'b-blue';
	if (in_array($s, array('qc_review','assigned','in_progress','submitted','contacted','qualified','nearby','sent'), true)) $class = 'b-amber';
	if (in_array($s, array('cancelled','rejected','overdue','none','closed','inactive'), true)) $class = 'b-red';
	return '<span class="badge ' . $class . '">' . e(status_label($status)) . '</span>';
}

function save_upload($field, $subdir) {
	global $ROOT;
	if (empty($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) return null;
	$allowed = array('jpg','jpeg','png','gif','webp','pdf','doc','docx');
	$ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
	if (!in_array($ext, $allowed, true)) return null;
	$dir = $ROOT . '/uploads/' . $subdir;
	if (!is_dir($dir)) mkdir($dir, 0777, true);
	$name = uniqid('f_', true) . '.' . $ext;
	$path = $dir . '/' . $name;
	if (!move_uploaded_file($_FILES[$field]['tmp_name'], $path)) return null;
	return 'uploads/' . $subdir . '/' . $name;
}
