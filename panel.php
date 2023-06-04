<?php
require ('theme/verification.php');
require ('theme/header.php');
require_once ('functions/startFunctionScript.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);
?>

<!-- start banner Area -->
<section class="banner-area relative" id="home">	
	<div class="container">				
		<div class="row d-flex align-items-center justify-content-center">
			<div class="about-content col-lg-12">
				<h1 class="text-white">
				Panello di gestione di [Bot]Ti.		
				</h1>	
				<p class="text-white link-nav"><a href="admin.php">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="panel.php"> Pannello di gestione</a></p>
			</div>	
		</div>
	</div>
</section>
<!-- End banner Area -->

<!-- Start products Area -->
<section class="products-area section-gap">
	<div class="container">
		<?php
		// Form for view the table for update setting variable
		if (isset($_POST["Valori"])) {
			$ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_STRING);
			$extractParam = dbParamExtraction('ID = '.$ID);
			//Variable extract: Code, Param, SoftDesc, Active, Log, ID
			foreach ($extractParam as $extract) { ?>
				<div class="row d-flex justify-content-center">  
					<form id="changesetting" action="panel.php" method="post" accept-charset="UTF-8">
					<fieldset>
					<legend>Aggiorna i settaggi/paramentri:</legend>
					<div class="form-group">
						<label for="software" >Software*: </label>
						<input class="form-control" type="software" name="software" id="software" value="<?php echo $extract['SoftDesc'];?>" readonly />
						<label for="code" >Variabile*: </label>
						<input class="form-control" type="code" name="code" id="code" value="<?php echo $extract['Code'];?>" readonly />
						<label for="param" >Valore*: </label>
						<input class="form-control" type="param" name="param" id="param" value="<?php echo $extract['Param'];?>" maxlength="300" required="1"/>
						<label for="note" >Note: </label>
						<input class="form-control" type="note" name="note" id="note" value="<?php echo $extract['Note'];?>" maxlength="200" />
						<label for="active" >Stato*: </label>
						<select class="form-control" name="active">
							<?php
							if ($extract['Active']){ ?>
									<option value="1">Attivo</option>
									<option value="0">Disattivo</option>
							<?php
							}else{
							?>
									<option value="0">Disattivo</option>
									<option value="1">Attivo</option
							<?php		
							} ?>
						</select>
						<input class="form-control" type="hidden" name="ID" id="ID" value="<?php echo $extract['ID'];?>" />
						<br>
						<input class="genric-btn primary-border circle" type="submit" name="Cambia" value="Cambia" />
					</div>
					</fieldset>
					</form>
			</div>
		   <?php }
			} ?>
		<?php
		// Change the variable
		if (isset($_POST["Cambia"])) {
			$software = filter_input(INPUT_POST, 'software', FILTER_SANITIZE_STRING);
			$code = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_STRING);
			$param = filter_input(INPUT_POST, 'param', FILTER_SANITIZE_STRING);
			$note = filter_input(INPUT_POST, 'note', FILTER_SANITIZE_STRING);
			$ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_STRING);
			$state = filter_input(INPUT_POST, 'active', FILTER_SANITIZE_STRING);
			$user = $_SESSION['username'];
			$submit = filter_input(INPUT_POST, 'Cambia', FILTER_SANITIZE_STRING);
		if (!empty($submit)) {
			dbParamUpdate($ID, $software, $code, $param, $state, $user, $note);?>
			<div class="row d-flex justify-content-center">
				<h2>Hai aggiornato correttamente i valori</h2>
			</div>
		<?php
		} else  {	
		?>
			<div class="row d-flex justify-content-center">
				<h2>Hai inserito in modo sbagliato il parametro, ritenta!</h2>
			</div>
		<?php
		  }
		}
		?>
		<?php
		if (isset($_POST["Test"])) {
			$submit = filter_input(INPUT_POST, 'Test', FILTER_SANITIZE_STRING);

		if (!empty($submit)) {
			$error = sendMail("Test di configurazione","Configurazione OK.");
			$messageResponse = "Hai configurato correttamente la mail. Controlla se ti &egrave; arrivato il messaggio via e-mail.";
			if (empty($error)){
				?>
				<div class="row d-flex justify-content-center">
					<h2><?php echo $messageResponse; ?></h2>
				</div>
				<?php
				} else {	
				?>
				<div class="row d-flex justify-content-center">
					<h2>Errore: <?php echo $error; ?></h2>
				</div>
				<?php
				}
			}
		}
		?>
		<!-- Start control admin -->
		<?php if (($_SESSION['level']) == 'admin') { ?>
		<!-- Table for view the variable of bot -->
		<div id="content" class="clearfix">  
			<form method="post" action="panel.php">
			<div align="center">  
				<button class="genric-btn primary-border circle" type='submit' name='Valori' value='Valori' />Modifica i valori dei parametri</button>
				<button class="genric-btn primary-border circle" type='submit' name='Test' value='Test' />Test di configurazione Mail</button>
			</div>
			<div class="section-top-border">
				<div class="table-wrapper">
					<table id="order">
						<thead>
							<tr>
								<th>Software</th>
								<th>Tipo var.</th>
								<th>Valore</th>
								<th>Stato</th>
								<th>Note</th>
								<th>Log</th>
								<th>Sel.</th>
							</tr> 
						</thead>
						<tbody>
							<?php $extractParam = dbParamExtraction("SoftDesc is not null");
							foreach ($extractParam as $extract) { ?>
							<tr>
								<td><?php echo $extract['SoftDesc']; ?></td>
								<td><?php echo $extract['Code']; ?></td>
								<td><?php
									if ($extract['Code'] == "key" or $extract['Code'] == "password" or $extract['Code'] == "token" or $extract['Code'] == "key_secret" or $extract['Code'] == "token_secret"){
										echo '*********';
									}else{
										echo $extract['Param'];
									}
									?>
								</td>
								<td><?php echo $extract['Active']; ?></td>
								<td><?php echo $extract['Note']; ?></td>
								<td><?php echo $extract['Log']; ?></td>
								<td><input type="radio" name="ID" value="<?php echo $extract['ID']; ?>" /></td>
							</tr>
							<?php
							} ?>
						</tbody>            
					</table>
				</div>
			</div>
			</form>
		</div>
		<?php } else { ?>         
		<div class="row d-flex justify-content-center">  
			<h2><b>Utente non autorizzato.</b></h2>
		</div>
		<?php } ?>
		<!-- End control admin -->
	</div>
</section>
<!-- End products Area -->

<!-- Footer page -->
<?php include ('theme/footer.php');?>