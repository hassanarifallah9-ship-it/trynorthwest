<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('lender'));
$title = 'Inspections';

$st = db()->prepare('SELECT id, name FROM projects WHERE company_id=? AND status="active"');
$st->execute(array($u['company_id']));
$projects = $st->fetchAll();

if (is_post() && csrf_check() && post('action') === 'request') {
	db()->prepare('INSERT INTO inspections (project_id, type, draw_number, requested_by, due_date, status, notes) VALUES (?,?,?,?,?, "requested", ?)')
		->execute(array((int)post('project_id'), post('type'), (int)post('draw_number'), $u['id'], post('due_date') ?: null, post('notes')));
	$admins = db()->query("SELECT id FROM users WHERE role IN ('admin','staff') AND status='active'")->fetchAll();
	foreach ($admins as $a) notify($a['id'], 'New inspection request', post('type'), '/admin/inspections.php');
	log_activity('request', 'inspections', db()->lastInsertId());
	flash_set('success', 'Inspection requested. NWCC will assign an inspector.');
	redirect('/lender/inspections.php');
}

$st = db()->prepare('SELECT i.*, p.name project_name FROM inspections i JOIN projects p ON p.id=i.project_id WHERE p.company_id=? ORDER BY i.id DESC');
$st->execute(array($u['company_id']));
$rows = $st->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<div class="card">
	<h3>Request inspection</h3>
	<form method="post">
		<?php echo csrf_field(); ?><input type="hidden" name="action" value="request">
		<div class="row">
			<div><label>Project</label>
				<select name="project_id" required><?php foreach ($projects as $p): ?><option value="<?php echo (int)$p['id']; ?>"><?php echo e($p['name']); ?></option><?php endforeach; ?></select></div>
			<div><label>Type</label>
				<select name="type">
					<option>Residential Draw Inspection</option>
					<option>Commercial Draw Inspection</option>
					<option>Land Development Inspection</option>
					<option>Foundation Verification</option>
					<option>Pre-Construction Inspection</option>
					<option>Clear Lot Inspection</option>
					<option>Project Cost Review</option>
					<option>Contractor Review</option>
				</select>
			</div>
			<div><label>Draw #</label><input type="number" name="draw_number" value="1"></div>
			<div><label>Needed by</label><input type="date" name="due_date"></div>
			<div><label>Notes</label><input name="notes"></div>
		</div>
		<button class="btn" type="submit">Submit request</button>
	</form>
</div>
<table class="data" style="margin-top:20px">
	<tr><th>Project</th><th>Type</th><th>Draw</th><th>Due</th><th>Status</th></tr>
	<?php foreach ($rows as $r): ?>
	<tr>
		<td><?php echo e($r['project_name']); ?></td>
		<td><?php echo e($r['type']); ?></td>
		<td><?php echo (int)$r['draw_number']; ?></td>
		<td><?php echo e($r['due_date']); ?></td>
		<td><?php echo badge($r['status']); ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
