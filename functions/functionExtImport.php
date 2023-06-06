<?php
require_once ('functionExt.php');
require_once (dirname(__FILE__).'/../config.php');
/* Delete all DB External */
dbExtDeleteAll(); 
/* Import Ical Events into DB */
Ics('www.parks.it/ical/parks_generale.ics', 'Parchi Fvg', '1', '0');

/* Import also information into DB */
WiFiFVG('https://www.insiel.it/dati/fvgwifi.json'); // New json may 2023
farmOnlineFVGExt('https://sapi.sanita.fvg.it/farmacie/orario?APID=PM3EF4Q9CSNFJGW0LSSDVFBM81NLPGV17TSZ2XEAGY2VDQJNNF6T0HCUGLUOVZ3K');
?>
<p>Aggiornamento banca dati avvenuta con successo.</p>
