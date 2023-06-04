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
				Esito dell'invio del messaggio singolo		
				</h1>	
				<p class="text-white link-nav"><a href="admin.php">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="message.php"> Ritorna ai messaggi</a></p>
			</div>	
		</div>
	</div>
</section>
<!-- End banner Area -->

<!-- Start products Area -->
<section class="products-area section-gap">
<div class="container"> 
	<h2>
<?php
	//Ricevo il testo e id utente a cui inviare il messaggio da message.php
	$testo_ricevuto = filter_input(INPUT_POST, 'testo', FILTER_DEFAULT);
	$id_user = filter_input(INPUT_POST, 'id_user', FILTER_SANITIZE_STRING);
	$id_message = filter_input(INPUT_POST, 'id_message', FILTER_SANITIZE_STRING);
	$id_total = filter_input(INPUT_POST, 'id_total', FILTER_SANITIZE_STRING);
	/******
	 * questa fase cicla sugli utenti attivi inseriti nel database e per ciascun id
	 * richiama la funzione sendMessage per spedire il testo passato con post
	 * ogni chat_id una singola spedizione messaggio
	 ******/
	if (!empty($testo_ricevuto)){
	$testo_ricevuto_add = $testo_ricevuto.' <b>Inviato da:</b> '.$_SESSION['signature'];
	sendMessage($id_user, $testo_ricevuto_add);
	dbLogTextSend ($testo_ricevuto,$_SESSION['username'],$id_message, $id_total);?>
		<div class="row d-flex justify-content-center"><strong>Hai inviato il seguente testo:</strong></div>
		<div class="row d-flex justify-content-center"> <?php echo $testo_ricevuto; ?></div>
		<div class="row d-flex justify-content-center">Al seguente identificativo: <br> <strong><?php echo $id_user; ?></strong></div>
<?php 
	} else { 
?>
		<div class="row d-flex justify-content-center"><strong>Non puoi inviare dei messaggi vuoti!</strong></div>
<?php
	}
	?>
	</h2>			
</div>
</section>
<!-- End products Area -->

<!-- Footer page -->
<?php include ('theme/footer.php');?>