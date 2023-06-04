<?php
require ('theme/verification.php');
require ('theme/header.php');
require_once ('functions/startFunctionScript.php');
?>

<!-- start banner Area -->
<section class="banner-area relative" id="home">	
	<div class="container">				
		<div class="row d-flex align-items-center justify-content-center">
			<div class="about-content col-lg-12">
				<h1 class="text-white">
				Cambio password in [Bot]Ti.		
				</h1>	
				<p class="text-white link-nav"><a href="admin.php">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="changePwd.php"> Cambio password</a></p>
			</div>	
		</div>
	</div>
</section>
<!-- End banner Area -->

<!-- Start products Area -->
<section class="products-area section-gap">
	<div class="container">
		<?php
			if (isset($_POST["Salva"])) {
				$username=$_SESSION['username'];
				$password=filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
				$newpassword=filter_input(INPUT_POST, 'newpassword', FILTER_SANITIZE_STRING);
				$submit = filter_input(INPUT_POST, 'Salva', FILTER_SANITIZE_STRING);
				//controllo se inserito le password due volte uguali
				if ($password==$newpassword){
					if (!empty($submit)) {
						dbUpdatePwd($username,$password); ?>
						<div class="row d-flex justify-content-center"> 
							<h3>Hai cambiato correttamente la password.</h3>
						</div>
						<div class="row d-flex justify-content-center"> 
							Al prossimo login effettua l'accesso con la nuova password.
						</div>
		<?php			}
				} else  { ?>	
						<div class="row d-flex justify-content-center"> 
							<h2>Hai inserito in modo sbagliato la password reinserisci i dati correttamente!</h2>
						</div>
		<?php	}
			} ?>
		<div class="row d-flex justify-content-center"> 
			<h3>Per cambiare la password di <strong> <?php echo $_SESSION['username'] ?> </strong> compila questi campi:</h3>
		</div>
			<form id="pwd" action="changePwd.php" method="post" accept-charset="UTF-8">
				<fieldset >
				<div class="row d-flex justify-content-center"> 
				<input class="form-control" type="hidden" name="submitted" id="submitted" value="1"/>
				<label for="password" >Nuova Password*: </label>
				<input class="form-control" type="password" name="password" id="password" maxlength="50" />
				<label for="newpassword" >Ripeti Password*: </label>
				<input class="form-control" type="password" name="newpassword" id="newpassword" maxlength="50" />
				<br>
				<input class="genric-btn primary-border circle" type="submit" name="Salva" value="Salva" />
				</div>
				</fieldset>
			</form>
	</div>
</section>
<!-- End products Area -->

<!-- Footer page -->
<?php include ('theme/footer.php'); ?>