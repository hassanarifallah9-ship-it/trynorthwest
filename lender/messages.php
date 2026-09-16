<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('lender'));
$title = 'Messages';

$staff = db()->query("SELECT id, first_name, last_name FROM users WHERE role IN ('admin','staff') AND status='active'")->fetchAll();
if (is_post() && csrf_check()) {
	db()->prepare('INSERT INTO messages (from_user, to_user, body) VALUES (?,?,?)')->execute(array($u['id'], (int)post('to_user'), post('body')));
	notify((int)post('to_user'), 'Lender message', substr(post('body'), 0, 80), '/admin/messages.php');
	flash_set('success', 'Message sent to NWCC.');
	redirect('/lender/messages.php');
}
$st = db()->prepare('SELECT m.*, f.first_name ffn, f.last_name fln FROM messages m JOIN users f ON f.id=m.from_user WHERE m.from_user=? OR m.to_user=? ORDER BY m.id DESC LIMIT 40');
$st->execute(array($u['id'], $u['id']));
$rows = $st->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<div class="card">
	<h3>Message NWCC</h3>
	<form method="post" class="stack-form">
		<?php echo csrf_field(); ?>
		<div class="field">
			<label for="to_user">To</label>
			<select id="to_user" name="to_user"><?php foreach ($staff as $s): ?><option value="<?php echo (int)$s['id']; ?>"><?php echo e($s['first_name'].' '.$s['last_name']); ?></option><?php endforeach; ?></select>
		</div>
		<div class="field">
			<label for="body">Message</label>
			<textarea id="body" name="body" rows="5" required placeholder="Write your message..."></textarea>
		</div>
		<button class="btn" type="submit">Send</button>
	</form>
</div>
<?php if (!$rows): ?>
	<div class="card empty" style="margin-top:20px">No messages yet.</div>
<?php else: ?>
<table class="data">
	<tr><th>When</th><th>From</th><th>Message</th></tr>
	<?php foreach ($rows as $r): ?>
	<tr><td><?php echo e($r['created_at']); ?></td><td><?php echo e($r['ffn'].' '.$r['fln']); ?></td><td><?php echo e($r['body']); ?></td></tr>
	<?php endforeach; ?>
</table>
<?php endif; ?>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
