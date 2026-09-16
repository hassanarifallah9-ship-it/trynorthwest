<?php
require_once __DIR__ . '/includes/app.php';

if (current_user()) {
	redirect(portal_home(current_user()['role']));
}

$error = '';
if (is_post()) {
	$email = strtolower(post('email'));
	$pass = isset($_POST['password']) ? $_POST['password'] : '';
	try {
		$st = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
		$st->execute(array($email));
		$user = $st->fetch();
		if (!$user || !password_verify($pass, $user['password_hash'])) {
			$error = 'Invalid email or password.';
		} elseif ($user['status'] !== 'active') {
			$error = 'Account is pending or inactive.';
		} else {
			$_SESSION['user_id'] = (int)$user['id'];
			db()->prepare('UPDATE users SET last_login = NOW() WHERE id = ?')->execute(array($user['id']));
			log_activity('login', 'users', $user['id']);
			$go = !empty($_SESSION['after_login']) ? $_SESSION['after_login'] : ($APP_BASE . portal_home($user['role']));
			unset($_SESSION['after_login']);
			header('Location: ' . $go);
			exit;
		}
	} catch (Exception $e) {
		$error = 'Database not ready. Run setup first.';
	}
}
$flash = flash_get();
?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>User Login | NWCC</title>
	<link rel="icon" type="image/png" href="<?php echo $APP_BASE; ?>/images/sites/ncc/NWCC-favicon.png">
	<link rel="stylesheet" href="<?php echo $APP_BASE; ?>/portal/assets/portal.css">
</head>
<body class="auth-body">
<div class="auth-card">
	<a href="<?php echo $APP_BASE; ?>/" class="auth-logo">NWCC</a>
	<h1>User Login</h1>
	<p class="muted">Access projects, inspections, and reports.</p>
	<?php if ($flash): ?><div class="alert <?php echo e($flash['type']); ?>"><?php echo e($flash['msg']); ?></div><?php endif; ?>
	<?php if ($error): ?><div class="alert error"><?php echo e($error); ?></div><?php endif; ?>
	<form method="post">
		<label>Email</label>
		<input type="email" name="email" required placeholder="you@company.com">
		<label>Password</label>
		<input type="password" name="password" required>
		<button class="btn" type="submit">Sign in</button>
	</form>
	<p><a href="<?php echo $APP_BASE; ?>/">Back to website</a></p>
</div>
</body>
</html>
