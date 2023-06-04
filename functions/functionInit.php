<?php
/* 
 * Function for more services
 * 
 */

/*
 * initSendAnswer
 * 
 * for widows: http://www.brinkster.com/KB/Article~KBA-01132-T2P9H8~How-do-I-send-email-with-PHPMailer-for-Windows-Hosting%3F
 * for use with gmail setting ON "Allow less secure apps: ON" in this page https://myaccount.google.com/security?pli=1#activity
 *
 * @param $chat_id,$first_name_id,$message_id,$text, 
 * Permette l'inserimento in Bot di messaggi non conmtemplati in altre funzioni e invio mail di avviso 
 *  
 * @return anything
 */
function initSendAnswer($chat_id,$first_name_id,$message_id,$text)
{
    // Extract param for message responce where not responce found
    $tableParmExit = dbParamExtraction('SoftDesc = "Message" AND Active = "1"');
    foreach ($tableParmExit as $param) {
        if ($param['Code'] == "error"){
            $messageError = $param['Param'];
        }  
    }
    if($messageError != ''){
	apiRequest("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => "Ciao ".$first_name_id.", ".$messageError));
    }
    // Insert log action
    dbLogTextOn($chat_id,$first_name_id,$message_id,$text);
    // Extract info url site
    $tableDomain = dbParamExtraction('SoftDesc = "Domain" AND Active = "1"');
    foreach ($tableDomain as $paramDomain) {
            if ($paramDomain['Code'] == "name"){$domain = $paramDomain['Param'];}
    }
    // Send mail for alert request
    sendMail("Messaggio dal Bot Telegram","<!doctype html><html lang=\"it\"><body>Inviato da: ".$first_name_id."</br> Testo del messaggio: ".$text."</br> Rispondi da: ".$domain."</body></html>");
    //Extract param for message responce with Google Search
    $tableParmSearch = dbParamExtraction('SoftDesc = "Search" AND Active = "1"');
    foreach ($tableParmSearch as $param) {
        if ($param['Code'] == "url"){
            $link = $param['Param'];
        }
        if ($param['Code'] == "text"){
            $linkText = $param['Param'];
        }   
    }
    if($link != '' & $linkText != ''){
        $text = str_replace(" ","+" ,$text);
        $messagePrivate = $linkText . " " . $link . $text;
        apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $messagePrivate, 'disable_web_page_preview' => true));
    }
}

/*
 * sendMail
 * Function for send e-mail
 * This function is not complete
 * Next step implement to send e-mail for alert the 
 * 
 */
function sendMail($subject, $corpo_messaggio)
{
    $mittente = "";
    $nomemittente = "";
    $destinatario = "";
    $nomedestinatario  = "";
    $serversmtp = "";
    $port = "";
    $secure = "";       // Setting the tsl, ssl ecc
    $username = "";     // User server SMTP autentich
    $password = "";     // Password server SMTP autentich
    // Extract to Db the setting parameter
    $tableParm = dbParamExtraction('SoftDesc = "Mail" AND Active = "1"');
    foreach ($tableParm as $param) {
	if ($param['Code'] == "mittente"){$mittente = $param['Param'];}
        if ($param['Code'] == "nomemittente"){$nomemittente = $param['Param'];}
        if ($param['Code'] == "destinatario"){$destinatario = $param['Param'];}
        if ($param['Code'] == "nomedestinatario"){$nomedestinatario = $param['Param'];}
        if ($param['Code'] == "serversmtp"){$serversmtp = $param['Param'];}
        if ($param['Code'] == "port"){$port = $param['Param'];}
        if ($param['Code'] == "secure"){$secure = $param['Param'];}
        if ($param['Code'] == "username"){$username = $param['Param'];}
        if ($param['Code'] == "password"){$password = $param['Param'];}
    }
    // Control parameter also skeep
    if (empty($mittente)){return "Errore di configurazione per il mittente";}
    if (empty($destinatario)){return "Errore di configurazione per il destinatario";}
    if (empty($serversmtp)){return "Errore di configurazione per il server smtp";}
    if (empty($port)){return "Errore di configurazione per la porta";}
    if (empty($username)){return "Errore di configurazione per nome utente";}
    if (empty($password)){
	return "Errore di configurazione per la password";
    } else {   
        /*
        // For setting
        $messaggio = new PHPMailer\PHPMailer\PHPMailer(true);
        $messaggio->IsSMTP(); 
        */
        /* Enable SMTP debugging
         * 0 = off (for production use)
         * 1 = client messages
         * 2 = client and server messages
         */
        /*
        $messaggio->SMTPDebug = 0;
        $messaggio->SMTPAuth = true;     // abilita autenticazione SMTP
        $messaggio->SMTPKeepAlive = "true";
        $messaggio->isHTML(false);
        $messaggio->Host  = $serversmtp;
        $messaggio->Port = $port;
        $messaggio->SMTPSecure = $secure;
        $messaggio->Username = $username;
        $messaggio->Password = $password;
        $messaggio->Subject = $subject;
        $messaggio->CharSet = "UTF-8";
        $messaggio->setFrom($mittente, $nomemittente);
        $messaggio->addReplyTo($destinatario, $nomedestinatario);
        $messaggio->addAddress($destinatario, $nomedestinatario);
        $messaggio->Body  = $corpo_messaggio;
        $messaggio->AltBody = 'This is a plain-text message body';
        $messaggio ->Send();
        return $messaggio->ErrorInfo;
        */
    }
}

/*
 * buttonDemone
 * Function for menu of bot (demone)
 * 
 * @return array  
 */
function buttonDemone()
{
    $buttonArray[] = dbParamExtraction('SoftDesc = "Button" AND Active = 1'); 
}

/*
 * initShort
 * Function for return the Short Link from Service
 * Use the Api for Bitly
 * 
 * Return link not short (not key found)
 * Return short link if key found
 * 
 * @return array
 */
function initShort($link)
{ 
    $long_url = "$link";
    $apiv4 = 'https://api-ssl.bitly.com/v4/bitlinks';
    $params = array();
    // Create instance with key
    // Extract BitLy API key
    $tableParm = dbParamExtraction('SoftDesc = "Bitly" AND Active = "1"');
    $bitlyKey = "";
    foreach ($tableParm as $param) {
        if ($param['Code'] == "key"){$bitlyKey = $param['Param'];}
    }
    if($bitlyKey != ""){
        $genericAccessToken = $bitlyKey;
    }
    $data = array(
        'long_url' => $long_url
    );
    $payload = json_encode($data);

    $header = array(
        'Authorization: Bearer ' . $genericAccessToken,
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload)
    );

    $ch = curl_init($apiv4);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $result = curl_exec($ch);
    $resultToJson = json_decode($result);

    if (isset($resultToJson->link)) {
        return $resultToJson->link;
    }
    else {
        return '$link';
    }
}

/*
 * street
 * Function for geodecoding the coordinate
 * Code from Andrea Vigato: http://andreavigato.it/blog/gmaps-reverse-geocoding-in-php
 * This function is not free and is possibile only with the payment the API code of Google with
 * 
 * @param array $coordinate array latitude and logitude
 * 
 * @return string 
 */
 
function street($coordinate)
{
    // $lat e $lng sono le variabili che contengono latitudine e longitudine
    $lat = $coordinate[0];
    $lng = $coordinate[1];

    $reverseUrl = "http://maps.googleapis.com/maps/api/geocode/xml?latlng=".$lat.",".$lng;
    // Example 1: https://maps.googleapis.com/maps/api/geocode/json?latlng=40.714224,-73.961452&key=xxxx
    // Example 2: http://maps.googleapis.com/maps/api/geocode/xml?latlng=40.714224,-73.961452&sensor=false

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$reverseUrl);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/40.0.0.0');
    $result = curl_exec($ch);
    curl_close($ch);
    /*
    * Or use:
    * preg_match_all('/<formatted_address>(.*)<\/formatted_address>/m', $result, $outputAddressAll, PREG_SET_ORDER); 
    */
    preg_match_all('#<formatted_address>(.*?)</formatted_address>#s', $result, $outputAddressAll, PREG_SET_ORDER);
    /* 
     * Type of result for: 
     * $x == 1 –> indirizzo completo
     * $x == 2 –> cap città, provincia, stato
     * $x == 3 –> città, provincia, stato
     * $x == 4 –> provincia, stato
     * $x == 5 –> regione, stato
     * $x == 6 –> stato
    */
    $type = 1; // Init variable
    foreach($outputAddressAll as $address){
        if($type==1){ 
            // Type of output
            // Position one of array $outputAddressAll <item>xxx</item>
            $outAddress = $address[1];
        }
        $type++;
    }
    if(empty($outAddress)){
        return ' Servizio Google a pagamento =(';
    }else{
        return $outAddress;
    }
}

/*
 * topMenu
 * Function for create the menu for site page
 * 
 * @param array $menu array associativo con descrizione del link => link
 */
function topMenu($menu){
    echo '<div>';
    echo '<ul id="admin_menu">';
    printMenuItems($menu);
    echo '</ul>';
    echo '</div>';
}

/*
 * printMenuItems
 * Function for print the menu
 * 
 * @param array $menu array associativo con descrizione del link => link
 */
function printMenuItems($menu){
    foreach ($menu as $nome=>$uri) {
	echo '<li><a href="'.$uri.'">'.$nome.'</a></li> ';
    }
}

$menu = array(
    'Utenti attivi in [Bot]Ti'=>'user.php',
    'Coda dal sito Telegram'=>'coda.php',
    'Messaggi degli utenti'=>'message.php',
    'Messaggi inviati'=>'fullSend.php',
    'Cambio password'=>'pwd.php'
    );
    
/*
 * randomFile
 * Function for create the new file everytime (problem cache file telegram app)
 * 
 * Code from: http://free-script.it/post/Script_php_Copiare_un_file_remoto_sul_proprio_server-62.htm
 * Function for downalod a File and rename (from site with static image) 
 * @return url for download a save file
 */

function randomFile($urlFile,$ext)
{
    /*
    *  Variable setting
    */  
    set_time_limit(300);
    // This is the folder for downalod (the possible creare a parameter for setting)
    $urlFolder = "/upload_img/send/";  

    // Path remote file
    $remoteFile="$urlFile";
    // Local folder into copy the remote file
    $path = dirname($_SERVER['PHP_SELF']);
    // Path absolute for downaload
    $folderPath=$path.$urlFolder; 
    // Open the remote file for read with support SSL 10/06/2019 (no control certificate)
    $opts=array(
            "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
            ),
    );
    $srcfile1 = fopen("$remoteFile", 'rb', false, stream_context_create($opts));
    // Capture the name of file
    $nameFile=basename($remoteFile);
    //apro il file in locale
    if (!($fp1 = fopen($folderPath.$nameFile,"w")));
    //scrivo contenuto del file remoto, ora in temp file, in file locale
    while ($contents = fread( $srcfile1, 8192 )) {
            fwrite( $fp1, $contents, strlen($contents) );
    }
    $newNameTmp = date('m-d-Y_hia');
    $newName = $folderPath.$newNameTmp.$nameFile.$ext;
    $oldName = $folderPath.$nameFile;
    rename($oldName, $newName);
    $newNameUrl =  $urlFolder.$newNameTmp.$nameFile.$ext;
    //chiudo i due files
    fclose($srcfile1);
    fclose($fp1);
    /*
    * Extract domain name from DB config
    * alternative insert domain here in this mode: $site = "http://site-url.it/bot/";
    */
    $tableParm = dbParamExtraction('SoftDesc = "Domain" AND Active = "1"');
    foreach ($tableParm as $param) {
        if ($param['Code'] == "name"){$site = $param['Param'];}
    }
    $newNameUrl = substr($newNameUrl,1);
    $urlComplete = $site.$newNameUrl;
    return $urlComplete;
}
  
/*
 * emoticonConvert
 * Convert text to code of emoticons (problem Unicode/UTF-8 in telegram)
 * 
 * @return array of list emoticons
 */

function emoticonConvert($txtUnicode)
{
	/*
	*  Array of emoticons
	*/
	$emoticons = array(); //setting variable
	//Emoticons decode UTF-8
	$emoticons[] = array("PAPERCLIP", "\xF0\x9F\x93\x8E");
	$emoticons[] = array("BLACK SUN WITH RAYS", "\xE2\x98\x80");
	$emoticons[] = array("AMBULANCE", "\xF0\x9F\x9A\x91");
	$emoticons[] = array("PILL", "\xF0\x9F\x92\x8A");
	$emoticons[] = array("FUEL PUMP", "\xE2\x9B\xBD");
	$emoticons[] = array("SUNRISE OVER MOUNTAINS", "\xF0\x9F\x8C\x84");
	$emoticons[] = array("ARIES", "\xE2\x99\x88");
	$emoticons[] = array("TAURSU", "\xE2\x99\x89");
	$emoticons[] = array("GEMINI", "\xE2\x99\x8A");
	$emoticons[] = array("CANCER", "\xE2\x99\x8B");
	$emoticons[] = array("LEO", "\xE2\x99\x8C");
	$emoticons[] = array("VIRGO", "\xE2\x99\x8D");
	$emoticons[] = array("LIBRA", "\xE2\x99\x8E");
	$emoticons[] = array("SCORPIUS", "\xE2\x99\x8F");
	$emoticons[] = array("SAGITTARIUS", "\xE2\x99\x90");
	$emoticons[] = array("CAPRICORN", "\xE2\x99\x91");
	$emoticons[] = array("AQUARIUS", "\xE2\x99\x92");
	$emoticons[] = array("PISCES", "\xE2\x99\x93");
	$emoticons[] = array("FACTORY", "\xF0\x9F\x8F\xAD");
	$emoticons[] = array("NEWSPAPER", "\xF0\x9F\x93\xB0");
	$emoticons[] = array("CINEMA", "\xF0\x9F\x8E\xA6");
	$emoticons[] = array("KEY", "\xF0\x9F\x94\x91");
	$emoticons[] = array("PUSHPIN", "\xF0\x9F\x93\x8C");
	$emoticons[] = array("INFORMATION", "\xE2\x84\xB9");
	$emoticons[] = array("HAMBURGER", "\xF0\x9F\x8D\x94");
	$emoticons[] = array("SUNRISE", "\xF0\x9F\x8C\x85");
	$emoticons[] = array("SUNSET OVER BUILDINGS", "\xF0\x9F\x8C\x87");
	$emoticons[] = array("SUN WITH FACE", "\xF0\x9F\x8C\x9E");
	$emoticons[] = array("DOUBLE EXCLAMATION MARK", "\xE2\x80\xBC");
	$emoticons[] = array("PARTY POPPER", "\xF0\x9F\x8E\x89");
	$emoticons[] = array("CONFETTI BALL", "\xF0\x9F\x8E\x8A");
	$emoticons[] = array("FORK AND KNIFE", "\xF0\x9F\x8D\xB4");
	$emoticons[] = array("CAMERA PHOTO", "\xF0\x9F\x93\xB7");
	$emoticons[] = array("SOCCER BALL", "\xE2\x9A\xBD");
	$emoticons[] = array("PERSONAL COMPUTER", "\xF0\x9F\x92\xBB");
	$emoticons[] = array("SNOWFLAKE", "\xE2\x9D\x84");
	$emoticons[] = array("POLICE CARS REVOLVING LIGHT", "\xF0\x9F\x9A\xA8");
	$emoticons[] = array("COOKING", "\xF0\x9F\x8D\xB3");
	$emoticons[] = array("BIRTHDAY CAKE", "\xF0\x9F\x8E\x82");
	$emoticons[] = array("BILLIARDS", "\xF0\x9F\x8E\xB1");
	$emoticons[] = array("ONCOMING BUS", "\xF0\x9F\x9A\x8D");
	$emoticons[] = array("INFORMATION SOURCE", "\xE2\x84\xB9");
	$emoticons[] = array("TELEVISION", "\xF0\x9F\x93\xBA");
	$emoticons[] = array("CLOCK FACE ONE-THIRTY", "\xF0\x9F\x95\x9C");
	$emoticons[] = array("CHART WITH DOWNWARDS TREND", "\xF0\x9F\x93\x89");
	$emoticons[] = array("BOOKS", "\xF0\x9F\x93\x9A");
	$emoticons[] = array("FAMILY", "\xF0\x9F\x91\xAA");
	$emoticons[] = array("BLACK UNIVERSAL RECYCLING SYMBOL", "\xE2\x99\xBB");
	$emoticons[] = array("ELECTRIC LIGHT BULB", "\xF0\x9F\x92\xA1");
	$emoticons[] = array("CONSTRUCTION SIGN", "\xF0\x9F\x9A\xA7");
	$emoticons[] = array("AMBULANCE", "\xF0\x9F\x9A\x91");
	$emoticons[] = array("LEDGER", "\xF0\x9F\x93\x92");
	$emoticons[] = array("CALENDAR", "\xF0\x9F\x93\x85");
	$emoticons[] = array("FUEL PUMP", "\xE2\x9B\xBD");
	$emoticons[] = array("ANTENNA WITH BARS", "\xF0\x9F\x93\xB6");
	$emoticons[] = array("FRENCH FRIES", "\xF0\x9F\x8D\x9F");
	$emoticons[] = array("CHURCH", "\xE2\x9B\xAA");
	
	/*
	*  Convert emoticons
	*/ 
	foreach ($emoticons as $emoticon){
            $txtUnicode = str_replace($emoticon[0],json_decode('"'.$emoticon[1].'"'),$txtUnicode);
	}
	return $txtUnicode;
}

/*
 * truncateMessage
 * Function for bypass the limit text for single message in telegram
 * 
 * @return array of list emoticons
 */

function truncateMessage($txtResult, $chat_id, $user_id, $reply_markup)
{
    $outPut = $txtResult; //cloning
    $lunghezzaLetto = strlen($outPut);
    $maxExport = 3900;
    $ini = 0;
    if($lunghezzaLetto<$maxExport){
        $lettoC = $outPut;
        dbTrackerInsert($chat_id,$user_id,'segue',$lettoC);
        apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $lettoC, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
    }else{
        while ($ini<=$lunghezzaLetto){
            if($maxExport+$ini<=$lunghezzaLetto){
                $lettoC = substr($outPut, $ini, $maxExport);
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $lettoC, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
                dbTrackerInsert($chat_id, $user_id, 'segue',$lettoC);
            }else{
                $lettoC = substr($outPut, $ini, $lunghezzaLetto);
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $lettoC, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
                dbTrackerInsert($chat_id, $user_id, 'segue',$lettoC);
            }
            $ini = $ini+$maxExport;
        }
    }
}	

/*
 * Function: truncateMessageNoPlugin
 * Divide message to input for max length (telegram) 4096 char
 * @return String of message
 * 
 */
function truncateMessageNoPlugin($txtResult, $chat_id, $user_id, $reply_markup)
{
    $outPut = $txtResult; //cloning
    $lunghezzaLetto = strlen($outPut);
    $maxExport = 3900;
    $ini = 0;
    if($lunghezzaLetto<$maxExport){
        $lettoC = $outPut;
        apiRequest("sendMessage", array('chat_id' => $user_id, 'text' => $lettoC, 'parse_mode' => 'HTML', 'disable_web_page_preview' => 'true'));
    }else{
        while ($ini<=$lunghezzaLetto){
            if($maxExport+$ini<=$lunghezzaLetto){
                $lettoC = substr($outPut, $ini, $maxExport);
                apiRequest("sendMessage", array('chat_id' => $user_id, 'text' => $lettoC, 'parse_mode' => 'HTML', 'disable_web_page_preview' => 'true'));
            }else{
                $lettoC = substr($outPut, $ini, $lunghezzaLetto);
                apiRequest("sendMessage", array('chat_id' => $user_id, 'text' => $lettoC, 'parse_mode' => 'HTML', 'disable_web_page_preview' => 'true'));
            }
            $ini = $ini+$maxExport;
        }
    }
}	