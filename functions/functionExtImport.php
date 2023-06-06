<?php
require_once ('functionExt.php');
require_once (dirname(__FILE__).'/../config.php');
/* Delete all DB External */
dbExtDeleteAll(); 
/* Import Ical Events into DB */
Ics('http://www.prolocoregionefvg.it/?plugin=all-in-one-event-calendar&controller=ai1ec_exporter_controller&action=export_events&no_html=true', 'Proloco Fvg', '1', '0');
Ics('https://www.girofvg.com/?ec3_ical', 'Giro Fvg', '1', '0');
Ics('www.parks.it/ical/parks_generale.ics', 'Parchi Fvg', '1', '0');
Ics('https://www.facebook.com/events/ical/upcoming/?uid=100000190884618&key=twRqmKQ85ip7TuKu', 'Facebook', '1', '0');
/* Al momento sospeso troppa pubblicita
Ics('https://www.anteprimasagre.it/?plugin=all-in-one-event-calendar&controller=ai1ec_exporter_controller&action=export_events&no_html=true', 'Anteprima Sagre', '1', '0');
*/
Ics ('http://zerowastefvg.it/events.ics', '@zerowastefvg', '0', '0');

/* Import also information into DB */
WiFiFVG('https://www.insiel.it/dati/fvgwifi.json'); // New json may 2023
farmOnlineFVGExt('https://sapi.sanita.fvg.it/farmacie/orario?APID=PM3EF4Q9CSNFJGW0LSSDVFBM81NLPGV17TSZ2XEAGY2VDQJNNF6T0HCUGLUOVZ3K');
?>
<p>Aggiornamento banca dati avvenuta con successo.</p>
