<!DOCTYPE html>
<html lang="ita" class="no-js">
	<head>
		<!-- Mobile Specific Meta -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Favicon-->
		<link rel="shortcut icon" href="theme/img/favicon.ico">
		<!-- Author Meta -->
		<meta name="author" content="Matteo Guion">
		<meta name="email" content="info[at]guion78[dot]com">
		<meta name="copyright" content="Copyright 2018-2025 © Matteo Guion">
		<!-- Meta Description -->
		<meta name="description" content="Piattaforma per la gestione dei Bot">
		<!-- Meta Keyword -->
		<meta name="keywords" content="[Bot]Ti">
		<!-- meta character set -->
		<meta charset="UTF-8">
		<!-- Site Title -->
		<title>[Bot]Ti</title>
		<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet"> 
			<!--
			CSS
			============================================= -->
			<link rel="stylesheet" href="theme/css/linearicons.css">
			<link rel="stylesheet" href="theme/css/font-awesome.min.css">
			<link rel="stylesheet" href="theme/css/bootstrap.css">
			<link rel="stylesheet" href="theme/css/magnific-popup.css">
			<link rel="stylesheet" href="theme/css/nice-select.css">	
			<link rel="stylesheet" href="theme/css/hexagons.min.css">							
			<link rel="stylesheet" href="theme/css/animate.min.css">
			<link rel="stylesheet" href="theme/css/owl.carousel.css">
			<link rel="stylesheet" href="theme/css/main.css">
			<!-- 
			jQuery
			=============================================
			<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhOdIF3Y9382fqJYt5I_sswSrEw5eihAA"></script>
			-->
			<script src="theme/js/vendor/jquery-2.2.4.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
			<script src="theme/js/vendor/bootstrap.min.js"></script>			
			<script src="theme/js/easing.min.js"></script>			
			<script src="theme/js/hoverIntent.js"></script>
			<script src="theme/js/superfish.min.js"></script>	
			<script src="theme/js/jquery.ajaxchimp.min.js"></script>
			<script src="theme/js/jquery.magnific-popup.min.js"></script>	
			<script src="theme/js/owl.carousel.min.js"></script>	
			<script src="theme/js/hexagons.min.js"></script>							
			<script src="theme/js/jquery.nice-select.min.js"></script>	
			<script src="theme/js/jquery.counterup.min.js"></script>
			<script src="theme/js/waypoints.min.js"></script>							
			<script src="theme/js/mail-script.js"></script>	
			<script src="theme/js/main.js"></script>
			<!-- Insert personal script -->	
			<script src='https://www.google.com/recaptcha/api.js?hl=it'></script> 
			<script type="text/javascript" src="theme/js/calendar/ts_picker.js"></script>
    </head>
	<body>	
		<header id="header">
			<div class="container main-menu">
				<div class="row align-items-center justify-content-between d-flex">
				  <div id="logo">
					<a href="index.php"><img src="theme/img/logo.png" alt="[Bot]Ti" title="[Bot]Ti" /></a>
				  </div>
				  <nav id="nav-menu-container">
				  <ul class="nav-menu">
				  <?php if (!empty($_SESSION['username'])) { ?> 
				  <li class="menu-has-children"><a href="">Gestione</a>
					<ul>
					<li><a href="admin.php">Invia messaggi</a></li>
					<li><a href="message.php">Messaggi di utenti</a></li>
					<li><a href="fullSend.php">Messaggi di massa</a></li>
					<li><a href="user.php">Utenti attivi</a></li>
					<li><a href="userLog.php">Log User</a></li>
					<li><a href="userGB.php">Ex User</a></li>
					<li><a href="statisticsUser.php">Funzioni usate</a></li>
					</ul>
				  </li>
				  <li class="menu-has-children"><a href="">Pianifica Invio</a>
					<ul>
					<li><a href="crontabInsert.php">Inserisci</a></li>
					</ul>
				  </li>
				  <li class="menu-has-children"><a href="">Pannello Controllo</a>
					<ul>
					<li><a href="queue.php">Coda Telegram</a></li>
					<li><a href="panel.php">Gestione</a></li>
					<li><a href="panelButton.php">Gestione Pulsanti</a></li>
					<li><a href="updateCron.php">Update Dati</a></li>
					<li><a href="panelButtonTag.php">Gestione Tag</a></li>
					<li><a href="changePwd.php">Cambio password</a></li>
					<li><a href="addAdmin.php">Gestione Admin</a></li>
					</ul>
				  </li>
				  <li class="menu-has-children"><a href="">Aggiornamenti</a>
					<ul>   
					<li><a href="update.php">Controlla</a></li>
					</ul>
				  </li>
				  <li><a href="help.php">Help / Informazioni</a></li>
				  <li><a href="logout.php">Esci 
				  <?php echo " / ".strtoupper($_SESSION['username']); ?></a></li>
				  <?php } else { ?>
				  <li><a href="index.php">Home page [Bot]Ti</a></li>
				  <?php }  ?>			              			              
						  </ul>
						  </nav><!-- #nav-menu-container -->		    		
				</div>
			</div>
		  </header>
<!-- Fine header -->