<?php

/* 
 * Function for implements with plugin
 */

/* Function Launcher($chat_id,$reply_markup,$user_id,$param)
 * Launcher for all function
 * 
 * @return Array of param
 */
function Launcher($chat_id, $reply_markup, $user_id, $param)
{
    $functionParam = explode("|", $param);
    $errorFunctionMessageMail = "C'e' un errore nella funzione dal nome: ";
    $erroFunctionMessage = "Chiedo scusa per l'errore, ho gia' avvisato il mio gestore.";
	
    if (function_exists($functionParam[0])) {
        // In position 0 this is a function 
        // Insert into array information
	// Order of Parameter function (with 1 parameter): $Function = $link[0]; $Parameter = $link[1]; $chat_id = $link[2]; $reply_markup = $link[3]; $user_id = $link[4];
        $functionParam[] = $chat_id;
        $functionParam[] = $reply_markup; 
        $functionParam[] = $user_id;
        return $functionParam[0]($functionParam);
    }else{
        sendMail($errorFunctionMessageMail,$functionParam[0]);
        return $erroFunctionMessage;
    }
}

/*
 * ##################################################################################
 * START FUNCTION DEFAULT
 * ##################################################################################
 */


/*
 * Function Clean
 * This function clean string for web tag of text
 * 
 * @return string
*/
function Clean($text)
{
    if (mb_detect_encoding($text, 'UTF-8', true) == false){
        $str = utf8_encode($text);
        $text = $str;
    }
    //Exception of html/xml with error/not standard
    $text = strip_tags(str_replace('<', ' <', $text));
    $text = str_replace("<br />","\n ",$text);
    $text = str_replace("<br/>","\n ",$text);
    $text = str_replace("</br>","\n ",$text);
    $text = str_replace("&lt;/a&gt;"," ",$text);
    $text = str_replace("&lt;/br&gt;"," ",$text);
    $text = str_replace("&lt;i&gt;"," ",$text);
    $text = str_replace("&lt;/i&gt;"," ",$text);
    $text = str_replace("&lt;img src='","img",$text);
    $text = str_replace("'&gt;"," ",$text);
    $text = str_replace("\" target=\"_blank\"&gt;"," ",$text);
    $text = str_replace("&lt;a href=\""," ",$text);
    $text = str_replace("<b>"," ",$text);
    $text = str_replace("</b>"," ",$text);
    $text = str_replace("&lt;br/&gt;","\n ",$text);
    $text = str_replace("&lt;b&gt;"," ",$text);
    $text = str_replace("&lt;/b&gt;"," ",$text);
    $text = str_replace("&nbsp;"," ",$text);
    $text = str_replace("&#39;","\'",$text);
    $regex = "(img?/([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)";
    $text = preg_replace($regex," ", $text);
    $text = preg_replace('/\\s{2,}/'," ",$text);
    $text = strip_tags($text);
    return $text;
}

/*
 * Function StripSinglTag
 * Other function for clean HTML/RSS/XML
 *
 * @return string
*/
function StripSinglTag($tag, $string)
{
   $string = preg_replace('/<'.$tag.'[^>]*>/i', '', $string);
   $string = preg_replace('/<\/'.$tag.'>/i', '', $string);
   return $string;
}

function StripSingleTagCont($tag, $string) {
   $string = preg_replace('/<'.$tag.'[^>]*>(.*?)<\/'.$tag.'>/i', '', $string);
   return $string;
}

/*
 * Function ore()
 * 
 * @return string
*/
function Ore()
{
    $houre = "Ora esatta: ".date("j F Y, H:i:s", time());
    return $houre;
}

/*
 * Function privacy()
 * 
 * @return string
*/
function privacy($variable)
{
    // Variable setting
    $chat_id = $variable[1];
    $reply_markup = $variable[2];
    $user_id = $variable[3];	
    $messageMailTitle = "Copia dati personali.";
    $messageMail = "L'utente vuole una copia dei suoi dati: ";
    $messageForBot = "Utente: ".$user_id." elaboro copia dei tuoi dati personali quanto prima. Verrai ricontattato dal Bot entro 10 giorni!";	
    sendMail($messageMailTitle, $messageMail.$user_id);
    dbLogTextOn($user_id,"-",$chat_id,$messageMail);
    return $messageForBot;
}

/**
 * Function setting($userID, $type, $value)
 * insert setting user for bot 
 * 
 * @return text
*/
function Setting($also)
{
    $typeSet = $also[1];
    $valueSet = $also[2];
    $chatIdSet = $also[3];
    $date = dbServiceControl($chatIdSet, $typeSet);
    if (empty($date)){
		//For insert service one time
		$error = dbServiceInsert($chatIdSet, $typeSet, $valueSet);
		if ($error == 0){
			return "Settaggio aggiornato correttamente.";
		}else{
			return "Aggiornamento non avvenuto, ritenta mi spiace";
		}
	}else{
		//For update service
		$error = dbServiceUpdate($chatIdSet, $typeSet, $valueSet);
		if ($error == 0){
			return "Settaggio aggiornato correttamente.";
		}else{
			return "Aggiornamento non avvenuto, ritenta mi spiace";
		}
    }
}

/*
 * Function Read($link)
 * Function for read a feed rss/xml and return to clean text
 * 
 * @return string
*/
function Read($link)
{
    // Variable setting
    $linkNew = $link[1];
    $chat_id = $link[2];
    $reply_markup = $link[3];
    $user_id = $link[4];
    $url_flux_rss = $linkNew;
    $limite = 30; // number max of Rss
    $message="Ci sono molti dati da elaborare, ancora un momento!"; //Message to attemp

    // active of class
    $rss = new lastRSS;

    // option
    $rss->cache_dir   = './cache'; // folder for cache
    $rss->cache_time  = 3600;      // time to live cache (second)
    $rss->date_format = 'd/m/y';   // Date format italian
    $rss->CDATA       = 'content'; // content tag CDATA
    $outPut = "";
    $risultato = "";

    if ($rs = $rss->get($url_flux_rss)){
        for($i=0; $i<$limite; $i++){
            if($i==11){
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $message, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
            }
            $titolo = $rs['items'][$i]['title'];
            if ($titolo != ""){
                $datePubb = $rs['items'][$i]['pubDate'];
                $weblink = $rs['items'][$i]['link'];
                $description = $rs['items'][$i]['description'];
                if ($description != ""){
                        $description = Clean($description);
                }else{
                        $description = $rs['items'][$i]['content:encoded'];
                        $description = Clean($description);
                }
                //Short link create
                $shortURL = initShort($weblink);
                //Concatenated
                $risultato = "Pubblicato: ".$datePubb."\r\n".$titolo."\r\n".$description."\r\n".$shortURL."\r\n\r\n".$risultato;
            }		
        }
    } else {
        $risultato = "Non ci sono feed rss per oggi.";
    }    
    truncateMessage($risultato, $chat_id, $user_id, $reply_markup);
    return "Lettura dati terminata.";
}

/*
 * Function: linkImgOutput 
 * 
 * @return url for my site for download image
 */ 
 
function linkImgOutput($url)
{
    $urlImgInput = $url[1];
    $chat_id = $url[2];
    $reply_markup = $url[3];
    $user_id = $url[4];
    $OutputParam = explode(";", $urlImgInput);
    foreach ($OutputParam as $value){
            //Randomize image for resolve problem cache Telegram app
            $urlImgOutput =  randomFile($value,"");
            apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $urlImgOutput, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup));
            dbTrackerInsert($chat_id,$user_id,"segue", $urlImgOutput);
            sleep(1);
    }
    return "Lettura dati terminata.";
}

/*
 * Function serviceTmp 
 * This function use for save the service with position select
 * 
 * @return string
 */

function serviceTmp($any)
{
    $serviceSet = $any[1];
    $chatIdSet = $any[2];
    $control = dbLocalizationTmpSelect($chatIdSet);
    if (empty($control)){
		$error = dbLocalizationTmpInsert($chatIdSet, $serviceSet);
		if ($error == 0){
			$message = "Servizio scelto correttamente, ora puoi inviare la posizione usando PAPERCLIP.";
			$responceMessage =  emoticonConvert($message);
			return $responceMessage;
		}
    }else{
		dbLocalizationTmpDelete($chatIdSet);
        dbLocalizationTmpInsert($chatIdSet, $serviceSet);
        $message = "Servizio scelto correttamente, ora puoi inviare la posizione usando PAPERCLIP.";
        $responceMessage =  emoticonConvert($message);
        return $responceMessage;
    }
}

/*
 * Function GasolineSearch
 * 
 * @return array of list gasoline  
 */
 
function GasolineSearch($longitude, $latitude, $chat_id, $first_name_id, $message_id, $reply_markup)
{
    //Setting variable AND Initialize variable
    $link = "http://maps.google.com/maps?daddr=".$latitude."," .$longitude ."&amp;ll=";
    $positionRecive = "Ti trovi presso: ";
    $coordinate = array($latitude, $longitude);
    $distanceSearch = "10";
    $messageSend = "Sto calcolando i distributori nel raggio di ".$distanceSearch." km.";
    dbLogTextOn($chat_id,$first_name_id,$message_id,'<br><a href="'.$link.'" target="_blank">'.$link.'</a>');
    //reverse coordinate to address
    $address = street($coordinate);
    $positionRecive = $positionRecive . $address;

    //send message position and attemp
    apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $positionRecive, 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
    apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $messageSend, 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
    $tableGasoline = dbGasolineSelect($latitude, $longitude, $distanceSearch);
    return $tableGasoline;
}

/*
 * Function PharmaSearch
 * 
 * @return array of list Pharma
 */
 
function PharmaSearch($longitude, $latitude, $chat_id, $first_name_id, $message_id, $reply_markup)
{
    //Setting variable AND Initialize variable
    $link = "http://maps.google.com/maps?daddr=".$latitude."," .$longitude ."&amp;ll=";
    $positionRecive = "Ti trovi presso: ";
    $coordinate = array($latitude, $longitude);
    $distanceSearch = "25";
    $messageSend = "Sto calcolando le farmacie in ordine di DISTANZA MINORE nel raggio di ".$distanceSearch." km.";
    dbLogTextOn($chat_id,$first_name_id,$message_id,'<br><a href="'.$link.'" target="_blank">'.$link.'</a>');
    //reverse coordinate to address
    $address = street($coordinate);
    $positionRecive = $positionRecive . $address;

    //send message position and attemp
    apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $positionRecive, 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
    apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $messageSend, 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
    $tablePharma = dbPharmacieSelect($latitude, $longitude, $distanceSearch);
    return $tablePharma;
}

/*
 * Function PharmaFvgSearch
 * 
 * @return array of list PharmaFvg
 */
 
function PharmaFvgSearch($longitude, $latitude, $chat_id, $first_name_id, $message_id, $reply_markup)
{
    //Setting variable AND Initialize variable
    $link = "http://maps.google.com/maps?daddr=".$latitude."," .$longitude ."&amp;ll=";
    $positionRecive = "Ti trovi presso: ";
    $coordinate = array($latitude, $longitude);
    $distanceSearch = "15";
    $messageSend = "Sto calcolando le farmacie APERTE intorno a te, max ".$distanceSearch." km.";
    dbLogTextOn($chat_id,$first_name_id,$message_id,'<br><a href="'.$link.'" target="_blank">'.$link.'</a>');
    //reverse coordinate to address
    $address = street($coordinate);
    $positionRecive = $positionRecive . $address;

    //send message position and attemp
    apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $positionRecive, 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
    apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $messageSend, 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
    $tablePharma = dbPharmacieFvgSelect($latitude, $longitude, $distanceSearch);
    return $tablePharma;
}

/*
 * Function CommerceSearch
 * 
 * @return array of list Commerce 
 */
 
function CommerceSearch($longitude, $latitude, $chat_id, $first_name_id, $message_id, $reply_markup)
{
    //Setting variable AND Initialize variable
    $link = "http://maps.google.com/maps?daddr=".$latitude."," .$longitude ."&amp;ll=";
    $positionRecive = "Ti trovi presso: ";
    $coordinate = array($latitude, $longitude);
    $distanceSearch = "10";
    $messageSend = "Sto calcolando i bar/gelaterie/osterie/ristoranti nel raggio di ".$distanceSearch." km.";
    dbLogTextOn($chat_id,$first_name_id,$message_id,'<br><a href="'.$link.'" target="_blank">'.$link.'</a>');
    //reverse coordinate to address
    $address = street($coordinate);
    $positionRecive = $positionRecive . $address;

    //send message position and attemp
    apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $positionRecive, 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
    apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $messageSend, 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
    $tableCommerce = dbCommerceSelect($latitude, $longitude, $distanceSearch);
    return $tableCommerce;
}

/*
 * Function ArtSearch
 * 
 * @return array of list of Art
 */
 
function ArtSearch($longitude, $latitude, $chat_id, $user_id, $first_name_id, $message_id, $reply_markup)
{
    //Setting variable AND Initialize variable
    $link = "http://maps.google.com/maps?daddr=".$latitude."," .$longitude ."&amp;ll=";
    $positionRecive = "Ti trovi presso: ";
    $coordinate = array($latitude, $longitude);
    $distanceSearch = "3";
    $messageSend = "Sto cercando le opere intorno a te nel raggio di ".$distanceSearch." km.";
    dbLogTextOn($chat_id,$first_name_id,$message_id,'<br><a href="'.$link.'" target="_blank">'.$link.'</a>');
    //reverse coordinate to address
    $address = street($coordinate);
    $positionRecive = $positionRecive . $address;

    //send message position and attemp
    apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $positionRecive, 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
    apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $messageSend, 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
    $tableArt = dbArtSelect($latitude, $longitude, $distanceSearch);
    $listArt = "";
    foreach($tableArt as $tableExplodeArt){
        $linkTemp = "http://maps.google.com/maps?daddr=".$tableExplodeArt["LAT"].",".$tableExplodeArt["LON"]."&amp;ll=";
        $linkMaps = $linkTemp;
        $linkArt = $tableExplodeArt["IMG"];
        if($linkArt != ""){$linkArt = "\n Foto: ".$linkArt;}else{$linkArt = "";}
        $elementi = explode(',', $tableExplodeArt["LOCALIZZAZIONE"]);
        $listArt .= $tableExplodeArt["BENE_CULTURALE"]."  - ".$elementi[3]." - ".$tableExplodeArt["CONTENITORE"]." - ".$linkArt."\n Loc: ".$linkMaps."\n\n";
    }
    if(empty($tableArt)){
        return "Non ci sono opere interessanti intorno a te";
    }else{
        truncateMessage($listArt, $chat_id, $user_id, $reply_markup);
        return "Lettura dati terminata";
    }
}

/*
 * Function WifiSearch
 * 
 * @return array of list of Art
 */
 
function WifiSearch($longitude, $latitude, $chat_id, $user_id, $first_name_id, $message_id, $reply_markup)
{
    //Setting variable AND Initialize variable
    $link = "http://maps.google.com/maps?daddr=".$latitude."," .$longitude ."&amp;ll=";
    $positionRecive = "Ti trovi presso: ";
    $coordinate = array($latitude, $longitude);
    $distanceSearch = "2";
    $messageSend = "Sto cercando i punti Wifi intorno a te nel raggio di ".$distanceSearch." km.";
    dbLogTextOn($chat_id,$first_name_id,$message_id,'<br><a href="'.$link.'" target="_blank">'.$link.'</a>');
    //reverse coordinate to address
    $address = street($coordinate);
    $positionRecive = $positionRecive . $address;

    //send message position and attemp
    apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $positionRecive, 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
    apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $messageSend, 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
    $tableWifi = dbWiFiSelect($latitude, $longitude, $distanceSearch);
    $listWifi = "";
    foreach($tableWifi as $tableExplodeWifi){
            $linkTemp = "http://maps.google.com/maps?daddr=".$tableExplodeWifi["Latitude"].",".$tableExplodeWifi["Longitude"]."&amp;ll=";
            $linkMaps = $linkTemp;
            $listWifi .= $tableExplodeWifi["Name"]."  - \n Loc: ".$linkMaps."\n\n";
    }
    if(empty($tableWifi)){
            return "Non ci sono punti wifi intorno a te";
    }else{
            truncateMessage($listWifi, $chat_id, $user_id, $reply_markup);
            return "Lettura dati terminata. \n Fonte: https://bit.ly/2Qlimtl";
    }
}

/*
 * Function Photo
 * Extract photo from Flickr
 * For more setting read: https://www.flickr.com/services/api/flickr.photos.search.html
 * Fro example JSON RETURN: https://www.flickr.com/services/api/explore/flickr.photos.search
 * 
 * @return string
 */
 
function Photo($anyId)
{
    $oneParam = $anyId[1];
    $chatId = $anyId[2];
    // Setting number random
    $numRan = rand(0,150);

    // Extract key Flickr
    $tableParmExit = dbParamExtraction('SoftDesc = "Flickr" AND Active = "1"');
    foreach ($tableParmExit as $param) {
            if ($param['Code'] == "key"){
                    $flickrKey = $param['Param'];
            }  
    }
    // Variable Flickr
    $tag = $oneParam; //Tag to search
    $perPage = 500; //Number of photos to return per page. If this argument is omitted, it defaults to 100. The maximum allowed value is 500.
    $url = 'https://api.flickr.com/services/rest/?method=flickr.photos.search';
    $url.= '&api_key='.$flickrKey;
    $url.= '&tags='.$tag;
    $url.= '&per_page='.$perPage;
    $url.= '&page='.$numRan;
    $url.= '&format=json';
    $url.= '&nojsoncallback=1';

    // Extract 
    $json = file_get_contents($url);
    $response = json_decode($json, TRUE);
    $photo_array=(array)$response['photos']['photo'];

    // Utilizzo la funzione array_rand per estrarre a caso uno degli elementi della array
    $n = array_rand($photo_array, 1);

    $single_photo = $photo_array[$n];
    $farm_id = $single_photo['farm'];
    $server_id = $single_photo['server'];
    $photo_id = $single_photo['id'];
    $secret_id = $single_photo['secret'];
    $title = $single_photo['title'];

    $size = 'z';
    $photo_url = 'http://farm'.$farm_id.'.staticflickr.com/'.$server_id.'/'.$photo_id.'_'.$secret_id.'_'.$size.'.'.'jpg';
    $flickr = randomFile("$photo_url","");
    $title = "<b>Foto: ".$title."</b>\n ".$flickr;
    return $title;
}

/*
 * Function InfoTrafficoAutostradaFvg()
 * Function scrape the info to web page
 * Source: https://www.infoviaggiando.it/it
 * 
 * @return string
 */
function InfoTrafficoAutostradaFvg()
{
    $linkNew = "https://www.infoviaggiando.it/it";
    $txt = file_get_contents($linkNew);
    $txt_i = "<div class=\"c-newsticker__text\">";
    $txt_f = "</div>";
    $off = "0";
    // To Clean Text-HTML and convet character
    $letto = scrapeInfoTrafficoAutostradaFvg($txt,$txt_i,$txt_f,$off);
    $letto = strip_tags($letto, '<strong>');
    $letto = preg_replace('/\\s{2,}/',"\n",$letto);
    return $letto."\n\nFonte: https://infotraffico.autovie.it/";
}

/*
 * Function scrapeInfoTrafficoAutostradaFvg()
 * Fonte: https://infotraffico.autovie.it/
 * 
 * @return string
 */
function scrapeInfoTrafficoAutostradaFvg($testo,$txt_inizio,$txt_fine,$offset)
{
    $start = strpos($testo,$txt_inizio);
    $long = strlen($txt_inizio);
    $start = $start+$long;
    $end = strpos($testo,$txt_fine,$start);
    $forReturn = substr($testo,$start,$end-$start+$offset);
    return $forReturn;
}

/*
 * ##################################################################################
 * FINE FUNCTION DEFAULT
 * ##################################################################################
 */

/*
 * Function PsOnlineFVG()
 * https://servizionline.sanita.fvg.it/tempiAttesaService/tempiAttesaPs
 * Json info Pronto Soccorso Fvg
 * https://servizi.regione.fvg.it/portale/
 * 
 * @return String
*/
function PsOnlineFVG($variable)
{
    $chat_id = $variable[1];
    $reply_markup = $variable[2];
    $user_id = $variable[3];	
    // create curl resource
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    // set url
    curl_setopt($ch, CURLOPT_URL, "https://servizionline.sanita.fvg.it/tempiAttesaService/tempiAttesaPs");
    
    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/50.0.0.0');
    
    // $outPut contains the output Array
    $page = curl_exec($ch);
    $outPut = json_decode($page, true);
    // Array multidimensionale
    $azOnline = $outPut['aziende'];
    // Number of elements in select Array
    $numAZ = count($azOnline);
    $outPutFunction = '';

    //Aziende ospedaliere
    for($i=0; $i<$numAZ; $i++){
        $psOnLineName = '';
        $azName = $outPut['aziende'][$i]['descrizione'];
        $psOnline = $outPut['aziende'][$i]['prontoSoccorsi'];
        $numPS = count($psOnline);
        //Pronto soccorso
        for($j=0; $j<$numPS; $j++){
            $dpOnLineName = '';
            $dpOnline = $outPut['aziende'][$i]['prontoSoccorsi'][$j]['dipartimenti'];
            $numDP = count($dpOnline);
            //Department
            for($x=0; $x<$numDP; $x++){
                    $color = '';
                    $colorOnline = $outPut['aziende'][$i]['prontoSoccorsi'][$j]['dipartimenti'][$x]['codiciColore'];
                    $numColor = count($colorOnline);
                    for($y=0; $y<$numColor; $y++){
                            $colorDescription = $outPut['aziende'][$i]['prontoSoccorsi'][$j]['dipartimenti'][$x]['codiciColore'][$y]['descrizione'];
                            $color .= "<b>".$colorDescription."</b>\n";
                            //Information at state of code color
                            $infoColor = $outPut['aziende'][$i]['prontoSoccorsi'][$j]['dipartimenti'][$x]['codiciColore'][$y]['situazionePazienti'];
                            $color .= "(In attesa ".$infoColor['numeroPazientiInAttesa'].")\n(Tempo attesa ".$infoColor['mediaAttesa'].")\n";
                    }
                    $dpOnLineName .= "# ".$outPut['aziende'][$i]['prontoSoccorsi'][$j]['dipartimenti'][$x]['descrizione']." # \n".$color."\n";
            }
            $psOnLineName .= $dpOnLineName;
        }
        $outPutFunction = "<b>".$azName."</b>\n".$psOnLineName;
        truncateMessage($outPutFunction, $chat_id, $user_id, $reply_markup);
        //Sleep for time out (error 400) of Telegram
        sleep(2);
    }
	
    // close curl resource to free up system resources
    curl_close($ch);
    
    return "Lettura dati terminata.";
}


/*
 * Function SSM()
 * https://online.ssm.it/php/mobile/parkinfo
 * Json info parking Udine Sistema Sosta mobilit�
 * http://www.ssm.it/
 * Free parking
 * 
 * @return string
*/
function Ssm()
{
    // create curl resource
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    // set url
    curl_setopt($ch, CURLOPT_URL, "https://online.ssm.it/php/mobile/parkinfo");
    
    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/50.0.0.0');
    
    // $outPut contains the output Array
    $page = curl_exec($ch);
    $outPut = json_decode($page, true);
    // Array multidimensional
    $bodyInfo = $outPut['response']['body'];
    // Number of elements in select Array
    $numPark = count($bodyInfo);
    $outPutFunction='';
    for($i=1; $i<$numPark; $i++){
        $j=$bodyInfo[$i];
        // Control if full
        if($j['free']==0){
                $ssmState='PIENO';
        }else{
                $ssmState='LIBERO';
        }
        if($j['enabled']==1){
                $outPutFunction = $outPutFunction."> <b>".$j['extensioname']."</b> - ".$ssmState." ".$j['free'].'/'.$j['total']."\n";
        }
    }
    $date = strtotime($outPut['response']['body']['general']['lastreceive']); 
    $newDate = date('d-m-Y H:i', $date);
    $outPutFunction = $outPutFunction.'<b>Aggiornamento:</b> '.$newDate;
    
    // close curl resource to free up system resources
    curl_close($ch);
    return $outPutFunction;
}

/*
 * Function AlertProtezione($link)
 * For extract txt from http://www.protezionecivile.fvg.it/it/allerte
 * 
 * @return string
 */
function AlertProtezione($link)
{
    $linkNew = $link[1];
    $chat_id = $link[2];
    $reply_markup = $link[3]; 
    $user_id = $link[4];
    $urlPdf = "";
    $shortUrlFinal = "";

    $txt = file_get_contents($linkNew);
    //$txt_i = "<h3 class=\"field-content\">ALLERTA REGIONALE";
    $txt_i = "<div class=\"views-field views-field-field-body\">";
    $txt_m = "<div class=\"views-row\">";
    $txt_f = "<!-- /#main, /#main-wrapper -->";
    $off = "0";
    $letto = scrapeAlertProtezione($txt, $txt_i, $txt_m, $txt_f, $off);
    $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?.pdf)\\1[^>]*>(.*)<\/a>";
    if(preg_match_all("/$regexp/siU", $letto, $matches, PREG_SET_ORDER)){
            $i = 0;
            foreach($matches as $match) {
                    $i = $i+1;
                    $urlPdf = $match[2];
                    $shortURL = $i.") ".initShort($urlPdf);
                    $shortUrlFinal = $shortUrlFinal . "\n" . $shortURL;
            }
    }
    $letto = strip_tags(str_replace('<', ' <', $letto));
    $letto = str_replace(array("\n"), "", $letto);
    $letto = $letto . "\n " . $shortUrlFinal; //For url of pdf insert space
    $lettoC = substr($letto, 0, 4069);
    apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $lettoC, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
    dbTrackerInsert($chat_id,$user_id,'segue',$lettoC);
    return "Lettura dati terminata";
}

/*
 * Function scrapeAlertProtezione
 * For extract txt from http://www.protezionecivile.fvg.it/it/allerte
 * Scrape function
 * 
 * @return string
 */
function scrapeAlertProtezione($txt, $txt_init, $txt_med, $txt_fine, $offset){
    $init = strpos($txt, $txt_init);
    $med  = strpos($txt, $txt_med);
    if(empty ($init)){
        return "Al momento non sono state pubblicate allerte meteo sul sito della Protezione Civile F.V.G..";
    }else{
        $init = $init+0;
        $fine = strpos($txt, $txt_fine , $init);
        $outPut = substr($txt, $init, $fine-$init+$offset);
        return $outPut;
    }
}

/*
 * Function AlboPretorioFvg($link)
 * For extract txt from http://albopretorio.regione.fvg.it/ap/albo
 * 
 * @return string
 */
function AlboPretorioFvg($link)
{
    $linkNew = $link[1];
    $chat_id = $link[2];
    $reply_markup = $link[3]; 
    $urlPdf = "";
    $txt = file_get_contents($linkNew);
    $txt_i = "<div class=\"atti\">";
    $txt_f = "</form>";
    $off = "0";
    $letto = scrapeAlboPretorioFvg($txt,$txt_i,$txt_f,$off);
    $lunghezzaLetto = strlen($letto);
    $maxExport = 4000;
    $ini = 0;
    if($lunghezzaLetto<$maxExport){
        $lettoC = utf8_encode($letto);
        apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $lettoC, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup));
    }else{
        while ($ini<=$lunghezzaLetto){
            if($maxExport+$ini<=$lunghezzaLetto){
                $lettoC = substr($letto, $ini, $maxExport);
                //$lettoC = utf8_encode($lettoB);
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $lettoC, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup));
            }else{
                $lettoC = substr($letto, $ini, $lunghezzaLetto);
                //$lettoC = utf8_encode($lettoB);
                apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $lettoC, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup));
            }
            $ini = $ini+$maxExport;
        }
    }
    return "Lettura dati terminata.";
}

/*
 * Function AlboPretorioFvg($link)
 * For extract txt from http://albopretorio.regione.fvg.it/ap/albo
 * Function scrape
 * 
 * @return string
 */
function scrapeAlboPretorioFvg($testo,$txt_inizio,$txt_fine,$offset)
{
    $inizio = strpos($testo,$txt_inizio);
    if(empty ($inizio)){
        return "Non ci sono pubblicazioni al momento attuale, ritenta.";
    }else{
        $inizio = $inizio+0;
        $fine = strpos($testo,$txt_fine,$inizio);
        //OutPut Total Data not Empty
        $outPut = substr($testo,$inizio,$fine-$inizio+$offset);
        $outPutFinal = scrapeAlboPretorioFvgMid($outPut);
        return $outPutFinal;
    }
}

/*
 * Function AlboPretorioFvg($link)
 * For extract txt from http://albopretorio.regione.fvg.it/ap/albo
 * Function middle scrape
 * 
 * @return string
 */
function scrapeAlboPretorioFvgMid($testo)
{
    //Parte tipo
    $txt_i = "<div class=\"etic\">";
    $txt_f = "</div>";
    $alboFinal = " ";
    $inizio = strpos($testo,$txt_i);
    if(empty ($inizio)){
        return $alboFinal;
    } else {
        $inizio = $inizio+0;
        $fine = strpos($testo,$txt_f,$inizio);
        if(empty ($fine)){
            return $alboFinal;
        } else {
            $alboPartial1 = substr($testo,$inizio,$fine-$inizio);
            //Parte dato
            $txt_i = "<div class=\"dato\">";
            $txt_f = "</div>";
            $inizio = strpos($testo,$txt_i);
            $inizio = $inizio+0;
            $fine = strpos($testo,$txt_f,$inizio);
            $alboPartial2 = substr($testo,$inizio,$fine-$inizio);
            //Clean Code from tag html
            $alboPartial1 = strip_tags(str_replace('<', ' <', $alboPartial1));
            $alboPartialClean1 = str_replace(array("\r", "\r\n", "\n"), '', $alboPartial1);
            $alboPartialClean1 = trim($alboPartialClean1);
            $alboPartial2 = strip_tags(str_replace('<', ' <', $alboPartial2));    
            $alboPartialClean2 = str_replace(array("\r", "\r\n", "\n"), '', $alboPartial2);
            $alboPartialClean2 = trim($alboPartialClean2);
            //Resto da controllare
            $offsetTag = 6;
            $alboPartial3 = substr($testo,$fine+$offsetTag);
            $alboFinal = "<b>".$alboPartialClean1."</b> ".$alboPartialClean2."\n\r".$alboFinal;
            if(!empty($alboPartial3)){
                $alboFinal = $alboFinal.scrapeAlboPretorioFvgMid($alboPartial3);
            }
        }
    }
    //Ritorna per ricorsione
    return $alboFinal;
}  

/*
 * Function ElencoServiziFvg($link)
 * For extract
 * 
 * @return string
 */
function ElencoServiziFvg($input)
{
    $text = $input[1];
    $chat_id = $input[2];
    $reply_markup = $input[3]; 
    return "Funzione in test.";
}  

/*
 * Function ConcorsiFvg()
 * Site of Public http://www.regione.fvg.it/rafvg/concorsi/concorsiInt.act?dir=/rafvg/cms/RAFVG/Concorsi/
 * 
 * @return string
 */
function ConcorsiFvg($link)
{
    // Variable:
    $linkNew = $link[1];
    $chat_id = $link[2];
    $reply_markup = $link[3];
    $txt = file_get_contents($linkNew);
    $txt_i = "<div class=\"box-content\">";
    $txt_f = "<div class=\"pagination-container\">";
    $off = "0";
	
    // To Clean Text-HTML and convet character
    $letto = scrapeConcorsiFvg($txt,$txt_i,$txt_f,$off);
    $letto = strip_tags(str_replace('<', ' <', $letto));
    $letto = preg_replace("/\r\n|\r|\n/", " ", $letto);
    $letto = utf8_encode($letto);
    $lunghezzaLetto = strlen($letto);
    $maxExport = 4000;
    $ini = 0;
    if($lunghezzaLetto<$maxExport){
        $lettoC = utf8_encode($letto);
        apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $lettoC, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup));
    }else{
        while ($ini<=$lunghezzaLetto){
        if($maxExport+$ini<=$lunghezzaLetto){
            $lettoC = substr($letto, $ini, $maxExport);
            //$lettoC = utf8_encode($lettoB);
            apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $lettoC, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup));
        }else{
            $lettoC = substr($letto, $ini, $lunghezzaLetto);
            //$lettoC = utf8_encode($lettoB);
            apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $lettoC, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup));
        }
            $ini = $ini+$maxExport;
        }
    }
	return "\n\nFonte: Regione FVG";
}

/*
 * Function ConcorsiFvg()
 * Site of Public http://www.regione.fvg.it/rafvg/concorsi/concorsiInt.act?dir=/rafvg/cms/RAFVG/Concorsi/
 * Scrape function
 * 
 * @return string
 */
function scrapeConcorsiFvg($testo,$txt_inizio,$txt_fine,$offset)
{
    $start = strpos($testo,$txt_inizio);;
    $end = strpos($testo,$txt_fine,$start);
    $forReturn = substr($testo,$start,$end-$start+$offset);
    return $forReturn;
}

