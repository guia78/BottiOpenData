<?php
session_start();
if (empty($_SESSION['username'])) {
include('theme/header.php');
?>

<!-- start Area -->
<section class="banner-area" id="home">
    <div class="container">
        <div class="row fullscreen d-flex align-items-center justify-content-center">
            <div class="banner-content col-lg-6 col-md-6">
                <h1><a href="index.php">Fai nuovamente il login.</a></h1>																										
            </div>
        </div>
    </div>
</section>
<!-- End Area -->
<?php 
include ('theme/footer.php');
die(); } ?>