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
				Assistenza a [Bot]Ti.		
				</h1>	
				<p class="text-white link-nav"><a href="admin.php">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="update.php"> Help on-line</a></p>
			</div>	
		</div>
	</div>
</section>
<!-- End banner Area -->

<!-- Start products Area -->
<section class="products-area section-gap">
<div class="container">
	<div class="row d-flex justify-content-center">  
	<h2>
	Questo link permette di mandare una mail al team di sviluppo:
	<a href="mailto:botti@guion78.com?Subject=Assistenza per [Bot]Ti" target="_top">Invia Mail</a>
	</h2>
	Note: Ti risponderemo quanto prima.
	<a href="#" target="_blank"> Cronologia aggiornamenti degli aggiornamenti rilasciati</a>
	</div>
</div>
</section>
<!-- End products Area -->

<!-- Footer page -->
<?php include ('theme/footer.php');?>