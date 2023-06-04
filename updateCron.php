<?php
require ('theme/verification.php');
require ('theme/header.php');
// Not insert other require!!
?>

<!-- start banner Area -->
<section class="banner-area relative" id="home">	
	<div class="container">				
		<div class="row d-flex align-items-center justify-content-center">
			<div class="about-content col-lg-12">
				<h1 class="text-white">
				Aggiornamento delle banche dati esterne.		
				</h1>	
				<p class="text-white link-nav"><a href="admin.php">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="updateCron.php"> Aggiornamenti base dati</a></p>
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
			require_once ('functions/functionExtImport.php');
		?>
	</div>
</div>
</section>
<!-- End products Area -->

<!-- Footer page -->
<?php include ('theme/footer.php');?>