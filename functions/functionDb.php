<?php

/**
 * ############################################
 * Connection Mysql
 * ############################################
 */


/**
 * getDbConnection
 * Function for connet the DB and return the connection if the DB is OK, or return the message error
 * 
 * @return \PDO
 * @throws Exception
 */

function getDbConnection()
{
    // Apertura connessione al database
    // NB: Non necessita di chiusura connessione - vedi http://php.net/manual/en/pdo.connections.php
    try {
        $mysqlConn = new PDO('mysql:dbname='.$GLOBALS['mysql_db'].';port='.$GLOBALS['mysql_port'].';host='.$GLOBALS['mysql_host'],$GLOBALS['mysql_user'],$GLOBALS['mysql_pass']);
        if ($mysqlConn===false) {
            throw new Exception ('Apertura database MySql fallita. Host '.$GLOBALS['mysql_host'].', User '.$GLOBALS['mysql_user']);
		}
        /* Only for debug
         * $mysqlConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         * $mysqlConn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        */
    } catch (PDOException $e) {
        die("Connessione fallita al DB (connection failed). Non posso proseguire <br>");
        /* Only for debug
         *  return 'Connection failed: ' . $e->getMessage();
         */
    }
    return $mysqlConn;
}

/**
 * ############################################
 * Function database for setting demone
 * ############################################
 */

/**
 * Function dbDemoneStatus
 * Function for control state of Demone active/deactive
 * 
 * @return array 
 */
function dbDemoneStatus()
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT Active FROM `software_config` WHERE SoftDesc = 'Demone' AND Code = 'status'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $value=$stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($value['Active']);
}

/**
 * Function dbDemName
 * Function for name of bot telegram
 * 
 * @return string 
 */
function dbDemName()
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT Param FROM `software_config` WHERE Code = 'nomebot' AND SoftDesc = 'Demone'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $name=$stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return $name;
}

/**
 * Function dbDemFunction
 * Function for list variable of only demone
 * 
 * @return string 
 */
function dbDemFunction($variable)
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT Param FROM `software_config` WHERE Code=:variable AND SoftDesc = 'Demone'";
        $stmt = $conn->prepare($sql);
	$stmt->bindValue(':variable',$variable, PDO::PARAM_STR);
        $stmt->execute();
        $name=$stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return $name;
}

/**
 * ###########################################
 * Function database for setting keyboard Bot
 * ###########################################
 */

/**
 * Function dbDemoneKeyboard
 * Function for principal keyboard construct
 * 
 * @return array
 */
function dbDemoneKeyboard($value)
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT Titolo, Param, Type FROM `software_config_button` WHERE $value AND Active=1 ORDER BY Number";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableButton=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableButton[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableButton);
}

/**
 * Function dbDemoneKeyboardSub
 * Function for sub keyboard
 * 
 * @return string
 */
function dbDemoneKeyboardSub($value, $limLow, $limTop)
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT Titolo, Param, Type FROM `software_config_button` WHERE $value AND Active=1 ORDER BY Number";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableButton=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableButton[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableButton);
}

/**
 * Function dbDemoneCountKeyboard
 * Function for count the level of Keyboard
 * 
 * @return integer 
 */

function dbDemoneCountKeyboard()
{
    try {
        $conn=getDbConnection();
        $sql = "Select Count(Titolo) FROM `software_config_button` WHERE Active=1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $number=$stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return $number;
}

/**
 * Function dbDemoneTmpUserButton
 * Function for create temporary keyboard in the next level
 * 
 * @return string 
 */

function dbDemoneTmpUserButton($userid)
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT UserID, IdLevel, IdLevelIndoor, IdButton FROM tmpUserButton WHERE UserID=:userid";
        $stmt = $conn->prepare($sql);
	$stmt->bindValue(':userid',$userid, PDO::PARAM_STR);
        $stmt->execute();
        $list=$stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return $list;
}

/**
 * Function dbDemoneNumberKeyboard
 * Function for the max level of Keyboard to create
 * 
 * @return integer 
 */

function dbDemoneNumberKeyboard()
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT MAX(Number) AS numero FROM `software_config_button` where Active = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $maxNumber=$stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return $maxNumber;
}

/** 
 * ##########################################
 * Function database for setting button
 * ##########################################
 */

/**
 * Function dbButtonExtraction
 * Function for the values of parameters for setting the bot
 * 
 * @return array 
 */
function dbButtonExtraction($value)
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT ID, SoftDesc, Param, Number, Type, Titolo, Active, Log, DateUpdt FROM `software_config_button` WHERE $value ORDER BY Number, SoftDesc";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableButton=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableButton[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableButton);
}

/**
 * Function dbButtonUpdate
 * Function for update the parameter of button 
 * 
 * @return 0 
 */
function dbButtonUpdate($ID, $software, $param, $tipo, $number, $state, $user, $titolo)    
{
    try {
        $conn=getDbConnection();
        $sql = "UPDATE software_config_button SET SoftDesc=:software, Param=:param, Type=:tipo, Number=:number, Active=:state, Log=:user, Titolo=:titolo, DateUpdt=now() WHERE ID=:ID";      
        $stmt = $conn->prepare($sql);        
        $stmt->bindValue(':ID',$ID, PDO::PARAM_INT);
        $stmt->bindValue(':software',$software, PDO::PARAM_STR);
        $stmt->bindValue(':param',$param, PDO::PARAM_STR);
        $stmt->bindValue(':tipo',$tipo, PDO::PARAM_STR);
        $stmt->bindValue(':number',$number, PDO::PARAM_INT);
        $stmt->bindValue(':state',$state, PDO::PARAM_STR);
        $stmt->bindValue(':titolo',$titolo, PDO::PARAM_STR);
        $stmt->bindValue(':user',$user, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
        }
    return 0;
}

/**
 * Function dbButtonInsert
 * Function for insert the value for setting in the table
 * 
 * @return 0 
 */
function dbButtonInsert($software, $param, $tipo, $number, $active, $user, $titolo)
{
    try {
        $conn=getDbConnection();
        $sql = "insert software_config_button SET SoftDesc=:software, Param=:param, Type=:tipo, Number=:number, Active=:active, Log=:user, Titolo=:titolo, DateUpdt=now()";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':software',$software, PDO::PARAM_STR);
        $stmt->bindValue(':param',$param, PDO::PARAM_STR);
        $stmt->bindValue(':tipo',$tipo, PDO::PARAM_STR);
        $stmt->bindValue(':number',$number, PDO::PARAM_INT);
        $stmt->bindValue(':active',$active, PDO::PARAM_STR);
        $stmt->bindValue(':titolo',$titolo, PDO::PARAM_STR);
        $stmt->bindValue(':user',$user, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}

/**
 * Function dbButtonDelete
 * Use this function for delete record of button
 * 
 * @return 0 
 */
function dbButtonDelete($ID)    
{
    try {
        $conn=getDbConnection();
        $sql = "delete from software_config_button WHERE ID=:ID";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ID',$ID, PDO::PARAM_INT);
        $stmt->execute();
        } catch (Exception $ex) {
            return $ex->getMessage();
            }
        return 0;
}

/** 
 * ###########################################
 * Function database for setting scheduler send
 * ###########################################
 */

/**
 * Function dbSchedulerExtraction
 * Function for return the all value of setting parameters
 * 
 * @return type Array 
 */
function dbSchedulerExtraction($value)
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT ID, DataInsert, DataScheduler, Repeater, NumberRepeat, HowOften, Text, Note, Signature, SingleUserID, AlreadySent, Counter FROM `message_scheduler` WHERE $value ORDER BY DataScheduler DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableButton=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableButton[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableButton);
}

/**
 * Function dbSchedulerUpdate
 * Function for change the scheduler message
 * 
 * @return 0 
 */
function dbSchedulerUpdate($ID, $date, $signature, $text, $note, $alreadysent)    
{
    try {
        $conn=getDbConnection();
        $sql = "UPDATE message_scheduler SET DataScheduler=:date, Text=:text, Note=:note, Signature=:signature, AlreadySent=:alreadysent WHERE ID=:ID";      
        $stmt = $conn->prepare($sql);        
        $stmt->bindValue(':ID',$ID, PDO::PARAM_INT);
        $stmt->bindValue(':date',date('Y-m-d H:i:s', strtotime ($date)), PDO::PARAM_STR);
        $stmt->bindValue(':text',$text, PDO::PARAM_STR);
        $stmt->bindValue(':note',$note, PDO::PARAM_STR);
        $stmt->bindValue(':signature',$signature, PDO::PARAM_STR);
	$stmt->bindValue(':alreadysent',$alreadysent, PDO::PARAM_INT);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
        }
    return 0;
}

/**
 * Function dbSchedulerInsert
 * Function for insert scheduler message
 * 
 * @return 0 
 */
function dbSchedulerInsert($date, $signature, $text, $note)
{
    try {
        $conn=getDbConnection();
        $sql = "INSERT message_scheduler SET DataInsert=now(), DataScheduler=:date, Text=:text, Note=:note, Signature=:signature, AlreadySent=1";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':date',date('Y-m-d H:i:s', strtotime ($date)), PDO::PARAM_STR);
        $stmt->bindValue(':text',$text, PDO::PARAM_STR);
        $stmt->bindValue(':note',$note, PDO::PARAM_STR);
        $stmt->bindValue(':signature',$signature, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}

/**
 * Function dbSchedulerDelete
 * Function for delete record of scheduler
 * 
 * @return 0 
 */
function dbSchedulerDelete($ID)    
{
    try {
        $conn=getDbConnection();
        $sql = "DELETE from message_scheduler WHERE ID=:ID";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ID',$ID, PDO::PARAM_INT);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
        }
    return 0;
}

/**
 * Function dbCronUpdate
 * Function for change the cron services
 * 
 * @return 0 
 */
function dbCronUpdate($ID)    
{
    try {
        $conn=getDbConnection();
        $sql = "UPDATE message_scheduler SET AlreadySent=0 WHERE ID=:ID";      
        $stmt = $conn->prepare($sql);        
        $stmt->bindValue(':ID',$ID, PDO::PARAM_INT);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
        }
    return 0;
}

/** 
 * ##########################################
 * Function database for setting system status
 * ##########################################
 */

/** 
 * Function dbInsertAdmin
 * Function for insert the new admin users 
 *  
 * @return int 0 or 1 for error
 */
function dbInsertAdmin ($username, $password, $signature)
{
    try {
        $conn=getDbConnection();
        $sql="insert admins set username=:username, password=:password, signature=:signature, active='1'";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username',$username, PDO::PARAM_STR);
        $stmt->bindValue(':signature',$signature, PDO::PARAM_STR);
        $stmt->bindValue(':password',create_hash($password), PDO::PARAM_STR);    
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
        //Replace the error output
        return '1';
    }
    return 0;
} 
/**
 * Function dbChangeSignatureAdmin
 * Function for update the signed admin when send the message
 *  
 *  @return int 0 or 1 for error
 */

function dbChangeSignatureAdmin ($username, $signature)
{
    try {
        $conn=getDbConnection(); 
        $sql="UPDATE admins SET signature=:signature WHERE username=:username";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username',$username, PDO::PARAM_STR);
        $stmt->bindValue(':signature',$signature, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
        //Replace the error output
        return '1';
    }
    return 0;
}

/**
 * Function dbChangeStateAdmin
 * Function for update state users admin active/deactive
 *  
 * @return 0
 */
function dbChangeStateAdmin ($id, $active)
{
    try {
        $conn=getDbConnection(); 
        $sql="UPDATE admins SET active=:active WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id',$id, PDO::PARAM_STR);
        $stmt->bindValue(':active',$active, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}

/**
 * Function dbChangeLevelAdmin
 * Function for change the level admin/user
 *  
 * @return 0
 */

function dbChangeLevelAdmin ($id, $level)
{
    try {
        $conn=getDbConnection(); 
        $sql="UPDATE admins SET level=:level WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id',$id, PDO::PARAM_STR);
        $stmt->bindValue(':level',$level, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}

/**
 * Function dbSelectAdmin
 * Function for select single active admin in bot
 *   
 * @return array
 */
function dbSelectAdmin()
{
    try {
        $conn=getDbConnection();
        $sql = "select username, signature, level from admins where active=1 order BY username";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableAdmin=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableAdmin[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableAdmin);
}

/**
 * Function dbSelectAllAdmin
 * Function for select all admin in bot
 *   
 * @return array
 */
 
function dbSelectAllAdmin()
{
    try {
        $conn=getDbConnection();
        $sql = "select id, username, signature, level, active from admins order BY username";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableAdmin=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableAdmin[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableAdmin);
} 

/**
 * Function dbUpdatePwd
 * Function for change the password user/admin in bot
 *   
 * @return 0
 */
 
function dbUpdatePwd($username,$password)
{
    try {
        $conn=getDbConnection();
        $sql="UPDATE admins SET password=:password WHERE username=:username";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username',$username, PDO::PARAM_STR);
        $stmt->bindValue(':password',create_hash($password), PDO::PARAM_STR);    
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}
/**
 * Function dbLogUserStart
 * Function for insert user active in the system
 *  
 * @return 0
 */

function dbLogUserStart ($chat,$first_name,$last_name,$username)
{
    try {
        $conn=getDbConnection();
        $sql = "select UserID from utenti where UserID=:UserID";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':UserID',$chat,PDO::PARAM_STR);
        $stmt->execute();
        if ($id=$stmt->fetchColumn(0)) {
            // Se l'utente gia conosciuto cambio il suo stato mettendolo a 1
            $sql = "UPDATE utenti SET StatoUtente=1, FirstName=:FirstName, LastName=:LastName, Username=:Username,DataInsert=now() where UserID=:UserID and StatoUtente=0";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':UserID',$id , PDO::PARAM_STR);
            $stmt->bindValue(':FirstName',$first_name , PDO::PARAM_STR);
            $stmt->bindValue(':LastName',$last_name , PDO::PARAM_STR);
            $stmt->bindValue(':Username',$username , PDO::PARAM_STR);
            $stmt->execute();
        } else {
            // Se l'utente non conosciuto salvo i suoi dati nel db
            $sql = "insert into utenti(UserID, FirstName, LastName, Username, StatoUtente, DataInsert) values (:UserID, :FirstName, :LastName, :Username, 1, now())";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':UserID',$chat , PDO::PARAM_STR);
            $stmt->bindValue(':FirstName',$first_name , PDO::PARAM_STR);
            $stmt->bindValue(':LastName',$last_name , PDO::PARAM_STR);
            $stmt->bindValue(':Username',$username , PDO::PARAM_STR);
            $stmt->execute();
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}

/**
 * Function dbLogUserStop
 * Function for setting state=0 when the user exit to bot ora banned bot
 * 
 * @return 0
 */
function dbLogUserStop($chat)
{
    try {
        $conn=getDbConnection();
        $sql = "UPDATE utenti SET StatoUtente=0, DataDelete=now() WHERE UserID=:UserID";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':UserID',$chat,PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return ($ex->getMessage());
    }
    return 0;
} 
 
/**
 * Function dbLogUserUpdate
 * Function when the user recconnecting at bot and control/change the ID bot/user
 * Invocata quando un utente si ricollega:
 * Creo un aggiornamento dell'identificativo utente per tenerlo aggiornato ai cambiamenti
 *
 * @return 0
 */
function dbLogUserUpdate ($chat,$first_name,$last_name,$username)
{
    try {
        $conn=getDbConnection();
	$sql = "UPDATE utenti SET FirstName=:FirstName, LastName=:LastName, Username=:Username, StatoUtente=1 where UserID=:UserID";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':UserID',$chat , PDO::PARAM_STR);
        $stmt->bindValue(':FirstName',$first_name , PDO::PARAM_STR);
        $stmt->bindValue(':LastName',$last_name , PDO::PARAM_STR);
        $stmt->bindValue(':Username',$username , PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return ($ex->getMessage());
    }
    return 0;
}       

/**
 * Function dbTrackerInsert
 * Function for tracking users when use the bot, capture every operation
 * 
 * @return 0 
 */
function dbTrackerInsert($chat, $user, $operation, $result)
{
    try {
        $conn=getDbConnection();
        $sql = "INSERT into utenti_log(ChatID, UserID, LogDate, Operation, Result) values (:ChatID, :UserID, now(), :Operation, :Result)";
            $stmt = $conn->prepare($sql); 
            $stmt->bindValue(':ChatID',$chat , PDO::PARAM_STR);
            $stmt->bindValue(':UserID',$user , PDO::PARAM_STR);
            $stmt->bindValue(':Operation',$operation , PDO::PARAM_STR);
            $stmt->bindValue(':Result',$result , PDO::PARAM_STR);
            $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}

/**
 * Function dbTrackerSelect
 * Function for return the tracker users
 * 
 * @return array 
 */
function dbTrackerSelect($start, $forPage)
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT Firstname, LastName, Username, utenti_log.UserID, Operation, SUBSTRING(`Result`, 1, 200) as Result, DATE_FORMAT(LogDate,'%d/%m/%Y-%T') as LogDate from utenti_log, utenti where utenti_log.UserID=utenti.UserID ORDER BY IdOperation DESC LIMIT :limit , :offset";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':limit', $start, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $forPage, PDO::PARAM_INT);
        $stmt->execute();
        $tableTracker=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableTracker[]=$riga;
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableTracker);
}

/**
 * Function dbTrackerCount
 * Function for counting row of log table
 * 
 * @return array 
 */
function dbTrackerCount()
{
    try {
        $conn=getDbConnection();
        $sql = "select count(*) as conteggioLog from utenti_log";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $valore = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    // restituisce il numero totale utenti attivi
    return ($valore['conteggioLog']);
}

/**
 * Function dbTrackerStatistics
 * Function for statistic tracking users
 * 
 * @return array 
 */
function dbTrackerStatistics()
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT Operation, COUNT(*) as Total FROM `utenti_log` WHERE Operation LIKE '/%' GROUP BY Operation ORDER BY Total DESC LIMIT 21";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $statisticsUser=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $statisticsUser[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($statisticsUser);
}

/**
 * Function dbTrackerUserGoodBye
 * Function for devide the page for read all tracking 
 * 
 * @return array 
 */
function dbTrackerUserGoodBye($start, $forPage)
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT UserID, FirstName, LastName, Username, DataInsert, DataDelete, DATEDIFF(DataDelete, DataInsert) AS DateDiff FROM utenti where StatoUtente=0 ORDER BY DataDelete DESC, DateDiff DESC LIMIT :limit , :offset";
        $stmt = $conn->prepare($sql);
	$stmt->bindValue(':limit', $start, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $forPage, PDO::PARAM_INT);
        $stmt->execute();
        $userGoodbye=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $userGoodbye[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($userGoodbye);
}
       
/**
 * Function dbActiveUsers
 * Function for return the active user
 * 
 * @return array 
 */
function dbActiveUsers()
{
    try {
        $conn=getDbConnection();
        $sql = "select UserID from utenti where StatoUtente=1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $active=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $active[]=$riga['UserID'];
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($active);
}

/**
 * Function dbCountActiveUsers
 * Function for return the number of active users
 * 
 * @return array 
 */
function dbCountActiveUsers()
{
    try {
        $conn=getDbConnection();
        $sql = "select count(*) as conteggio from utenti where StatoUtente=1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $valore = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    // restituisce il numero totale utenti attivi
    return ($valore['conteggio']);
}

/**
 * Function dbActiveUsersFull
 * Function for devide the page for read the active users 
 * 
 * @return array 
 */
function dbActiveUsersFull($start, $forPage)
{
    try {
        $conn=getDbConnection();
        $sql = "select UserID, FirstName, LastName, Username, DATE_FORMAT(DataInsert,'%d/%m/%Y') as insertDate from utenti where StatoUtente=1 ORDER BY DataInsert LIMIT :limit , :offset";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':limit', $start, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $forPage, PDO::PARAM_INT);
        $stmt->execute();
        $tableUser=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableUser[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableUser);
}

/**
 * Function dbLogTextOn
 * Inserisce nel DB utenti_message i messaggi lasciati dagli utenti 
 * 
 * @return type Array 
 */
function dbLogTextOn ($chat,$first_name,$message,$text)
{
    try {
        $conn=getDbConnection();
        $sql = "insert into utenti_message(UserID, FirstName, DataInsert, Message, Text, Archive) values (:UserID, :FirstName, now(),:Message,:Text,'1')";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':UserID',$chat , PDO::PARAM_STR);
        $stmt->bindValue(':FirstName',$first_name , PDO::PARAM_STR);
        $stmt->bindValue(':Message',$message , PDO::PARAM_STR);
        $stmt->bindValue(':Text',$text , PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}

/**
 * Function dbLogTextFull
 * Function to return all message send from users
 * 
 * @return array 
 */
function dbLogTextFull()
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT UserID, FirstName, DataInsert, Text, ID, Message, Archive from utenti_message where Archive='1' OR Archive IS NULL order BY DataInsert desc";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableMessage=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableMessage[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableMessage);
}

/**
 * Function dbLogTextUpdate
 * Function for update the messages to archive (don't view but don't delete)
 * 
 * @return 0 
 */
function dbLogTextUpdate ($ID)
{
    try {
        $conn=getDbConnection();
        $sql = "UPDATE utenti_message SET Archive=0 WHERE ID=:ID";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ID',$ID,PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return ($ex->getMessage());
    }
    return 0;
}  

/**
 * Function dbLogSearchFull()
 * Search into utenti_message the words
 * 
 * 
 * @return array 
 */
function dbLogSearchFull($type, $param1)
{
    try {
        $conn=getDbConnection();
        $sql = "select UserID, FirstName, DataInsert,Text, ID, Message, Archive from utenti_message where Archive=$type AND Text Like '%$param1%' order BY DataInsert desc";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableMessage=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableMessage[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableMessage);
}

/**
 * Function dbLogTextSend
 * Function for insert the message sending for all users
 * 
 * @return 0
 */
function dbLogTextSend ($text, $signature,$MessageID, $Utenti_messageID)
{
    try {
        $conn=getDbConnection();
        $sql = "insert into message_send(DataInsert, Text, Signature, MessageID, Utenti_messageID, Archive) values (now(),:Text,:Signature,:MessageID,:Utenti_messageID,'1')";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':Text',$text , PDO::PARAM_STR);
        $stmt->bindValue(':Signature',$signature , PDO::PARAM_STR);
        $stmt->bindValue(':MessageID',$MessageID , PDO::PARAM_STR);
        $stmt->bindValue(':Utenti_messageID',$Utenti_messageID , PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}

/**
 * Function dbLogTextFullSend
 * Function for read the messages sending at users
 * 
 * @return array 
 */
function dbLogTextFullSend()
{
    try {
        $conn=getDbConnection();
        $sql = "select ID, DataInsert, Text, Signature from message_send where Archive=1 AND MessageID=0 OR Archive=1 AND MessageID IS NULL OR Archive IS NULL AND MessageID IS NULL order BY DataInsert desc";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableMessage=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableMessage[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableMessage);
}

/**
 * Function dbLogTextUpdate
 * Function for update the message send for archive
 * Archive 0/1 (don't view/view)
 * 
 * @return 0 
 */
function dbLogTextUpdateSend($ID)
{
    try {
        $conn=getDbConnection();
        $sql = "UPDATE message_send SET Archive=0 WHERE ID=:ID";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ID',$ID,PDO::PARAM_INT);
        $stmt->execute();
    } catch (Exception $ex) {
        return ($ex->getMessage());
    }
    return 0;
}

/**
 * Function dbJoinMessageSend
 * Function to concatenate user messages (first, second..) when having a chat with the user
 * 
 * @return array 
 */
function dbJoinMessageSend($Message)
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT utenti_message.FirstName,utenti_message.DataInsert, utenti_message.Text, message_send.Text, message_send.DataInsert, message_send.Signature FROM utenti_message, message_send WHERE utenti_message.Message=MessageID  AND utenti_message.Message=$Message";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':MessageID',$Message,PDO::PARAM_STR);
        $stmt->execute();
        $tableJoinMessage=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableJoinMessage[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableJoinMessage);
}

/**
 * Function dbParamExtraction
 * Function for return the all value of parameters for setting bot
 * 
 * @return array 
 */
function dbParamExtraction($function)
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT Code, Param, SoftDesc, Active, Log, ID, Note, Number FROM `software_config` WHERE $function ORDER BY SoftDesc, Code";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableParam=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableParam[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableParam);
}

/**
 * Function dbParamUpdate
 * Function for change the value of parameters 
 * 
 * @return 0 
 */
function dbParamUpdate($ID, $software, $code, $param, $state, $user, $note)    
{
    try {
        $conn=getDbConnection();
        $sql = "UPDATE software_config SET SoftDesc=:software, Code=:code, Param=:param, Active=:state, Log=:user, Note=:note, DateUpdt=now() WHERE ID=:ID";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ID',$ID, PDO::PARAM_STR);
        $stmt->bindValue(':software',$software, PDO::PARAM_STR);
        $stmt->bindValue(':code',$code, PDO::PARAM_STR);
        $stmt->bindValue(':param',$param, PDO::PARAM_STR);
        $stmt->bindValue(':state',$state, PDO::PARAM_STR);
        $stmt->bindValue(':note',$note, PDO::PARAM_STR);
        $stmt->bindValue(':user',$user, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
        }
    return 0;
}

/**
 * Function dbParamInsert
 * Function for insert the value for setting bot
 * 
 * @return 0 
 */
function dbParamInsert($software, $param, $valore, $attivo, $user, $note)
{
    try {
        $conn=getDbConnection();
        $sql = "insert software_config set SoftDesc=:software, Code=:param, Param=:valore, Active=:active, Note=:note, Log=:user, DateUpdt=now()";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':software',$software, PDO::PARAM_STR);
        $stmt->bindValue(':param',$param, PDO::PARAM_STR);
        $stmt->bindValue(':valore',$valore, PDO::PARAM_STR);
        $stmt->bindValue(':active',$attivo, PDO::PARAM_STR);
        $stmt->bindValue(':user',$user, PDO::PARAM_STR);
        $stmt->bindValue(':note',$note, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}

/**
 * ############################################
 * Function database for service -> user -> output
 * ############################################
 */

/**
 * Function dbServiceSelect
 * Function for the select service/users
 * 
 * @return array 
 */
function dbServiceSelect($id, $type, $value)
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT UserID, Type, Value from utenti_service where UserID=:id AND Type=:type AND Value=:value";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
        $stmt->bindValue(':value', $value, PDO::PARAM_STR);
        $stmt->execute();
        $tableTracker=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableTracker[]=$riga;
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableTracker);
}

/**
 * Function dbServiceInsert
 * Function for insert the select service from user
 * 
 * @return 0 
 */
function dbServiceInsert($id, $type, $value)
{
    try {
        $conn=getDbConnection();
        $sql = "INSERT utenti_service SET UserID=:id, Type=:type, Value=:value, DataInsert=now()";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
        $stmt->bindValue(':value', $value, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}

/**
 * Function dbServiceUpdate
 * Function for update the select service from user
 * 
 * @return 0 
 */
function dbServiceUpdate($id, $type, $value)
{
    try {
        $conn=getDbConnection();
        $sql = "UPDATE utenti_service SET Value=:value, DataInsert=now() WHERE UserID=:id AND Type=:type";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
        $stmt->bindValue(':value', $value, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}

/**
 * ############################################
 * Function for memorize/delete users position
 * ############################################
 */

/**
 * Function dbLocalizationTmpInsert
 * Function for insert the position of user for service/local
 * 
 * @return 0
 */
function dbLocalizationTmpInsert($id, $service)
{
    try {
        $conn=getDbConnection();
        $sql = "INSERT INTO tmpUserService SET UserID=:id, Service=:service, DataInsert=now()";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':service', $service, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}

/**
 * Function dbLocalizationTmpSelect
 * Function for select the position of user for service/local
 * 
 * @return array 
 */
function dbLocalizationTmpSelect($id)
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT * FROM tmpUserService WHERE UserID=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $tableService=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableService[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableService);
}

/**
 * Function dbLocalizationTmpDelete
 * Function for clean the position of user for service/local
 * 
 * @return 0 
 */
function dbLocalizationTmpDelete($id)
{
    try {
        $conn=getDbConnection();
        $sql = "DELETE FROM tmpUserService WHERE UserID=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}

/**
 * ############################################
 * Function database for single service with position
 * ############################################
 */

/**
 * Function dbGasolineSelect
 * Function for select ALL Gasoline Registry and Price from distance
 * 
 * @return type Array 
 */
function dbGasolineSelect($lat, $lon, $dist)
{
    try {
        $conn=getDbConnection();
        $sql ="(SELECT * FROM viewGasolinePriceRegistry WHERE descCarburante='Benzina' AND (TRUNCATE ( 6363 * sqrt( POW( RADIANS('$lat') - RADIANS(Latitudine) , 2 ) + POW( RADIANS('$lon') - RADIANS(Longitudine) , 2 ) ) , 3 ) < $dist) AND dtComu > DATE_SUB(current_date(), INTERVAL 3 DAY) ORDER BY prezzo LIMIT 5)
        UNION
        (SELECT * FROM viewGasolinePriceRegistry WHERE descCarburante='Gasolio' AND 'dtComu' > DATE_SUB(current_date(), INTERVAL 2 DAY) AND(TRUNCATE ( 6363 * sqrt( POW( RADIANS('$lat') - RADIANS(Latitudine) , 2 ) + POW( RADIANS('$lon') - RADIANS(Longitudine) , 2 ) ) , 3 ) < $dist) AND dtComu > DATE_SUB(current_date(), INTERVAL 3 DAY) ORDER BY prezzo LIMIT 5)
        UNION
        (SELECT * FROM viewGasolinePriceRegistry WHERE descCarburante='GPL' AND 'dtComu' > DATE_SUB(current_date(), INTERVAL 2 DAY) AND(TRUNCATE ( 6363 * sqrt( POW( RADIANS('$lat') - RADIANS(Latitudine) , 2 ) + POW( RADIANS('$lon') - RADIANS(Longitudine) , 2 ) ) , 3 ) < $dist) AND dtComu > DATE_SUB(current_date(), INTERVAL 3 DAY) ORDER BY prezzo LIMIT 5)";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableGasoline=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableGasoline[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableGasoline);
}

/**
 * Function dbPharmacieSelect
 * Function for select ALL Pharmacie from distance
 * 
 * @return array 
 */
function dbPharmacieSelect($lat, $lon, $dist)
{
    try {
        $conn=getDbConnection();
	$sql = "SELECT * FROM(SELECT DESCRIZIONEFARMACIA,INDIRIZZO,DESCRIZIONECOMUNE,LATITUDINE,LONGITUDINE,(TRUNCATE ( 6363 * sqrt( POW( RADIANS('$lat') - RADIANS(TmpPharma.LATITUDINE) , 2 ) + POW( RADIANS('$lon') - RADIANS(TmpPharma.LONGITUDINE) , 2 ) ) , 3 ))AS dis FROM (SELECT DESCRIZIONEFARMACIA,INDIRIZZO,DESCRIZIONECOMUNE, (replace(LATITUDINE,',','.'))AS LATITUDINE,(replace(LONGITUDINE,',','.'))AS LONGITUDINE FROM `viewPharmacies` )as TmpPharma ORDER BY `dis`)as TmpPharmaOrder where TmpPharmaOrder.dis < $dist LIMIT 15";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tablePharma=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tablePharma[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tablePharma);
}

/**
 * Function dbCommerceSelect
 * Function for select ALL Commerce Registry from OpenStreetMap
 * 
 * @return array 
 */
function dbCommerceSelect($lat, $lon, $dist)
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT * FROM(SELECT Nome,Comune,Latitudine,Longitudine,(TRUNCATE ( 6363 * sqrt( POW( RADIANS('$lat') - RADIANS(TmpCommerce.Latitudine) , 2 ) + POW( RADIANS('$lon') - RADIANS(TmpCommerce.Longitudine) , 2 ) ) , 3 ))AS dis FROM (SELECT Nome,Comune,Latitudine,Longitudine FROM `viewCommerce` )as TmpCommerce ORDER BY `dis`)as TmpCommerceOrder where TmpCommerceOrder.dis < $dist LIMIT 25";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableCommerce=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableCommerce[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableCommerce);
}

/**
 * Function dbArtSelect
 * Function for select ALL Art Registry from Opendata: http://www.catalogo.beniculturali.it
 * 
 * @return array 
 */
function dbArtSelect($lat, $lon, $dist)
{
    try {
        $conn=getDbConnection();
	$sql = "SELECT * FROM(SELECT IMG,BENE_CULTURALE,LOCALIZZAZIONE,CONTENITORE,LAT,LON, (TRUNCATE ( 6363 * sqrt( POW( RADIANS('$lat') - RADIANS(TmpArt.LAT) , 2 ) + POW( RADIANS('$lon') - RADIANS(TmpArt.LON) , 2 ) ) , 3 ))AS dis FROM (SELECT IMG,BENE_CULTURALE,LOCALIZZAZIONE,CONTENITORE,LAT,LON FROM `Ext_Art` )as TmpArt ORDER BY `dis`)as TmpArtOrder where TmpArtOrder.dis < $dist LIMIT 80";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableArt=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableArt[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableArt);
}

/**
 * Function dbWiFiSelect
 * Funciont for select form site the WiFi point (openDataFvg)
 * 
 * @return array 
 */
function dbWiFiSelect($lat, $lon, $dist)
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT * FROM(SELECT Name, Address, Latitude, Longitude, Note, (TRUNCATE ( 6363 * sqrt( POW( RADIANS('$lat') - RADIANS(TmpWifi.Latitude) , 2 ) + POW( RADIANS('$lon') - RADIANS(TmpWifi.Longitude) , 2 ) ) , 3 ))AS dis FROM (SELECT Name, Address, Latitude, Longitude, Note FROM `Ext_fvg_wifi` )as TmpWifi ORDER BY `dis`)as TmpWifiOrder where TmpWifiOrder.dis < $dist";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableWifi=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableWifi[]=$riga;            
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableWifi);
}

/**
 * ############################################
 * Function database for Ics Service
 * ############################################
 */

/**
 * Function dbIcsSelect
 * Function for return the all event (import from ICS)
 * 
 * @return array 
 */
function dbIcsSelect()
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT Name, Description, DATE_FORMAT(DataStart,'%d/%m/%Y') as startDate, DATE_FORMAT(DataEnd,'%d/%m/%Y') as endDate, Local, Url, Source FROM `Ext_ics_event` WHERE DATE(DataStart) >= curdate()  ORDER BY DataStart ASC, DataEnd DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableIcs=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableIcs[]=$riga;
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableIcs);
} 

/**
 * Function dbIcsSelectDay
 * Function for return the day event, control with the day of server (import from ICS)
 * 
 * @return type Array 
 */
function dbIcsSelectDay()
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT Name, Description, DATE_FORMAT(DataStart,'%d/%m/%Y') as startDate, DATE_FORMAT(DataEnd,'%d/%m/%Y') as endDate, Local, Url, Source FROM `Ext_ics_event` WHERE DataStart<=(curdate()) AND DataEnd>=(curdate()) ORDER BY DataStart ASC, DataEnd DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableIcs=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableIcs[]=$riga;
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableIcs);
} 

/**
 * Function dbIcsSelectDayDistrict
 * Function for return the day event (import from ICS)
 * The alternative implementation of dbIcsSelect()
 * 
 * @return array 
 */
function dbIcsSelectDayDistrict($district)
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT Name, Description, DATE_FORMAT(DataStart,'%d/%m/%Y') as startDate, DATE_FORMAT(DataEnd,'%d/%m/%Y') as endDate, Local, Url, Source FROM `Ext_ics_event` WHERE district=:District AND DATE(NOW()) BETWEEN DATE(DataStart) AND DATE(DataEnd) ORDER BY DataStart ASC, DataEnd DESC";
        $stmt = $conn->prepare($sql);
	$stmt->bindValue(':District',$district,PDO::PARAM_STR);
        $stmt->execute();
        $tableIcs=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableIcs[]=$riga;
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableIcs);
} 

/**
 * Function dbIcsSelectTomorrow
 * Function for return the tomorrow event (import from ICS)
 * 
 * @return array 
 */
function dbIcsSelectTomorrow()
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT Name, Description, DATE_FORMAT(DataStart,'%d/%m/%Y') as startDate, DATE_FORMAT(DataEnd,'%d/%m/%Y') as endDate, Local, Url, Source FROM `Ext_ics_event` WHERE DataStart<=(curdate()+ INTERVAL 2 day) AND DataEnd>=(curdate()+ INTERVAL 1 day) ORDER BY DataStart ASC, DataEnd DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableIcs=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableIcs[]=$riga;
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableIcs);
}

/**
 * Function dbIcsSelectDayAfterTomorrow
 * Function for return the day after tomorrow event (import from ICS)
 * 
 * @return array 
 */
function dbIcsSelectDayAfterTomorrow()
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT Name, Description, DATE_FORMAT(DataStart,'%d/%m/%Y') as startDate, DATE_FORMAT(DataEnd,'%d/%m/%Y') as endDate, Local, Url, Source FROM `Ext_ics_event` WHERE DataStart<=(curdate()+ INTERVAL 3 day) AND DataEnd>=(curdate()+ INTERVAL 2 day) ORDER BY DataStart ASC, DataEnd DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableIcs=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableIcs[]=$riga;
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableIcs);
}

/**
 * ############################################
 * Function database for correct the error of users
 * ############################################
 */
 
/**
 * Function dbButtonTag
 * Function for select the tag for single button
 * 
 * @return array 
 */
function dbButtonTag($input)
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT Ttag.ID, SoftDesc, Number, Titolo, Param, Tag, idButton, Description FROM `software_config_button_tag` as Ttag, software_config_button as Tbut WHERE Tag=:input and Ttag.IdButton = Tbut.ID and Tbut.Active = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':input', $input, PDO::PARAM_STR);
        $stmt->execute();
        $tableTag=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableTag[]=$riga;
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableTag);
} 

/**
 * Function dbButtonTagSelect
 * Function for select the correct tag for button
 * 
 * @return array 
 */
function dbButtonTagSelect()
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT Ttag.ID, SoftDesc, Number, Titolo, Active, Tag, idButton, Description FROM `software_config_button_tag` as Ttag, software_config_button as Tbut WHERE Ttag.IdButton = Tbut.ID and Tbut.Active = 1 ORDER BY Tbut.SoftDesc, Tbut.Titolo";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tableTag=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableTag[]=$riga;
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableTag);
} 

/**
 * Function dbButtonTagSelectSingle
 * Function for return the single tag
 * 
 * @return array 
 */
function dbButtonTagSelectSingle($ID)
{
    try {
        $conn=getDbConnection();
        $sql = "SELECT ID, IdButton, Tag, Description FROM software_config_button_tag WHERE ID=:ID";
        $stmt = $conn->prepare($sql);
	$stmt->bindValue(':ID', $ID, PDO::PARAM_INT);
        $stmt->execute();
        $tableTag=array();
        while ($riga=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableTag[]=$riga;
        }
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return ($tableTag);
} 

/**
 * Function dbButtonTagInsert
 * Function for insert the tag/button correct and description
 * 
 * @return 0
 */
 function dbButtonTagInsert($idbutton, $tag, $description)
{
    try {
        $conn=getDbConnection();
        $sql = "INSERT INTO software_config_button_tag SET idButton=:idbutton, Tag=:tag, Description=:description";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':idbutton', $idbutton, PDO::PARAM_INT);
        $stmt->bindValue(':tag', $tag, PDO::PARAM_STR);
		$stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}

/**
 * Function dbButtonTagUpdate
 * Function for update the tag/button correct and description
 * 
 * @return 0
 */
 function dbButtonTagUpdate($ID, $idbutton, $tag, $description)
{
    try {
        $conn=getDbConnection();
        $sql = "UPDATE software_config_button_tag SET idButton=:idbutton, Tag=:tag, Description=:description WHERE ID=:ID";
        $stmt = $conn->prepare($sql);
		$stmt->bindValue(':ID',$ID, PDO::PARAM_INT);
        $stmt->bindValue(':idbutton', $idbutton, PDO::PARAM_INT);
        $stmt->bindValue(':tag', $tag, PDO::PARAM_STR);
		$stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}

/**
 * Function dbLogSoftware
 * Use this function only for DEBUG.
 * 
 * @return 0
 */
function dbLogSoftware ($chat, $first_name, $last_name, $username, $message)
{
    try {
        $conn=getDbConnection();
		$sql = "insert into software_log(UserID, FirstName, LastName, Username, Message, DataInsert) values (:UserID, :FirstName, :LastName, :Username, :Message, now())";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':UserID', $chat , PDO::PARAM_STR);
		$stmt->bindValue(':FirstName', $first_name , PDO::PARAM_STR);
		$stmt->bindValue(':LastName', $last_name , PDO::PARAM_STR);
		$stmt->bindValue(':Username', $username , PDO::PARAM_STR);
		$stmt->bindValue(':Message', $message , PDO::PARAM_STR);
		$stmt->execute();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
    return 0;
}