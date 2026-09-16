<?php
$page_title = 'Solutions | NorthWest Construction Control';
$page_desc = 'Flexible technology options: software, API, and industry solutions.';
$current = 'solutions';
$body_id = "body_page";
$body_class = "body_page";
include __DIR__ . "/includes/head.php";
include __DIR__ . "/includes/nav.php";
?>
<div class="page-hero">
	<div class="bg-img" style="background-image:url(https://s3.amazonaws.com/hoth.bizango/images/872766/smartmockups_kvgvapds_home.png)"></div>
	<div class="page_frame">
		<h1>Proven Solutions For Your Industry</h1>
		<p>You don't have to choose between technology and a trusted partner.</p>
	</div>
</div>

<div class="block background-color__light-gray-2 inner-block">
  <div class="page_frame" style="text-align:center;max-width:900px;margin:0 auto">
    <p>Our solutions seamlessly support existing workflows, address key priorities, and drive efficiency across your unique organization. Services are available through a complimentary cloud-based system, software partners, or a direct API integration.</p>
  </div>
  <div class="page_frame inner-grid-3" style="margin-top:40px">
    <div class="feature-card">
      <img class="img-round" src="https://s3.amazonaws.com/hoth.bizango/images/931694/360_F_323664516_h0MlexrdXe8Cg9J9Zb96059JhOmazI5W_feature.jpeg" alt="Banks">
      <h3 style="text-align:center">For Banks</h3>
      <p style="text-align:center">Mitigate risk, streamlining your operations, and align to regulatory guidance.</p>
      <p style="text-align:center"><a class="button" href="<?php echo $BASE; ?>/soutions-banks"><span style="color:#92d050">Learn More</span></a></p>
    </div>
    <div class="feature-card">
      <img class="img-round" src="https://s3.amazonaws.com/hoth.bizango/images/931715/istockphoto-1190969143-612x612_home.jpeg" alt="Private lenders">
      <h3 style="text-align:center">For Private Lenders</h3>
      <p style="text-align:center">Rapidly scale your lending program while protecting investors' capital.</p>
      <p style="text-align:center"><a class="button" href="<?php echo $BASE; ?>/solutions-private-lenders"><span style="color:#92d050">Learn More</span></a></p>
    </div>
    <div class="feature-card">
      <img class="img-round" src="https://s3.amazonaws.com/hoth.bizango/images/931701/istockphoto-1203814636-612x612_home.jpeg" alt="Credit unions">
      <h3 style="text-align:center">For Credit Unions</h3>
      <p style="text-align:center">Exceed your members' expectations with excellent customer service.</p>
      <p style="text-align:center"><a class="button" href="<?php echo $BASE; ?>/solutions-credit-unions"><span style="color:#92d050">Learn More</span></a></p>
    </div>
  </div>
  <div class="page_frame inner-grid-2" style="margin-top:40px">
    <div class="feature-card">
      <h3>Software</h3>
      <p>Manage projects with ease in our complimentary cloud-based application.</p>
      <p><a class="button" href="<?php echo $BASE; ?>/solutions-web-application"><span style="color:#92d050">Learn more</span></a></p>
    </div>
    <div class="feature-card">
      <h3>API</h3>
      <p>Flexible integration options for software partners and tech-enabled lenders.</p>
      <p><a class="button" href="<?php echo $BASE; ?>/solutions-api-integration"><span style="color:#92d050">Learn more</span></a></p>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/cta.php'; ?>
<?php include __DIR__ . "/includes/footer.php"; ?>
