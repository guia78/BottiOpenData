<?php
/* 
 * Page of login for only Admin user
 * 
 */
session_start();

include ('theme/header.php');
require_once ('functions/startFunctionScript.php');
?>

<?php
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$captcha = filter_input(INPUT_POST, 'captcha');
$submit = filter_input(INPUT_POST, 'Submit', FILTER_SANITIZE_STRING);

if (!empty($submit)){
	if ($captcha != $_SESSION['captcha']){ ?>
		<!-- start Area -->
		<section class="banner-area" id="home">
			<div class="container">
				<div class="row fullscreen d-flex align-items-center justify-content-center">
					<div class="banner-content col-lg-6 col-md-6">
						<h2>Codice di sicurezza non valido:<br>
						<a href="index.php">Torna alla pagina di Login</a></h2>
					</div>
				</div>
			</div>
		</section>
		<!-- End start Area -->
	<?php
	} else {
		$conn = getDbConnection();
		$sql = "select username,password,signature,level from admins where username=:username and active=1";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':username',$username, PDO::PARAM_STR);
		$stmt->execute();
		$riga = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($riga != FALSE && validate_password($password, $riga['password'])){
			$_SESSION['username']=$riga['username'];
			$_SESSION['signature']=$riga['signature'];
			$_SESSION['level']=$riga['level'];
		} else {
			unset($_SESSION['username']);
		}
	}
}

if (!empty($_SESSION['username'])){
    header('Location: admin.php');?>
	<!-- start Area -->
	<section class="banner-area" id="home">
		<div class="container">
			<div class="row fullscreen d-flex align-items-center justify-content-center">
				<div class="banner-content col-lg-6 col-md-6">
					<h1>Hai gi&agrave; effettuato il login.</h1>
					<h2>Usa il men&ugrave; per spostarti nelle funzioni</h2>
				</div>
			</div>
		</div>
	</section>
	<!-- End start Area --> 
<?php } else { ?>
    <!-- start Area -->
	<section class="banner-area" id="home">
		<div class="container">
			<div class="row fullscreen d-flex align-items-center justify-content-center">
				<div class="banner-content col-lg-6 col-md-6">
					<h2>Username o password non validi, ritenta:<br>
					<a href="index.php">Torna alla pagina di Login</a></h2>
				</div>
			</div>
		</div>
	</section>
    <!-- End start Area -->
<?php } ?>

<!-- Footer page -->
<?php include ('theme/footer.php');?>