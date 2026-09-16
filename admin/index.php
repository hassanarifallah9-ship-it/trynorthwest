<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('admin', 'staff'));
$title = 'Dashboard';

$counts = array(
	'leads' => db()->query("SELECT COUNT(*) FROM leads WHERE status='new'")->fetchColumn(),
	'projects' => db()->query("SELECT COUNT(*) FROM projects WHERE status='active'")->fetchColumn(),
	'open_insp' => db()->query("SELECT COUNT(*) FROM inspections WHERE status IN ('requested','assigned','in_progress','submitted','qc_review')")->fetchColumn(),
	'invoices' => db()->query("SELECT COUNT(*) FROM invoices WHERE status IN ('sent','overdue')")->fetchColumn(),
);
$recent = db()->query('SELECT i.*, p.name project_name FROM inspections i JOIN projects p ON p.id=i.project_id ORDER BY i.id DESC LIMIT 8')->fetchAll();
$acts = db()->query('SELECT a.*, u.first_name, u.last_name FROM activity_log a LEFT JOIN users u ON u.id=a.user_id ORDER BY a.id DESC LIMIT 8')->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<div class="cards">
	<div class="card"><div class="n"><?php echo (int)$counts['leads']; ?></div><div class="l">New leads</div></div>
	<div class="card"><div class="n"><?php echo (int)$counts['projects']; ?></div><div class="l">Active projects</div></div>
	<div class="card"><div class="n"><?php echo (int)$counts['open_insp']; ?></div><div class="l">Open inspections</div></div>
	<div class="card"><div class="n"><?php echo (int)$counts['invoices']; ?></div><div class="l">Unpaid invoices</div></div>
</div>
<div class="row">
	<div class="card">
		<h3>Latest inspections</h3>
		<table class="data">
			<tr><th>Project</th><th>Type</th><th>Status</th></tr>
			<?php foreach ($recent as $r): ?>
			<tr>
				<td><a href="inspections.php?id=<?php echo (int)$r['id']; ?>"><?php echo e($r['project_name']); ?></a> #<?php echo (int)$r['draw_number']; ?></td>
				<td><?php echo e($r['type']); ?></td>
				<td><?php echo badge($r['status']); ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<div class="card">
		<h3>Activity</h3>
		<table class="data">
			<?php foreach ($acts as $a): ?>
			<tr>
				<td><?php echo e(trim($a['first_name'] . ' ' . $a['last_name'])); ?></td>
				<td><?php echo e($a['action'] . ' ' . $a['entity']); ?></td>
				<td class="muted"><?php echo e($a['created_at']); ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
