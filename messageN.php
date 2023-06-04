<?php 
require ('theme/verification.php');
require ('theme/header.php');
require_once ('functions/startFunctionScript.php');
?>




<?php
if (isset($_POST['archivia'])) {
        $selected_radio = $_POST['update_archivia'];
        foreach ($selected_radio as $value){
			dbLogTextUpdate($value);  
        }
    }
?>

<!-- start banner Area -->
<section class="banner-area relative" id="home">	
	<div class="container">				
		<div class="row d-flex align-items-center justify-content-center">
			<div class="about-content col-lg-12">
				<h1 class="text-white">
					Messaggi degli utenti				
				</h1>	
				<p class="text-white link-nav"><a href="admin.php">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="message.php"> Messaggi di utenti</a></p>
			</div>	
		</div>
	</div>
</section>
<!-- End banner Area -->

<!-- Start products Area -->

<!-- Start control admin -->
<?php 
if (($_SESSION['level']) == 'admin') { 
?>

<!-- Table for view the message user -->
<section class="products-area section-gap">
	<div class="container">
		<div class="row d-flex justify-content-center">
			<form action="search.php" method="POST">
				<legend>Funzione di ricerca tra i messaggi ricevuti</legend>
				<label>La funzione di ricerca utilizza al massimo UNA PAROLA CHIAVE.</label>
				<fieldset>
					Recenti <input type="radio" name="messaggi" value="1" checked="checked" />
					Archivio <input type="radio" name="messaggi" value="0"/>
					<input class="form-control" type="text" name="testo" placeholder="Termine da cercare" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Termine da cercare'" required="1" />
				</fieldset>
				<br>
				<button class="genric-btn primary-border circle" type="submit" id="cerca" name="Cerca" value="Cerca" />Cerca</button>
				<br>
			</form>
		</div>
		<div class="row d-flex justify-content-center">
			<form action="messageExport.php"> 
				<fieldset>
					<button class="genric-btn primary-border circle" type="submit" name="esportaUser" value="Esporta in Excel tutti i messaggi" />Esporta tutti i messaggi riceuti in formato xls</button>
				</fieldset>
			</form>
		</div>
		
		<!-- This table view the message send by single user -->
		<div class="section-top-border">
			<div overflow:auto> 
				<table>
					<tr>
						<th>Ins.</th>
						<th>Nome</th>
						<th>Messaggio</th>
						<th>Risp.</th>
						<th>Arch.</th>
					</tr>
					<?php
					$messageUsers = dbLogTextFull();
					foreach ($messageUsers as $message) { ?>
						<tr>
							<td align="center">
								<?php
								echo (date('d/m/y', strtotime($message['DataInsert']))) . "<br>" . (date('H:i', strtotime($message['DataInsert']))); 
								?>
							</td>
							<td><?php echo $message['FirstName']; ?></td>
							<td><?php echo $message['Text']; ?></td>
							<td width=100>
								<form method="post" action="joinMessage.php" method="POST">
									<input type="hidden" name="id_message" value="<?php echo $message['Message']; ?>" />
									<input type="submit" id="join" name="join" value="+" />
								</form>
							</td>
							<td>
								<form method="post" action="message.php" method="POST">
									<input type="hidden" name="update_archivia[]" value="<?php echo $message['ID']; ?>" />
									<input type="submit" id="archivia" name="archivia" value="-" />
								</form>
							</td>
						</tr>
					<?php 
					} ?>
				</table>		
			</div>
		</div>
	</div>
</section>

<?php } else { ?>         
<section class="products-area section-gap">
	<div class="container">
		<div class="row d-flex justify-content-center">
			<h2><b>Utente non autorizzato </b></h2>
		</div>
	</div>
</section>
<?php } ?>
<!-- End control admin -->
<!-- End products Area -->
<!-- Footer page -->
<?php include ('theme/footer.php');?>