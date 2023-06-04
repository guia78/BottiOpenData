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
				Gestione amministratore di [Bot]Ti.		
				</h1>	
				<p class="text-white link-nav"><a href="admin.php">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="addAdmin.php"> Gestione Admin</a></p>
			</div>	
		</div>
	</div>
</section>
<!-- End banner Area -->

<!-- Start products Area -->
<section class="products-area section-gap">
	<div class="container">
		<!-- Start control admin -->
		<?php if (($_SESSION['level']) == 'admin') { ?>
		<div class="row d-flex justify-content-center">
		<?php
		if (isset($_POST["Salva"])) {
			$idUser = filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_STRING);
			$user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_EMAIL);
			$level = filter_input(INPUT_POST, 'level', FILTER_SANITIZE_STRING);
			$salva = filter_input(INPUT_POST, 'Salva', FILTER_SANITIZE_STRING);
			if($level=="admin"){
				$putStato="Admin";   
			} else {
				$putStato="User";
			}
			//controllo se i campi sono compilati
			if (!empty($salva)) {
				if (dbChangeLevelAdmin ($idUser, $level) == 1){
				echo'<p><h3>E\' accorso un errore, ritenta nuovamente.</h3></p>';
				} else {
				echo'<p><h3>Hai aggiornato l\'utente: <strong>'.$user.'</strong> a questo stato: '.$putStato.' in modo corretto</h3></p>';
				}   
			}
		}
		?>
		</div>
		<div class="row d-flex justify-content-center">
			<h4>Utenti amministratori presenti nel BOT</h4>
		</div>
		<!-- Table for view the user in the bot -->
		<div class="table-wrapper"> 
			<table id="order"> 
				<tr>
				  <th>Nome Utente</th>
				  <th>Firma</th>
				  <th>Profilo</th>
				  <th>Aggiorna livello</th>
				  <th>Stato attuale</th>
				  <th>Attiva/Disattiva</th>
				</tr>
				<?php $activeAdmin = dbSelectAllAdmin();
				foreach ($activeAdmin as $user) { 
					if($user['active']==1){
						$stato="Attivo";   
					} else {
						$stato="Disattivo";
					}
					echo'<tr>'
					.   '<td>'.$user['username'].'</td>'
					.   '<td>'.$user['signature'].'</td>'
					.   '<td>'.$user['level'].'</td>'
					.   '<td>'
					.   '<form method="post" action="addAdmin.php" method="POST">'
					.   '<input type="hidden" id="idUser" name="idUser" value='.$user['id'].'/>'
					.   '<input type="hidden" id="idUser" name="user" value='.$user['username'].'/>'
					.	'<div class="form-select">'
					.   '<select name="level">';
					if($user['level']=="admin"){
						echo '<option value="user">User</option>';  
					} else {
						echo '<option value="admin">Admin</option>';
					}
					echo   '</select>'
					.	'</div>'
					.   '<input type="submit" class="genric-btn primary-border small" name="Salva" value="Salva" />'
					.   '</form>'
					.   '<td>'.$stato.'</td>'
					.   '<td>'
					.   '<form method="post" action="addAdmin.php" method="POST">'
					.   '<input type="hidden" id="idUser" name="idUser" value='.$user['id'].'/>'
					.   '<input type="hidden" id="idUser" name="user" value='.$user['username'].'/>'
					.	'<div class="form-select">'
					.   '<select name="stato">';
					if($user['active']==1){
						echo '<option value="0">Disattiva</option>';  
					} else {
						echo '<option value="1">Attiva </option>';
					}
					echo   '</select>'
					.	'</div>'
					.   '<input type="submit" class="genric-btn primary-border small" name="Aggiorna" value="Aggiorna" />'
					.   '</form>'
					.   '</tr>';
					}
				?>
			</table>
			</div>

		<?php
		if (isset($_POST["Aggiorna"])) {
			$idUser = filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_STRING);
			$user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_EMAIL);
			$active = filter_input(INPUT_POST, 'stato', FILTER_SANITIZE_STRING);
			$aggiorna = filter_input(INPUT_POST, 'Aggiorna', FILTER_SANITIZE_STRING);
			if($active==1){
				$putStato="Attivo";   
			} else {
				$putStato="Disattivo";
			}
			//controllo se i campi sono compilati
			if (!empty($aggiorna)) {
				if (dbChangeStateAdmin ($idUser, $active) == 1){
				echo'<div class="row d-flex justify-content-center"> 
					<p align="center"><h2>E\' accorso un errore, ritenta nuovamente.</h2></p>
					</div>';
				} else {
				echo'<div class="row d-flex justify-content-center"> 
					<p align="center"><h2>Hai '.$putStato.' correttamente l\'utente: <strong>'.$user.'</strong></h2></p>
					</div>';
				}   
			}
		}
		?>
		</div>
		<div class="row d-flex justify-content-center"> 
			<h3>Per inserire un nuovo utente compila questi campi:</h3>
		</div>
		<div class="row d-flex justify-content-center"> 
			<form id='pwd' action='addAdmin.php' method='post' accept-charset='UTF-8'>
				<fieldset>
					<div class="mt-10">
						<input type='text' id='username' name='username' placeholder='Nome Utente' onfocus="this.placeholder = ''" onblur="this.placeholder = 'username'" maxlength='50' required class="single-input">
					</div>
					<div class="mt-10">
						<input type='text' id='password'  name='password' placeholder='Password' onfocus="this.placeholder = ''" onblur="this.placeholder = 'password'" maxlength='50' required class="single-input">
					</div>
					<div class="mt-10">
						<input type='text' id='repeatePassword' name='repeatePassword' placeholder='Reinserisci la password' onfocus="this.placeholder = ''" onblur="this.placeholder = 'repeatePassword'" maxlength='50' required class="single-input">
					</div>
					<div class="mt-10">
						<input type='text' id='signature' name='signature' placeholder='Inserisci la firma' onfocus="this.placeholder = ''" onblur="this.placeholder = 'signature'" maxlength='50' required class="single-input">
					</div>
					<p align="center"><input class="genric-btn primary-border circle" type='submit' name='Aggiungi' value='Aggiungi nuovo utente' required="1"/></p>
				</fieldset>
			</form>			
<?php
		if (isset($_POST["Aggiungi"])) {
			$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_EMAIL);
			$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
			$repeatePassword = filter_input(INPUT_POST, 'repeatePassword', FILTER_SANITIZE_STRING);
			$signature = filter_input(INPUT_POST, 'signature', FILTER_SANITIZE_STRING);
			$aggiungi = filter_input(INPUT_POST, 'Aggiungi', FILTER_SANITIZE_STRING);
			//Array of control input for any field
			$controllo = array($username,$password,$repeatePassword,$signature);
			foreach($controllo as $item){
				if(empty($item)){ 
					$error = "Non hai compilato tutti i campi per poter inserire il nuovo utente.";   
				}
			}         
			if (!empty($aggiungi)){
				if(!empty($error)){ 
					echo'<div class="row d-flex justify-content-center"> '
					.   '<h2>'.$error.'</h2>'
					.   '</div>';
				} else {
					if ($repeatePassword == $password){
					// Function to insert new user
					if( dbInsertAdmin ($username, $password, $signature) == 1){
						echo'<div class="content-row">'
						.   '<h2>Hai inserito un utente gi&agrave; presente nella banca dati. Scegli un username differente.</h2>'
						.   '</div>';
					} else {
						echo'<div class="row d-flex justify-content-center"> '
						.   '<h2>Hai inserito correttamente l\'utente: '.$username.'</h2>'
						.   '<br>Al prossimo login puoi effettuare l\'accesso con il nuovo utente. Aggiorna la pagina per vedere le modifiche agli elenchi.'
						.   '</div>';
					}
					} else {
						echo'<div class="row d-flex justify-content-center"> '
						.   '<h2>Hai inserito la password in modo errato, riprova nuovamente.</h2>'
						.   '</div>';
					}
				}
			}
		}
		?>
		</div>
		<div class="row d-flex justify-content-center"> 
			<h3>Per cambiare la firma compila questo campo:</h3>
		</div>
		<div class="row d-flex justify-content-center"> 
				<form id='changeSignature' action='addAdmin.php' method='post' accept-charset='UTF-8'>
				<fieldset>
				<div class="mt-10">
					<input type='text' id='signature' name='signature' placeholder='Inserisci la firma' onfocus="this.placeholder = ''" onblur="this.placeholder = 'signature'" maxlength='50' required class="single-input">
				</div>
				<p align="center"><input class="genric-btn primary-border circle" type='submit' name='Cambia' value='Cambia la Firma' /></p>
				</fieldset>
				</form>
		<?php
		if (isset($_POST["Cambia"])) {
			$username=$_SESSION['username'];
			$signature=filter_input(INPUT_POST, 'signature', FILTER_SANITIZE_STRING);
			$cambia = filter_input(INPUT_POST, 'Cambia', FILTER_SANITIZE_STRING);
			if (!empty($cambia)) { 
				if(empty($signature)){
				echo'<div class="row d-flex justify-content-center"> '
					.   '<p><br><br>'
					.   '<h2>Devi compilare il campo della firma. Non pu&ograve; essere vuoto.</h2>'
					.   '</p></div>';    
				} else {
				if(dbChangeSignatureAdmin ($username, $signature) == 1){
					echo'<div class="row d-flex justify-content-center"> '
					.   '<p><br><br>'
					.   '<h2>Sono accorsi degli errori, ritenta il cambio firma.</h2>'
					.   '</p></div>';
				  } else {
					echo '<div class="row d-flex justify-content-center"> '
					.   '<p><br><br>'
					.   '<h2>Hai inserito la firma: "'.$signature.'" per l\'utente '.$username.'</h2>'
					.   '<br>Aggiorna la pagina per vedere le modifiche.'
					.   '</p></div>';
				}
				}
			}   
		}
		?>
		</div>
		<?php } else { ?>         
		<div class="row d-flex justify-content-center"> 
			<h2><b>Utente non autorizzato </b></h2>
		</div>
		<?php } ?>
		<!-- End control admin -->
	</div>
</section>
<!-- End products Area -->

<!-- Footer della pagina html -->
<?php include ('theme/footer.php'); ?>