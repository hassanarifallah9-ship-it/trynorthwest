<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('admin', 'staff'));
$title = 'Messages';

$users = db()->query('SELECT id, first_name, last_name, role FROM users WHERE id <> ' . (int)$u['id'] . ' ORDER BY first_name')->fetchAll();
if (is_post() && csrf_check()) {
	db()->prepare('INSERT INTO messages (from_user, to_user, project_id, body) VALUES (?,?,?,?)')
		->execute(array($u['id'], (int)post('to_user'), post('project_id') ?: null, post('body')));
	notify((int)post('to_user'), 'New message', substr(post('body'), 0, 80), '/admin/messages.php');
	flash_set('success', 'Message sent.');
	redirect('/admin/messages.php');
}
$rows = db()->query('SELECT m.*, f.first_name ffn, f.last_name fln, t.first_name tfn, t.last_name tln
	FROM messages m JOIN users f ON f.id=m.from_user JOIN users t ON t.id=m.to_user
	ORDER BY m.id DESC LIMIT 50')->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<div class="card">
	<h3>New message</h3>
	<form method="post" class="stack-form">
		<?php echo csrf_field(); ?>
		<div class="field">
			<label for="to_user">To</label>
			<select id="to_user" name="to_user">
				<?php foreach ($users as $x): ?>
				<option value="<?php echo (int)$x['id']; ?>"><?php echo e($x['first_name'].' '.$x['last_name'].' ('.$x['role'].')'); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="field">
			<label for="body">Message</label>
			<textarea id="body" name="body" rows="5" required placeholder="Write your message..."></textarea>
		</div>
		<button class="btn" type="submit">Send</button>
	</form>
</div>

<?php if (!$rows): ?>
	<div class="card empty" style="margin-top:20px">No messages yet. Send one using the form above.</div>
<?php else: ?>
<table class="data">
	<tr><th>When</th><th>From</th><th>To</th><th>Message</th></tr>
	<?php foreach ($rows as $r): ?>
	<tr>
		<td><?php echo e($r['created_at']); ?></td>
		<td><?php echo e($r['ffn'].' '.$r['fln']); ?></td>
		<td><?php echo e($r['tfn'].' '.$r['tln']); ?></td>
		<td><?php echo e($r['body']); ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php endif; ?>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
