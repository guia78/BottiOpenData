<?php
/*
 * Ultimate revision 01/04/2018
 */
require_once ('config.php');
require_once ('functions/function.php');
require_once ('functions/functionDb.php');
require_once ('functions/functionInit.php');
require_once ('functions/functionPlugin.php');
require_once ('functions/functionTwitter.php');
require_once ('functions/functionRss.php');
require_once ('functions/functionBitly.php');
#require_once ('functions/PHPMailer/src/PHPMailer.php');
#require_once ('functions/PHPMailer/src/SMTP.php');
#require_once ('functions/PHPMailer/src/Exception.php');
#require_once ('functions/PHPMailer/src/OAuth.php');
require_once ('functions/pdfConvert/lib/GrabzItClient.class.php');
?>

<?php
$last_id = null;
while(true) {
	$args = array();
	if ($last_id) {
		$args["offset"] = $last_id;
	}
	$args["timeout"] = 200;

	$updates = apiRequest("getUpdates", $args);
	if ($updates === false){
	/*
	 * Received an error
	 */
		sleep(1);
		continue;
	}
	foreach ($updates as $update) {
		/*
		 * Start/Stop Demone
		*/
		$status=dbDemoneStatus();
		if($status=="1"){
			$last_id = $update["update_id"] + 1;
			if (isset($update["message"])) {
				processMessage($update["message"]);
			}
		}else{
			sleep(15); 
		}
	}
} //end cicle while