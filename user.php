<?php
require ('theme/verification.php');
require ('theme/header.php');
require_once ('functions/startFunctionScript.php');
?>

<?php
$userActive = dbCountActiveUsers(); //User active
$name = dbDemName(); //Name of Bot
$forPage = 20; //Limit record view from page
$totPage=ceil($userActive/$forPage); //Ceil for page
?>

<!-- start banner Area -->
<section class="banner-area relative" id="home">	
	<div class="container">				
		<div class="row d-flex align-items-center justify-content-center">
			<div class="about-content">
				<h1 class="text-white">
				Ci sono attualmente <strong> <?php echo $userActive; ?> </strong> utenti attivi<br>
				in <?php if(isset($name)){echo $name[Param];}?>.		
				</h1>	
				<p class="text-white link-nav"><a href="admin.php">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="user.php"> Utenti attivi</a></p>
			</div>	
		</div>
	</div>
</section>
<!-- End banner Area -->

<!-- Start products Area -->
<section class="products-area section-gap">
	<div class="container">   
		<?php	
		if($userActive>0){ 
			//Current Page
			if(isset($_GET["idpag"])){
				$idpag=$_GET["idpag"];
			} else {  
				$idpag='1';
			}
			//Calcolo i numeri iniziale e finale che andranno a limitare la query
			if($idpag==1){
				$init=0;
			} else {
				$init=($idpag*$forPage)-$forPage;
			}
			//Query della pagina: la ripeto limitando ai record che devono essere mostrati nella pagina
			$userActivePage = dbActiveUsersFull($init, $forPage); ?>
			<div class="table-wrapper">
				<table id="order">
					<thead>
						<tr>
							<th>N.</th>
							<th>Ident</th>
							<th>Nome</th>
							<th>Cognome</th>
							<th>Username</th>
							<th>Inserito</th>
						</tr>
					</thead>
					<tbody>
						<?php $initCount = $init;
						foreach ($userActivePage as $user) {
							$initCount = $initCount+1;
							$insert = $user['insertDate']; ?>
							<tr>
								<td><?php echo $initCount; ?></td>
								<td><?php echo $user['UserID']; ?></td>
								<td><?php if(!empty($user['FirstName'])){echo $user['FirstName'];}else{echo "--";} ?></td>
								<td><?php if(!empty($user['LastName'])){echo $user['LastName'];}else{echo "--";} ?></td>
								<td><?php if(!empty($user['Username'])){echo $user['Username'];}else{echo "--";} ?></td>
								<td><?php if(!empty($insert)){echo $insert;}else{echo "--";} ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>				
			</div>
			<!-- table order --> 
			<div class="row d-flex justify-content-center">   
				<?php
				//Link per scorrere le pagine
				if($idpag>1){ ?>
					<span style="text-decoration: underline; margin-right: 10px"><a href="?idpag=<?php echo ($idpag-1);?>"> << </a></span>
				<?php 
				}else{ 
				?>
					<span> << </span>
				<?php 
				}
				$i=1;
				do{
					//Link per scorrere le pagine: la pagina corrente ha un aspetto diverso
					if($i==$idpag){ ?>
						<span style="text-decoration: none; font-weight: bold; margin-right: 10px"><a href="?idpag=<?php echo $i; ?>"><?php echo $i; ?></a></span>
					<?php 
					}else{ 
					?>
						<span style="text-decoration: underline; margin-right: 10px"><a href="?idpag=<?php echo $i;?>"><?php echo $i;?></a></span>
					<?php 
					}
					$i++;
				}while($i<=$totPage);
				if($idpag<$totPage){?>
						<span style="text-decoration: underline; margin-right: 10px"><a href="?idpag=<?php echo ($idpag+1);?>"> >> </a></span>
				<?php 
				}else{
				?>
					<span> >> </span>
				<?php 
				}
				?>
			</div>
		<?php
		} //end if
		?>  
	</div>
</section>
<!-- End products Area -->

<!-- Footer page -->
<?php include ('theme/footer.php');?>