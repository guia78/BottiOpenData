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
				Risultati della ricerca		
				</h1>	
				<p class="text-white link-nav"><a href="admin.php">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="message.php"> Torna ai messaggi</a></p>
			</div>	
		</div>
	</div>
</section>
<!-- End banner Area -->

<!-- Start products Area -->
<section class="products-area section-gap">
<div class="container">
	<div class="row d-flex justify-content-center">   
        <b>Risultati della ricerca:</b>
	</div>
	<?php
	/******
	 * This table view the search message for
	 * functione dbLogSearchFull($type, $param1, $param2, $param3)
	 ******/
	$type = filter_input(INPUT_POST, 'messaggi', FILTER_SANITIZE_STRING);
	$testo_ricevuto = filter_input(INPUT_POST, 'testo', FILTER_SANITIZE_STRING);
	/******
	 * questa fase cicla sugli utenti attivi inseriti nel database e per ciascun id
	 * richiama la funzione sendMessage per spedire il testo passato con post
	 * ogni chat_id una singola spedizione messaggio
	 ******/
	if (!empty($testo_ricevuto)){ ?>
	<div class="row d-flex justify-content-center">
		<table>
			<tr>
				<td>Data inserimento</td>
				<td>Nome</td>
				<td>Messaggio ricevuto</td>
				<td>Risposta</td>
				<td>Risp.</td>
				<td>Archivia</td>
			</tr>	
			<?php
			$param = explode(" ", $testo_ricevuto);
			$param1 = $param[0];
			$messageUsers = dbLogSearchFull($type, $param1);
			foreach ($messageUsers as $message) { 
			?>
			<tr>
				<td><?php echo (date('d/m/Y H:i', strtotime($message['DataInsert']))) ?></td>
				<td><?php echo $message['FirstName'] ?></td>
				<td><?php echo $message['Text'] ?></td>
				<td>
					<form method="post" action="sendSingle.php" method="POST" />'
					<textarea class="common-textarea form-control"  name="testo" placeholder="Messaggio di risposta" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Messeggio di risposta'" required="1"></textarea>
					<input type="hidden" name="id_user" value="<?php echo $message['UserID'] ?>" />
					<input type="hidden" name="id_message" value="<?php echo $message['Message'] ?>" />
					<input type="hidden" name="id_total" value="<?php echo $message['ID'] ?>" />
					<br>
				   <input type="submit" id="invia" name="invia" value="Invia" />
				   </form>
				</td>
				<td>
				   <form method="post" action="joinMessage.php" method="POST" />
				   <input type="hidden" name="id_message" value="<?php echo $message['Message'] ?>" />
				   <input type="submit" id="join" name="join" value="+"></form>
				</td>
				<td align="center">
				   <form method="post" action="message.php" method="POST" />
				   <input type="hidden" name="update_archivia[]" value="<?php echo $message['ID'] ?>" />
				   <input type="submit" name="Archivia" value="Archivia" />'
				</td>
			</tr>
		<?php 
		}
		?>
		</table>
	</div>
	<?php 
	} else {
	?>
		<div class="row d-flex justify-content-center">
			<strong>Non ci sono risultati per il termine di ricerca utilizzato.</strong>
		</div>
	<?php 
	}
	?>
</div>
</section>
<!-- End products Area -->

<!-- Footer page -->
<?php include ('theme/footer.php');?>