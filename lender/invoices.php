<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('lender'));
$title = 'Invoices';
$st = db()->prepare('SELECT i.*, p.name project_name FROM invoices i LEFT JOIN projects p ON p.id=i.project_id WHERE i.company_id=? ORDER BY i.id DESC');
$st->execute(array($u['company_id']));
$rows = $st->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<table class="data">
	<tr><th>ID</th><th>Project</th><th>Amount</th><th>Due</th><th>Status</th></tr>
	<?php foreach ($rows as $r): ?>
	<tr>
		<td><?php echo (int)$r['id']; ?></td>
		<td><?php echo e($r['project_name']); ?></td>
		<td>$<?php echo number_format($r['amount'], 2); ?></td>
		<td><?php echo e($r['due_date']); ?></td>
		<td><?php echo badge($r['status']); ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
