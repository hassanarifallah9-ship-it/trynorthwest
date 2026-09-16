<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('admin', 'staff'));
$title = 'Invoices';

if (is_post() && csrf_check()) {
	if (post('action') === 'status') {
		$st = post('status');
		if ($st === 'paid') {
			db()->prepare("UPDATE invoices SET status='paid', paid_at=NOW() WHERE id=?")->execute(array((int)post('id')));
		} else {
			db()->prepare('UPDATE invoices SET status=? WHERE id=?')->execute(array($st, (int)post('id')));
		}
		flash_set('success', 'Invoice updated.');
		redirect('/admin/invoices.php');
	}
}
$rows = db()->query('SELECT i.*, c.name company_name, p.name project_name
	FROM invoices i JOIN companies c ON c.id=i.company_id
	LEFT JOIN projects p ON p.id=i.project_id
	ORDER BY i.id DESC')->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<table class="data">
	<tr><th>ID</th><th>Company</th><th>Project</th><th>Amount</th><th>Due</th><th>Status</th><th></th></tr>
	<?php foreach ($rows as $r): ?>
	<tr>
		<td><?php echo (int)$r['id']; ?></td>
		<td><?php echo e($r['company_name']); ?></td>
		<td><?php echo e($r['project_name']); ?></td>
		<td>$<?php echo number_format($r['amount'], 2); ?></td>
		<td><?php echo e($r['due_date']); ?></td>
		<td><?php echo badge($r['status']); ?></td>
		<td>
			<form method="post"><?php echo csrf_field(); ?>
				<input type="hidden" name="action" value="status"><input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
				<select name="status">
					<?php foreach (array('draft','sent','paid','overdue') as $s): ?>
					<option <?php echo $r['status']===$s?'selected':''; ?>><?php echo $s; ?></option>
					<?php endforeach; ?>
				</select>
				<button class="btn small">Save</button>
			</form>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
