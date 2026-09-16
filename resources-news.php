<?php
$page_title = 'Resources | Northwest Construction Control';
$page_desc = 'News and updates from NorthWest Construction Control.';
$current = 'resources';
include __DIR__ . '/includes/head.php';
include __DIR__ . '/includes/nav.php';

require_once __DIR__ . '/includes/app.php';
$posts = array(
	array('Join Us at Western States CREF', 'Aug 19, 2026', 'NWCC and Trinity Real Estate Solutions will exhibit at the 2026 Western States CREF Conference, September 9-11 at the ARIA Resort & Casino in Las Vegas.'),
	array('Grow Your Industry Connections at the Mile High Mixer!', 'Aug 11, 2026', 'Join us and fellow construction lending professionals for an evening of networking, collaboration, and meaningful connections.'),
	array('Join Us at Fortra Newport Beach', 'Aug 5, 2026', 'NWCC is excited to exhibit at Fortra alongside sister company Trinity. Two days of networking, market insights, and relationship-building.'),
	array('What Lenders Should Look for in a Construction Lending Inspection Partner', 'Jul 16, 2026', 'Construction lending is built on one simple principle: funds should be released based on verified progress.'),
	array('The Truth About Construction Draw Inspections: What Lenders Need to Know Before It’s Too Late', 'Jun 16, 2026', 'Construction draw inspections play a critical role in protecting collateral, validating project progress, and controlling funding risk.'),
	array('Fraud Isn’t Always Obvious: Why NWCC’s Strong Process Is a Lender’s Best Defense', 'May 8, 2026', 'In private lending, fraud rarely presents itself as a dramatic event. Real protection comes from repeatable, consistent structure.'),
	array('Join NWCC at the NPLA 2026 Conference in Miami', 'Mar 10, 2026', 'NWCC, in partnership with Trinity Real Estate Solutions, will participate in the NPLA Conference, March 16–18, 2026 in Miami.'),
	array('Trinity to Attend ProSight Women in Financial Services Summit', 'Mar 6, 2026', 'Penny Roach, Senior Vice President of Client Growth, will represent our organization at the ProSight summit on March 11, 2026.'),
	array('Built on Substance to Deliver Sustained Growth', 'Feb 23, 2026', 'Draw inspections have become one of the most essential tools for protecting construction or renovation loan portfolios.'),
	array('Connect with NWCC at the 2026 Activate Conference in Las Vegas', 'Feb 13, 2026', 'NWCC and Trinity will attend Activate: The Private Lending Growth Summit on February 26–27, 2026 at the Wynn Las Vegas.'),
	array('Why Lenders Should Hire a Nationwide Draw Inspection Company in 2026', 'Feb 9, 2026', 'As the housing market resets in 2026, lenders face both opportunity and risk. Draw inspections validate progress before funds are disbursed.'),
	array('Join NWCC at LeverageCon 7 in San Diego', 'Dec 1, 2025', 'NWCC will be represented at LeverageCon 7, December 7–8, 2025 at the Hilton La Jolla Torrey Pines in San Diego.'),
	array('NWCC to Attend AAPL Private Lending Conference', 'Nov 4, 2025', 'NWCC and Trinity are heading to Las Vegas for the AAPL Annual Conference, November 10–11.'),
	array('Borrower Liquidity: The Hidden Risk That Can Stall Your Project', 'Oct 29, 2025', 'When a budget is built on optimistic assumptions, limited borrower liquidity can stall a project and threaten draw schedules.'),
	array('How Construction Draw Inspections Keep Projects on Track', 'Oct 20, 2025', 'A construction draw inspection verifies that the amount of funds requested matches the work completed.'),
	array('Why Q4 Is the Smartest Time to Strengthen Your Draw Inspection Process', 'Oct 10, 2025', 'October is the ideal time for banks, credit unions, and private lenders to reinforce draw inspection processes.'),
	array('How Credit Unions Can Lend with Confidence: Insights from ACUMA 2025', 'Sep 29, 2025', 'Penny Roach outlined a clear path for credit unions to become confident, proactive players in construction finance.'),
	array('Join Us at ACUMA Make Your Mark 2025', 'Sep 15, 2025', 'NWCC participated in ACUMA Make Your Mark 2025 at the Colorado Convention Center in Denver.'),
);
try {
	$dbposts = db()->query("SELECT title, DATE_FORMAT(published_at, '%b %e, %Y') d, excerpt FROM blog_posts WHERE status='published' ORDER BY published_at DESC")->fetchAll();
	$extra = array();
	foreach ($dbposts as $p) {
		$extra[] = array($p['title'], $p['d'], $p['excerpt']);
	}
	if ($extra) $posts = array_merge($extra, $posts);
} catch (Exception $e) {}
?>
<div class="page-hero">
	<div class="bg-img" style="background-image:url(https://s3.amazonaws.com/hoth.bizango/images/752743/contract-review.jpg)"></div>
	<div class="page_frame">
		<h1>Our Blog</h1>
		<p>News and updates from NorthWest Construction Control</p>
	</div>
</div>
<div class="block background-color__light-gray-2 inner-block">
	<div class="page_frame">
		<div class="blog-list">
			<?php foreach ($posts as $post): ?>
			<article class="blog-card">
				<div class="date"><?php echo htmlspecialchars($post[1]); ?></div>
				<h3><?php echo htmlspecialchars($post[0]); ?></h3>
				<p><?php echo htmlspecialchars($post[2]); ?></p>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<?php include __DIR__ . '/includes/cta.php'; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
