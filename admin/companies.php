<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('admin', 'staff'));
$title = 'Companies';

if (is_post() && csrf_check() && post('action') === 'create') {
	db()->prepare('INSERT INTO companies (name, type, phone, email, city, state, zip, status) VALUES (?,?,?,?,?,?,?,?)')
		->execute(array(post('name'), post('type'), post('phone'), post('email'), post('city'), post('state'), post('zip'), 'active'));
	flash_set('success', 'Company added.');
	redirect('/admin/companies.php');
}
$rows = db()->query('SELECT * FROM companies ORDER BY name')->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<div class="card">
	<form method="post">
		<?php echo csrf_field(); ?><input type="hidden" name="action" value="create">
		<div class="row">
			<div><label>Name</label><input name="name" required></div>
			<div><label>Type</label><select name="type"><option>bank</option><option>credit_union</option><option>private_lender</option><option>other</option></select></div>
			<div><label>Email</label><input type="email" name="email"></div>
			<div><label>Phone</label><input name="phone"></div>
			<div><label>City</label><input name="city"></div>
			<div><label>State</label><input name="state"></div>
			<div><label>ZIP</label><input name="zip"></div>
		</div>
		<button class="btn" type="submit">Add company</button>
	</form>
</div>
<table class="data" style="margin-top:20px">
	<tr><th>Name</th><th>Type</th><th>Contact</th><th>Location</th><th>Status</th></tr>
	<?php foreach ($rows as $r): ?>
	<tr>
		<td><?php echo e($r['name']); ?></td>
		<td><?php echo e(status_label($r['type'])); ?></td>
		<td><?php echo e($r['email']); ?><br><?php echo e($r['phone']); ?></td>
		<td><?php echo e($r['city'] . ' ' . $r['state']); ?></td>
		<td><?php echo badge($r['status']); ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
