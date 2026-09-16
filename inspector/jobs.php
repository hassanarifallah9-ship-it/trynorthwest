<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('inspector'));
$title = 'My Jobs';
$id = (int)getv('id');

if (is_post() && csrf_check()) {
	$iid = (int)post('inspection_id');
	$own = db()->prepare('SELECT * FROM inspections WHERE id=? AND inspector_id=?');
	$own->execute(array($iid, $u['id']));
	$job = $own->fetch();
	if ($job) {
		if (post('action') === 'start') {
			db()->prepare("UPDATE inspections SET status='in_progress' WHERE id=?")->execute(array($iid));
			flash_set('success', 'Job started.');
		}
		if (post('action') === 'submit') {
			$exists = db()->prepare('SELECT id FROM reports WHERE inspection_id=?');
			$exists->execute(array($iid));
			if ($exists->fetch()) {
				db()->prepare('UPDATE reports SET summary=?, percent_complete=?, findings=?, qc_status="pending" WHERE inspection_id=?')
					->execute(array(post('summary'), post('percent_complete'), post('findings'), $iid));
			} else {
				db()->prepare('INSERT INTO reports (inspection_id, summary, percent_complete, findings, qc_status) VALUES (?,?,?,?, "pending")')
					->execute(array($iid, post('summary'), post('percent_complete'), post('findings')));
			}
			$path = save_upload('photo', 'photos');
			if ($path) {
				db()->prepare('INSERT INTO photos (inspection_id, file_path, caption, uploaded_by) VALUES (?,?,?,?)')
					->execute(array($iid, $path, post('caption'), $u['id']));
			}
			db()->prepare("UPDATE inspections SET status='submitted' WHERE id=?")->execute(array($iid));
			$admins = db()->query("SELECT id FROM users WHERE role IN ('admin','staff') AND status='active'")->fetchAll();
			foreach ($admins as $a) notify($a['id'], 'Report submitted', 'QC needed', '/admin/reports.php');
			log_activity('submit_report', 'inspections', $iid);
			flash_set('success', 'Report submitted for quality review.');
		}
	}
	redirect('/inspector/jobs.php?id=' . $iid);
}

if ($id) {
	$st = db()->prepare('SELECT i.*, p.name project_name, p.address, p.city, p.state, p.zip FROM inspections i JOIN projects p ON p.id=i.project_id WHERE i.id=? AND i.inspector_id=?');
	$st->execute(array($id, $u['id']));
	$job = $st->fetch();
	$rep = null;
	if ($job) {
		$r = db()->prepare('SELECT * FROM reports WHERE inspection_id=?');
		$r->execute(array($id));
		$rep = $r->fetch();
	}
	include dirname(__DIR__) . '/portal/_head.php';
	if (!$job) { echo '<p>Job not found.</p>'; include dirname(__DIR__) . '/portal/_foot.php'; exit; }
	?>
	<div class="card">
		<h3><?php echo e($job['project_name']); ?></h3>
		<p><?php echo e($job['address'] . ', ' . $job['city'] . ', ' . $job['state'] . ' ' . $job['zip']); ?></p>
		<p><?php echo e($job['type']); ?> · Draw #<?php echo (int)$job['draw_number']; ?> · <?php echo badge($job['status']); ?></p>
		<p><?php echo e($job['notes']); ?></p>
		<?php if ($job['status'] === 'assigned'): ?>
			<form method="post"><?php echo csrf_field(); ?>
				<input type="hidden" name="inspection_id" value="<?php echo (int)$job['id']; ?>">
				<button class="btn" name="action" value="start">Start inspection</button>
			</form>
		<?php endif; ?>
		<?php if (in_array($job['status'], array('in_progress','submitted','qc_review'), true)): ?>
			<form method="post" enctype="multipart/form-data">
				<?php echo csrf_field(); ?>
				<input type="hidden" name="inspection_id" value="<?php echo (int)$job['id']; ?>">
				<label>Summary</label>
				<textarea name="summary" rows="4" required><?php echo e($rep ? $rep['summary'] : ''); ?></textarea>
				<label>% complete</label>
				<input type="number" step="0.01" name="percent_complete" value="<?php echo e($rep ? $rep['percent_complete'] : '0'); ?>">
				<label>Findings</label>
				<textarea name="findings" rows="4"><?php echo e($rep ? $rep['findings'] : ''); ?></textarea>
				<label>Photo</label>
				<input type="file" name="photo" accept="image/*">
				<label>Caption</label>
				<input name="caption">
				<button class="btn" name="action" value="submit">Submit for QC</button>
			</form>
		<?php endif; ?>
	</div>
	<p><a href="jobs.php">Back to jobs</a></p>
	<?php
	include dirname(__DIR__) . '/portal/_foot.php';
	exit;
}

$st = db()->prepare('SELECT i.*, p.name project_name, p.city, p.state FROM inspections i JOIN projects p ON p.id=i.project_id WHERE i.inspector_id=? ORDER BY i.id DESC');
$st->execute(array($u['id']));
$rows = $st->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<table class="data">
	<tr><th>Project</th><th>Type</th><th>Draw</th><th>Due</th><th>Status</th><th></th></tr>
	<?php foreach ($rows as $r): ?>
	<tr>
		<td><?php echo e($r['project_name']); ?><div class="muted"><?php echo e($r['city'].', '.$r['state']); ?></div></td>
		<td><?php echo e($r['type']); ?></td>
		<td><?php echo (int)$r['draw_number']; ?></td>
		<td><?php echo e($r['due_date']); ?></td>
		<td><?php echo badge($r['status']); ?></td>
		<td><a class="btn small" href="jobs.php?id=<?php echo (int)$r['id']; ?>">Open</a></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
