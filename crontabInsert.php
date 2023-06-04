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
				<h1 class="text-white">Inserire un evento schedulato</h1>
				<h4>(Al momento attuale &egrave; possibile inserire l'invio di una informazione <strong>senza alcuna ripetizione</strong>.)</h4>				
				<p class="text-white link-nav"><a href="admin.php">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="crontabInsert.php"> Inserisci invio schedulato</a></p>
			</div>	
		</div>
	</div>
</section>
<!-- End banner Area -->

<!-- Start products Area -->
<section class="products-area section-gap">
	<div class="container">
		<div class="row d-flex justify-content-center"> 
			<?php
			//Insert new button
			if (isset($_POST["New"])) { ?>
				<form id='formData' name="formData" action='crontabInsert.php' method='post' accept-charset='UTF-8'>
				<fieldset>
				<legend>Per inserire nuovi Scheduler, compila questi campi:</legend>
					<div class="form-group">
					<input type='hidden' name='alreadysent' id='alreadysent' value='1'/>
					<label for='testo' >Testo*: </label>
					<div class="mt-10">
						<textarea type="max" class='form-control' type='testo' name='testo' id='testo' maxlength='4096' required='1' cols="30" rows="10"></textarea>
					</div>
					<label for="firma" >Firma da inserire nel messaggio*: </label>
					<input class="form-control" type="signature" name="signature" id="signature" required="1" />
					<label for="note" >Note (non inviate): </label>
					<input class="form-control" type="note" name="note" id="note" maxlength="500">
					<label for="date" >Inserisci la data di invio*: </label>
					<a href="javascript:show_calendar('document.formData.data1', document.formData.data1.value);"><img src="theme/img/cal.gif" width="16" height="16" border="0" alt="Seleziona la data"></a>
					<input class="form-control" type="Text" name="data1" value="" required="1">
					<br>
					<input class="genric-btn primary-border circle" type="submit" name="Inserisci" value="Inserisci" />
					</div>
				</fieldset>
				</form>
			<?php 
			} ?>
        </div>
		<?php
		// Insert the variable
		if (isset($_POST["Inserisci"])) {
			$AlreadySent = filter_input(INPUT_POST, 'alreadysent', FILTER_SANITIZE_STRING);
			$Signature = filter_input(INPUT_POST, 'signature', FILTER_SANITIZE_STRING);
			$Note = filter_input(INPUT_POST, 'note', FILTER_SANITIZE_STRING);
			$Testo = filter_input(INPUT_POST, 'testo', FILTER_DEFAULT);
			$Date = filter_input(INPUT_POST, 'data1', FILTER_SANITIZE_STRING);
			$submit = filter_input(INPUT_POST, 'Inserisci', FILTER_SANITIZE_STRING);
			if (!empty($submit)) {
				$errore = dbSchedulerInsert($Date, $Signature, $Testo, $Note);
				if ($errore == 0){ ?>
					<div id="content" class="clearfix" align="center">
						<h2>Hai inserito correttamente il nuovo scheduler.</h2>
					</div>
		<?php	} else { ?>
					<div id="content" class="clearfix" align="center">
						<h2>Non hai inserito in modo corretto lo scheduler, riprova, grazie!</h2>
					</div>
		<?php	}
			} else  { ?>	
					<div id="content" class="clearfix" align="center">
						<h2>Errore generico, riprova!</h2>
					</div>
		<?php }
		}
		// Form for view the table for update setting variable
		if (isset($_POST["Valori"])) {
			$ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_STRING);
			$extractScheduler = dbSchedulerExtraction('ID = '.$ID);
			//Variable extract: ID, Date, Signature, Text, Note
			foreach ($extractScheduler as $extract) { ?>
				<div id="content" class="clearfix">
					<form id="changesetting" name="formData" action="crontabInsert.php" method="post" accept-charset="UTF-8">
						<fieldset>
							<legend>Aggiorna i parametri dello scheduler selezionato</legend>
							<div class="form-group">
								<div class="mt-10">
									<label for="testo" >Messaggio da inviare*:</label>
									<textarea type="max" class="common-textarea form-control" type='testo' name='testo' id='testo' maxlength='4096' required='1' cols="30" rows="10"><?php echo $extract['Text']; ?></textarea>
								</div>
								<div class="input-group-icon mt-10">
								<label for="attdisa" >Attivo/Disattivo*:</label>
									<select class="form-control" name="alreadysent" id="alreadysent" required="1">
										<?php 
										if ($extract['AlreadySent']=='1') { ?>
											<option value='1'>Attivo</option>
											<option value='0'>Disattivo</option>
										<?php
										} else { ?>
											<option value='0'>Disattivo</option>
											<option value='1'>Attivo</option>
										<?php
										}
										?>
									</select>
									</div>
								</div>							
								<label for="firma" >Firma da inserire nel messaggio*: </label>
								<input class="form-control" type="signature" name="signature" id="signature" value="<?php echo $extract['Signature']; ?>" required="1" />
								<label for="note" >Note (non inviate): </label>
								<input class="form-control" type="note" name="note" id="note" maxlength="500" value="<?php echo $extract['Note']; ?>">
								<label for="date" >Inserisci la data di invio*: </label>
								<a href="javascript:show_calendar('document.formData.data1', document.formData.data1.value);"><img src="theme/img/cal.gif" width="16" height="16" border="0" alt="Seleziona la data"></a>
								<input class="form-control" type="Text" name="data1" value="<?php echo $extract['DataScheduler'] ?>" required="1">
								<input type="hidden" name="ID" id="ID" value="<?php echo $extract['ID']; ?>" />
								<br>
								<input class="genric-btn primary-border circle" type="submit" name="Cambia" value="Cambia" />
							</div>
						</fieldset>
					</form>
		<?php
			}
		}
		// Change the variable
		if (isset($_POST["Cambia"])) {
			$AlreadySent = filter_input(INPUT_POST, 'alreadysent', FILTER_SANITIZE_NUMBER_INT);
			$Signature = filter_input(INPUT_POST, 'signature', FILTER_SANITIZE_STRING);
			$Note = filter_input(INPUT_POST, 'note', FILTER_SANITIZE_STRING);
			$Testo = filter_input(INPUT_POST, 'testo', FILTER_DEFAULT);
			$Date = filter_input(INPUT_POST, 'data1', FILTER_DEFAULT);
			$ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
			$submit = filter_input(INPUT_POST, 'Cambia', FILTER_SANITIZE_STRING);
			if (!empty($submit)) {
			$erroreUpdate = dbSchedulerUpdate($ID, $Date, $Signature, $Testo, $Note, $AlreadySent);
				if ($erroreUpdate == 0){ ?>
					<div class="row d-flex justify-content-center"> 
						<h3>Hai aggiornato correttamente lo scheduler.</h3>
					</div>
			<?php } else { ?>
					<div class="row d-flex justify-content-center"> 
						<h3>Non hai inserito i dati in modo corretto.</h3>
					</div>
			<?php  } 
			} else  {	?>
				<div class="row d-flex justify-content-center"> 
					<h3>Errore generico, riprova!</h3>
				</div>
			<?php
			  }
		}
		// Form for upload the document in Botti
		if (isset($_POST["Documento"])) { ?>
			<div id="content" class="clearfix" align="center">
				<fieldset>
					<legend>Carica un documento per poterlo inviare in modo schedulato:</legend>
					<div class="form-group">
						<form method="post" action="crontabInsert.php" method="post" enctype="multipart/form-data">
							<label>Sfoglia per caricare il file: </label>
								<!-- Campo file di nome "image" -->
								<input class="form-control" name="uploaded_file" type="file" size="40" />
							<br>
							<!-- Button -->
							<button class="genric-btn primary circle" style="float: center;" name="Upload" id="Upload" value="Upload" type="submit">Upload File</button>
						</form>
					</div>
				</fieldset>
			</div>
		<?php 
		}		
		
		// Upload the document in Botti
		if (isset($_POST["Upload"])) { 
			$path = "upload_img/";
			$path = $path . basename($_FILES['uploaded_file']['name']);
			/*
			* Extract domain name from DB config
			* alternative insert domain here in this mode: $site = "http://site-url.it/bot/";
			*/
			$tableParm = dbParamExtraction('SoftDesc = "Domain" AND Active = "1"');
			foreach ($tableParm as $param) {
				if ($param['Code'] == "name"){$site = $param['Param'];}
			}
			$pathWeb = $site.$path;
			if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path)){ ?>
				<div id="content" class="clearfix" align="center">
					<h2>Hai caricato correttamente sul server il file:</h2></br>
					<p><?php echo $pathWeb; ?></p>
				</div>
			<?php 	
			} else { ?>
				<div id="content" class="clearfix" align="center">
					<h2>Errore nel caricamento del file! Riprova.</h2>
				</div>
			<?php 
			}
		}
		?>
		<!-- Table for view the scheduler of bot -->
		<div id="content" class="clearfix">
			<form method="post" action="crontabInsert.php">   
				<div class="row d-flex justify-content-center"> 
					<button class="genric-btn primary-border circle" type="submit" name="New" value="New" />Inserisci un evento</button>
					<button class="genric-btn primary-border circle" type="submit" name="Valori" value="Valori" />Modifica un evento</button>
					<button class="genric-btn primary-border circle" type="submit" name="Documento" value="Documento" />Upload Documento</button>
				</div>
				<div class="section-top-border">
					<div class="table-wrapper"> 
						<table id="order">
							<thead>
								<tr>
									<th>Invio</th>
									<th>Testo</th>
									<th>Note</th>
									<th>Firma</th>
									<th>Stato</th>
									<th>Sel.</th>
								</tr>
							</thead>             
							<tbody>
							<?php $extractParam = dbSchedulerExtraction("ID is not null AND AlreadySent=1");
							foreach ($extractParam as $extract) { ?>
								<tr>
									<td><?php echo $extract['DataScheduler']; ?></td>
									<td><?php echo $extract['Text']; ?></td>
									<td><?php echo $extract['Note']; ?></td>
									<td><?php echo $extract['Signature']; ?></td>
									<td><?php echo $extract['AlreadySent']; ?></td>
									<td><input type="radio" name="ID" value="<?php echo $extract['ID']; ?>" /></td>
								</tr>
							<?php } ?>
							</tbody>            
						</table>
					</div>
				</div>
			</form>
		</div>
	</div>	
</section>
<!-- End products Area -->

<!-- Footer page -->
<?php include ('theme/footer.php');?>