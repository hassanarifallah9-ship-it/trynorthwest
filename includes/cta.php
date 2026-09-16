<div class="block_container">
<div id="221160" class="block block_id-221160 block_type__cta bg-angle__angle special-arrow__no">
	<div class="block__bg-img"></div>
	<div class="page_frame group">
		<div class="all-cols-wrap">
			<div id="cta" class="cta-bottom-section flex">
				<div class="col-1">
					<h2>Let us <span style="color:#92d050;">prove it</span></h2>
					<p>Nationwide draw inspection turnaround in two business days. Contact us now today to schedule a demo and learn more about how we can work for you.</p>
				</div>
				<div class="col-2">
					<div class="cta-form-wrapper">
						<form id="bizango_superform" class="superform" action="<?php echo $BASE; ?>/superform.php" method="post">
							<div class="form-field form-field__input-text first-name">
								<label for="form-field__0">First Name</label>
								<input id="form-field__0" type="text" placeholder="First Name" name="First Name" class="superform_field first-name required">
							</div>
							<div class="form-field form-field__input-text last-name">
								<label for="form-field__1">Last Name</label>
								<input id="form-field__1" type="text" placeholder="Last Name" name="Last Name" class="superform_field last-name required">
							</div>
							<div class="form-field form-field__input-text company-name">
								<label for="form-field__2">Company Name</label>
								<input id="form-field__2" type="text" placeholder="Company Name" name="Company Name" class="superform_field company-name">
							</div>
							<div class="form-field form-field__input-select">
								<label for="form-field__3">Select Service of Interest</label>
								<select id="form-field__3" name="Select Service of Interest" class="superform_field select-service-of-interest">
									<option value="" selected disabled>Select Service of Interest</option>
									<option value="Residential Progress Inspections">Residential Progress Inspections</option>
									<option value="Commercial Progress Inspections">Commercial Progress Inspections</option>
									<option value="Feasibility Reviews">Feasibility Reviews</option>
									<option value="Other Support Services">Other Support Services</option>
								</select>
							</div>
							<div class="form-field form-field__input-tel phone">
								<label for="form-field__4">Phone</label>
								<input id="form-field__4" type="tel" placeholder="Phone" name="Phone" class="superform_field phone required">
							</div>
							<div class="form-field form-field__input-email email">
								<label for="form-field__5">Email</label>
								<input id="form-field__5" type="email" placeholder="Email" name="Email" class="superform_field email required">
							</div>
							<div class="flex" style="position: relative;">
								<div class="form-field form-field__submit">
									<button type="submit" id="superform_submit">Get Started Today</button>
								</div>
							</div>
						</form>
						<div>Turnaround in 2 business days, nationwide!</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<a class="bouncy-arrow" href="#cta"></a>
</div>
</div>
<script src="<?php echo $BASE; ?>/javascripts/jquery.form.min.js" type="text/javascript"></script>
<script src="<?php echo $BASE; ?>/javascripts/jquery.validate.1.11.1.min.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
	jQuery.validator.addMethod("defaultInvalid", function(value, element){
		return !(element.value == element.defaultValue);
	});
	$("#bizango_superform").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				target: "#bizango_superform",
				replaceTarget: true
			});
		}
	});
	jQuery.extend(jQuery.validator.messages, {
		required: "Please include this information.",
		email: "Please include a valid email address."
	});
});
</script>
