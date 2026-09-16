<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('admin', 'staff'));
$title = 'Projects';

$companies = db()->query('SELECT id, name FROM companies WHERE status="active" ORDER BY name')->fetchAll();
$lenders = db()->query('SELECT id, first_name, last_name FROM users WHERE role="lender" AND status="active" ORDER BY last_name')->fetchAll();

if (is_post() && csrf_check()) {
	if (post('action') === 'create') {
		db()->prepare('INSERT INTO projects (company_id, lender_user_id, name, address, city, state, zip, project_type, budget, contractor_name, status, notes) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)')
			->execute(array(
				(int)post('company_id'), post('lender_user_id') ?: null, post('name'), post('address'), post('city'), post('state'), post('zip'),
				post('project_type'), post('budget') ?: null, post('contractor_name'), post('status'), post('notes')
			));
		log_activity('create', 'projects', db()->lastInsertId());
		flash_set('success', 'Project created.');
		redirect('/admin/projects.php');
	}
	if (post('action') === 'status') {
		db()->prepare('UPDATE projects SET status=? WHERE id=?')->execute(array(post('status'), (int)post('id')));
		flash_set('success', 'Project updated.');
		redirect('/admin/projects.php');
	}
}
$rows = db()->query('SELECT p.*, c.name company_name FROM projects p JOIN companies c ON c.id=p.company_id ORDER BY p.id DESC')->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<div class="card">
	<h3>New project</h3>
	<form method="post">
		<?php echo csrf_field(); ?>
		<input type="hidden" name="action" value="create">
		<div class="row">
			<div><label>Company</label>
				<select name="company_id" required><?php foreach ($companies as $c): ?><option value="<?php echo (int)$c['id']; ?>"><?php echo e($c['name']); ?></option><?php endforeach; ?></select></div>
			<div><label>Lender user</label>
				<select name="lender_user_id"><option value="">—</option><?php foreach ($lenders as $l): ?><option value="<?php echo (int)$l['id']; ?>"><?php echo e($l['first_name'].' '.$l['last_name']); ?></option><?php endforeach; ?></select></div>
			<div><label>Name</label><input type="text" name="name" required></div>
			<div><label>Type</label>
				<select name="project_type"><option>residential</option><option>commercial</option><option>land development</option></select></div>
			<div><label>Address</label><input type="text" name="address" required></div>
			<div><label>City</label><input type="text" name="city" required></div>
			<div><label>State</label><input type="text" name="state" required></div>
			<div><label>ZIP</label><input type="text" name="zip" required></div>
			<div><label>Budget</label><input name="budget" type="number" step="0.01"></div>
			<div><label>Contractor</label><input type="text" name="contractor_name"></div>
			<div><label>Status</label>
				<select name="status"><option>active</option><option>on_hold</option><option>completed</option></select></div>
			<div><label>Notes</label><textarea name="notes" rows="2"></textarea></div>
		</div>
		<button class="btn" type="submit">Create project</button>
	</form>
</div>
<table class="data" style="margin-top:20px">
	<tr><th>ID</th><th>Project</th><th>Company</th><th>Location</th><th>Type</th><th>Status</th><th></th></tr>
	<?php foreach ($rows as $r): ?>
	<tr>
		<td><?php echo (int)$r['id']; ?></td>
		<td><?php echo e($r['name']); ?></td>
		<td><?php echo e($r['company_name']); ?></td>
		<td><?php echo e($r['city'] . ', ' . $r['state'] . ' ' . $r['zip']); ?></td>
		<td><?php echo e(status_label($r['project_type'])); ?></td>
		<td><?php echo badge($r['status']); ?></td>
		<td>
			<form method="post"><?php echo csrf_field(); ?>
				<input type="hidden" name="action" value="status"><input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
				<select name="status">
					<?php foreach (array('active','on_hold','completed','cancelled') as $s): ?>
					<option <?php echo $r['status']===$s?'selected':''; ?>><?php echo $s; ?></option>
					<?php endforeach; ?>
				</select>
				<button class="btn small">Update</button>
			</form>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
