<?php
require ('theme/verification.php');
require ('theme/header.php');
require_once ('functions/startFunctionScript.php'); 
?>

<!-- start banner Area -->
<section class="banner-area" id="home">
	<div class="container">
		<div class="row fullscreen d-flex align-items-center justify-content-center">	
			<h2>Stato del sistema:
			<?php
			//Status Telegram Bot
			$output = controlTelgramState();
			$risultato = $output[0];
			$controllo = $output[1];
			$name = dbDemName(); 
			if( $risultato == $controllo ){ ?>
				Il sistema funziona correttamente.<br> 
			<?php
				if(isset($name)){
					echo 'Stai usando: '.$name['Param'];
				} 
			} else { 
			?>
				Il sistema non sta funzionando correttamente, <a href="queue.php">controlla la coda.</a><br>
		<?php } ?>
			</h2>			
		</div>
   </div>
</section>
<!-- End banner Area -->

<!-- Start products Area -->
<section class="products-area section-gap">
	<div class="container">
		<div id="content" class="clearfix" align="center">
			<?php
			//For send Document/Photo ecc.
			if (isset($_POST["invio"])) {
				//Value text for send
				$testo_ricevuto=filter_input(INPUT_POST, 'testo', FILTER_DEFAULT);
				//Type document to send (photo, voice, document ...)
				$typeDocument = filter_input(INPUT_POST, 'type', FILTER_DEFAULT); 
				$numeroInvi = 0;  
				//Control upload file
				if($typeDocument <> 'text'){
					$path = "upload_img/";
					$path = $path . basename($_FILES['uploaded_file']['name']);
						if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path)){
							//Send Document or Photo in Botti
							$fileSend= $path;
							$activeUsers = dbActiveUsers();
							$userCount = dbCountActiveUsers();
							foreach ($activeUsers as $user){
							  //Control for channel
								if (strpos($user, "@") === FALSE) {
									$controlActive = dbServiceSelect($user, 'Message', '0');
									if(empty($controlActive)){
										$numeroInvi = $numeroInvi+1; //counter for number of send
										$tmpProgress = ($numeroInvi/$userCount)*100;
										//Control type file for type function
										if ($typeDocument=='photo'){
											sendPicture($user, $fileSend);
											sendMessage($user, $testo_ricevuto);
										}
										if ($typeDocument=='document'){
											sendDocument($user, $fileSend);
											sendMessage($user, $testo_ricevuto);
										}
									}
								} else {
									//Control type file for type function
									$numeroInvi=$numeroInvi+1;
									//Control type file for type function
									if ($typeDocument=='photo'){
										sendPicture($user, $fileSend);
										sendMessageChannel($user, $testo_ricevuto);
									}
									if ($typeDocument=='document'){
										sendDocument($user, $fileSend);
										sendMessageChannel($user, $testo_ricevuto);
									}
								}  
							} 
							dbLogTextSend($testo_ricevuto,$_SESSION['username'],'','');
							dbLogTextSend('Hai inviato il file: '.basename($_FILES['uploaded_file']['name']),$_SESSION['username'],'','');
							?>
							<div class="row d-flex justify-content-center">
								<h3>Il file <?php basename($_FILES['uploaded_file']['name']) ?> &egrave; stato salvato sul server.</h3>
								</br>
								<h2>Hai inviato le informazioni <strong>'<?php echo $numeroInvi; ?>'</strong> utenti del servizio.</h2>
							</div>
							<?php		
						} else { 
						?>
						<div class="row d-flex justify-content-center">
							<h2>Ci sono stati dei problemi nel caricamento e spedizione!</h2>
						</div>
					<?php
					}
				} else {
					$activeUsers = dbActiveUsers();
					foreach ($activeUsers as $user) {
						//Control for channel
						if (strpos($user, "@") === FALSE) {
							$controlActive = dbServiceSelect($user, 'Message', '0');
							if(empty($controlActive)){  
								$numeroInvi = $numeroInvi+1;  
								sendMessage($user, $testo_ricevuto);
							}
						} else {
							$numeroInvi = $numeroInvi+1;  
							sendMessageChannel($user, $testo_ricevuto);
						}  
					}
					dbLogTextSend ($testo_ricevuto,$_SESSION['username'],'',''); ?>
					<div class="row d-flex justify-content-center">
						<h2>Hai inviato il testo a <?php echo $numeroInvi; ?> utenti del Bot.</h2>
					</div>
					<?php 
				} 	
			}
			 ?>
		</div>	
		</br>
		<div id="content" class="clearfix" align="center">
			<fieldset>
				<legend>Compila i campi per inviare messaggi agli utenti:</legend>
				<div class="form-group">
					<form method="post" action="admin.php" method="post" enctype="multipart/form-data">
						<div class="mt-10">
							<textarea type="max" id="msg" name="testo" placeholder="Inserisci qui il messaggio da inviare (non puoi superare il limite di lunghezza). Leggi il contatore posto sotto!" onkeyup="ContaCaratteri()" required="1" maxlength="8190" cols="30" rows="10"></textarea><br>					
							<span id="conteggio"></span>
						</div>
						<label for="param" >Tipo di invio:</label>
							<select class="form-control" name="type" required="1">
								<option value="">Seleziona una opzione</option>
								<option value="text">Solo testo</option>
								<option value="photo">Foto + testo</option>
								<option value="document">Documento + testo</option>
							</select>
						<label>Sfoglia per caricare il file: </label>
							<!-- Campo file di nome "image" -->
							<input class="form-control" name="uploaded_file" type="file" size="40" />
						<br>
						<!-- Button -->
						<button class="genric-btn primary circle" style="float: center;" name="invio" id="invio" value="invio" type="submit">Invia messaggio</button>
					</form>
					<!-- Count digit character -->
					<script type="text/javascript">
					//Control keyup
					$('textarea#msg').keyup(function() {
						var limite = 8190; //Limit
						var quanti = $(this).val().length;
						var rimanenti = limite - quanti;
						//Count in real-time
						$('span#conteggio').html(quanti + ' di ' + limite + ', ammessi ancora: ' + rimanenti + ' caratteri');
						//Go to limit
						if(quanti >= limite) {
							//Message alert
							$('span#conteggio').html('<strong>Non puoi inserire pi&ugrave; di ' + limite + ' caratteri!</strong>');
							//Tail character out
							var $contenuto = $(this).val().substr(0,limite);
							$('textarea#msg').val($contenuto);
						}
					}
					);
					</script>
				</div>
			</fieldset>
		</div>
	</div>   
</section>
<!-- End Area -->

<!-- Footer page -->
<?php include ('theme/footer.php');?>