<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('admin'));
$title = 'Users';

$companies = db()->query('SELECT id, name FROM companies ORDER BY name')->fetchAll();
if (is_post() && csrf_check()) {
	if (post('action') === 'create') {
		$hash = password_hash(post('password') ?: 'ChangeMe@123', PASSWORD_DEFAULT);
		db()->prepare('INSERT INTO users (company_id, role, first_name, last_name, email, phone, password_hash, status) VALUES (?,?,?,?,?,?,?,?)')
			->execute(array(post('company_id') ?: null, post('role'), post('first_name'), post('last_name'), strtolower(post('email')), post('phone'), $hash, post('status')));
		$uid = db()->lastInsertId();
		if (post('role') === 'inspector') {
			db()->prepare('INSERT INTO inspector_profiles (user_id, status) VALUES (?, "approved")')->execute(array($uid));
		}
		flash_set('success', 'User created.');
		redirect('/admin/users.php');
	}
	if (post('action') === 'status') {
		db()->prepare('UPDATE users SET status=? WHERE id=?')->execute(array(post('status'), (int)post('id')));
		redirect('/admin/users.php');
	}
}
$rows = db()->query('SELECT u.*, c.name company_name FROM users u LEFT JOIN companies c ON c.id=u.company_id ORDER BY u.id DESC')->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<div class="card">
	<h3>Add user</h3>
	<form method="post">
		<?php echo csrf_field(); ?><input type="hidden" name="action" value="create">
		<div class="row">
			<div><label>First</label><input name="first_name" required></div>
			<div><label>Last</label><input name="last_name" required></div>
			<div><label>Email</label><input type="email" name="email" required></div>
			<div><label>Phone</label><input name="phone"></div>
			<div><label>Role</label>
				<select name="role"><option>lender</option><option>inspector</option><option>staff</option><option>admin</option></select></div>
			<div><label>Company</label>
				<select name="company_id"><option value="">—</option><?php foreach ($companies as $c): ?><option value="<?php echo (int)$c['id']; ?>"><?php echo e($c['name']); ?></option><?php endforeach; ?></select></div>
			<div><label>Password</label><input name="password" placeholder="ChangeMe@123"></div>
			<div><label>Status</label><select name="status"><option>active</option><option>pending</option><option>inactive</option></select></div>
		</div>
		<button class="btn" type="submit">Create user</button>
	</form>
</div>
<table class="data" style="margin-top:20px">
	<tr><th>Name</th><th>Email</th><th>Role</th><th>Company</th><th>Status</th><th></th></tr>
	<?php foreach ($rows as $r): ?>
	<tr>
		<td><?php echo e($r['first_name'].' '.$r['last_name']); ?></td>
		<td><?php echo e($r['email']); ?></td>
		<td><?php echo e(status_label($r['role'])); ?></td>
		<td><?php echo e($r['company_name']); ?></td>
		<td><?php echo badge($r['status']); ?></td>
		<td>
			<form method="post"><?php echo csrf_field(); ?>
				<input type="hidden" name="action" value="status"><input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
				<select name="status"><option <?php echo $r['status']==='active'?'selected':''; ?>>active</option><option <?php echo $r['status']==='pending'?'selected':''; ?>>pending</option><option <?php echo $r['status']==='inactive'?'selected':''; ?>>inactive</option></select>
				<button class="btn small">Save</button>
			</form>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
