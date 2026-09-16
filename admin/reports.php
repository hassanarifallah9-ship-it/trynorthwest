<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('admin', 'staff'));
$title = 'Reports / QC';

if (is_post() && csrf_check()) {
	$id = (int)post('id');
	$act = post('action');
	$rep = db()->prepare('SELECT * FROM reports WHERE id=?');
	$rep->execute(array($id));
	$r = $rep->fetch();
	if ($r) {
		if ($act === 'approve') {
			db()->prepare("UPDATE reports SET qc_status='approved', qc_by=?, qc_notes=? WHERE id=?")->execute(array($u['id'], post('qc_notes'), $id));
			db()->prepare("UPDATE inspections SET status='qc_review' WHERE id=?")->execute(array($r['inspection_id']));
			flash_set('success', 'Report approved in QC.');
		}
		if ($act === 'reject') {
			db()->prepare("UPDATE reports SET qc_status='rejected', qc_by=?, qc_notes=? WHERE id=?")->execute(array($u['id'], post('qc_notes'), $id));
			db()->prepare("UPDATE inspections SET status='in_progress' WHERE id=?")->execute(array($r['inspection_id']));
			flash_set('success', 'Report sent back to inspector.');
		}
		if ($act === 'release') {
			db()->prepare("UPDATE reports SET qc_status='approved', qc_by=?, released_at=NOW() WHERE id=?")->execute(array($u['id'], $id));
			db()->prepare("UPDATE inspections SET status='released' WHERE id=?")->execute(array($r['inspection_id']));
			$proj = db()->prepare('SELECT p.lender_user_id, p.company_id, p.id pid FROM inspections i JOIN projects p ON p.id=i.project_id WHERE i.id=?');
			$proj->execute(array($r['inspection_id']));
			$p = $proj->fetch();
			if ($p && $p['lender_user_id']) notify($p['lender_user_id'], 'Report released', 'Your inspection report is ready.', '/lender/reports.php');
			db()->prepare('INSERT INTO invoices (company_id, project_id, inspection_id, amount, status, due_date) VALUES (?,?,?,?, "sent", DATE_ADD(CURDATE(), INTERVAL 15 DAY))')
				->execute(array($p['company_id'], $p['pid'], $r['inspection_id'], 375));
			flash_set('success', 'Report released to lender and invoice created.');
		}
		log_activity($act, 'reports', $id);
	}
	redirect('/admin/reports.php');
}

$rows = db()->query('SELECT r.*, i.type, i.draw_number, i.status insp_status, p.name project_name,
	CONCAT(u.first_name," ",u.last_name) inspector_name
	FROM reports r
	JOIN inspections i ON i.id=r.inspection_id
	JOIN projects p ON p.id=i.project_id
	LEFT JOIN users u ON u.id=i.inspector_id
	ORDER BY r.id DESC')->fetchAll();
$photos = db()->query('SELECT * FROM photos ORDER BY id DESC')->fetchAll();
$byInsp = array();
foreach ($photos as $ph) $byInsp[$ph['inspection_id']][] = $ph;

include dirname(__DIR__) . '/portal/_head.php';
?>
<?php foreach ($rows as $r): ?>
<div class="card" style="margin-bottom:16px">
	<h3><?php echo e($r['project_name']); ?> — <?php echo e($r['type']); ?> #<?php echo (int)$r['draw_number']; ?></h3>
	<p><?php echo badge($r['insp_status']); ?> QC: <?php echo badge($r['qc_status']); ?> · Inspector: <?php echo e($r['inspector_name']); ?> · Complete: <?php echo e($r['percent_complete']); ?>%</p>
	<p><?php echo nl2br(e($r['summary'])); ?></p>
	<p class="muted"><?php echo nl2br(e($r['findings'])); ?></p>
	<?php if (!empty($byInsp[$r['inspection_id']])): ?>
		<div class="actions">
		<?php foreach ($byInsp[$r['inspection_id']] as $ph): ?>
			<a href="<?php echo $APP_BASE . '/' . e($ph['file_path']); ?>" target="_blank">Photo</a>
		<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<form method="post">
		<?php echo csrf_field(); ?>
		<input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
		<label>QC notes</label>
		<input name="qc_notes" value="<?php echo e($r['qc_notes']); ?>">
		<div class="actions">
			<button class="btn small" name="action" value="approve">Approve QC</button>
			<button class="btn small secondary" name="action" value="reject">Send back</button>
			<button class="btn small" name="action" value="release">Release to lender</button>
		</div>
	</form>
</div>
<?php endforeach; ?>
<?php if (!$rows): ?><p class="muted">No reports yet.</p><?php endif; ?>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
