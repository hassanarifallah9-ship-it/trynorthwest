<?php
require_once dirname(__DIR__) . '/includes/app.php';
header('Content-Type: text/html; charset=utf-8');

$type = post('type') === 'career' ? 'career' : 'inspector';
$name = post('name');
$email = post('email');
$phone = post('phone');
$message = post('message');
if ($name === '' || $email === '') {
	flash_set('error', 'Name and email are required.');
	redirect($type === 'career' ? '/careers' : '/inspectors');
}
try {
	db()->prepare('INSERT INTO applications (type, name, email, phone, message) VALUES (?,?,?,?,?)')
		->execute(array($type, $name, $email, $phone, $message));
	$admins = db()->query("SELECT id FROM users WHERE role IN ('admin','staff') AND status='active'")->fetchAll();
	foreach ($admins as $a) notify($a['id'], 'New ' . $type . ' application', $name, '/admin/applications.php');
} catch (Exception $e) {}
flash_set('success', 'Thank you. We will review your information and connect soon.');
redirect($type === 'career' ? '/careers' : '/inspectors');
