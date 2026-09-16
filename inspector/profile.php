<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('inspector'));
$title = 'Profile';

$st = db()->prepare('SELECT * FROM inspector_profiles WHERE user_id=?');
$st->execute(array($u['id']));
$p = $st->fetch();
if (is_post() && csrf_check()) {
	db()->prepare('UPDATE users SET phone=? WHERE id=?')->execute(array(post('phone'), $u['id']));
	if ($p) {
		db()->prepare('UPDATE inspector_profiles SET certifications=?, experience_years=?, coverage_notes=? WHERE user_id=?')
			->execute(array(post('certifications'), (int)post('experience_years'), post('coverage_notes'), $u['id']));
	} else {
		db()->prepare('INSERT INTO inspector_profiles (user_id, certifications, experience_years, coverage_notes, status) VALUES (?,?,?,?, "pending")')
			->execute(array($u['id'], post('certifications'), (int)post('experience_years'), post('coverage_notes')));
	}
	flash_set('success', 'Profile saved.');
	redirect('/inspector/profile.php');
}
include dirname(__DIR__) . '/portal/_head.php';
?>
<div class="card">
	<form method="post">
		<?php echo csrf_field(); ?>
		<label>Phone</label><input name="phone" value="<?php echo e($u['phone']); ?>">
		<label>Years of experience</label><input type="number" name="experience_years" value="<?php echo e($p ? $p['experience_years'] : 0); ?>">
		<label>Certifications</label><input name="certifications" value="<?php echo e($p ? $p['certifications'] : ''); ?>">
		<label>Coverage notes</label><input name="coverage_notes" value="<?php echo e($p ? $p['coverage_notes'] : ''); ?>">
		<p>Network status: <?php echo $p ? badge($p['status']) : badge('pending'); ?></p>
		<button class="btn" type="submit">Save</button>
	</form>
</div>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
