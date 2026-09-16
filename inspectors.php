<?php
$page_title = 'Inspectors | Northwest Construction Control';
$page_desc = 'Join the NWCC inspection network.';
$current = 'about';
$body_id = "body_page";
$body_class = "body_page";
include __DIR__ . "/includes/head.php";
include __DIR__ . "/includes/nav.php";
?>
<div class="page-hero">
	<div class="bg-img" style="background-image:url(https://s3.amazonaws.com/hoth.bizango/images/753733/construction-view-2.jpg)"></div>
	<div class="page_frame">
		<h1>Join Our Inspection Network</h1>
		<p>More earnings. Less hassle. Own your schedule.</p>
	</div>
</div>

<div class="block background-color__light-gray-2 inner-block">
  <div class="page_frame inner-grid-3">
    <div class="feature-card"><h3>More Earnings</h3><p>Use your expertise and experience to easily tap into additional work.</p></div>
    <div class="feature-card"><h3>Less Hassle</h3><p>Move fast by focusing on what you do best — we'll take care of the admin work.</p></div>
    <div class="feature-card"><h3>Own Your Schedule</h3><p>You're the boss — fit in new inspections when and where it makes sense.</p></div>
  </div>
  <div class="page_frame" style="margin-top:30px;max-width:860px">
    <h3>If that sounds like you, we'd love to connect.</h3>
    <p>Inspectors in our network are typically certified home inspectors, licensed contractors, or certified appraisers. In addition, we look for several years of experience in either construction or draw inspections.</p>
    <p>Please include any relevant certifications and experience in the message field. We look forward to connecting!</p>
    <?php
      require_once __DIR__ . '/includes/app.php';
      $flash = flash_get();
      if ($flash) echo '<p class="lime">'.htmlspecialchars($flash['msg']).'</p>';
    ?>
    <form method="post" action="<?php echo $BASE; ?>/api/apply.php" style="margin-top:20px">
      <input type="hidden" name="type" value="inspector">
      <p><input name="name" placeholder="Full name" required style="width:100%;padding:10px;margin-bottom:8px"></p>
      <p><input type="email" name="email" placeholder="Email" required style="width:100%;padding:10px;margin-bottom:8px"></p>
      <p><input name="phone" placeholder="Phone" style="width:100%;padding:10px;margin-bottom:8px"></p>
      <p><textarea name="message" placeholder="Certifications and experience" rows="4" style="width:100%;padding:10px"></textarea></p>
      <p><button class="button" type="submit">Apply to the network</button></p>
    </form>
  </div>
</div>

<?php include __DIR__ . '/includes/cta.php'; ?>
<?php include __DIR__ . "/includes/footer.php"; ?>
