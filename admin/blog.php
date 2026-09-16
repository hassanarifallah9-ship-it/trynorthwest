<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('admin', 'staff'));
$title = 'Blog';

if (is_post() && csrf_check()) {
	$slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', post('title')));
	db()->prepare('INSERT INTO blog_posts (title, slug, excerpt, body, status, published_at) VALUES (?,?,?,?,?,?)')
		->execute(array(post('title'), $slug . '-' . time(), post('excerpt'), post('body'), post('status'), post('published_at') ?: date('Y-m-d')));
	flash_set('success', 'Post saved.');
	redirect('/admin/blog.php');
}
$rows = db()->query('SELECT * FROM blog_posts ORDER BY id DESC')->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<div class="card">
	<form method="post">
		<?php echo csrf_field(); ?>
		<label>Title</label><input name="title" required>
		<label>Excerpt</label><input name="excerpt">
		<label>Body</label><textarea name="body" rows="5"></textarea>
		<div class="row">
			<div><label>Date</label><input type="date" name="published_at"></div>
			<div><label>Status</label><select name="status"><option>published</option><option>draft</option></select></div>
		</div>
		<button class="btn" type="submit">Publish</button>
	</form>
</div>
<table class="data" style="margin-top:20px">
	<tr><th>Date</th><th>Title</th><th>Status</th></tr>
	<?php foreach ($rows as $r): ?>
	<tr><td><?php echo e($r['published_at']); ?></td><td><?php echo e($r['title']); ?></td><td><?php echo badge($r['status']); ?></td></tr>
	<?php endforeach; ?>
</table>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
