<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('admin', 'staff'));
$title = 'Applications';

if (is_post() && csrf_check()) {
	db()->prepare('UPDATE applications SET status=? WHERE id=?')->execute(array(post('status'), (int)post('id')));
	flash_set('success', 'Application updated.');
	redirect('/admin/applications.php');
}
$rows = db()->query('SELECT * FROM applications ORDER BY id DESC')->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<table class="data">
	<tr><th>When</th><th>Type</th><th>Name</th><th>Contact</th><th>Message</th><th>Status</th><th></th></tr>
	<?php foreach ($rows as $r): ?>
	<tr>
		<td><?php echo e($r['created_at']); ?></td>
		<td><?php echo e($r['type']); ?></td>
		<td><?php echo e($r['name']); ?></td>
		<td><?php echo e($r['email']); ?><br><?php echo e($r['phone']); ?></td>
		<td><?php echo e($r['message']); ?></td>
		<td><?php echo badge($r['status']); ?></td>
		<td>
			<form method="post"><?php echo csrf_field(); ?>
				<input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
				<select name="status">
					<?php foreach (array('new','reviewing','accepted','rejected') as $s): ?>
					<option <?php echo $r['status']===$s?'selected':''; ?>><?php echo $s; ?></option>
					<?php endforeach; ?>
				</select>
				<button class="btn small">Save</button>
			</form>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<?php if (!$rows): ?><p class="muted">No inspector or career applications yet.</p><?php endif; ?>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
