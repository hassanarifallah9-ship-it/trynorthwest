<?php
$page_title = 'Careers | Northwest Construction Control';
$page_desc = 'Join the NWCC team.';
$current = 'about';
$body_id = "body_page";
$body_class = "body_page";
include __DIR__ . "/includes/head.php";
include __DIR__ . "/includes/nav.php";
?>
<div class="page-hero">
	<div class="bg-img" style="background-image:url(https://s3.amazonaws.com/hoth.bizango/images/835143/shutterstock_519019975_feature.jpeg)"></div>
	<div class="page_frame">
		<h1>Careers</h1>
		<p>Join our growing team!</p>
	</div>
</div>

<div class="block background-color__light-gray-2 inner-block">
  <div class="page_frame">
    <h2>Be Part of Something Special</h2>
    <p>From our beginnings in 1985 as a local service business outside of Tacoma, Washington to our growth into a nationwide platform, our team is constantly balancing technology and innovation with proven best practices and a dedication to service.</p>
    <p>At NWCC we build teams obsessed with excellence. We are proud of our unmatched quality and honored by trust placed on us by financial institutions of all types and sizes across the country.</p>
    <div class="inner-grid-2" style="margin-top:30px">
      <div class="feature-card"><h3>Our Team</h3><p>We're a group of knowledgeable, hard-working individuals that know what it means to be on a team. We believe that character counts, that cutting corners doesn't work, and that there's always room for innovation.</p></div>
      <div class="feature-card"><h3>Our Mission</h3><p>Since 1985, we've existed to provide trusted ground-truth reporting so our clients can operate with confidence, ease, and speed.</p></div>
    </div>
    <h2 style="margin-top:40px">Benefits To Joining Our Team</h2>
    <div class="inner-grid-3">
      <div class="feature-card"><h3>Life Insurance</h3></div>
      <div class="feature-card"><h3>Vision Insurance</h3></div>
      <div class="feature-card"><h3>Health &amp; Wellness</h3></div>
    </div>
    <?php
      require_once __DIR__ . '/includes/app.php';
      $flash = flash_get();
      if ($flash) echo '<p class="lime">'.htmlspecialchars($flash['msg']).'</p>';
    ?>
    <div class="feature-card" style="margin-top:30px">
      <h3>Apply</h3>
      <form method="post" action="<?php echo $BASE; ?>/api/apply.php">
        <input type="hidden" name="type" value="career">
        <p><input name="name" placeholder="Full name" required style="width:100%;padding:10px;margin-bottom:8px"></p>
        <p><input type="email" name="email" placeholder="Email" required style="width:100%;padding:10px;margin-bottom:8px"></p>
        <p><input name="phone" placeholder="Phone" style="width:100%;padding:10px;margin-bottom:8px"></p>
        <p><textarea name="message" placeholder="Tell us about yourself" rows="4" style="width:100%;padding:10px"></textarea></p>
        <p><button class="button" type="submit">Submit application</button></p>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/cta.php'; ?>
<?php include __DIR__ . "/includes/footer.php"; ?>
