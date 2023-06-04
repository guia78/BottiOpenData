<?php 
require ('theme/verification.php');
require_once ('functions/startFunctionScript.php');
?>
<?php
$filename="sheet.xls";
header ("Content-Type: application/vnd.ms-excel");
header ("Content-Disposition: inline; filename=$filename");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="copyright" content="Copyright 2018-2025 © Guion Matteo">
        <meta name="language" content="italian">
        <meta name="email" content="botti[at]guion78[dot]com">
        <title>[Bot}Ti- Telegram Bot by Matteo Guion</title>
    </head>
    <body>
        <div>
            <br>
            <table border="1">              
                <tr>
                    <td>Nome utente</td>
                    <td>Data inserimento</td>
                    <td>Messaggio</td
                </tr>
                <?php
                /******
                 * questa fase cicla sugli utenti attivi inseriti nel database 
                 ******/
                $messageUsers = dbLogTextFull();
                foreach ($messageUsers as $message) { 
                    echo '<tr>';
                       echo '<td>'.$message['FirstName'].'</td>';
                       echo '<td>'.(date('d/m/Y H:i:s', strtotime($message['DataInsert']))).'</td>';
                       echo '<td>'.$message['Text'].'</td>';
                }
                ?>
                </table>	
        </div>			
    </body>
</html>