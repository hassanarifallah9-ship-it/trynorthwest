<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('inspector'));
$title = 'Inspector dashboard';

$st = db()->prepare("SELECT COUNT(*) FROM inspections WHERE inspector_id=? AND status IN ('assigned','in_progress')");
$st->execute(array($u['id']));
$open = $st->fetchColumn();
$st = db()->prepare("SELECT COUNT(*) FROM inspections WHERE inspector_id=? AND status IN ('submitted','qc_review','released')");
$st->execute(array($u['id']));
$done = $st->fetchColumn();
include dirname(__DIR__) . '/portal/_head.php';
?>
<div class="cards">
	<div class="card"><div class="n"><?php echo (int)$open; ?></div><div class="l">Open jobs</div></div>
	<div class="card"><div class="n"><?php echo (int)$done; ?></div><div class="l">Submitted / released</div></div>
</div>
<p><a class="btn" href="jobs.php">View my jobs</a></p>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
