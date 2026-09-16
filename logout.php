<?php
require_once __DIR__ . '/includes/app.php';
$_SESSION = array();
session_destroy();
header('Location: ' . $APP_BASE . '/login.php');
exit;
