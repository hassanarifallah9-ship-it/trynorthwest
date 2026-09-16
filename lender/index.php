<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('lender'));
$title = 'Lender dashboard';

$st = db()->prepare('SELECT COUNT(*) FROM projects WHERE company_id=? AND status="active"');
$st->execute(array($u['company_id']));
$projects = $st->fetchColumn();
$st = db()->prepare('SELECT COUNT(*) FROM inspections i JOIN projects p ON p.id=i.project_id WHERE p.company_id=? AND i.status IN ("requested","assigned","in_progress","submitted","qc_review")');
$st->execute(array($u['company_id']));
$open = $st->fetchColumn();
$st = db()->prepare('SELECT COUNT(*) FROM reports r JOIN inspections i ON i.id=r.inspection_id JOIN projects p ON p.id=i.project_id WHERE p.company_id=? AND r.released_at IS NOT NULL');
$st->execute(array($u['company_id']));
$reports = $st->fetchColumn();
$st = db()->prepare('SELECT i.*, p.name project_name FROM inspections i JOIN projects p ON p.id=i.project_id WHERE p.company_id=? ORDER BY i.id DESC LIMIT 8');
$st->execute(array($u['company_id']));
$recent = $st->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<div class="cards">
	<div class="card"><div class="n"><?php echo (int)$projects; ?></div><div class="l">Active projects</div></div>
	<div class="card"><div class="n"><?php echo (int)$open; ?></div><div class="l">Open inspections</div></div>
	<div class="card"><div class="n"><?php echo (int)$reports; ?></div><div class="l">Released reports</div></div>
</div>
<p><a class="btn" href="inspections.php">Request an inspection</a></p>
<table class="data">
	<tr><th>Project</th><th>Type</th><th>Draw</th><th>Status</th></tr>
	<?php foreach ($recent as $r): ?>
	<tr>
		<td><?php echo e($r['project_name']); ?></td>
		<td><?php echo e($r['type']); ?></td>
		<td><?php echo (int)$r['draw_number']; ?></td>
		<td><?php echo badge($r['status']); ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
