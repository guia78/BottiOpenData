<?php      
/*
 * processMessage
 * Function that processes incoming message Telegram Server
 * The first function
*/  

function processMessage($message)
{
    /*
    * Init variable
    */
    $user_id = "";
    $first_name_id = "";
    $message_id = "";
    $first_name = "";
    //Variable setting
    $message_id = $message['message_id'];
    $chat_id = $message['chat']['id'];
    //Keyword
    $key = createKeyboard($chat_id);
    $num0 = $key[0];
    $reply_markup = $key[1];
    if (isset($message['from']['id'])) {
        $user_id=$message['from']['id'];
    } else {
        $user_id='';
    }
    if (isset($message['from']['first_name'])) {
        $first_name_id=$message['from']['first_name'];
    } else {
        $first_name_id='';
    }
    if (isset($message['from']['last_name'])) {
        $last_name_id=$message['from']['last_name'];
    } else {
        $last_name_id='';
    }
    if (isset($message['from']['username'])) {
        $username_id=$message['from']['username'];
    } else {
        $username_id='';
    } 
    /*
    * Update identity of person/group use the Bot
    */
    dbLogUserUpdate ($chat_id,$first_name_id,$last_name_id, $username_id);
    /*
    * Controll Message of Text or another type
    */
    if (isset($message['text'])) {
        $text = $message['text'];
        /*
        * The very function of process messag
        */   
        if (strpos($text, "/start") === 0) {
            apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $num0, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup));
            /*
            * Log the user in DB. If the user is already activated only you change their status in active also.
            */
            dbLogUserStart ($chat_id,$first_name_id,$last_name_id, $username_id);
            $textControl = "";
        } else if ($text === "/stop") {
            /*
             * Here inserted disabling user from the DB (not cleared but only put off)
            */
            $tableParmExit = dbParamExtraction('SoftDesc = "Message" AND Active = "1"');
            foreach ($tableParmExit as $param) {
                if ($param['Code'] == "exit"){
                    $messageExit = $param['Param'];
                }  
            }
            if($messageExit != ""){
                apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "Ciao ".$first_name_id.", ".$messageExit));
            }
            dbLogUserStop ($chat_id);
            $textControl = "";
        } else if (strpos($text, "Stop") === 0) {
            /*
             * For exception 
             * Here inserted disabling user from the DB (not cleared but only put off)
            */
            $tableParmExit = dbParamExtraction('SoftDesc = "Message" AND Active = "1"');
            foreach ($tableParmExit as $param) {
                if ($param['Code'] == "exit"){
                    $messageExit = $param['Param'];
                }  
            }
            if($messageExit != ''){ //For error not found
                apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "Ciao ".$first_name_id.", ".$messageExit));
            }
            dbLogUserStop ($chat_id);
        } else {
            /*
            * Variable for control Bot and Group into insert Bot
            */
            //Copy of variable for more control exit to cicle and query control group telegram
            $textControl = $text; 
            $nameBotForGroup = dbDemFunction('botGroup');
            $textControl = str_replace($nameBotForGroup, '', $textControl);
            //Filtering response for select button for users with title in DB
            $responceKey = dbDemoneKeyboard("Titolo = '". $textControl ."'");
            //Variable empty for message wait processing
            $messageWait = "";
            foreach ($responceKey as $responceKeyFinal){
                //Insert here for control, this is a function, not a button. Implement this control with if function
                if ($responceKeyFinal['Type'] == 'Function'){
                    //Launch function with messagge time wait
                    $tableParm = dbParamExtraction('SoftDesc = "Message" AND Active = "1"');
                    foreach ($tableParm as $param) {
                        if ($param['Code'] == "waiting"){$messageWait = $param['Param'];}
                    }
                    if($messageWait != ""){
                        /*
                        * This is a Function (WITH please wait) with responce
                        */
                        apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $messageWait, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup));
                        $functionPersonal = Launcher($chat_id, $reply_markup, $user_id, $responceKeyFinal['Param']); //Launch function 
                        apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $functionPersonal, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup));
                        dbTrackerInsert($chat_id,$user_id,$text,$functionPersonal);
                        $textControl = "";
                        break; //Exit cicle
                    } else {
                        //This is a Function (WITHOUT please wait) with responce
                        $functionPersonal = Launcher($chat_id,$reply_markup,$user_id,$responceKeyFinal['Param']);  
                        apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $functionPersonal, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup));
                        dbTrackerInsert($chat_id,$user_id,$text,$functionPersonal);
                        $textControl = "";
                        break; //Exit cicle
                    }
                } else if ($textControl == $responceKeyFinal['Titolo']){ //Response for no function request
                    $responceFinal = html_entity_decode($responceKeyFinal['Param']);
                    $responceFinal = str_replace ("&#39;","'" ,$responceFinal);
                    $responceFinalEmoticons =  emoticonConvert($responceFinal);
                    truncateMessageNoPlugin($responceFinalEmoticons, $chat_id, $user_id, $reply_markup);
                    dbTrackerInsert($chat_id,$user_id,$text,$responceFinalEmoticons);
                    $textControl = "";
                    break; //Exit cicle
                }  
            }
        } if ($textControl != "") {
            /*
             *  Last attempt to answer request
            */
            $numText = count(explode(" ", $text)); //Count number of words
            $controlExit = ""; //Variable for exit
            if($numText<4){
                $text = trim($text); //Clean to text
                $tableTag = dbButtonTag($text); 
                foreach ($tableTag as $paramTag) {
                    if($controlExit==""){
                        if($paramTag['Number']>0 && $paramTag['Number']<9){
                            $responceFinalEmoticons = emoticonConvert($paramTag['Param']);
                            apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $responceFinalEmoticons, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup));
                            dbTrackerInsert($chat_id,$user_id,$text,$responceFinalEmoticons);
                            $controlExit = "exit";
                        } elseif ($paramTag['Number']==-1) { //-1 is the param of service into table button
                            $responceFinalEmoticons =  emoticonConvert($paramTag['Description']);
                            apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $responceFinalEmoticons, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup));
                            dbTrackerInsert($chat_id,$user_id,$textControl,$responceFinalEmoticons);
                            $controlExit = "exit";
                        } else {
                            $responceFinalEmoticons =  emoticonConvert($paramTag['Titolo']);
                            apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $responceFinalEmoticons, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup));
                            dbTrackerInsert($chat_id,$user_id,$textControl,$responceFinalEmoticons);
                            $controlExit = "exit";
                        } 
                    }//Exit if controlExit 
                }  
            }
            if($controlExit == ""){
                /*
                 *  Function that stores all messages that users send through extra bot
                */
                initSendAnswer($chat_id,$first_name_id,$message_id,$text);
            }
          } 
}//End control Message of Text

    /*
     * System for recive file/photo/document ecc.
     */    
    $fileRecive = "Grazie per avermi mandato il file."; 
    /*
     * Here control the format message to send (photo)
     */     
    if (isset($message['photo'])){
        $nameObject = $message['photo'];
        $id_foto = $nameObject[1]['file_id'];
        $createLink = downLoad($id_foto,"");
        dbLogTextOn($chat_id,$first_name_id,$message_id,'<br><a href="'.$createLink.'" target="_blank">'.$createLink.'</a>');
        sendMail("Una nuova foto",'Scarica la foto da '.$first_name_id.': <br><a href="'.$createLink.'">'.$createLink.'</a>');
        apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $fileRecive, 'reply_markup' => $reply_markup));
        $text = "";
    }
    /*
     * Here control the format message to send (position ecc.) and return gasoline price/registry
     */
    if (isset($message['location'])){
        $longitude = $message['location']['longitude'];
        $latitude = $message['location']['latitude'];
        /*
        * Here function caption the service for position
        */
        $responseService = "Devi prima selezionare il servizio e poi inviare la posizione con PAPERCLIP.";
        $responseNotFound = "Servizio ancora non attivo.";
        $responceServiceEmoticons =  emoticonConvert($responseService);
        $result = dbLocalizationTmpSelect($chat_id);
        if(empty($result[0]['Service'])){
          apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $responseServiceEmoticons, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup));  
          $text = "";
        } 
        switch ($result[0]['Service']) {
            case "Farmacia":
                $tablePharma = PharmaSearch($longitude, $latitude, $chat_id, $first_name_id, $message_id, $reply_markup);
                $listPharma = "";
                foreach($tablePharma as $tableExplodePharma){
                        $linkTemp = "http://maps.google.com/maps?daddr=".$tableExplodePharma["LATITUDINE"].",".$tableExplodePharma["LONGITUDINE"]."&amp;ll=";
                        $linkMaps = initShort($linkTemp);
                        $namePharma = strtoupper($tableExplodePharma["DESCRIZIONEFARMACIA"]);
                        $adressPharma = $tableExplodePharma["INDIRIZZO"];
                        $comunePharma = $tableExplodePharma["DESCRIZIONECOMUNE"];
                        $listPharma .= "<b>". $comunePharma ."</b>\n" . $namePharma . "\n" . $adressPharma . "\n". $linkMaps ."\n\n";
                }
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $listPharma, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
                $text = "";  
                break;
            case "FarmaciaFvg":
                $tablePharma = PharmaFvgSearch($longitude, $latitude, $chat_id, $first_name_id, $message_id, $reply_markup);
                $listPharma = "";
                foreach($tablePharma as $tableExplodePharma){
                        $linkTemp = "http://maps.google.com/maps?daddr=".$tableExplodePharma["latitude"].",".$tableExplodePharma["longitude"]."&amp;ll=";
                        $linkMaps = initShort($linkTemp);
                        $namePharma = strtoupper($tableExplodePharma["name"]);
                        $adressPharma = $tableExplodePharma["address"];
                        $comunePharma = $tableExplodePharma["city"];
                        $disPharma = round($tableExplodePharma["dis"], 0);
                        $closePharma = date("d-m-Y H:i", strtotime($tableExplodePharma["until0"]));
                        $listPharma = ">> ". $comunePharma ." (" . $disPharma . "km)\n" . $namePharma . "\n" . $adressPharma . "\nAperta fino: " . $closePharma. "\n" . $linkMaps ."\n\n" . $listPharma;
                }
                truncateMessage($listPharma, $chat_id, $user_id, $reply_markup);
                //apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $listPharma, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
                $text = "";  
                break;
            case "Gasoline": 
                $tableGasoline = GasolineSearch($longitude, $latitude, $chat_id, $first_name_id, $message_id, $reply_markup);
                $listGasoline = "";
                foreach($tableGasoline as $tableExplodeGasoline){
                        $linkTemp = "http://maps.google.com/maps?daddr=".$tableExplodeGasoline["Latitudine"].",".$tableExplodeGasoline["Longitudine"]."&amp;ll=";
                        $linkMaps = initShort($linkTemp);
                        $typeFuel = strtoupper($tableExplodeGasoline["descCarburante"]);
                        $dateUpdateFuel = substr($tableExplodeGasoline["dtComu"], 0, 10);
                        $listGasoline .= "<b>".$typeFuel."</b> - Euro: ".$tableExplodeGasoline["prezzo"]." - ".$tableExplodeGasoline["Bandiera"]." - ".$linkMaps." (".$dateUpdateFuel.")\n\n";
                }
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $listGasoline, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
                $text = "";  
                break;
            case "Commerce": 
                $tableCommerce = CommerceSearch($longitude, $latitude, $chat_id, $first_name_id, $message_id, $reply_markup);
                $listCommerce = "";
                foreach($tableCommerce as $tableExplodeCommerce){
                        $linkTemp = "http://maps.google.com/maps?daddr=".$tableExplodeCommerce["Latitudine"].",".$tableExplodeCommerce["Longitudine"]."&amp;ll=";
                        $linkMaps = initShort($linkTemp);
                        $listCommerce .= "<b>".$tableExplodeCommerce["Nome"]."</b> - ".$tableExplodeCommerce["Comune"]." - "."\n".$linkMaps."\n\n";
                }
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $listCommerce, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
                $text = "";  
                break;
            case "Art": 
                $responceArt = ArtSearch($longitude, $latitude, $chat_id, $user_id, $first_name_id, $message_id, $reply_markup);
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $responceArt, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
                dbTrackerInsert($chat_id,$user_id,"/Arte","Lettura dati terminata");
                $text = "";  
                break;
            case "Wifi": 
                $responceWifi = WifiSearch($longitude, $latitude, $chat_id, $user_id, $first_name_id, $message_id, $reply_markup);
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $responceWifi, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
                dbTrackerInsert($chat_id,$user_id,"/Wifi","Lettura dati terminata");
                $text = "";  
                break;
        }        
    }
    /*
     * Here control the format message to send (video ecc.)
     */
    if (isset($message['video'])){ 
        $nome = $message['video'];
        $id_file = $nome['file_id'];
        $createLink = downLoad($id_file,".mpeg");
        dbLogTextOn($chat_id,$first_name_id,$message_id,'<br><a href="'.$createLink.'" target="_blank">'.$createLink.'</a>');
        sendMail("Un nuovo file video",'Scarica il file video da '.$first_name_id.': <br><a href="'.$createLink.'">'.$createLink.'</a>');
        apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $fileRecive, 'reply_markup' => $reply_markup));
        $text = ""; 
    } 
    /*
     * Here control the format message to send (image, document ecc.)
     */ 
    if (isset($message['document'])){ 
        $nome = $message['document'];
        $tipo = $nome['mime_type'];
        $id_file = $nome['file_id'];
        $text = ""; 
        switch ($tipo){
            case 'audio/mpeg':
                $createLink = downLoad($id_file,".mpeg");
                dbLogTextOn($chat_id,$first_name_id,$message_id,'<br><a href="'.$createLink.'" target="_blank">'.$createLink.'</a>');
                sendMail("Un nuovo file audio",'Scarica il file audio da '.$first_name_id.': <br><a href="'.$createLink.'">'.$createLink.'</a>');
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $fileRecive, 'reply_markup' => $reply_markup));
                $text = "";
                break;
            case 'audio/ogg':
                $createLink = downLoad($id_file,".ogg");
                dbLogTextOn($chat_id,$first_name_id,$message_id,'<br><a href="'.$createLink.'" target="_blank">'.$createLink.'</a>');
                sendMail("Un nuovo file vocale",'Scarica il file vocale da '.$first_name_id.': <br><a href="'.$createLink.'">'.$createLink.'</a>');
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $fileRecive, 'reply_markup' => $reply_markup));
                $text = "";
                break;
            case 'text/plain':
                $createLink = downLoad($id_file,"");
                dbLogTextOn($chat_id,$first_name_id,$message_id,'<br><a href="'.$createLink.'" target="_blank">'.$createLink.'</a>');
                sendMail("Un nuovo file di testo",'Scarica il testo da '.$first_name_id.': <br><a href="'.$createLink.'">'.$createLink.'</a>');
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $fileRecive, 'reply_markup' => $reply_markup));
                $text = "";
                break;
            case 'application/pdf':
                $createLink = downLoad($id_file,"");
                dbLogTextOn($chat_id,$first_name_id,$message_id,'<br><a href="'.$createLink.'" target="_blank">'.$createLink.'</a>');
                sendMail("Un nuovo pdf",'Scarica il pdf da '.$first_name_id.': <br><a href="'.$createLink.'">'.$createLink.'</a>');
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $fileRecive, 'reply_markup' => $reply_markup));
                $text = "";
                break;
            case 'image/jpeg':
                $createLink = downLoad($id_file,"");
                dbLogTextOn($chat_id,$first_name_id,$message_id,'<br><a href="'.$createLink.'" target="_blank">'.$createLink.'</a>');
                sendMail("Un nuovo file immagine",'Scarica la foto da '.$first_name_id.': <br><a href="'.$createLink.'">'.$createLink.'</a>');
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $fileRecive, 'reply_markup' => $reply_markup));
                $text = "";
                break;
            case 'video/mpeg':
                $createLink = downLoad($id_file,".mpeg");
                dbLogTextOn($chat_id,$first_name_id,$message_id,'<br><a href="'.$createLink.'" target="_blank">'.$createLink.'</a>');
                sendMail("Un nuovo file video",'Scarica il video da '.$first_name_id.': <br><a href="'.$createLink.'">'.$createLink.'</a>');
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $fileRecive, 'reply_markup' => $reply_markup));
                $text = "";
                break;
            default:
                $fileRecive = "Non posso gestire questo file per ora.";
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $fileRecive, 'reply_markup' => $reply_markup));
                $text = ""; 
                break;              
          } 
    }
    /*
     * Here control the format message to send (voice message)
     */
    if (isset($message['voice'])){ 
        $nome = $message['voice'];
        $tipo = $nome['mime_type'];
        $id_file = $nome['file_id'];
        $text = ""; 
        switch ($tipo){
            default:
                $createLink = downLoad($id_file,".ogg");
                dbLogTextOn($chat_id,$first_name_id,$message_id,'<br><a href="'.$createLink.'" target="_blank">'.$createLink.'</a>');
                sendMail("Un nuovo file vocale",'Scarica il messaggio vocale da '.$first_name_id.': <br><a href="'.$createLink.'">'.$createLink.'</a>');
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $fileRecive, 'reply_markup' => $reply_markup));
                $text = "";
                break;            
        }
    }
    /*
     * Here control the format message to send (Video Note message ecc.)
     */ 
    if (isset($message['video_note'])){ 
        $nome = $message['video_note'];
        //$tipo = $nome['mime_type'];
        $id_file = $nome['file_id'];
        $text = ""; 
        switch ($tipo){
            default:
                $createLink = downLoad($id_file,".mp4");
                dbLogTextOn($chat_id,$first_name_id,$message_id,'<br><a href="'.$createLink.'" target="_blank">'.$createLink.'</a>');
                sendMail("Un nuovo Video Messaggio",'Scarica il messaggio video da '.$first_name_id.': <br><a href="'.$createLink.'">'.$createLink.'</a>');
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $fileRecive, 'reply_markup' => $reply_markup));
                $text = "";
                break;            
        }
    }
} //End function processMessage

/*
 * apiRequest
 * Function to connect Telgram with API
 * 
*/

function apiRequest($method, $parameters)
{
    if (!is_string($method)) {
        error_log("Method name must be a string\n");
        return false;
    }
    if (!$parameters) {
        $parameters = array();
    } else if (!is_array($parameters)) {
        error_log("Parameters must be an array\n");
        return false;
    }
    foreach ($parameters as $key => &$val) {
        // encoding to JSON array parameters, for example reply_markup
        if (!is_numeric($val) && !is_string($val)) {
            $val = json_encode($val);
        }
    }
    $url = API_URL.$method.'?'.http_build_query($parameters);
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60); 
    $response = curl_exec($handle);
    if ($response === false) {
        $errno = curl_errno($handle);
        $error = curl_error($handle);
        error_log("Curl returned error $errno: $error\n");
        curl_close($handle);
        return false;
    }
    $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
    curl_close($handle);
    if ($http_code >= 500) {
        // we wouldn't want to DDOS the server if something goes wrong
        sleep(10); //default 10
        return false;
    } else if ($http_code != 200) {
        $response = json_decode($response, true);
        // View the error into console
        error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
        /*
        * Also error
        */
        if ($http_code == 401) {
            // Sperimental:
            // dbLogSoftware ($parameters['$chat_id'],$parameters['first_name_id'],$parameters['last_name_id'], $parameters['username_id'], $parameters['message']);
            throw new Exception('Invalid access token provided');
        }
        // This error "403": Request has failed with error 403: Bot was blocked by the user - Delete user from Bot for block
        if ($http_code == 403) {
            dbLogUserStop($parameters['chat_id']);
        }
        // Too many request from user
        if ($http_code == 429) {
            $message = "<b>Stai facendo troppe richieste, ora devi attendere un momento.</b>";
            apiRequest("sendMessage", array('chat_id' => $parameters['chat_id'], 'text' => $message, 'parse_mode' => 'HTML'));
        }
        return false;
    } else {
        $response = json_decode($response, true);
        if (isset($response['description'])) {
            error_log("Request was successfull: {$response['description']}\n");
        }
        $response = $response['result'];
    }
    return $response;
}
// Fine API Telegram

/*
 * sendMessage
 * Function for process send message for all users
 * 
 */
function sendMessage($user_id, $message)
{
    //Correzione dei caratteri utf-8 particolari ed inoltre apici
    //Funzione deprecata -> $message = html_entity_decode($message);
    $message = str_replace ("&#39;","'" ,$message);
    $message =  emoticonConvert($message);
    $key = createKeyboard($user_id);
    $reply_markup = $key[1];
    $chat_id = $user_id;
    //Funzione di Send Message
    truncateMessageNoPlugin($message, $chat_id, $user_id, $reply_markup);
}

/*
 * sendMessageChannel
 * Function for process send message to Telegram channel 
 * 
 */
function sendMessageChannel($user_id, $message)
{
    //Correzione dei caratteri utf-8 particolari ed inoltre apici
    $message = html_entity_decode($message);
    $message = str_replace ("&#39;","'" ,$message);
    $message =  emoticonConvert($message);
    //Function of Send Message to Channel 
    truncateMessageNoPlugin($message, "", $user_id, "");
    //Old function
    //apiRequest("sendMessage", array('chat_id' => "$user_id", 'text' => "$message", 'parse_mode' => 'HTML'));
}

/*
 * sendPicture
 * Function for process send photo after upload on server
 * 
 */
function sendPicture($chat_id, $photo)
{
    $cfile = new CURLFile(realpath("$photo")); //first parameter is YOUR IMAGE path   
    $data = [
            'chat_id' => $chat_id , 
            'photo' => $cfile
            ];
    $ch = curl_init(API_URL.'sendPhoto');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 3500);
    curl_setopt($curl, CURLOPT_TIMEOUT_MS, 6000);
    $result = curl_exec($ch); // For debug
    curl_close($ch);
}

/*
 * sendDocument
 * Function for process send documents
 * 
 */
function sendDocument($chat_id, $document)
{
    $cfile = new CURLFile(realpath("$document")); //first parameter is YOUR Document path   
    $data = [
            'chat_id' => $chat_id , 
            'document' => $cfile
            ];
    $ch = curl_init(API_URL.'sendDocument');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 3500);
    curl_setopt($curl, CURLOPT_TIMEOUT_MS, 6000);
    $result = curl_exec($ch);
    curl_close($ch);
}

/*
 * controlTelgramState
 * Control state of platform
 * 
 * @return array of state code telegram
 * 
*/ 
function controlTelgramState()
{
    $ch = curl_init();
    // Set URL resource in the variable
    // New variable for send Photo
    $url = API_URL.'getUpdates';
    $handle = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    // setting no header downaload
    curl_setopt($ch, CURLOPT_HEADER, 0);
    // second of timeout for stop the time on the server
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60); 
    // block the output of curl
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // insert the result in the variable
    $risultato = curl_exec($ch);
    curl_close($ch);
    // output of Telegram site when the result is OK
    $controllo = "{\"ok\":true,\"result\":[]}";
    return (array($risultato, $controllo));
}

/*
 * controlUserState
 * Control the stop bot from users (banned bot)
 * 
 * @return array
 */ 
 
function controlUserState($indirizzo)
{
    $ch = curl_init();
    // Set URL della risorsa remota da scaricare
    // New variable for send Photo
    $url = $indirizzo; //API_URL.'getUpdates';
    $handle = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    // setting no header download
    curl_setopt($ch, CURLOPT_HEADER, 0);
    // second of timeout for stop the time on the server
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60); 
    // block output of curl
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // insert the result in the variable
    $risultato = curl_exec($ch);
    curl_close($ch);
    // output di Telegram site when the result is OK
    // $controllo = "{\"ok\":false,\"error_code\":403,\"description\":\"[Error]: Bot was kicked from a chat\"}";
    $controllo = "Request has failed with error 403: Bot was blocked by the user";
    return (array($risultato, $controllo));
}

/*
 * createKeyboard
 * Function for create a normal Keyboard
 * 
 * @return array of keyboard 
 */
function createKeyboard($userid)
{
    //This is a message of Hello when start connection with user
    $key0 = '';
    $num = dbDemoneKeyboard("Number=0");
    foreach ($num as $numText){$key0 = $numText['Param'];}
    //Keyboard structure
    $outKeywordTmp = dbDemoneTmpUserButton($userid);
    if (!empty($outKeywordTmp)){
        $levelInput = $outKeywordTmp['IdLevel'];
        echo $levelInput;
        $levelIndoorInput = $outKeywordTmp['IdLevelIndoor']; 
        echo $levelIndoorInput;
        $sqlString = "Level=".$levelInput." AND LevelIndoor=".$levelIndoorInput." AND SoftDesc='Button'";
        //First line	
        $numKey1 = dbDemoneKeyboard($sqlString);
        //Second line
        $numKey2 = dbDemoneKeyboard($sqlString);
        $numKey2 = ['Home, Back'];
    } else {	
        //First line
        $numKey1 = dbDemoneKeyboard("Number BETWEEN 1 AND 4 AND SoftDesc='Button'");
        //Second line
        $numKey2 = dbDemoneKeyboard("Number BETWEEN 5 AND 8 AND SoftDesc='Button'");
    }
    //Outuput keyboard
    $reply_markup = createArrayKeyboard($numKey1, $numKey2);
    return [$key0, $reply_markup]; //In position 0 the Hello message and in position 1 the keyboard structure
}

/*
 * createArrayKeyboard
 * Function for create a normal Keyboard
 * 
 * @return array of keyboard 
 */
function createArrayKeyboard($Key1, $Key2)
{
    foreach ($Key1 as $numKeyText){
    $num1[] = $numKeyText['Titolo'];
    } 
    foreach ($Key2 as $numKeyText){
        $num2[] = $numKeyText['Titolo'];
    }
    //Array of keyboard for Bot
    $keyboard = [
        $num1, 
        $num2   
    ];
    //Array of array
    $reply_markup = [
        'keyboard' => $keyboard, 
        'resize_keyboard' => true, 
        'one_time_keyboard' => false,
        'force_reply' => true
    ];
	return $reply_markup;
}

function downLoad($id_file,$ext)
{
    $ch = curl_init();
    // Set URL for resource of downalod (remote site link -> telegram api)
    $url = API_URL.'getFile?file_id='.$id_file;
    
    $handle = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    // Not downalod header web page if exist
    curl_setopt($ch, CURLOPT_HEADER, 0);
    // Second of timeout for limit the attempt time
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60); 
    // Stop output of curl
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Insert the result into variable
    $linkResult = curl_exec($ch);
    $temp_decodificato = json_decode($linkResult,TRUE);
    $file_path = $temp_decodificato['result']['file_path'];
    /* 
    * New path api telegram
    * https://api.telegram.org/file/bot<token>/<file_path>
    */
    $pathNewTelegram = 'https://api.telegram.org/file/bot'.BOT_TOKEN.'/';
    $link_file = $pathNewTelegram.$file_path;
    curl_close($ch);
    $linkLocal = randomFile($link_file,$ext);
    // output of Telegram site when the result is OK
    return ($linkLocal);
}
