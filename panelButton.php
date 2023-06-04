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
				Panello di gestione delle funzioni di [Bot]Ti.	
				</h1>
				<br>
				<h3>
				Al momento attuale &egrave; possibile usare fino ad <strong>un massimo di 8 pulsanti (4X4)</strong>.
				Puoi disabilitare i pulsanti per ridurne il numero in ogni singola riga.<br>
				Il pulsante con <strong>numero ZERO</strong> deve sempre esistere ed &egrave; il messaggio di benvenuto!
				</h3>
				<h4>
				Puoi inserire invece comandi del tipo /news e assegnarli un numero superiore a 20.
				</h4>
				I pulsanti possono essere di tipo <strong>"normale" o di tipo "funzione"</strong> nell'ultimo caso devi crearti una funzione in<br>
				"functionPlugin.php" ed inserire nel campo testo il nome della funzione e i suoi parametri con il seguente formato:<br>
				nome_funzione|nome_parametro1|nome_parametro2 , per passare i parametri separali con il "|".
					
				<p class="text-white link-nav"><a href="admin.php">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="panelButton.php"> Pannello delle funzioni</a></p>
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
			<?php //Insert new button
				if (isset($_POST["New"])) { ?>
					<form id='setting' action='panelButton.php' method='post' accept-charset='UTF-8'>
						<fieldset>
						<legend>Per inserire nuovi pulsanti, compila questi campi:</legend>
							<div class="form-group">
								<input type='hidden' name='state' id='state' value='1'/>
								<label for="software" >Descrizione (Button per i bottoni, o altro)*: </label>
								<input class="form-control" type="software" name="software" id="software" required="1"/>
								<label for="number">Ordine*: </label>
								<input class="form-control" type="number" name="number" id="number" maxlength="2" required="1"/>
								<label for="titolo" >Nome del bottone*: </label>
								<input class="form-control" type="titolo" name="titolo" id="titolo" maxlength="50" required="1">
								<label for="param" >Testo*: </label>
								<textarea type="max" class="form-control" type="param" name="param" id="param" maxlength="8190" required="1"></textarea>
								<label for="param" >Tipo operazione del pulsante*: </label>
								<select class="form-control" name="type" required="1">
								<option value="Normal">Normale</option>
								<option value="Function">Funzione</option>
								</select>
								<br>
								<input class="genric-btn primary-border circle"type="submit" name="Inserisci" value="Inserisci" />
							</div>
						</fieldset>
					</form>
			</div>
		<?php }

		// Form for view the table for update setting variable
		if (isset($_POST["Valori"])) {
			$ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_STRING);
			$extractButton = dbButtonExtraction('ID = '.$ID);
			//Variable extract: Code, Param, SoftDesc, Active, Log, ID
			foreach ($extractButton as $extract) {
			?>
			   <div id="content" class="clearfix" align="center">
					<form id="changesetting" action="panelButton.php" method="post" accept-charset="UTF-8">
						<fieldset>
						<legend>Aggiorna i parametri dei pulsanti usati</legend>
						<div class="form-group">
						<label for="software" >Software*: </label>
						<input class="form-control" type="software" name="software" id="software" value="<?php echo $extract['SoftDesc']; ?>" />
						<label for="number">Ordine*: </label>
						<input class="form-control" type="number" name="number" id="number" value="<?php echo $extract['Number']; ?>" />
						<label for="titolo" >Nome del bottone: </label>
						<input class="form-control" type="titolo" name="titolo" id="titolo" value="<?php echo $extract['Titolo']; ?>" />
						<label for="param" >Testo*: </label>
						<textarea type="max" class="form-control" type="param" name="param" id="param" maxlength="8190"><?php echo $extract['Param']; ?></textarea>
						<label for="param" >Tipo operazione del pulsante*: </label>
						<select class="form-control" name="type">
						<?php
						if ($extract['Type'] == "Normal"){
						?>
							<option value="Normal">Normale</option>
							<option value="Function">Funzione</option>
						<?php
						}else{  
						?>
							<option value="Function">Funzione</option>
							<option value="Normal">Normale</option>
						<?php
						}
						?>
						</select>
						<label for="active" >Stato*: </label>
						<select class="form-control" name="active">
						<?php
						if ($extract['Active'] == "1"){
						?>
							<option value="1">Attivo</option>
							<option value="0">Disattivo</option>
						<?php
						}else{   
						?>
							<option value="0">Disattivo</option>
							<option value="1">Attivo</option>
						<?php
						}
						?>
						</select>
						<input type="hidden" name="ID" id="ID" value="<?php echo $extract['ID']; ?>" />
						<br>
						<input class="genric-btn primary-border circle" type="submit" name="Cambia" value="Cambia" />
						</div>
						</fieldset>
					</form>
				</div>
			<?php
			}
		}

		// Change the variable
		if (isset($_POST["Cambia"])) {
			$software = filter_input(INPUT_POST, 'software', FILTER_SANITIZE_STRING);
			$number = filter_input(INPUT_POST, 'number', FILTER_SANITIZE_NUMBER_INT);
			$param = filter_input(INPUT_POST, 'param', FILTER_DEFAULT);
			$tipo = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
			$titolo = filter_input(INPUT_POST, 'titolo', FILTER_SANITIZE_STRING);
			$ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
			$state = filter_input(INPUT_POST, 'active', FILTER_SANITIZE_NUMBER_INT);
			$user = $_SESSION['username'];
			$submit = filter_input(INPUT_POST, 'Cambia', FILTER_SANITIZE_STRING);
			if (!empty($submit)) {
				$error = dbButtonUpdate($ID, $software, $param, $tipo, $number, $state, $user, $titolo);
				if ($error == 0){    
				?>
					<div id="content" class="clearfix" align="center">
						<h2>Hai aggiornato correttamente i valori.</h2>
					</div>
				<?php
				}else {	?>
					<div id="content" class="clearfix" align="center">
						<h2>Hai inserito in modo sbagliato i parametri dei pulsanti, riprova!</h2>
					</div>
				<?php
				} 
			}
		}
		// Insert the variable
		if (isset($_POST["Inserisci"])) {
			$software = filter_input(INPUT_POST, 'software', FILTER_SANITIZE_STRING);
			$number = filter_input(INPUT_POST, 'number', FILTER_SANITIZE_NUMBER_INT);
			$param = filter_input(INPUT_POST, 'param', FILTER_DEFAULT);
			$tipo = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
			$titolo = filter_input(INPUT_POST, 'titolo', FILTER_SANITIZE_STRING);
			$active = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_NUMBER_INT);
			$user = $_SESSION['username'];
			$submit = filter_input(INPUT_POST, 'Inserisci', FILTER_SANITIZE_STRING);
			if (!empty($submit)) {
				dbButtonInsert($software, $param, $tipo, $number, $active, $user, $titolo); ?>
				<div class="row d-flex justify-content-center"> 
					<h2>Hai inserito correttamente il nuovo parametro.</h2>
				</div>     
				<?php 
			} else  {	?>
				<div class="row d-flex justify-content-center"> 
					<h2>Hai inserito in modo sbagliato i parametri dei pulsanti, riprova!</h2>
				</div>
			<?php }
		} ?>
		<!-- Table for view the variable of bot -->
		<div id="content" class="clearfix">
			<form method="post" action="panelButton.php">   
			<div class="row d-flex justify-content-center"> 
				<button class="genric-btn primary-border circle" type='submit' name='New' value='New' />Inserisci un pulsante</button>
				<button class="genric-btn primary-border circle" type='submit' name='Valori' value='Valori' />Modifica il pulsante</button>
			</div>
			<div class="section-top-border">
				<div class="table-wrapper">
					<table id="order">
						<thead>
							<tr>
								<th>Desc.</th>
								<th>N.</th>
								<th>Nome</th>
								<th>Testo</th>
								<th>Tipo</th>
								<th>Stato</th>
								<th>Sel.</th>
							</tr>
						</thead>
						<tbody>          
						<?php $extractParam = dbButtonExtraction("SoftDesc is not null");
						foreach ($extractParam as $extract) { ?> 
							<tr>
								<td><?php echo $extract['SoftDesc']; ?></td>
								<td><?php echo $extract['Number']; ?></td>
								<td><?php echo $extract['Titolo']; ?></td>
								<td><?php echo $extract['Param']; ?></td>
								<td><?php echo $extract['Type']; ?></td>
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
		<?php 
		} else { ?>
			<div class="row d-flex justify-content-center"> 
				<h2><b>Utente non autorizzato </b></h2>
			</div>
		<?php 
		} ?>
		<!-- End control admin -->
	</div>
</section>
<!-- End products Area -->

<!-- Footer page -->
<?php include ('theme/footer.php');?>