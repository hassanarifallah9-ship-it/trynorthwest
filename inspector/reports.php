<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('inspector'));
$title = 'My Reports';
$st = db()->prepare('SELECT r.*, i.type, i.draw_number, i.status insp_status, p.name project_name
	FROM reports r JOIN inspections i ON i.id=r.inspection_id JOIN projects p ON p.id=i.project_id
	WHERE i.inspector_id=? ORDER BY r.id DESC');
$st->execute(array($u['id']));
$rows = $st->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<table class="data">
	<tr><th>Project</th><th>Type</th><th>%</th><th>QC</th><th>Job status</th></tr>
	<?php foreach ($rows as $r): ?>
	<tr>
		<td><?php echo e($r['project_name']); ?></td>
		<td><?php echo e($r['type']); ?> #<?php echo (int)$r['draw_number']; ?></td>
		<td><?php echo e($r['percent_complete']); ?></td>
		<td><?php echo badge($r['qc_status']); ?></td>
		<td><?php echo badge($r['insp_status']); ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
