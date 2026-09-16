<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('admin', 'staff'));
$title = 'Inspections';

$inspectors = db()->query("SELECT id, first_name, last_name FROM users WHERE role='inspector' AND status='active'")->fetchAll();
$projects = db()->query('SELECT id, name FROM projects ORDER BY name')->fetchAll();

if (is_post() && csrf_check()) {
	$act = post('action');
	$id = (int)post('id');
	if ($act === 'create') {
		db()->prepare('INSERT INTO inspections (project_id, inspector_id, type, draw_number, requested_by, due_date, status, notes) VALUES (?,?,?,?,?,?,?,?)')
			->execute(array((int)post('project_id'), post('inspector_id') ?: null, post('type'), (int)post('draw_number'), $u['id'], post('due_date') ?: null, post('inspector_id') ? 'assigned' : 'requested', post('notes')));
		$iid = db()->lastInsertId();
		if (post('inspector_id')) notify((int)post('inspector_id'), 'Inspection assigned', post('type'), '/inspector/jobs.php');
		log_activity('create', 'inspections', $iid);
		flash_set('success', 'Inspection created.');
	}
	if ($act === 'assign' && $id) {
		db()->prepare("UPDATE inspections SET inspector_id=?, status='assigned' WHERE id=?")->execute(array((int)post('inspector_id'), $id));
		notify((int)post('inspector_id'), 'Inspection assigned', 'You have a new field job.', '/inspector/jobs.php');
		log_activity('assign', 'inspections', $id);
		flash_set('success', 'Inspector assigned.');
	}
	if ($act === 'status' && $id) {
		db()->prepare('UPDATE inspections SET status=? WHERE id=?')->execute(array(post('status'), $id));
		log_activity('status', 'inspections', $id, post('status'));
		flash_set('success', 'Status updated.');
	}
	redirect('/admin/inspections.php');
}

$rows = db()->query('SELECT i.*, p.name project_name, p.city, p.state,
	CONCAT(ins.first_name, " ", ins.last_name) inspector_name
	FROM inspections i
	JOIN projects p ON p.id=i.project_id
	LEFT JOIN users ins ON ins.id=i.inspector_id
	ORDER BY i.id DESC')->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<div class="card">
	<h3>Create / assign inspection</h3>
	<form method="post">
		<?php echo csrf_field(); ?><input type="hidden" name="action" value="create">
		<div class="row">
			<div><label>Project</label><select name="project_id" required><?php foreach ($projects as $p): ?><option value="<?php echo (int)$p['id']; ?>"><?php echo e($p['name']); ?></option><?php endforeach; ?></select></div>
			<div><label>Type</label>
				<select name="type">
					<option>Residential Draw Inspection</option>
					<option>Commercial Draw Inspection</option>
					<option>Land Development Inspection</option>
					<option>Foundation Verification</option>
					<option>Pre-Construction Inspection</option>
					<option>Clear Lot Inspection</option>
					<option>Insurance Loss Inspection</option>
					<option>REO Status Report</option>
				</select>
			</div>
			<div><label>Draw #</label><input type="number" name="draw_number" value="1"></div>
			<div><label>Due</label><input type="date" name="due_date"></div>
			<div><label>Inspector</label>
				<select name="inspector_id"><option value="">Assign later</option><?php foreach ($inspectors as $i): ?><option value="<?php echo (int)$i['id']; ?>"><?php echo e($i['first_name'].' '.$i['last_name']); ?></option><?php endforeach; ?></select></div>
			<div><label>Notes</label><input name="notes"></div>
		</div>
		<button class="btn" type="submit">Save</button>
	</form>
</div>
<table class="data" style="margin-top:20px">
	<tr><th>ID</th><th>Project</th><th>Type</th><th>Draw</th><th>Inspector</th><th>Due</th><th>Status</th><th></th></tr>
	<?php foreach ($rows as $r): ?>
	<tr>
		<td><?php echo (int)$r['id']; ?></td>
		<td><?php echo e($r['project_name']); ?><div class="muted"><?php echo e($r['city'].', '.$r['state']); ?></div></td>
		<td><?php echo e($r['type']); ?></td>
		<td><?php echo (int)$r['draw_number']; ?></td>
		<td><?php echo e($r['inspector_name'] ?: '—'); ?></td>
		<td><?php echo e($r['due_date']); ?></td>
		<td><?php echo badge($r['status']); ?></td>
		<td>
			<form method="post"><?php echo csrf_field(); ?>
				<input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
				<input type="hidden" name="action" value="assign">
				<select name="inspector_id"><?php foreach ($inspectors as $i): ?><option value="<?php echo (int)$i['id']; ?>"><?php echo e($i['first_name'].' '.$i['last_name']); ?></option><?php endforeach; ?></select>
				<button class="btn small">Assign</button>
			</form>
			<form method="post" style="margin-top:6px"><?php echo csrf_field(); ?>
				<input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
				<input type="hidden" name="action" value="status">
				<select name="status">
					<?php foreach (array('requested','assigned','in_progress','submitted','qc_review','released','cancelled') as $s): ?>
					<option <?php echo $r['status']===$s?'selected':''; ?>><?php echo $s; ?></option>
					<?php endforeach; ?>
				</select>
				<button class="btn small">Set</button>
			</form>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
