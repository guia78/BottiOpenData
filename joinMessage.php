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
				Risposte inviate al messaggio.		
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
<?php
		/******
		 * This function filter message send for single user
		 ******/
		$id_message=filter_input(INPUT_POST, 'id_message', FILTER_SANITIZE_STRING);
		$messageSend = dbJoinMessageSend($id_message);
		if(!empty($messageSend)){ ?>
		<div class="row d-flex justify-content-center"> 
			<table border="1">
			<tr>
				<td>Data invio</td>
				<td>Messaggio</td>
				<td>User Send</td>
			</tr>
<?php 		foreach ($messageSend as $message) { ?>
			<tr>
				<td><?php echo (date('d/m/Y H:i:s', strtotime($message['DataInsert']))); ?></td>
				<td><?php echo $message['Text']; ?></td>
				<td><?php echo $message['Signature']; ?></td>
				</tr>
<?php 		} ?>
			</table>
		</div>
<?php	} else { ?>
		<div class="row d-flex justify-content-center">Non hai inviato nessun messaggio.</div>
<?php 	} ?>
</div>
</section>
<!-- End products Area -->

<!-- Footer page -->
<?php include('theme/footer.php'); ?>