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
				Verifica se ci sono messaggi non scaricati.		
				</h1>	
				<p class="text-white link-nav"><a href="admin.php">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="coda.php"> Messaggi nella coda</a></p>
			</div>	
		</div>
	</div>
</section>
<!-- End banner Area -->

<!-- Start products Area -->
<section class="products-area section-gap">
	<div class="container">
		<div class="row d-flex justify-content-center">    
			<?php header('Refresh: 20');
			// inizializzo cURL
			$output = controlTelgramState();
			$risultato = $output[0];
			$controllo = $output[1];
			if( $risultato == $controllo ){
				echo '<h2><br>Non ci sono messaggi in coda. Il sistema funziona correttamente.</h2>'; 
			} else {
				echo '<h2><br>Il sistema non st&agrave funzionando correttamente (controlla il demone).</h2><br>
				Hai questi messaggi in coda: <br><br>'.$risultato;  
			}?>			          
		</div>
	</div>
</section>
<!-- End products Area -->

<!-- Footer page -->
<?php include ('theme/footer.php');?>