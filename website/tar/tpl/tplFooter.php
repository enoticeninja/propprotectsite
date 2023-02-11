<style>
	.footer-small-links a {
		color: white;
		margin-left: 20px;
	}

	.g-recaptcha {
		display: inline-block;
	}
</style>
<!-- .site-footer -->
<footer id="contact">
	<div class="site-footer">
		<div class="container">
			<div class="row align-items-end">
				<div class="col-md-5 offset-md-1">
					<div class="contact-form">
						<h2>CONTACT US</h2>
						<div class="alert alert-danger d-none" role="alert" id="form-error">

						</div>
						<div class="alert alert-success d-none" role="alert" id="form-success">

						</div>
						<form id="contact-form" onSubmit="return false;">
							<input type="hidden" id="action" name="action" class="form-control-cpg" required value="submit-contact-form" />
							<div class="form-group-cpg fname">
								<i class="fa fa-user" aria-hidden="true"></i>
								<input type="text" id="name" name="en-fname" class="form-control-cpg" placeholder="Name" required />
								<div class="text-center" id="name_error" style="color:red;"></div>
							</div>
							<div class="form-group-cpg">
								<i class="fa fa-envelope" aria-hidden="true"></i>
								<input type="email" id="email" name="en-email" class="form-control-cpg" placeholder="Email id" required />
								<div class="text-center" id="email_error" style="color:red;"></div>
							</div>
							<div class="form-group-cpg">
								<i class="fa fa-phone" aria-hidden="true"></i>
								<textarea class="form-control-cpg" id="message" name="en-message" placeholder="Your message"></textarea>
								<div class="text-center" id="message_error" style="color:red;"></div>
							</div>
							<div class="form-group-cpg">
								<div class="col-md-12 text-center">
									<div class="g-recaptcha  text-center" data-sitekey="6LcMOtsZAAAAADCDwC9RTO6JJw3KlaEDvXSLI8aj" data-callback="recaptchaCallback"></div>
									<div id="captcha_error" style="color:red;"></div>
								</div>
							</div>
							<div class="send-btn text-center">
								<button class="btn" onClick="register()">SEND MESSAGE</button>
							</div>
						</form>
					</div>
				</div>
				<div class="col-md-4 offset-md-2">
					<div class="contact-info">
						<h3>Contact Information</h3>
						<div class="contact-info">
							<div class="location">
								<a>
									<i class="fa fa-map-marker" aria-hidden="true"></i>
									<p>ENOTICE NINJA SOFTTECH PRIVATE LIMITED
										</br>Corporate office: Poonam plaza, Above Bank of Maharashtra Market Yard Road, Pune 411037<br>							
										<br>Registered Office: 1st Floor,Sonigra Chambers, Near Petrol pump
										<br> Market Yard Road, Pune 411037</p>
								</a>
							</div>
							<div class="email">
								<a href="mailto:info@enoticeninja.com">
									<i class="fa fa-envelope" aria-hidden="true"></i>
									<p>info@enoticeninja.com</p>
								</a>
							</div>
							<div class="tel d-none">
								<a href="tel:8600100070">
									<i class="fa fa-phone" aria-hidden="true"></i>
									<p>+91 20 24269016, 8600100070 </p>
								</a>
							</div>
						</div>
						<ul class="d-block pl-0 ml-0 social-links">
							<li>
								<a href="https://www.facebook.com/enoticeninja" class="facebook" target="_blank">
									<i class="fa fa-facebook" aria-hidden="true"></i>
								</a>
							</li>
							<li>
								<a href="https://www.instagram.com/enotice_ninja/" class="insta" target="_blank">
									<i class="fa fa-instagram" aria-hidden="true"></i>
								</a>
							</li>
							<li>
								<a href="https://mobile.twitter.com/eNotice_Ninja" class="twitter" target="_blank">
									<i class="fa fa-twitter" aria-hidden="true"></i>
								</a>
							</li>
							<!--<li>
								<a href="#" class="youtube" target="_blank">
									<i class="fa fa-youtube-play" aria-hidden="true"></i>
								</a>
							</li>
							<li>
								<a href="#" class="linkedin" target="_blank">
									<i class="fa fa-linkedin" aria-hidden="true"></i>
								</a>
							</li>-->
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="copyright">
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<p>
						<span>Copyright &copy; 2020 e-Notice Ninja Private Limited - All rights reserved</span>
						<span class="footer-small-links"><a href="<?php echo SITE_PATH ?>/terms.php">Terms and Conditions</a></span>
						<span class="footer-small-links"><a href="<?php echo SITE_PATH ?>/refund.php">Refund Policy</a></span>
						<span class="footer-small-links"><a href="<?php echo SITE_PATH ?>/privacy.php">Privacy Policy</a></span>
					</p>
				</div>
			</div>
		</div>
	</div>
</footer>
<!-- ./site-footer -->
<!-- custom js -->
<script src="assets/scripts/jquery.min.js"></script>
<script src="assets/scripts/popper.min.js"></script>
<script src="assets/bootstrap-4/bootstrap.min.js"></script>
<script src="assets/scripts/slick.js"></script>
<script src="assets/scripts/jquery.matchHeight-min.js"></script>
<script src="assets/scripts/main.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
	function isCaptchaChecked() {
		return grecaptcha && grecaptcha.getResponse().length !== 0;
	}

	function recaptchaCallback() {
		if (!isCaptchaChecked()) {
			document.getElementById('captcha_error').style.display = '';
			document.getElementById('captcha_error').innerHTML = 'Please Solve the Captcha';
			iserror = false;
			return false;

		} else {
			document.getElementById('captcha_error').style.display = 'none';
			document.getElementById('captcha_error').innerHTML = '';
			iserror = true;
		}
	}

	function register() {
		var name = document.getElementById("name").value;
		var email = document.getElementById("email").value;
		var message = document.getElementById("message").value;

		iserror = true;



		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		if (name == '') {
			document.getElementById('name_error').style.display = '';
			document.getElementById('name_error').innerHTML = 'Please Enter Name';
			iserror = false;
			return false;

		} else {
			document.getElementById('name_error').style.display = 'none';
			document.getElementById('name_error').innerHTML = '';
			iserror = true;
		}
		if (email == '') {
			document.getElementById('email_error').style.display = '';
			document.getElementById('email_error').innerHTML = 'Please Enter a valid Email';
			iserror = false;
			return false;

		} else {
			document.getElementById('email_error').style.display = 'none';
			document.getElementById('email_error').innerHTML = '';
			iserror = true;
		}
		if ($.trim(message) == '') {
			document.getElementById('message_error').style.display = '';
			document.getElementById("message_error").innerHTML = 'Please Enter the Message';
			iserror = false;
			return false;
		} else {
			document.getElementById('message_error').style.display = 'none';
			document.getElementById('message_error').innerHTML = '';
			iserror = true;
		}
		if (reg.test(email) == false) {
			document.getElementById('email_error').style.display = '';
			document.getElementById('email_error').innerHTML = 'Please Enter a valid Email';
			iserror = false;
			return false;

		} else {
			document.getElementById('email_error').style.display = 'none';
			document.getElementById('email_error').innerHTML = '';
			iserror = true;
		}
		if (!isCaptchaChecked()) {
			document.getElementById('captcha_error').style.display = '';
			document.getElementById('captcha_error').innerHTML = 'Please Solve the Captcha';
			iserror = false;
			return false;

		} else {
			document.getElementById('captcha_error').style.display = 'none';
			document.getElementById('captcha_error').innerHTML = '';
			iserror = true;
		}

		if (!iserror) {

			alert("Enter Compolsary fields");
		} else {
			var formData = $('#contact-form').serializeArray();
			$.ajax({
				type: "POST",
				url: '',
				data: formData,
				success: function(dataJson) {
					var dataObj = JSON.parse(dataJson);
					if (dataObj['result'] == true) {
						$('#form-error').html('');
						$('#form-error').addClass('d-none');
						$('#form-success').html(dataObj.message);
						$('#form-success').removeClass('d-none');
						//$('#contact-form').addClass('d-none');
						document.getElementById("name").value='';
						document.getElementById("email").value='';
						document.getElementById("message").value='';
						grecaptcha.reset();
					} else {
						$('#form-success').html('');
						$('#form-success').addClass('d-none');
						$('#form-error').html(dataObj.message);
						$('#form-error').removeClass('d-none');
					}
					
				}
			});

		}

	}
</script>