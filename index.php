<?php
include 'theme/header.php';
session_start();
?>

<!-- start Area -->
<section class='banner-area' id='home'>
	<div class='container'>
		<div class='row fullscreen d-flex align-items-center justify-content-center'>
			<div class='banner-content col-lg-6 col-md-6'>
				<h1>System [Bot]Ti</h1>
				<p class='text-white text-uppercase'>(Your system for control any Bot Telegram)</p>
				<form id='login' action='login.php' method='post' accept-charset='UTF-8'>
				<fieldset >
				<legend>Effettua il login alla piattaforma</legend>
				<input type='hidden' name='submitted' id='submitted' value='1'/>
				<div class='form-group'>
					<label for='username' >UserName:</label>
					<input type='text'  class='form-control' name='username' id='username'  placeholder='Inserisci lo username' maxlength='50' />
				</div>
				<div class='form-group'>
					<label for='password' >Password: </label>
					<input type='password' class='form-control' name='password' id='password' placeholder='Inserisci la password' maxlength='50' />
				</div>
				<label>Codice di Sicurezza:</label>
				<div id='captcha'>
					<img src='captcha.php?<?php  echo strtotime("now"); ?>' id='captcha_image' />
					<a class='genric-btn info-border circle small' type='button' value='Nuovo codice' href='javascript:void(0);' id="reload_captcha">Nuovo Captcha</a>
					<script type="text/javascript"> 
						$('#reload_captcha').click(function(event){
						  $('#captcha_image').attr('src', $('#captcha_image').attr('src')+'#');
						});	
					</script>
				</div>
				<br>
				<input class='form-control' type='number' name='captcha' />
				<br>
				<input class='genric-btn primary-border circle arrow' type='submit' name='Submit' value='Accedi' />
				</fieldset>
				</form>
			</div>									
		</div>
	</div>
</section>
<!-- End Area -->

<!-- Footer page -->
<?php include ('theme/footer.php');?>