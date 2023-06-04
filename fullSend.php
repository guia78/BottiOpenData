<?php
require ('theme/verification.php');
require ('theme/header.php');
require_once ('functions/startFunctionScript.php');
?>

<?php
if (isset($_POST['Archivia'])) {
        $selectedRadio = $_POST['update_archivia'];
        foreach ($selectedRadio as $value){
        dbLogTextUpdateSend($value);  
        }
    }
?>
<!-- start banner Area -->
<section class="banner-area relative" id="home">	
	<div class="container">				
		<div class="row d-flex align-items-center justify-content-center">
			<div class="about-content col-lg-12">
				<h1 class="text-white">
				Messaggi inviati a tutti				
				</h1>	
				<p class="text-white link-nav"><a href="admin.php">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="fullSend.php"> Messaggi inviati a tutti</a></p>
			</div>	
		</div>
	</div>
</section>
<!-- End banner Area -->

<!-- Start products Area -->
<section class="products-area section-gap">
	<div class="container">
		<!-- Table for view the variable of bot -->
		<form method="post" action="fullSend.php" />
			<div class="row d-flex justify-content-center"> 
				<button class="genric-btn primary-border circle" type='submit' name='Archivia' value='Archivia' />Archivia</button>
			</div>
			<div class="table-wrapper"> 
				<table id="order">
						<tr>
							<th>Data inserimento</th>
							<th>Inviato da</th>
							<th>Messaggio</th>
							<th>TUTTI<input type="checkbox" id="select_all"/></th>
						</tr>          
					<?php
					/* Cicle for single users of Bot */
					$messageSend = dbLogTextFullSend();
					foreach ($messageSend as $message) { ?>
						<tr>
							<td><?php echo (date('d/m/Y H:i:s', strtotime($message['DataInsert']))); ?></td>
							<td><?php echo $message['Signature']; ?></td>
							<td><?php echo $message['Text']; ?></td>
							<td><input class="checkbox" type="checkbox" name="update_archivia[]" value="<?php echo $message['ID']; ?>" /> </td> 
						</tr>
					<?php } ?>      
				</table>
			<script type="text/javascript">
			//select all checkboxes
			$("#select_all").change(function(){  //"select all" change
				var status = this.checked; // "select all" checked status
				$('.checkbox').each(function(){ //iterate all listed checkbox items
					this.checked = status; //change ".checkbox" checked status
				});
			});

			$('.checkbox').change(function(){ //".checkbox" change
				//uncheck "select all", if one of the listed checkbox item is unchecked
				if(this.checked == false){ //if this item is unchecked
					$("#select_all")[0].checked = false; //change "select all" checked status to false
				}
			   
				//check "select all" if all checkbox items are checked
				if ($('.checkbox:checked').length == $('.checkbox').length ){
					$("#select_all")[0].checked = true; //change "select all" checked status to true
				}
			});
			</script>
			</div>	
		</form>	
	</div>
</section>
<!-- End products Area -->

<!-- Footer page -->
<?php include('theme/footer.php'); ?>