<?php

/**
 * ############################################
 * Function database for Import Ics Plugin Modul
 * ############################################
 */
 
/**
 * Function dbIcsInsert
 * Ritorna un array posizionale
 * 
 * @return 0 
 */
function dbIcsInsert($uid, $name, $desc, $loc, $disc, $dateS, $dateE, $url, $contact, $sourceSite, $authorized, $function)
{
    try {
        $conn=getDbConnection();
        $sql = "INSERT INTO Ext_ics_event SET UID=:uid, Name=:name, Description=:desc, Local=:loc,   District=:disc, DataStart=:dateS, DataEnd=:dateE, Url=:url, Contact=:contact, Source=:sourceSite, Visible=:authorized, IdFunctionCollegate=:function";
        $stmt = $conn->prepare($sql);
		$stmt->bindValue(':uid', $uid, PDO::PARAM_STR);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);	
		$stmt->bindValue(':desc', $desc, PDO::PARAM_STR);
        $stmt->bindValue(':loc', $loc, PDO::PARAM_STR);
		$stmt->bindValue(':disc', $disc, PDO::PARAM_STR);
        $stmt->bindValue(':dateS', $dateS, PDO::PARAM_STR);
        $stmt->bindValue(':dateE', $dateE, PDO::PARAM_STR);
        $stmt->bindValue(':url', $url, PDO::PARAM_STR);
        $stmt->bindValue(':contact', $contact, PDO::PARAM_STR);
		$stmt->bindValue(':sourceSite', $sourceSite, PDO::PARAM_STR);
		$stmt->bindValue(':authorized', $authorized, PDO::PARAM_STR);
		$stmt->bindValue(':function', $function, PDO::PARAM_INT);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
  return 0;
}

/**
 * Function dbIcsDeleteAll
 * Remove alla record of table
 * 
 * @return 0
 */
function dbExtDeleteAll()
{
    try {
        $conn=getDbConnection();
        $sql = "TRUNCATE table `Ext_ics_event`;";
		$sql .= "TRUNCATE table `Ext_fvg_wifi`;";
		$sql .= "TRUNCATE table `Ext_fvg_pharmacies`;";
		$sql .= "TRUNCATE table `Ext_fvg_pharmacies_hour`;";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}

/**
 * Function dbWifiInsert
 * Function for insert in table the wifi point FVG
 * 
 * @return 0 
 */
function dbWiFiInsert($id, $wifiName, $wifiAddress, $wifiLat, $wifiLon)
{
    try {
        $conn=getDbConnection();
        $sql = "INSERT INTO Ext_fvg_wifi SET  NumIDinternal=:id, Name=:wifiName, Address=:wifiAddress, Latitude=:wifiLat, Longitude=:wifiLon";
        $stmt = $conn->prepare($sql);
	$stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':wifiName', $wifiName, PDO::PARAM_STR);
	$stmt->bindValue(':wifiAddress', $wifiAddress, PDO::PARAM_STR);		
        $stmt->bindValue(':wifiLat', $wifiLat, PDO::PARAM_STR);
        $stmt->bindValue(':wifiLon', $wifiLon, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
  return 0;
}

/**
 * Function dbPharmaFvgInsert
 * Function for insert in table the pharma point FVG
 * 
 * @return 0 
 */
function dbPharmaFvgInsert($azName, $azID, $azBusinessName, $azCity, $azAdress, $azLon, $azLat, $azPhone)
{
    try {
        $conn=getDbConnection();
        $sql = "INSERT INTO Ext_fvg_pharmacies SET name=:azName, idPharmFvg=:azID, businessName=:azBusinessName, city=:azCity, address=:azAdress, longitude=:azLon, latitude=:azLat, phone=:azPhone";
        $stmt = $conn->prepare($sql);
            $stmt->bindValue(':azName', $azName, PDO::PARAM_STR);
            $stmt->bindValue(':azID', $azID, PDO::PARAM_INT);
            $stmt->bindValue(':azBusinessName', $azBusinessName, PDO::PARAM_STR);
            $stmt->bindValue(':azCity', $azCity, PDO::PARAM_STR);
            $stmt->bindValue(':azAdress', $azAdress, PDO::PARAM_STR);
            $stmt->bindValue(':azLon', $azLon, PDO::PARAM_STR); 
            $stmt->bindValue(':azLat', $azLat, PDO::PARAM_STR); 
            $stmt->bindValue(':azPhone', $azPhone, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
  return 0;
}

/**
 * Function dbPharmaHourFvgInsert
 * Function for insert in table the pharma hour FVG
 * 
 * @return 0 
 */

function dbPharmaHourFvgInsert($azID, $pharmDa, $pharmA, $pharmType)
{
    try {
        $conn=getDbConnection();
        $sql = "INSERT INTO Ext_fvg_pharmacies_hour SET idPharmFvg=:azID, from0=:pharmDa, until0=:pharmA, type0=:pharmType";
        $stmt = $conn->prepare($sql);
	$stmt->bindValue(':azID', $azID, PDO::PARAM_INT);
        $stmt->bindValue(':pharmDa', $pharmDa, PDO::PARAM_STR);	
        $stmt->bindValue(':pharmA', $pharmA, PDO::PARAM_STR);
        $stmt->bindValue(':pharmType', $pharmType, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
  return 0;
}