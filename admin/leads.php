<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('admin', 'staff'));
$title = 'Leads';

if (is_post() && csrf_check()) {
	$id = (int)post('id');
	if (post('action') === 'status' && $id) {
		db()->prepare('UPDATE leads SET status=?, assigned_to=? WHERE id=?')->execute(array(post('status'), $u['id'], $id));
		log_activity('update_lead', 'leads', $id, post('status'));
		flash_set('success', 'Lead updated.');
		redirect('/admin/leads.php');
	}
}
$rows = db()->query('SELECT * FROM leads ORDER BY id DESC')->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<table class="data">
	<tr><th>When</th><th>Name</th><th>Company</th><th>Service</th><th>Contact</th><th>Status</th><th></th></tr>
	<?php foreach ($rows as $r): ?>
	<tr>
		<td><?php echo e($r['created_at']); ?></td>
		<td><?php echo e($r['first_name'] . ' ' . $r['last_name']); ?></td>
		<td><?php echo e($r['company']); ?></td>
		<td><?php echo e($r['service']); ?></td>
		<td><?php echo e($r['email']); ?><br><?php echo e($r['phone']); ?></td>
		<td><?php echo badge($r['status']); ?></td>
		<td>
			<form method="post" class="inline">
				<?php echo csrf_field(); ?>
				<input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
				<input type="hidden" name="action" value="status">
				<select name="status">
					<?php foreach (array('new','contacted','qualified','converted','closed') as $s): ?>
					<option value="<?php echo $s; ?>" <?php echo $r['status']===$s?'selected':''; ?>><?php echo e(status_label($s)); ?></option>
					<?php endforeach; ?>
				</select>
				<button class="btn small" type="submit">Save</button>
			</form>
		</td>
	</tr>
	<?php if ($r['message']): ?><tr><td colspan="7" class="muted"><?php echo e($r['message']); ?></td></tr><?php endif; ?>
	<?php endforeach; ?>
</table>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
