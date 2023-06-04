<?php

/* 
 * Function for implements with plugin
 */

/*	Function Launcher($chat_id,$reply_markup,$user_id,$param)
 *  Launcher for all function
 * 	Return Arrayof param
 */
 
function Launcher($chat_id, $reply_markup, $user_id, $param){
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
 * Function Clean($text)
 * This function clean string for web tag of text
 * return string
*/

function Clean($text){
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
 * Other function for clean HTML/RSS/XML
 *
*/

function StripSinglTag($tag, $string) {
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
 * return time now
 * 
*/

function Ore(){
    $houre = "Ora esatta: ".date("j F Y, H:i:s", time());
    return $houre;
}

/*
 * Function privacy()
 * return string
 * 
*/

function privacy($variable){
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
 * @return text
*/

function Setting($also){
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
 * return string
*/

function Read($link){
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

	if ($rs = $rss->get($url_flux_rss)) {
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
 * Function Cinema($link)
 * For Mymovies
 * return string
 * 
 * Trovate questo e altri script su http://www.manuelmarangoni.it
 * Autore: Manuel Marangoni
 * Data messa online dello script: 9 ottobre 2013
 * Lo script mostra come recuperare un feed rss da un link online.
 * Utilizza le funzioni dell'XML DOM di PHP.
*/

function CinemaSearch($link){
	$linkNew = $link[1];
	$chat_id = $link[2];
	$reply_markup = $link[3];
	$user_id =  $link[4];
	$outPut = ""; 
	// crea un nuovo oggetto XML DOM
	$xmldom = new DOMDocument();
	// Carica il contenuto del feed presente al link indicato
	$xmldom->load($linkNew);
	//recupera il nodo rappresentato da <item>
	$nodo = $xmldom->getElementsByTagName("item");
	$risultato = "";
	$conteggio = 0;
	// Scorre tutti i nodi <item> della pagina
	// Limita a 20 il blocco di estrazione
	for($i=0; $i<=$nodo->length-1; $i++){
		$conteggio = $conteggio + 1;
		if ($conteggio>30){
			break;
		}else{
			// Estraggo il contenuto dei singoli tag del nodo <item>
			$titolo = $nodo->item($i)->getElementsByTagName("title")->item(0)->childNodes->item(0)->nodeValue;
			if ($titolo != ""){   
				$collegamento = $nodo->item($i)->getElementsByTagName("link")->item(0)->childNodes->item(0)->nodeValue;
				//Short link create
				$collegamento = $collegamento."cinema/friuliveneziagiulia/";
				$shortURL = initShort($collegamento);
				$risultato .= $titolo."\r\n".$shortURL."\r\n\r\n";
			}else{
				$risultato = "Non ci sono Film per oggi.";
			}
		}
	}
	$outPut = $risultato; //cloning
	$lunghezzaLetto = strlen($outPut);
	$maxExport = 3000;
	$ini = 0;
	if($lunghezzaLetto<$maxExport){
		$lettoC = $outPut;
		apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $lettoC, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
	}else{
		while ($ini<=$lunghezzaLetto){
			if($maxExport+$ini<=$lunghezzaLetto){
				$lettoC = substr($outPut, $ini, $maxExport);
				apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $lettoC, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
				dbTrackerInsert($chat_id,$user_id,'segue',$lettoC);
			}else{
				$lettoC = substr($outPut, $ini, $lunghezzaLetto);
				apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $lettoC, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup));
				dbTrackerInsert($chat_id,$user_id,'segue',$lettoC);
			}
			$ini = $ini+$maxExport;
		}
	}
	return "Per visualizzare orari e proiezioni clicca sui singoli link ai film.";
}

/*
 * Function FreeHost($link)
 * For extract txt from http://freetexthost.com/
 * Insert in to fine message: [fine]
 * 
 */

function FreeHost($link){
	$linkNew = $link[1];
	$chat_id = $link[2];
	$reply_markup = $link[3];
	$user_id =  $link[4];
	
	$txt = file_get_contents($linkNew);
	$txt_i = "<div id=\"contentsinner\">";
	$txt_f = "[fine]";
	$off = "0";
	$letto = scrapeFreeHost($txt,$txt_i,$txt_f,$off);
	$letto = str_replace("<div id=\"contentsinner\">","" ,$letto);
	$letto = str_replace("<br />","" ,$letto);
	$letto = str_replace("<b>","" ,$letto);
	$letto = str_replace("</b>","" ,$letto);
	apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $letto, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
	dbTrackerInsert($chat_id,$user_id,'segue',$letto);
	return "Lettura dati terminata.";
}

function scrapeFreeHost($testo,$txt_inizio,$txt_fine,$offset){
    $inizio = strpos($testo,$txt_inizio);
    $fine = strpos($testo,$txt_fine,$inizio);
    $daRestituire = substr($testo,$inizio,$fine-$inizio+$offset);
    return $daRestituire;
}

/*
 * Function FunzionePrevisioniMeteo($param)
 * Previsioni Meteo da 3B Meteo
 * 
 * Input: FunzionePrevisioniMeteo|18|regione6_verdi.jpg|0
 * function|hour|region|day
 * 
 */

function FunzionePrevisioniMeteo($param){
    $startDate = time();
    $gg = $param[3];
    $data = date('Y-m-d', strtotime('+'.$gg.' day', $startDate)); 
    /*
     * Example link of 3B meteo:
     * http://cdn4.3bmeteo.com/images/png_2014/2016-01-18_6_regione6_verdi.jpg
     * http://cdn4.3bmeteo.com/images/png_2014/2016-01-18_12_regione6_verdi.jpg
     * http://cdn4.3bmeteo.com/images/png_2014/2016-01-18_18_regione6_verdi.jpg
     * http://cdn4.3bmeteo.com/images/png_2014/2016-01-18_24_regione6_verdi.jpg            
    */ 
    $link = "http://cdn4.3bmeteo.com/images/png_2014/".$data."_".$param[1]."_".$param[2];  
    return $link;
}

/*
 * Function Oroscopo($link)
 * @return array
 * Oroscopo da: http://www.oggi.it/oroscopo/oroscopo-di-oggi/
 * 
 */

function Oroscopo($link){
    $linkNew = $link[1];
	$fonte = "Fonte http://www.oggi.it";
    $txt = file_get_contents($linkNew);
    $txt_i = "<h2 class=\"entry-title\">";
    $txt_f = "<!-- GIORNALIERO -->";
    $off = "0";
    // To Clean Text-HTML and convet character
    $letto = scrapeOroscopo($txt,$txt_i,$txt_f,$off);
    $letto = str_replace("&#8217;","'" ,$letto);
    $letto = str_replace("&#249;","u'" ,$letto);
    $letto = str_replace("&#232;","e'" ,$letto);
    $letto = str_replace("&#224;","a'" ,$letto);
    $letto = str_replace("&#242;","o'" ,$letto);
    $letto = str_replace("&#233;","e'" ,$letto);
    $letto = str_replace("&#236;","i'" ,$letto);
    $letto = str_replace("&#8230;","..." ,$letto);
    $letto = str_replace("&#8220;","\"" ,$letto);
    $letto = str_replace("&#8221;","\"" ,$letto);
    $letto = str_replace("&#160;"," " ,$letto);
    $letto = str_replace("&","" ,$letto);
    $letto = strip_tags(str_replace('<', ' <', $letto));
    $letto = preg_replace('/\\s{2,}/',' - ',$letto);
    return $letto.$fonte;
}

function scrapeOroscopo($testo,$txt_inizio,$txt_fine,$offset){
    $inizio = strpos($testo,$txt_inizio);
    $inizio = $inizio+25;
    $fine = strpos($testo,$txt_fine,$inizio);
    $daRestituire = substr($testo,$inizio,$fine-$inizio+$offset);
    return $daRestituire;
}

/*
 *
 * Function: linkImgOutput 
 * 
 * @return url for my site for download image
 *  
 */ 
 
function linkImgOutput($url){
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
 * Function: serviceTmp 
 * This function use for save the service with position select
 * @return string
 *  
 */
 
function serviceTmp($any){
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
 * Function: GasolineSearch
 * return array of list gasoline  
 */
 
function GasolineSearch($longitude, $latitude, $chat_id, $first_name_id, $message_id, $reply_markup){
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
 * Function: PharmaSearch
 * return array of list Pharma
 */
 
function PharmaSearch($longitude, $latitude, $chat_id, $first_name_id, $message_id, $reply_markup){
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
 * Function: PharmaFvgSearch
 * return array of list PharmaFvg
 */
 
function PharmaFvgSearch($longitude, $latitude, $chat_id, $first_name_id, $message_id, $reply_markup){
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
 * Function: CommerceSearch
 * return array of list Commerce 
 */
 
function CommerceSearch($longitude, $latitude, $chat_id, $first_name_id, $message_id, $reply_markup){
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
 * Function: ArtSearch
 * return array of list of Art
 */
 
function ArtSearch($longitude, $latitude, $chat_id, $user_id, $first_name_id, $message_id, $reply_markup){
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
 * Function: WifiSearch
 * return array of list of Art
 */
 
function WifiSearch($longitude, $latitude, $chat_id, $user_id, $first_name_id, $message_id, $reply_markup){
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
 * Function Extract photo from Flickr
 * For more setting read: https://www.flickr.com/services/api/flickr.photos.search.html
 * Fro example JSON RETURN: https://www.flickr.com/services/api/explore/flickr.photos.search
 * @return string
 * 
 */
 
function Photo($anyId){
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
	//print "<img title='".$title."' src='".$photo_url."' />";
	return $title;
}

/*
 * Function eventIcs
 * Event from table Ics 
 * @return string and array
 * 
*/

function EventIcs($link){ 
	$typeEvent = $link[1];
	$source = $link[2];
    $chat_id = $link[3];
    $reply_markup = $link[4];
    $user_id =  $link[5];
	/* Init variable */
	$emptyEvent = "Nessun evento trovato";
    $outPut = ""; 
	$outPutPartial = "";
	$local = "";
	$description = "";
	/* Type of query select from user */
	if($typeEvent=='day' && $source=='all'){
		$event = dbIcsSelectDay();
	} elseif ($typeEvent=='tomorrow' && $source=='all') {
		$event = dbIcsSelectTomorrow();
	} elseif ($typeEvent=='dayaftertomorrow' && $source=='all') {
		$event = dbIcsSelectDayAfterTomorrow();
	} elseif ($typeEvent=='all'&& $source=='all') {
		$event = dbIcsSelect();
	} elseif ($typeEvent=='ud'&& $source=='all') {
		$event = dbIcsSelectDayDistrict("ud");
	} elseif ($typeEvent=='go'&& $source=='all') {
		$event = dbIcsSelectDayDistrict("go");
	} elseif ($typeEvent=='ts'&& $source=='all') {
		$event = dbIcsSelectDayDistrict("ts");
	} elseif ($typeEvent=='pn'&& $source=='all') {
		$event = dbIcsSelectDayDistrict("pn");
	}
	/* Start query ics */
    if (empty($event)){
		$outPut = $emptyEvent;
	}else{
		foreach($event as $eventRt){
			$urLink = $eventRt['Url'];
			if ( $eventRt['Local'] != "" ) { 
				$local = "\nA: " . $eventRt['Local'];
			} else {
				$local = "";
			}
			if ( $eventRt['Local'] != "" ) { 
				$description = "\nDesc: " . $eventRt['Description'];
			} else {
				$description = "";
			}
			$outPutPartial = $eventRt['Name'] . $local . "\nDAL: " . $eventRt['startDate'] . " AL: " . $eventRt['endDate'] . "\nInfo: " . $urLink . "\nFonte: " . $eventRt['Source'] . "\n=====\n";         
			$outPutPartial = utf8_encode($outPutPartial);
			$outPut .= $outPutPartial;
		}
	}
    $outPut = strip_tags(str_replace('<', ' <', $outPut));
    $lunghezzaLetto = strlen($outPut);
    $maxExport = 3700;
    $ini = 0;
    if($lunghezzaLetto<$maxExport){
		$lettoC = utf8_encode($outPut);
		dbTrackerInsert($chat_id,$user_id,'segue',$lettoC);
		apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $lettoC, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
    }else{
        while ($ini<=$lunghezzaLetto){
			if($maxExport+$ini<=$lunghezzaLetto){
				$lettoC = substr($outPut, $ini, $maxExport);
				//$lettoC = utf8_encode($lettoB);
				apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $lettoC, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
				dbTrackerInsert($chat_id,$user_id,'segue',$lettoC);
			}else{
				$lettoC = substr($outPut, $ini, $lunghezzaLetto);
				//$lettoC = utf8_encode($lettoB);
				apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $lettoC, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
				dbTrackerInsert($chat_id,$user_id,'segue',$lettoC);
			}
			$ini = $ini+$maxExport;
		}
    }
return "Lettura dati terminata.";
}

/*
 * Function Aforisma()
 * @return string
 * Aforismi da: http://aforismi.meglio.it/aforisma-del-giorno.htm
 * 
 */

function Aforisma(){
    $linkNew = "http://aforismi.meglio.it/aforisma-del-giorno.htm";
    $txt = file_get_contents($linkNew);
    $txt_i = "<!-- google_ad_section_start -->";
    $txt_f = "<!-- google_ad_section_end -->";
    $off = "0";
    // To Clean Text-HTML and convet character
    $letto = scrapeAforisma($txt,$txt_i,$txt_f,$off);
    $letto = strip_tags(str_replace('<', ' <', $letto));
	if (mb_detect_encoding($letto, 'UTF-8', true) == false){
		$str = utf8_encode($letto);
		$letto = $str;
	}
    return $letto."\n\nFonte: https://aforismi.meglio.it";
}

function scrapeAforisma($testo,$txt_inizio,$txt_fine,$offset){
    $start = strpos($testo,$txt_inizio);
    $start = $start+32;
    $end = strpos($testo,$txt_fine,$start);
    $forReturn = substr($testo,$start,$end-$start+$offset);
    return $forReturn;
}

/*
 * Function Proverbio()
 * @return string
 * Proverbi da: http://www.barbanera.it
 * 
 */

function Proverbio(){
    $linkNew = "http://www.barbanera.it/almaoggi.php";
    $txt = file_get_contents($linkNew);
    $txt_i = "<div class=\"proverbio\" align=\"center\">";
    $txt_f = "</div>";
    $off = "0";
    // To Clean Text-HTML and convet character
    $letto = scrapeProverbio($txt,$txt_i,$txt_f,$off);
    $letto = strip_tags(str_replace('<', ' <', $letto));
    $letto = utf8_encode($letto);
    return $letto."\n\nFonte: http://www.barbanera.it";
}

function scrapeProverbio($testo,$txt_inizio,$txt_fine,$offset){
    $start = strpos($testo,$txt_inizio);
    $start = $start+38;
    $end = strpos($testo,$txt_fine,$start);
    $forReturn = substr($testo,$start,$end-$start+$offset);
    return $forReturn;
}

/*
 * Function NatoOggi()
 * @return string
 * Proverbi da: http://www.barbanera.it
 * 
 */

function NatoOggi(){
    $linkNew = "http://www.barbanera.it/almaoggi.php";
    $txt = file_get_contents($linkNew);
    $txt_i = "<div class=\"comeoggi\" align=\"justify\">";
    $txt_f = "</div>";
    $off = "0";
    // To Clean Text-HTML and convet character
    $letto = scrapeNatoOggi($txt,$txt_i,$txt_f,$off);
    $letto = strip_tags(str_replace('<', ' <', $letto));
    $letto = utf8_encode($letto);
    return $letto."\n\nFonte: http://www.barbanera.it";
}

function scrapeNatoOggi($testo,$txt_inizio,$txt_fine,$offset){
    $start = strpos($testo,$txt_inizio);
    $start = $start+38;
    $end = strpos($testo,$txt_fine,$start);
    $forReturn = substr($testo,$start,$end-$start+$offset);
    return $forReturn;
}

/*
 * Function InfoTrafficoFvg()
 * @return string
 * Aforismi da: http://www.fvgstrade.it/
 * 
 */

function InfoTrafficoFvg(){
    $linkNew = "http://www.fvgstrade.it/";
    $txt = file_get_contents($linkNew);
    $txt_i = "<div id=\"Panel_OrdinanzeLista\" class=\"ordinanzeItemsHome\">";
    $txt_f = "<div class=\"home_colonna3 grid_8\">";
    $off = "0";
    // To Clean Text-HTML and convet character
    $letto = scrapeInfoTrafficoFvg($txt,$txt_i,$txt_f,$off);
    $letto = strip_tags(str_replace('<', ' <', $letto));
    $letto = preg_replace('/\\s{2,}/',' ',$letto);
    $letto = str_replace("vedi sulla mappa","\n",$letto);
    return $letto."\n\nFonte: http://www.fvgstrade.it";
}

function scrapeInfoTrafficoFvg($testo,$txt_inizio,$txt_fine,$offset){
    $start = strpos($testo,$txt_inizio);
    $long = strlen($txt_inizio);
    $start = $start+$long;
    $end = strpos($testo,$txt_fine,$start);
    $forReturn = substr($testo,$start,$end-$start+$offset);
    return $forReturn;
}

/*
 * Function InfoTrafficoAutostradaFvg()
 * @return string
 * Aforismi da: https://infotraffico.autovie.it/
 * 
 */

function InfoTrafficoAutostradaFvg(){
    $linkNew = "https://infotraffico.autovie.it/";
    $txt = file_get_contents($linkNew);
    $txt_i = "<div class=\"c-eventi\">";
    $txt_f = "<!--";
    $off = "0";
    // To Clean Text-HTML and convet character
    $letto = scrapeInfoTrafficoAutostradaFvg($txt,$txt_i,$txt_f,$off);
    $letto = strip_tags($letto, '<strong>');
    $letto = preg_replace('/\\s{2,}/',"\n",$letto);
    return $letto."\n\nFonte: https://infotraffico.autovie.it/";
}

function scrapeInfoTrafficoAutostradaFvg($testo,$txt_inizio,$txt_fine,$offset){
    $start = strpos($testo,$txt_inizio);
    $long = strlen($txt_inizio);
    $start = $start+$long;
    $end = strpos($testo,$txt_fine,$start);
    $forReturn = substr($testo,$start,$end-$start+$offset);
    return $forReturn;
}

/*
 * Function twitterInfo()
 * For extract txt from Twitter
 * Return string
*/

function twitterInfo($link){
	$userTwitter = $link[1];
	$chat_id = $link[2];
	$reply_markup = $link[3];
	$user_id =  $link[4];
	/*
	 * Variable key, token Twitter
	*/
	$tableParmExit = dbParamExtraction('SoftDesc = "Twitter" AND Active = "1"');
	foreach ($tableParmExit as $param) {
		switch ($param['Code']) {
			case "token":
				$twitterToken = $param['Param'];
				break;
			case "token_secret":
				$twitterTokenSecret = $param['Param'];
				break;
			case "key":
				$twitterKey = $param['Param'];
				break;
			case "key_secret":
				$twitterKeySecret = $param['Param'];
				break;
		}  
	}
	$settings = array(
	   'oauth_access_token' => $twitterToken,
	   'oauth_access_token_secret' => $twitterTokenSecret,
	   'consumer_key' => $twitterKey,
	   'consumer_secret' => $twitterKeySecret
	);

	//Scegli il metodo GET
	$requestMethod = "GET";
	
	//Per recuperare i tweet bisogna richiamare user_timeline.json
	$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
	
	/*
	 * Con questa istruzione impostiamo l'username
	 * dell'account Twitter dal quale vogliamo
	 * recuperare i tweet (@cnn) ed anche il numero
	 * di tweet (10)
	 */
	
	$getfield = '?screen_name='.$userTwitter.'&count=10';
	
	//Crea una istanza di TwitterAPIExchange
	$twitter = new TwitterAPIExchange($settings);
	
	/*
	 * Utilizza questi tre metodi in forma di
	 * "method chaining" per passare le informazioni
	 * necessarie alla classe e recuperare i tweet
	 */
	$user_timeline_json = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
	
	//Stampa la risposta JSON e termina lo script
	$user_timeline = json_decode($user_timeline_json, true);
	//Setting output and variable
	$outPutTwitter="";
	$reversed = array_reverse($user_timeline);
	
	foreach ($reversed as $tweetLine){
	  $whenTwitter = date('j.n.Y', strtotime($tweetLine['created_at']));
	  $outPutTwitter = $outPutTwitter.$whenTwitter." - ".$tweetLine['text']."\n\n";
	}
    //Send Message
    apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $outPutTwitter, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup, 'disable_web_page_preview' => 'true'));
    dbTrackerInsert($chat_id,$user_id,'segue',$outPutTwitter);
    return "Lettura dati terminata"; 
}

/*
 * ##################################################################################
 * FINE FUNCTION DEFAULT
 * ##################################################################################
 */

/*
 * Function fiaspPdf($link)
 * 
 * 
 */
function FiaspPdf($link){
	$linkNew = $link[1];
	$chat_id = $link[2];
	$reply_markup = $link[3];
	$user_id =  $link[4];
	// parameter
	setlocale(LC_TIME,"it_IT");
	$monthUrl = $today = strftime('%B');
	echo $monthUrl;
	if ($linkNew=1){
		$monthUrl = $monthUrl;
	}else{
		$monthUrl = $monthUrl+1;
	} 
	/*
	* Example link for calendar Fiasp
	* http://www.polisportivatrattoriafriuli.com/calendario-fiasp-2/aprile?tmpl=%2Fsystem%2Fapp%2Ftemplates%2Fprint%2F'
	*/
	$parameters = array('Secret' => 'A4tRRep2HLjhNXlo',
		'Token' => ' 836212921',
		'Url' => 'http://www.polisportivatrattoriafriuli.com/calendario-fiasp-2/luglio?tmpl=%2Fsystem%2Fapp%2Ftemplates%2Fprint%2F',
		'StoreFile' => 'true'
	);

$result = convert_api('web', 'pdf', $files, $parameters);
print(json_encode($result));
	
	
	$url = "http://www.polisportivatrattoriafriuli.com/calendario-fiasp-2/".$monthUrl."?tmpl=%2Fsystem%2Fapp%2Ftemplates%2Fprint%2F";
	$urlFinal = toPdfConvert($url);
	return $urlFinal;
} 

/*
 * Function EventiBoBoBo($link)
 * For extract txt from Eventi BOBO
 * Scrape function
 * 
 */
function EventiBoBoBo($link){
	$linkNew = $link[1];
	$txt = file_get_contents($linkNew);
	$txt_i = "1 - <span>";
	$txt_f = "59 - <span>";
	$off = "0";
	$letto = scrapeEventiBoBoBo($txt,$txt_i,$txt_f,$off);
	$letto = str_replace("&nbsp;","" ,$letto); 
	$letto = str_replace(array("\n","\r"), " ", $letto);
	$letto = strip_tags(str_replace('<', ' <', $letto));
	$lettoB = substr($letto, 0, 4069);
	$lettoC = utf8_encode($lettoB);
	return $lettoC;
}

function scrapeEventiBoBoBo($testo,$txt_inizio,$txt_fine,$offset){
    $inizio = strpos($testo,$txt_inizio);
    $inizio = $inizio+0;
    $fine = strpos($testo,$txt_fine,$inizio);
    $outPut = substr($testo,$inizio,$fine-$inizio+$offset);
    return $outPut;
}

/*
 * Function PsOnlineFVG()
 * https://servizionline.sanita.fvg.it/tempiAttesaService/tempiAttesaPs
 * Json info Pronto Soccorso Fvg
 * https://servizi.regione.fvg.it/portale/
 * 
 * @return String
*/
function PsOnlineFVG($variable){
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
 * Json info parking Udine Sistema Sosta mobilità
 * http://www.ssm.it/
 * Free parking
 * 
*/
function Ssm(){
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
 * Scrape function
 * 
 */
function AlertProtezione($link){
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
 * Function FvgJob($link)
 * For extract txt from FvgJob site
 * Scrape function
 * DEPRECATED
 */
function FvgJob($link){
	$linkNew = $link[1];
	$urlPdf = "";
	$txt = file_get_contents($linkNew);
	//$txt_i = "<h3 class=\"field-content\">ALLERTA REGIONALE";
	$txt_i = "<div class=\"box-last-info left relative\">";
	$txt_f = "<div class=\"box-last-facebook left relative\">";
	$off = "0";
	$letto = scrapeFvgJob($txt,$txt_i,$txt_f,$off);
	$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
	if(preg_match_all("/$regexp/siU", $letto, $matches, PREG_SET_ORDER)) {
		foreach($matches as $match){
			$url = $match[2];
			$url1 = "http://www.fvjob.it".$url;
			$letto = str_replace("<a href=\"".$url."\" class=\"box-link\">&nbsp;</a>", $url1, $letto);
			// $match[2] = link address
			// $match[3] = link text
		}
	}
	$letto = strip_tags(str_replace('<', ' <', $letto));
	$letto = preg_replace("/\r\n|\r|\n/", " ", $letto);
	$lettoB = substr($letto, 0, 4069);
	return $lettoB;
}

function scrapeFvgJob($testo,$txt_inizio,$txt_fine,$offset){
    $inizio = strpos($testo,$txt_inizio);
    if(empty ($inizio)){
		return "E' accorso un errore con l'estrazione dei dati, ritenta.";
    }else{
		$inizio = $inizio+0;
		$fine = strpos($testo,$txt_fine,$inizio);
		$outPut = substr($testo,$inizio,$fine-$inizio+$offset);
		return $outPut;
    }
}

/*
 * Function AlboPretorioFvg($link)
 * For extract txt from http://albopretorio.regione.fvg.it/ap/albo
 * Scrape function
 * 
 */
function AlboPretorioFvg($link){
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

function scrapeAlboPretorioFvg($testo,$txt_inizio,$txt_fine,$offset){
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

function scrapeAlboPretorioFvgMid($testo){
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
    //Restituisce per ricorsione
    return $alboFinal;
}  

/*
 * Function ElencoServiziFvg($link)
 * For extract
 * Scrape function
 * 
 */
function ElencoServiziFvg($input){
	$text = $input[1];
	$chat_id = $input[2];
	$reply_markup = $input[3]; 
	return "Funzione in test.";
}  

/*
 * Function Cinema($link)
 * return string
 * 
*/

function Cinema($link){
	/*******************************************************
	https://www.w3schools.com/PhP/php_ajax_rss_reader.asp
	*******************************************************/
	$total = "";
	$xml = $link[1];
	$xmlDoc = new DOMDocument();
	$xmlDoc->load($xml);

	$x=$xmlDoc->getElementsByTagName('item');
	  for ($i=0; $i<=2; $i++) {
		$item_title=$x->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
		//$item_link=$x->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
		$item_desc=$x->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
		$total = $item_desc . $total;
	  }
	$totale = substr($total, 0, 4069);
	return $total;
}

/*
 * Function lottoNumber($link)
 * For extract txt from http://estrazionidellotto.it/#tab1 
 * 
 * 
 */
function lottoNumber($link){
	$linkNew = $link[1];
	$chat_id = $link[2];
	$reply_markup = $link[3];
	$user_id =  $link[4];  
	$txt = file_get_contents($linkNew);
	$txt_Date = file_get_contents($linkNew);
	$txt_i_Date = "<h3 id=\"tab-title\" class=\"current\">";
	$txt_f_Date = "</h3>";
	$txt_i = "<td class=\"col50 alignleft\">";
	$txt_f = "</div>";
	$off = "0";
	$dateExt = scrapeLottoNumber($txt_Date, $txt_i_Date, $txt_f_Date, $off);
	$dateExt = strip_tags(str_replace('<', ' <', $dateExt));
	$letto = scrapeLottoNumber($txt, $txt_i, $txt_f, $off);
	$letto = strip_tags(str_replace('<', ' <', $letto));
	$letto = str_replace(array(' ',''), "", $letto);
	$letto = str_replace(array('\n',' ','\b'), "", $letto);
	$letto = preg_replace('/\\s{2,}/','-',$letto);
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
				dbTrackerInsert($chat_id,$user_id,'segue',$lettoC);
			}else{
				$lettoC = substr($letto, $ini, $lunghezzaLetto);
				//$lettoC = utf8_encode($lettoB);
				apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' =>  $lettoC, 'parse_mode' => 'HTML', 'reply_markup' => $reply_markup));
				dbTrackerInsert($chat_id,$user_id,'segue',$lettoC);
			}
			$ini = $ini+$maxExport;
		}
	}
	return $dateExt.". Le estrazioni escono solitamente alle ore 20.00!";
}

function scrapeLottoNumber($testo,$txt_inizio,$txt_fine,$offset){
    $inizio = strpos($testo,$txt_inizio);
    $inizio = $inizio;
    $fine = strpos($testo,$txt_fine,$inizio);
    $daRestituire = substr($testo,$inizio,$fine-$inizio+$offset);
    return $daRestituire;
}

/*
 * Function ConcorsiFvg()
 * @return string
 * Site of Public http://www.regione.fvg.it/rafvg/concorsi/concorsiInt.act?dir=/rafvg/cms/RAFVG/Concorsi/
 * 
 */

function ConcorsiFvg($link){
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

function scrapeConcorsiFvg($testo,$txt_inizio,$txt_fine,$offset){
    $start = strpos($testo,$txt_inizio);;
    $end = strpos($testo,$txt_fine,$start);
    $forReturn = substr($testo,$start,$end-$start+$offset);
    return $forReturn;
}

