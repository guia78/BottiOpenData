<?php
require_once ('functions/function.php');
require_once ('functions/functionDb.php');
require_once ('config.php');
require_once ('functions/simplepie/autoloader.php');
require_once ('functions/functionPassword.php');
require_once ('functions/functionPlugin.php');
require_once ('functions/functionInit.php');
require_once ('functions/PHPMailer/src/PHPMailer.php');
require_once ('functions/PHPMailer/src/SMTP.php');
require_once ('functions/PHPMailer/src/Exception.php');
require_once ('functions/pdfConvert/lib/GrabzItClient.class.php');
require_once ('functions/functionBitly.php');

?>
<?php
//Questa funzione serve per inviare dei messaggi schedulati
$messageSchedulation = dbSchedulerExtraction("ID is not null");
//Format Date: 2016-05-07 21:30:00 
$currentSend = date("Y-m-d H:i:s"); 
$currentDate = strtotime($currentSend);

foreach ($messageSchedulation as $program){
	$schedulazione = strtotime($program['DataScheduler']);
	$numCron = $program['ID'];
	$sent = $program['AlreadySent'];
	$testo_ricevuto = $program['Text'];
	//Se cron attivo allora ha valore 1
	if ($schedulazione < $currentDate && $sent==1){
		//disattivo subito il cron per non avere ripetizioni
		dbCronUpdate($numCron);
		//estraggo gli utenti
		$activeUsers = dbActiveUsers();
		foreach ($activeUsers as $user) {
			//Control for channel
			if (strpos($user, "@") === FALSE) {
				$controlActive = dbServiceSelect($user, 'Message', '0');
				if(empty($controlActive)){    
				sendMessage($user, $testo_ricevuto);
				}
			} else {
				sendMessageChannel($user, $testo_ricevuto);
			}  
		}
		//registro operazione
		dbLogTextSend ($testo_ricevuto,'schedulato','','');
		//invio mail al gestore
		$currentTerm = date("Y-m-d H:i:s"); 
		sendMail("Messaggio Schedulato [Bot]Ti","Ho mandato il messaggio schedulato dalle ".$currentSend." alle ".$currentTerm.", con il seguente testo: $testo_ricevuto.");
	}
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="copyright" content="Copyright 2019-2025 © Matteo Guion">
    <title>[Bot]Ti</title>
</head>
    <div>
    Area Riservata, accesso NEGATO
    </div>
    </body>
</html>