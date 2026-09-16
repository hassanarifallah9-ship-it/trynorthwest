<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('admin', 'staff'));
$title = 'Inspectors';

if (is_post() && csrf_check()) {
	if (post('action') === 'status') {
		db()->prepare('UPDATE inspector_profiles SET status=? WHERE id=?')->execute(array(post('status'), (int)post('id')));
		$p = db()->prepare('SELECT user_id FROM inspector_profiles WHERE id=?');
		$p->execute(array((int)post('id')));
		$row = $p->fetch();
		if ($row && post('status') === 'approved') {
			db()->prepare("UPDATE users SET status='active', role='inspector' WHERE id=?")->execute(array($row['user_id']));
			notify($row['user_id'], 'Network approved', 'You can now receive inspection jobs.', '/inspector/index.php');
		}
		flash_set('success', 'Inspector profile updated.');
		redirect('/admin/inspectors.php');
	}
}
$rows = db()->query('SELECT ip.*, u.first_name, u.last_name, u.email, u.phone, u.status user_status
	FROM inspector_profiles ip JOIN users u ON u.id=ip.user_id ORDER BY ip.id DESC')->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<table class="data">
	<tr><th>Name</th><th>Email</th><th>Experience</th><th>Certs</th><th>Coverage</th><th>Status</th><th></th></tr>
	<?php foreach ($rows as $r): ?>
	<tr>
		<td><?php echo e($r['first_name'].' '.$r['last_name']); ?></td>
		<td><?php echo e($r['email']); ?><br><?php echo e($r['phone']); ?></td>
		<td><?php echo (int)$r['experience_years']; ?> yrs</td>
		<td><?php echo e($r['certifications']); ?></td>
		<td><?php echo e($r['coverage_notes']); ?></td>
		<td><?php echo badge($r['status']); ?></td>
		<td>
			<form method="post"><?php echo csrf_field(); ?>
				<input type="hidden" name="action" value="status"><input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
				<select name="status">
					<option <?php echo $r['status']==='pending'?'selected':''; ?>>pending</option>
					<option <?php echo $r['status']==='approved'?'selected':''; ?>>approved</option>
					<option <?php echo $r['status']==='inactive'?'selected':''; ?>>inactive</option>
				</select>
				<button class="btn small">Save</button>
			</form>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
