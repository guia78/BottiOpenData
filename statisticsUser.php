<?php
require ('theme/verification.php');
require ('theme/header.php');
require_once ('functions/startFunctionScript.php');
?>

<?php
$name = dbDemName(); //Name of Bot
?>

<!-- start banner Area -->
<section class="banner-area relative" id="home">	
	<div class="container">				
		<div class="row d-flex align-items-center justify-content-center">
			<div class="about-content col-lg-12">
				<h1 class="text-white">
				Le 20 maggiori funzioni usate in <?php if(isset($name)){echo $name[Param];}?>
				</h1>	
				<p class="text-white link-nav"><a href="admin.php">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="statisticsUser.php"> Statistiche</a></p>
			</div>	
		</div>
	</div>
</section>
<!-- End banner Area -->

<!-- Start products Area -->
<section class="products-area section-gap">
<div class="container">
	<div class="table-wrapper"> 
        <table id="order">
            <thead>
				<tr>
					<th>Posizione</th>
					<th>Funzione usata</th>
					<th>Numero di volte</th>
				</tr>
            </thead>
            <tbody> 
            <?php
                $userLogCount = dbTrackerStatistics();
                $initCount = $init;
                foreach ($userLogCount as $logCount) {
                    $initCount = $initCount+1; ?>
                    <tr >
                    <td><?php echo $initCount; ?></td>
                    <td><?php echo $logCount['Operation']; ?></td>
                    <td><?php echo $logCount['Total']; ?></td>
                    </tr>
            <?php } ?>       
            </tbody>
        </table>
    <!-- table order --> 
    <script type="text/javascript">
      $(function(){
      $('#order').tablesorter(); 
      });
    </script>
    </div>
</div>
</section>
<!-- End products Area -->

<!-- Footer page -->
<?php include ('theme/footer.php');?>