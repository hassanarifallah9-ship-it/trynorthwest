<div id="footer">
	<div class="page_frame">
		<div id="footer_nav">
			<a href="<?php echo $BASE; ?>/" class="logo"></a>
			<div class="footer-nav_container">
				<div class="top-footer-nav">
					<div id="nav_contact" class="nav-contact_container">
						<ul class="nav nav--contact">
							<li class="social-group">
								<ul>
									<li class="social">
										<a class="icons icons--facebook" href="https://www.facebook.com/northwestconstructioncontrol/" target="_blank" rel="noopener"></a>
									</li>
									<li class="social">
										<a class="icons icons--twitter" href="https://twitter.com/NorthWestConst3" target="_blank" rel="noopener"></a>
									</li>
									<li class="social social--last">
										<a class="icons icons--linkedin" href="https://linkedin.com/company/northwest-construction-control" target="_blank" rel="noopener"></a>
									</li>
								</ul>
							</li>
							<li>
								<p class="icons icons--phone">
									<a href="tel:(800) 698-3986"><span>(800) 698-3986</span></a>
								</p>
							</li>
						</ul>
					</div>
					<ul class="nav nav--utils">
						<li><a class="button" href="#cta">Contact Us</a></li>
						<li><a class="button" href="<?php echo $BASE; ?>/login.php">User Login</a></li>
					</ul>
				</div>
				<div class="nav-primary_container">
					<ul class="nav nav--primary">
						<li><a href="<?php echo $BASE; ?>/services">Services</a>
							<div class="mega-menu-content">
								<p><a href="<?php echo $BASE; ?>/services-project-monitoring">Draw Inspections</a></p>
								<p><a href="<?php echo $BASE; ?>/services-risk-reviews">Pre-Close&nbsp;Reviews</a></p>
								<p><a href="<?php echo $BASE; ?>/services-specialized-support">Specialized&nbsp;Services</a></p>
								<p><a href="<?php echo $BASE; ?>/services-residential">Residential</a></p>
								<p><a href="<?php echo $BASE; ?>/services-commercial">Commercial</a></p>
							</div>
						</li>
						<li><a href="<?php echo $BASE; ?>/solutions">Solutions</a>
							<div class="mega-menu-content">
								<p><a href="<?php echo $BASE; ?>/solutions-web-application">Software</a></p>
								<p><a href="<?php echo $BASE; ?>/solutions-api-integration">API</a></p>
								<p><a href="<?php echo $BASE; ?>/soutions-banks">Banks</a></p>
								<p><a href="<?php echo $BASE; ?>/solutions-credit-unions">Credit Unions</a></p>
								<p><a href="<?php echo $BASE; ?>/solutions-private-lenders">Private Lenders</a></p>
							</div>
						</li>
						<li><a href="<?php echo $BASE; ?>/about">About</a>
							<div class="mega-menu-content">
								<p><a href="<?php echo $BASE; ?>/about">About Us</a></p>
								<p><a href="<?php echo $BASE; ?>/careers">Careers&nbsp;</a></p>
								<p><a href="<?php echo $BASE; ?>/inspectors">Join Our Network</a></p>
							</div>
						</li>
						<li><a href="<?php echo $BASE; ?>/resources-news">Resources</a>
							<div class="mega-menu-content">
								<p><a href="<?php echo $BASE; ?>/resources-news">Our Blog</a></p>
								<p><a href="<?php echo $BASE; ?>/vendor-compliance">Vendor Diligence</a></p>
								<p><a href="<?php echo $BASE; ?>/privacy-policy">Web Privacy Policy</a></p>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="copyright-container">
	<span class="copyright"> &copy; 2026 Northwest Construction Control &nbsp;</span>
	<a id="bizango" href="https://www.bizango.com" title="Seattle Website Design" target="_blank">Seattle Website Design</a>
</div>

<script defer src="<?php echo $BASE; ?>/javascripts/ncc/lax.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('#mobile_nav .nav').hide();
	$('#mobile_nav li.mega-menu > a').click(function(e){
		e.preventDefault();
	});
	$('#mobile_nav .nav li .plus').on('click', function(e) {
		e.preventDefault();
		var plusExpanded = $(this).attr('aria-expanded') == 'false' ? 'true' : 'false';
		$(this).parent().attr('aria-expanded', plusExpanded);
		$(this).parents('li').toggleClass('active');
		$(this).parent().siblings('.sub-menu').slideToggle();
	});
	$("[data-toggle-show]").on("click", function(){
		var toggleState = $(this).attr('aria-expanded');
		var newToggleState = toggleState == 'false' ? 'true' : 'false';
		$(this).attr('aria-expanded', newToggleState).toggleClass('is-active');
		$('#mobile_nav .nav').slideToggle();
	});
	$('a[href="#cta"]').click(function(e){
		e.preventDefault();
		if ($('#cta.cta-bottom-section .col-2').offset()) {
			$('html,body').animate({
				scrollTop: $('#cta.cta-bottom-section .col-2').offset().top - 50
			}, 1200);
		}
	});
	setTimeout(function(){
		setInterval(function(){
			$('.bouncy-arrow').toggleClass('animate');
		},4000);
	}, 2000);
	if (typeof lax !== 'undefined') {
		lax.init();
		lax.addDriver('scrollY', function() { return window.scrollY; }, {inertiaEnabled: true});
	}
	$('#footer_nav .mega-menu-content').parent().addClass('li-mega-menu');
});
</script>
</body>
</html>
