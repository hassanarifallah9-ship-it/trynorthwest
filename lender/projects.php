<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('lender'));
$title = 'My Projects';

if (is_post() && csrf_check() && post('action') === 'create' && $u['company_id']) {
	db()->prepare('INSERT INTO projects (company_id, lender_user_id, name, address, city, state, zip, project_type, budget, contractor_name, status, notes) VALUES (?,?,?,?,?,?,?,?,?,?, "active", ?)')
		->execute(array($u['company_id'], $u['id'], post('name'), post('address'), post('city'), post('state'), post('zip'), post('project_type'), post('budget') ?: null, post('contractor_name'), post('notes')));
	log_activity('create', 'projects', db()->lastInsertId());
	flash_set('success', 'Project created.');
	redirect('/lender/projects.php');
}
$st = db()->prepare('SELECT * FROM projects WHERE company_id=? ORDER BY id DESC');
$st->execute(array($u['company_id']));
$rows = $st->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<div class="card">
	<h3>Add project</h3>
	<form method="post">
		<?php echo csrf_field(); ?><input type="hidden" name="action" value="create">
		<div class="row">
			<div><label>Name</label><input name="name" required></div>
			<div><label>Type</label><select name="project_type"><option>residential</option><option>commercial</option><option>land development</option></select></div>
			<div><label>Address</label><input name="address" required></div>
			<div><label>City</label><input name="city" required></div>
			<div><label>State</label><input name="state" required></div>
			<div><label>ZIP</label><input name="zip" required></div>
			<div><label>Budget</label><input name="budget" type="number" step="0.01"></div>
			<div><label>Contractor</label><input name="contractor_name"></div>
			<div><label>Notes</label><input name="notes"></div>
		</div>
		<button class="btn" type="submit">Save project</button>
	</form>
</div>
<table class="data" style="margin-top:20px">
	<tr><th>Project</th><th>Location</th><th>Type</th><th>Budget</th><th>Status</th></tr>
	<?php foreach ($rows as $r): ?>
	<tr>
		<td><?php echo e($r['name']); ?></td>
		<td><?php echo e($r['address'] . ', ' . $r['city'] . ' ' . $r['state']); ?></td>
		<td><?php echo e(status_label($r['project_type'])); ?></td>
		<td><?php echo $r['budget'] ? '$' . number_format($r['budget'], 0) : '—'; ?></td>
		<td><?php echo badge($r['status']); ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
