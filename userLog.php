<?php
require ('theme/verification.php');
require ('theme/header.php');
require_once ('functions/startFunctionScript.php');
?>

<?php
$userLog = dbTrackerCount(); //Log Count
$forPage = 20; //Limit record view from page
$totPage=ceil($userLog/$forPage); //Ceil for page
if ($totPage>30){ $totPage = 30; } //Limit the max page to view
?>

<!-- start banner Area -->
<section class="banner-area relative" id="home">	
<div class="container">				
	<div class="row d-flex align-items-center justify-content-center">
		<div class="about-content col-lg-12">
			<h1 class="text-white">
				Attivit&agrave; degli utenti sul Bot				
			</h1>	
			<p class="text-white link-nav"><a href="admin.php">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="userLog.php"> Log degli utenti</a></p>
		</div>	
	</div>
</div>
</section>
<!-- End banner Area -->

<!-- Start products Area -->
<section class="products-area section-gap">
	<div class="container">      
		<?php
		if($userLog>0){ 
			//Current Page
			if(isset($_GET["idpag"])){
				$idpag=$_GET["idpag"];
			}else{ 
				$idpag='1';
			}
			//Calcolo i numeri iniziale e finale che andranno a limitare la query
			if($idpag==1){
				$init=0;
			}else{
				$init=($idpag*$forPage)-$forPage;
			}
			//Query della pagina: la ripeto limitando ai record che devono essere mostrati nella pagina
			$trackerForPage = dbTrackerSelect($init, $forPage);
		?>
			<div class="section-top-border">
				<div class="table-wrapper"> 
					<table id="order">
						<thead>
							<tr>
								<th>N:</th>
								<th>Ident</th>
								<th>Nome</th>
								<th>Cognome</th>
								<th>Username</th>
								<th>Operazione</th>
								<th>Risultato</th>
								<th>Data oper.</th>
							</tr>
						</thead>
						<tbody> 
						<?php
							$initCount = $init;
							foreach ($trackerForPage as $tracker) {
								$initCount = $initCount+1; ?>
								<tr>
								<td><?php echo $initCount; ?></td>
								<td><?php echo $tracker['UserID']; ?></td>
								<td><?php if(!empty($tracker['Firstname'])){echo $tracker['Firstname'];}else{echo "--";} ?></td>
								<td><?php if(!empty($tracker['Lastname'])){echo $tracker['Lastname'];}else{echo "--";} ?></td>
								<td><?php if(!empty($tracker['Username'])){echo $tracker['Username'];}else{echo "--";} ?></td>
								<td><?php if(!empty($tracker['Operation'])){echo $tracker['Operation'];}else{echo "--";} ?></td>
								<td><?php echo $tracker['Result']; ?></td>
								<?php $insertDate = $tracker['LogDate']; ?>
								<td><?php echo $insertDate; ?></td>
								</tr>
							<?php } ?> 
						</tbody>
					</table>
				</div>
			</div>
	<div class="row d-flex justify-content-center"> 
		<?php
			//Link per scorrere le pagine
			if($idpag>1){?>
				<span style="text-decoration: underline; margin-right: 10px"><a href="?idpag=<?php echo ($idpag-1);?>"> << </a></span>
			<?php }else{?>
				<span> << </span>
			<?php }
			$i=1;
			do{
				//Link per scorrere le pagine: la pagina corrente ha un aspetto diverso
				if($i==$idpag){ ?>
					<span style="text-decoration: none; font-weight: bold; margin-right: 10px"><a href="?idpag=<?php echo $i; ?>"><?php echo $i; ?></a></span>
				<?php }else{ ?>
					<span style="text-decoration: underline; margin-right: 10px"><a href="?idpag=<?php echo $i;?>"><?php echo $i;?></a></span>
				<?php }
				$i++;
			}while($i<=$totPage);
			if($idpag<$totPage){?>
					<span style="text-decoration: underline; margin-right: 10px"><a href="?idpag=<?php echo ($idpag+1);?>"> >> </a></span>
			<?php }else{?>
					<span> >> </span>
			<?php }?>
        </div>
<?php } //if($num>0) ?>  
	</div>
</div>
</section>
<!-- End products Area -->

<!-- Footer page -->
<?php include ('theme/footer.php');?>