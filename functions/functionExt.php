<?php
include ('functionDb.php');
include ('functionPluginDb.php');

class ics {
    /**
    * Function getIcsEventsAsArray
    * Function is to get all the contents from ics and explode all the datas according to the events and its sections *
    * 
    * @return string 
    */
    function getIcsEventsAsArray($file)
    {
        $options = array(
                'http'=>array(
                'method'=>"GET",
                'header'=>"Accept-language: en\r\n" .
                "Cookie: foo=bar\r\n" . 
                "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/60.0.0.0" 
                )
        );
	$context = stream_context_create($options);
        $icalString = file_get_contents ($file, false, $context);
        $icsDates = array ();
        /* Explode the ICs Data to get datas as array according to string �BEGIN:� */
        $icsData = explode ( "BEGIN:", $icalString );
        /* Iterating the icsData value to make all the start end dates as sub array */
        foreach ( $icsData as $key => $value ) {
            $icsDatesMeta [$key] = explode ( "\n", $value );
        }
        /* Itearting the Ics Meta Value */
        foreach ( $icsDatesMeta as $key => $value ) {
            foreach ( $value as $subKey => $subValue ) {
                /* to get ics events in proper order */
                $icsDates = $this->getICSDates ( $key, $subKey, $subValue, $icsDates );
            }
        }
        return $icsDates;
    }
    /**
    * Function getICSDates
    * Function is to avaid the elements wich is not having the proper start, end  and summary informations
     * 
    * @return array 
    */
    function getICSDates($key, $subKey, $subValue, $icsDates)
    {
        if ($key != 0 && $subKey == 0) {
            $icsDates [$key] ["BEGIN"] = $subValue;
        } else {
            $subValueArr = explode ( ":", $subValue, 2 );
            if (isset ( $subValueArr [1] )) {
                $icsDates [$key] [$subValueArr [0]] = $subValueArr [1];
            }
        }
        return $icsDates;
    }
}

/**
* Function Ics
* Function for insert the event in the table
 * 
* @return string 
*/
function Ics($urlIcs, $source, $visible, $functionCollegate)
{
	/* Replace the URL / file path with the .ics url */
	$file = "$urlIcs";
	$sourceSite = $source;
	$authorized = $visible;
	$function = $functionCollegate;
	/* Getting events from isc file */
	$obj = new ics();
	$icsEvents = $obj->getIcsEventsAsArray( $file );

	/* Here we are getting the timezone to get the event dates according to gio location */
	$timeZone = trim ( $icsEvents [1] ['X-WR-TIMEZONE'] );
	/* If timeZone not found set to "Europe/Rome" */
	if ( $timeZone == '' ) {
		$timeZone = 'Europe/Rome';
	}
	unset( $icsEvents [1] );
	foreach( $icsEvents as $icsEvent){
		/* System control error Ics Import */
		$eventName = $icsEvent['SUMMARY'];
		$start = isset( $icsEvent ['DTSTART;VALUE=DATE'] ) ? $icsEvent ['DTSTART;VALUE=DATE'] : $icsEvent ['DTSTART'];
		$end = isset( $icsEvent ['DTEND;VALUE=DATE'] ) ? $icsEvent ['DTEND;VALUE=DATE'] : $icsEvent ['DTEND'];
		/* End variable setting for control */
		if ($eventName !='' && $end != '' && $start != 00000000){
			/* Getting UID events */
			$uid = $icsEvent['UID'];
			/* Getting start date and time */
			$start = isset( $icsEvent ['DTSTART;VALUE=DATE'] ) ? $icsEvent ['DTSTART;VALUE=DATE'] : $icsEvent ['DTSTART'];
			/* Converting to datetime and apply the timezone to get proper date time */
			$startDt = new DateTime ($start);
			$startDt->setTimeZone (new DateTimezone ( $timeZone ));
			$startDate = $startDt->format ( 'Y/m/d H:i' );
			/* Getting end date with time */
			$end = isset($icsEvent ['DTEND;VALUE=DATE'] ) ? $icsEvent ['DTEND;VALUE=DATE'] : $icsEvent ['DTEND'];
			/* Converting to datetime and apply the timezone to get proper date time */
			$endDt = new DateTime ($end);
			$endDt->setTimeZone (new DateTimezone ( $timeZone ));
			$endDate = $endDt->format ( 'Y/m/d H:i' );
			
			/* Getting the event Info */
			/* Name Event */
			$eventName = $icsEvent['SUMMARY'];
			$eventName = utf8_decode($eventName); 
			$eventName = str_replace("\,", ",", $eventName);
			$eventName = str_replace("??", " ", $eventName);   
			$eventName = str_replace("?", "'", $eventName);   
			/* Description Name */
			$eventDesc = $icsEvent['DESCRIPTION'];
			$eventDesc = str_replace("\,", ",", $eventDesc);
			$eventDesc = str_replace("??","", $eventDesc);
			$eventDesc = str_replace("?", "'", $eventDesc);
			$eventDesc = utf8_decode($eventDesc); 
			/* Location Event */
			$eventLoc = $icsEvent['LOCATION'];
			$eventLoc = str_replace("\\", "", $eventLoc);
			/* District Event */
			$eventDisc = $icsEvent['LOCATION'];
			$pattern = "/(UD){1}|(Ud){1}|(ud){1}|(GO){1}|(Go){1}|(go){1}|(TS){1}|(Ts){1}|(ts){1}|(PN){1}|(Pn){1}|(pn){1}/m";
			preg_match($pattern, $eventDisc, $resultDisc);
			$eventDiscFinal = $resultDisc[1].$resultDisc[2].$resultDisc[3].$resultDisc[4].$resultDisc[5].$resultDisc[6].$resultDisc[7].$resultDisc[8].$resultDisc[9].$resultDisc[10].$resultDisc[11].$resultDisc[12];
			/* Discrict Summary */
			$eventDiscSummary = $icsEvent['SUMMARY'];
			$patternSummary = "/(UD){1}|(Ud){1}|(ud){1}|(GO){1}|(Go){1}|(go){1}|(TS){1}|(Ts){1}|(ts){1}|(PN){1}|(Pn){1}|(pn){1}/m";
			preg_match($patternSummary, $eventDiscSummary, $resultDiscSummary);
			if ($eventDiscFinal == NULL){
				$eventDiscFinal = $resultDiscSummary[1].$resultDiscSummary[2].$resultDiscSummary[3].$resultDiscSummary[4].$resultDiscSummary[5].$resultDiscSummary[6].$resultDiscSummary[7].$resultDiscSummary[8].$resultDiscSummary[9].$resultDiscSummary[10].$resultDiscSummary[11].$resultDiscSummary[12];
			}
			/* Control URL for ICS different */
			$eventUrl = $icsEvent['URL'];
			if ( $eventUrl == '' ){
				$eventUrl = $icsEvent['URL;VALUE=URI'];
			}
			$eventGeo = $icsEvent['GEO'];
			$eventContact = $icsEvent['CONTACT'];
			$eventContact = str_replace("\;", " - ", $eventContact);
			$eventCost = $icsEvent['X-COST'];
			/* Insert into DB the ICS new*/
			dbIcsInsert($uid, $eventName, $eventDesc, $eventLoc, $eventDiscFinal, $startDate, $endDate, $eventUrl, $eventContact, $sourceSite, $authorized, $function);
		}
	  }
}

/*
 * Function WifiFVG()
 * Function for insert in DB the point WiFI
 * Json info Wifi Point FvgWiFi
 * http://www.insiel.it
 * Free access WiFi Friuli Venezia Giulia
 * 
 * @return string
*/

function WiFiFVG($url)
{
    //create curl resource
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    $file = "$url";
    //set url
    curl_setopt($ch, CURLOPT_URL, $file);
    
    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/50.0.0.0');
    
    //$outPut contains the output Array
    $page = curl_exec($ch);
    $outPut = json_decode($page, true);
    //Array multidimensional
    $pointCount = $outPut['features'];
    //Number of elements in select Array
    $numWifi = count($pointCount);
    //Create record data
    for($i=1; $i<$numWifi; $i++){
        $wifiName = $outPut['features'][$i]['properties']['name'];
        $wifiLon = $outPut['features'][$i]['geometry']['coordinates']['0'];
        $wifiLat = $outPut['features'][$i]['geometry']['coordinates']['1'];
        /* Insert into DB the new data */
        dbWiFiInsert($i, $wifiName, $wifiLat, $wifiLon);
    }
    //Close curl resource to free up system resources
    curl_close($ch);
}
/*
 * farmOnlineFVGExt
 * Function for import FVG pharma
 * 
 * https://sapi.sanita.fvg.it/farmacie/orario?APID=PM3EF4Q9CSNFJGW0LSSDVFBM81NLPGV17TSZ2XEAGY2VDQJNNF6T0HCUGLUOVZ3K
 * Json info Pronto Soccorso Fvg
 * https://servizi.regione.fvg.it/portale/
 * 
 * @return string
*/
function farmOnlineFVGExt($url)
{
    //Create curl resource
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    //Set url
    $file = "$url";
    curl_setopt($ch, CURLOPT_URL, $file);
    
    //Return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/50.0.0.0');
    
    //$outPut contains the output Array
    $page = curl_exec($ch);
    $outPut = json_decode($page, true);
    //Array multidimensional
    $farmOnline = $outPut['farmacie'];
    //Number of elements in select Array
    $numFarm = count($farmOnline);
    $outPutFunction = '';

    //Pharma of Friuli open with hours
    for($i=0; $i<$numFarm; $i++){
        $pharmOpen = '';
        $numPharmHour =  '';
        $numPharmHours =  '';
        $azID = $outPut['farmacie'][$i]['id'];
        $azName = $outPut['farmacie'][$i]['insegna'];
        $azBusinessName = $outPut['farmacie'][$i]['ragioneSociale'];
        $azCity = $outPut['farmacie'][$i]['comune'];
        $azAdress = $outPut['farmacie'][$i]['indirizzo'];
        $azLon = $outPut['farmacie'][$i]['longitudine'];
        $azLat = $outPut['farmacie'][$i]['latitudine'];
        $azPhone = $outPut['farmacie'][$i]['telefono'];
        $pharmHour = $outPut['farmacie'][$i]['orari'];
        $numPharmHours = count($pharmHour);
        //Insert in db
        dbPharmaFvgInsert($azName, $azID, $azBusinessName, $azCity, $azAdress, $azLon, $azLat, $azPhone);
        //Orari Farmacie
        for($j=0; $j<$numPharmHours; $j++){
            $pharmDa = $pharmHour[$j]['da'];
            $pharmA = $pharmHour[$j]['a'];
            $pharmType = $pharmHour[$j]['tipo'];
            //Insert in db
            dbPharmaHourFvgInsert($azID, $pharmDa, $pharmA, $pharmType);
        }
    }
    //Close curl resource to free up system resources
    curl_close($ch);
    return $outPutFunction;
}