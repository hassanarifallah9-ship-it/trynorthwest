<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('admin', 'staff'));
$title = 'Coverage ZIPs';

if (is_post() && csrf_check()) {
	if (post('action') === 'create') {
		db()->prepare('INSERT INTO coverage_zips (zip, city, state, status) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE city=VALUES(city), state=VALUES(state), status=VALUES(status)')
			->execute(array(post('zip'), post('city'), post('state'), post('status')));
		flash_set('success', 'Coverage saved.');
		redirect('/admin/coverage.php');
	}
	if (post('action') === 'delete') {
		db()->prepare('DELETE FROM coverage_zips WHERE id=?')->execute(array((int)post('id')));
		redirect('/admin/coverage.php');
	}
}
$rows = db()->query('SELECT * FROM coverage_zips ORDER BY state, city, zip')->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<div class="card">
	<form method="post">
		<?php echo csrf_field(); ?><input type="hidden" name="action" value="create">
		<div class="row">
			<div><label>ZIP</label><input name="zip" required></div>
			<div><label>City</label><input name="city" required></div>
			<div><label>State</label><input name="state" required></div>
			<div><label>Status</label>
				<select name="status"><option>covered</option><option>nearby</option><option>none</option></select></div>
		</div>
		<button class="btn" type="submit">Add / update ZIP</button>
	</form>
</div>
<table class="data" style="margin-top:20px">
	<tr><th>ZIP</th><th>City</th><th>State</th><th>Status</th><th></th></tr>
	<?php foreach ($rows as $r): ?>
	<tr>
		<td><?php echo e($r['zip']); ?></td>
		<td><?php echo e($r['city']); ?></td>
		<td><?php echo e($r['state']); ?></td>
		<td><?php echo badge($r['status']); ?></td>
		<td>
			<form method="post"><?php echo csrf_field(); ?>
				<input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
				<button class="btn small danger">Delete</button>
			</form>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
