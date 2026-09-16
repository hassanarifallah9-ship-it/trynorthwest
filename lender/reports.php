<?php
require_once dirname(__DIR__) . '/includes/app.php';
$u = require_login(array('lender'));
$title = 'Reports';

$st = db()->prepare('SELECT r.*, i.type, i.draw_number, p.name project_name
	FROM reports r
	JOIN inspections i ON i.id=r.inspection_id
	JOIN projects p ON p.id=i.project_id
	WHERE p.company_id=? AND r.released_at IS NOT NULL
	ORDER BY r.id DESC');
$st->execute(array($u['company_id']));
$rows = $st->fetchAll();
include dirname(__DIR__) . '/portal/_head.php';
?>
<?php foreach ($rows as $r): ?>
<div class="card" style="margin-bottom:16px">
	<h3><?php echo e($r['project_name']); ?> — <?php echo e($r['type']); ?> #<?php echo (int)$r['draw_number']; ?></h3>
	<p>Complete: <strong><?php echo e($r['percent_complete']); ?>%</strong> · Released <?php echo e($r['released_at']); ?></p>
	<p><?php echo nl2br(e($r['summary'])); ?></p>
	<p class="muted"><?php echo nl2br(e($r['findings'])); ?></p>
</div>
<?php endforeach; ?>
<?php if (!$rows): ?><p class="muted">No released reports yet. Request an inspection to get started.</p><?php endif; ?>
<?php include dirname(__DIR__) . '/portal/_foot.php'; ?>
