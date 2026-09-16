<?php
$page_title = 'About Us | NorthWest Construction Control, Inc.';
$page_desc = "Dedicated to lenders' success since 1985.";
$current = 'about';
$body_id = "body_page";
$body_class = "body_page";
include __DIR__ . "/includes/head.php";
include __DIR__ . "/includes/nav.php";
?>
<div class="page-hero">
	<div class="bg-img" style="background-image:url(https://s3.amazonaws.com/hoth.bizango/images/835143/shutterstock_519019975_feature.jpeg)"></div>
	<div class="page_frame">
		<h1>Who We Are</h1>
		<p>Dedicated to lenders' success since 1985</p>
	</div>
</div>

<div class="block background-color__light-gray-2 inner-block">
  <div class="page_frame">
    <h2>The NWCC Difference</h2>
    <p>NorthWest Construction Control (NWCC) has remained committed to the long-term success of our clients since day one. We have seen cycles come and go and are honored to share our hard-earned best practices. While we're proud of continuously evolving our technology and processes to surpass our clients' expectations, our focus on excellent service remains unchanged.</p>
    <div class="inner-grid-2" style="margin-top:30px">
      <div class="feature-card">
        <h3>How We Began</h3>
        <p>In 1985, Keith Schlemlein founded NWCC with a passion for expert boots-on-the-ground inspections. His focus on speed, accuracy, and efficiency resonated with clients, and the team began to grow.</p>
      </div>
      <div class="feature-card">
        <h3>Where We're Going</h3>
        <p>NWCC pioneered the use of software and internet technologies, enabling the rapid scaling of our services nationwide. Today, we continue to invest in our cloud-based platform. Our services may now be delivered on the cloud, but our boots remain on the ground.</p>
      </div>
    </div>
    <h2 style="margin-top:40px">Our Values</h2>
    <ul>
      <li><strong>Integrity:</strong> We don't cut corners and we're committed to absolute transparency for all stakeholders.</li>
      <li><strong>Service:</strong> We are obsessed with delivering outstanding results and are proud to be a trusted partner.</li>
      <li><strong>Innovation:</strong> While we respect hard-earned lessons and best practices, we're always looking for a better way.</li>
      <li><strong>Excellence:</strong> We consistently deliver reports that are accurate, objective, and on time.</li>
    </ul>
    <p style="margin-top:24px"><a class="button" href="<?php echo $BASE; ?>/careers"><span style="color:#92d050">Join Our Team</span></a></p>
  </div>
</div>

<?php include __DIR__ . '/includes/cta.php'; ?>
<?php include __DIR__ . "/includes/footer.php"; ?>
