<?php
require_once __DIR__ . '/includes/app.php';
$page_title = 'Lenders | Northwest Construction Control';
$page_desc = 'Check NWCC coverage for your city or ZIP code.';
$current = 'contact';
$body_id = 'body_lenders';
$body_class = 'body_page body_lenders';
include __DIR__ . '/includes/head.php';
include __DIR__ . '/includes/nav.php';

$zip = isset($_GET['zip']) ? trim($_GET['zip']) : '';
$project = isset($_GET['project']) ? trim($_GET['project']) : '';
$covered = 'covered';
$match = null;
if ($zip !== '') {
	$lower = strtolower($zip);
	if (preg_match('/canada|uk|mexico|europe|london|toronto|outside/i', $lower)) {
		$covered = 'none';
	} else {
		try {
			$digits = preg_replace('/\D+/', '', $zip);
			$zip5 = substr($digits, 0, 5);
			$st = db()->prepare('SELECT * FROM coverage_zips WHERE zip = ? OR city LIKE ? LIMIT 1');
			$st->execute(array($zip5 ?: $zip, '%' . $zip . '%'));
			$match = $st->fetch();
			if ($match) {
				$covered = $match['status'];
			} else {
				$covered = 'nearby';
			}
		} catch (Exception $e) {
			$covered = 'covered';
		}
	}
}
?>
<div class="page-hero">
	<div class="bg-img" style="background-image:url(https://s3.amazonaws.com/hoth.bizango/images/752804/us-map.jpg)"></div>
	<div class="page_frame">
		<h1>Let's Get Started!</h1>
		<p><?php echo $zip ? htmlspecialchars($zip) : 'Nationwide coverage across all 50 states'; ?></p>
	</div>
</div>
<div class="block background-color__light-gray-2 inner-block">
	<div class="page_frame">
		<?php if ($covered === 'none'): ?>
		<div class="coverage-box">
			<h2>Sorry, we're not there yet</h2>
			<p>We'd love to work with you, but at this time NorthWest Construction Control doesn't support projects outside the United States. Let us know if you have something else in the country we can help with!</p>
		</div>
		<?php elseif ($covered === 'nearby'): ?>
		<div class="coverage-box">
			<h2>We're close, let's discuss</h2>
			<p>We have inspectors in the state, but perhaps not very nearby. We'd love to connect to learn a bit more about your project to ensure we can coordinate a strong match!</p>
		</div>
		<?php else: ?>
		<div class="coverage-box yes">
			<h2>Yes, we've got it covered!</h2>
			<p>We've got trusted experts ready to go in this area. We'd love to learn more about you and the project — just reach out to get started.</p>
			<?php if ($match): ?><p><?php echo htmlspecialchars($match['city'] . ', ' . $match['state'] . ' ' . $match['zip']); ?></p><?php endif; ?>
			<?php if ($project): ?><p>Project type: <strong><?php echo htmlspecialchars($project); ?></strong></p><?php endif; ?>
		</div>
		<?php endif; ?>
		<form class="flex hero-home-form" action="<?php echo $BASE; ?>/lenders" method="get" style="justify-content:center;margin-top:30px;flex-wrap:wrap;gap:10px">
			<input type="text" name="zip" placeholder="Enter City or Zip Code" value="<?php echo htmlspecialchars($zip); ?>" required>
			<select name="project">
				<option disabled <?php echo $project===''?'selected':''; ?>>Type of Project</option>
				<option value="commercial" <?php echo $project==='commercial'?'selected':''; ?>>Commercial</option>
				<option value="residential" <?php echo $project==='residential'?'selected':''; ?>>Residential</option>
				<option value="land development" <?php echo $project==='land development'?'selected':''; ?>>Land Development</option>
			</select>
			<button type="submit">Check Coverage</button>
		</form>
	</div>
</div>
<?php include __DIR__ . '/includes/cta.php'; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
