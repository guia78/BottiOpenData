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
				<h1 class="text-white">Gestione interazione [Bot]Ti.</h1>
				<br>
				<h3> Puoi inserire tag "veloci" oppure definitivi per aumentare l'interazione di [Bot]Ti.</h3>
				<p class="text-white link-nav"><a href="admin.php">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="panelButtonTag.php"> Pannello Tag</a></p>
			</div>	
		</div>
	</div>
</section>
<!-- End banner Area -->

<!-- Start products Area -->
<section class="products-area section-gap">
	<div class="container">
		<!-- Start control admin -->
		<?php if (($_SESSION['level']) == 'admin') { ?>
			<!-- Table for view the variable of button -->
			<div id="content" class="clearfix" align="center">
				<?php 
				//Insert new button
				if (isset($_POST["New"])) { ?>
					<form id='setting' action='panelButtonTag.php' method='post' accept-charset='UTF-8'>
						<fieldset>
						<legend>Per inserire nuovi Tag, compila questi campi:</legend>
							<div class="form-group">
								<label for="software" >La parola/frase che vuoi associare* :</label>
								<input class="form-control" type="software" name="software" id="software" required="1"/>
								<label for="number">Testo alternativo se non esiste una funzione ad hoc :</label>
								<textarea type="max" class="form-control" type="param" name="param" id="param" maxlength="4090"></textarea>
								<label for="param" >A che funzione associ il tag: </label>
								<select class="form-control" name="type" required="0">
								<?php
								$buttonExtract = dbButtonExtraction("Active = 1");
								foreach ($buttonExtract as $extractDb){ ?>
									<option value="<?php echo $extractDb['ID']; ?>"><?php echo $extractDb['Number'].' # '.$extractDb['Titolo']; ?></option>
								<?php } ?>
								</select>
								<br>
								<input class="genric-btn primary-border circle" type="submit" name="Inserisci" value="Inserisci" />
							</div>
						</fieldset>
					</form>
			</div>
			<?php 
			} ?>
			<?php
			// Form for view the table for update setting variable
			if (isset($_POST["Valori"])) {
				$ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
				$extractButtonTag = dbButtonTagSelectSingle($ID);
				foreach ($extractButtonTag as $extract) { ?>
				   <div id="content" class="clearfix" align="center">
						<form id="changesetting" action="panelButtonTag.php" method="post" accept-charset="UTF-8">
							<fieldset>
								<div class="form-group">
									<label for="software" >La parola/frase che vuoi associare* :</label>
									<input class="form-control" type="software" name="software" id="software" value="<?php echo $extract['Tag']; ?>" required="1"/>
									<label for="number">Testo alternativo se non esiste una funzione ad hoc :</label>
									<textarea type="max" class="form-control" type="param" name="param" id="param" value="<?php echo $extract['Description']; ?>" maxlength="4090"><?php echo $extract['Description']; ?></textarea>
									<label for="param" >A che funzione associ il tag: </label>
									<select class="form-control" name="type" required="1">
									<?php
									// Select for updtate
									$IdChange = $extract['IdButton'];
									$buttonExtractSingle = dbButtonExtraction("ID = $IdChange");
									foreach ($buttonExtractSingle as $extractDbSingle){ ?>
										<option value="<?php echo $extractDbSingle['ID']; ?>"><?php echo $extractDbSingle['Number'].' # '.$extractDbSingle['Titolo']; ?></option>
									<?php 
									} 
									// Also select for change
									$buttonExtract = dbButtonExtraction("Active = 1");
									foreach ($buttonExtract as $extractDb){ ?>
										<option value="<?php echo $extractDb['ID']; ?>"><?php echo $extractDb['Number'].' # '.$extractDb['Titolo']; ?></option>
									<?php } ?>
									</select>
									<br>
									<input type="hidden" name="ID" id="ID" value="<?php echo $extract['ID']; ?>" />
									<br>
									<input class="genric-btn primary-border circle" type="submit" name="Update" value="Aggiorna" />
								</div>
							</fieldset>
						</form>
					</div>
				<?php
				}
			}
			// Change the variable
			if (isset($_POST["Update"])) {
				$ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
				$idbutton = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT);
				$tag = filter_input(INPUT_POST, 'software', FILTER_SANITIZE_STRING);
				$description = filter_input(INPUT_POST, 'param', FILTER_SANITIZE_STRING);
				$submit = filter_input(INPUT_POST, 'Update', FILTER_SANITIZE_STRING);
				if (!empty($submit)) {
					$error = dbButtonTagUpdate($ID, $idbutton, $tag, $description);
					if ($error == 0){ ?>  
						<div id="content" class="clearfix" align="center">
							<h2>Hai aggiornato correttamente i valori.</h2>
						</div>
					<?php
					} else  {
					?>
						<div id="content" class="clearfix" align="center">
							<h2>Hai inserito in modo sbagliato i parametri dei pulsanti, riprova!</h2>
						</div>
					<?php
					} 
				}
			}
	?>
	<?php
			// Insert the variable
			if (isset($_POST["Inserisci"])) {
				$idbutton = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT);
				$tag = filter_input(INPUT_POST, 'software', FILTER_SANITIZE_STRING);
				$description = filter_input(INPUT_POST, 'param', FILTER_SANITIZE_STRING);
				$submit = filter_input(INPUT_POST, 'Inserisci', FILTER_SANITIZE_STRING);
				if (!empty($submit)) {
					dbButtonTagInsert($idbutton, $tag, $description); ?>
					<div class="row d-flex justify-content-center"> 
						<h2>Hai inserito correttamente il nuovo parametro.</h2>
					</div>  
				<?php 
				} else  {	
				?>
					<div class="row d-flex justify-content-center"> 
						<h2>Hai inserito in modo sbagliato i parametri dei pulsanti, riprova!</h2>
					</div>
			<?php
				}
			} ?>

			<!-- Table for view the variable of bot -->
			<div id="content" class="clearfix">
				<form method="post" action="panelButtonTag.php">   
				<div class="row d-flex justify-content-center"> 
					<button class="genric-btn primary-border circle" type='submit' name='New' value='New' />Inserisci tag</button>
					<button class="genric-btn primary-border circle" type='submit' name='Valori' value='Valori' />Modifica tag</button>
				</div>
				<div class="section-top-border">
					<div class="table-wrapper"> 
						<table id="order">
							<thead>
								<tr>
									<th>N.</th>
									<th>Type</th>
									<th>Button</th>
									<th>Tag</th>
									<th>Testo (alternativo)</th>
									<th>Stato</th>
									<th>Sel.</th>
								</tr>
							</thead>
							<tbody>
							<?php $extractTag = dbButtonTagSelect();
								foreach ($extractTag as $extract) { ?>
								<tr>
									<td><?php echo $extract['ID']; ?></td>
									<td><?php echo $extract['SoftDesc']; ?></td>
									<td><?php echo $extract['Titolo']; ?></td>
									<td><?php echo $extract['Tag']; ?></td>
									<td><?php echo $extract['Description']; ?></td>
									<td><?php echo $extract['Active']; ?></td>
									<td><input type="radio" name="ID" value="<?php echo $extract['ID']; ?>" /></td>
								</tr>
							<?php } ?>
							</tbody>
						</table>		
					</div>
				</div>
				</form> 
			</div>
			<!-- End table for view the variable of bot -->
		<?php } else { ?>
			<div class="row d-flex justify-content-center"> 
				<h2><b>Utente non autorizzato </b></h2>
			</div>
		<?php } ?>
		<!-- End control admin -->
	</div>
</section>
<!-- End products Area -->

<!-- Footer page -->
<?php include ('theme/footer.php');?>