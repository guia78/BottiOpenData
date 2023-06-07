-- Script for first install
-- Versione PHP: 7.4.0 or higher

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `telegram`
-- Create a DB: `telegram`

-- --------------------------------------------------------

--
-- Struttura della tabella `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(120) NOT NULL,
  `password` varchar(120) NOT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `level` varchar(25) DEFAULT 'admin',
  `active` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dump dei dati per la tabella `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `signature`, `level`, `active`) VALUES
(16, 'admin', '$2y$10$1Zfc8UkSY6G7F/z5nPS9MOmEcgVFELiaV0t5WoeW3ZbxlL2DE6T8u', 'Il Team del Bot.', 'admin', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `Ext_Art`
--

CREATE TABLE `Ext_Art` (
  `IMG` varchar(98) DEFAULT NULL,
  `BENE_CULTURALE` varchar(128) DEFAULT NULL,
  `TITOLO` varchar(95) DEFAULT NULL,
  `SOGGETTO` varchar(203) DEFAULT NULL,
  `TIPO_SCHEDA` varchar(7) DEFAULT NULL,
  `CODICE_UNIVOCO` varchar(17) NOT NULL,
  `LOCALIZZAZIONE` varchar(118) DEFAULT NULL,
  `CONTENITORE` varchar(184) DEFAULT NULL,
  `DATAZIONE` varchar(157) DEFAULT NULL,
  `AMBITO_CULTURALE` varchar(132) DEFAULT NULL,
  `AUTORE` varchar(123) DEFAULT NULL,
  `MATERIA_TECNICA` varchar(186) DEFAULT NULL,
  `MISURE` varchar(118) DEFAULT NULL,
  `CONDIZIONE_GIURIDICA` varchar(102) DEFAULT NULL,
  `DATI_ANALITICI` varchar(2747) DEFAULT NULL,
  `ISCRIZIONE` varchar(9032) DEFAULT NULL,
  `NOTIZIE_STORICO-CRITICHE` varchar(1439) DEFAULT NULL,
  `ALTRA_LOCALIZZAZIONE` varchar(75) DEFAULT NULL,
  `REPERIMENTO` varchar(10) DEFAULT NULL,
  `ALTRE_ATTRIBUZIONI` varchar(21) DEFAULT NULL,
  `COMMITTENZA` varchar(65) DEFAULT NULL,
  `DATI_CATASTALI` varchar(10) DEFAULT NULL,
  `GEOREFERENZIAZIONE` varchar(394) DEFAULT NULL,
  `BIBLIOGRAFIA` varchar(87) DEFAULT NULL,
  `DEFINIZIONE` varchar(40) DEFAULT NULL,
  `DENOMINAZIONE` varchar(75) DEFAULT NULL,
  `CLASSIFICAZIONE` varchar(10) DEFAULT NULL,
  `REGIONE` varchar(21) DEFAULT NULL,
  `PROVINCIA` varchar(2) DEFAULT NULL,
  `COMUNE` varchar(29) DEFAULT NULL,
  `LOCALITA` varchar(38) DEFAULT NULL,
  `TOPONIMO` varchar(36) DEFAULT NULL,
  `DIOCESI` varchar(10) DEFAULT NULL,
  `INDIRIZZO` varchar(38) DEFAULT NULL,
  `PROVVEDIMENTI_TUTELA` varchar(10) DEFAULT NULL,
  `INVENTARIO` varchar(10) DEFAULT NULL,
  `STIMA` varchar(10) DEFAULT NULL,
  `RAPPORTO` varchar(289) DEFAULT NULL,
  `ALTRI_CODICI` varchar(10) DEFAULT NULL,
  `ENTE_SCHEDATORE` varchar(4) DEFAULT NULL,
  `ENTE_COMPETENTE` varchar(4) DEFAULT NULL,
  `AUTORI` varchar(257) DEFAULT NULL,
  `ANNO_CREAZIONE` int DEFAULT NULL,
  `ANNO_MODIFICA` varchar(4) DEFAULT NULL,
  `LAT` varchar(8) DEFAULT NULL,
  `LON` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `Ext_commerce_registry`
--

CREATE TABLE `Ext_commerce_registry` (
  `ID` int NOT NULL,
  `Comune` text NOT NULL,
  `Provincia` text NOT NULL,
  `Regione` text NOT NULL,
  `Nome` varchar(200) NOT NULL,
  `AnnoInserimento` text NOT NULL,
  `Data` text NOT NULL,
  `Identificatore` varchar(40) NOT NULL,
  `Longitudine` text NOT NULL,
  `Latitudine` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Struttura della tabella `Ext_fresh_fvg`
--

CREATE TABLE `Ext_fresh_fvg` (
  `idPoint` int NOT NULL,
  `Name` varchar(247) DEFAULT NULL,
  `Url` varchar(24) DEFAULT NULL,
  `Address` varchar(106) DEFAULT NULL,
  `Comune` varchar(34) DEFAULT NULL,
  `Provincia` varchar(2) DEFAULT NULL,
  `Latitudine` decimal(30,12) DEFAULT NULL,
  `Longitudine` decimal(30,12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Struttura della tabella `Ext_fvg_pharmacies`
--

CREATE TABLE `Ext_fvg_pharmacies` (
  `ID` int NOT NULL,
  `name` varchar(500) NOT NULL,
  `idPharmFvg` int NOT NULL,
  `businessName` varchar(500) NOT NULL,
  `city` char(200) NOT NULL,
  `idCity` int NOT NULL,
  `address` varchar(500) NOT NULL,
  `longitude` decimal(30,12) NOT NULL,
  `latitude` decimal(30,12) NOT NULL,
  `phone` text NOT NULL,
  `idAss` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `Ext_fvg_pharmacies_hour`
--

CREATE TABLE `Ext_fvg_pharmacies_hour` (
  `ID` int NOT NULL,
  `idPharmFvg` int NOT NULL,
  `from0` datetime NOT NULL,
  `until0` datetime NOT NULL,
  `type0` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `Ext_fvg_wifi`
--

CREATE TABLE `Ext_fvg_wifi` (
  `ID` int NOT NULL,
  `NumIDinternal` int DEFAULT NULL,
  `Name` text,
  `Address` varchar(255) DEFAULT NULL,
  `Latitude` varchar(255) DEFAULT NULL,
  `Longitude` varchar(255) DEFAULT NULL,
  `Note` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `Ext_gasoline_price`
--

CREATE TABLE `Ext_gasoline_price` (
  `idImpianto` int NOT NULL,
  `descCarburante` varchar(21) NOT NULL,
  `prezzo` decimal(4,3) DEFAULT NULL,
  `isSelf` int NOT NULL,
  `dtComu` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `Ext_gasoline_registry`
--

CREATE TABLE `Ext_gasoline_registry` (
  `idImpianto` int NOT NULL,
  `Gestore` varchar(247) DEFAULT NULL,
  `Bandiera` varchar(24) DEFAULT NULL,
  `Tipo Impianto` varchar(14) DEFAULT NULL,
  `Nome Impianto` varchar(96) DEFAULT NULL,
  `Indirizzo` varchar(106) DEFAULT NULL,
  `Comune` varchar(34) DEFAULT NULL,
  `Provincia` varchar(2) DEFAULT NULL,
  `Latitudine` decimal(30,12) DEFAULT NULL,
  `Longitudine` decimal(30,12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Struttura della tabella `Ext_ics_event`
--

CREATE TABLE `Ext_ics_event` (
  `ID` int NOT NULL,
  `UID` varchar(800) DEFAULT NULL,
  `Name` varchar(200) NOT NULL,
  `Description` varchar(500) DEFAULT NULL,
  `DataStart` datetime NOT NULL,
  `DataEnd` datetime NOT NULL,
  `Local` varchar(200) NOT NULL,
  `District` varchar(2) DEFAULT NULL,
  `Municipality` varchar(100) DEFAULT NULL,
  `Address` varchar(100) DEFAULT NULL,
  `Url` varchar(400) DEFAULT NULL,
  `UrlImage` varchar(400) DEFAULT NULL,
  `Lat` decimal(30,12) DEFAULT NULL,
  `Lon` decimal(30,12) DEFAULT NULL,
  `Contact` varchar(400) DEFAULT NULL,
  `Dur` text,
  `Source` text,
  `Visible` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1(True) 0(False)',
  `Approved` tinyint(1) DEFAULT '1' COMMENT '1(True) 0(False)',
  `IdFunctionCollegate` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Struttura della tabella `Ext_paraPharmacies_registry`
--

CREATE TABLE `Ext_paraPharmacies_registry` (
  `CODICEIDENTIFICATIVOSITO` int NOT NULL,
  `DENOMINAZIONESITOLOGISTICO` varchar(400) NOT NULL,
  `INDIRIZZO` varchar(400) NOT NULL,
  `PARTITAIVA` int NOT NULL,
  `CAP` int NOT NULL,
  `CODICECOMUNEISTAT` int NOT NULL,
  `DESCRIZIONECOMUNE` varchar(100) NOT NULL,
  `CODICEPROVINCIAISTAT` int NOT NULL,
  `SIGLAPROVINCIA` char(2) NOT NULL,
  `DESCRIZIONEPROVINCIA` varchar(100) NOT NULL,
  `CODICEREGIONE` int NOT NULL,
  `DESCRIZIONEREGIONE` varchar(100) NOT NULL,
  `DATAINIZIOVALIDITA` varchar(12) NOT NULL,
  `DATAFINEVALIDITA` varchar(12) NOT NULL,
  `LATITUDINE` varchar(255) NOT NULL,
  `LONGITUDINE` varchar(255) NOT NULL,
  `LOCALIZE` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Struttura della tabella `Ext_pharmacies_registry`
--

CREATE TABLE `Ext_pharmacies_registry` (
  `CODICEIDENTIFICATIVOFARMACIA` int NOT NULL,
  `CODFARMACIAASSEGNATODAASL` int NOT NULL,
  `INDIRIZZO` varchar(400) NOT NULL,
  `DESCRIZIONEFARMACIA` varchar(400) NOT NULL,
  `PARTITAIVA` int NOT NULL,
  `CAP` int NOT NULL,
  `CODICECOMUNEISTAT` int NOT NULL,
  `DESCRIZIONECOMUNE` varchar(100) NOT NULL,
  `FRAZIONE` varchar(50) NOT NULL,
  `CODICEPROVINCIAISTAT` int NOT NULL,
  `SIGLAPROVINCIA` char(2) NOT NULL,
  `DESCRIZIONEPROVINCIA` varchar(100) NOT NULL,
  `CODICEREGIONE` int NOT NULL,
  `DESCRIZIONEREGIONE` varchar(100) NOT NULL,
  `DATAINIZIOVALIDITA` varchar(12) NOT NULL,
  `DATAFINEVALIDITA` varchar(12) NOT NULL,
  `DESCRIZIONETIPOLOGIA` varchar(30) NOT NULL,
  `CODICETIPOLOGIA` int NOT NULL,
  `LATITUDINE` varchar(255) NOT NULL,
  `LONGITUDINE` varchar(255) NOT NULL,
  `LOCALIZE` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Struttura della tabella `Ext_Rifugi`
--

CREATE TABLE `Ext_Rifugi` (
  `ID` int NOT NULL,
  `Struttura` text NOT NULL,
  `Nome` varchar(300) NOT NULL,
  `Ubicazione` varchar(300) NOT NULL,
  `Comune` text NOT NULL,
  `Cai` text NOT NULL,
  `Link` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `Ext_Tweet`
--

CREATE TABLE `Ext_Tweet` (
  `IdTweet` int NOT NULL,
  `DescTweet` varchar(600) NOT NULL,
  `OnOff` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `message_scheduler`
--

CREATE TABLE `message_scheduler` (
  `ID` int NOT NULL,
  `DataInsert` datetime DEFAULT NULL,
  `DataScheduler` datetime DEFAULT NULL,
  `Repeater` tinyint(1) DEFAULT NULL COMMENT 'Ripetizioni Si/No',
  `NumberRepeat` int DEFAULT NULL COMMENT 'Max 9 ripetizioni',
  `HowOften` int DEFAULT NULL COMMENT 'Intervallo ripetizione in ore',
  `Text` varchar(2048) DEFAULT NULL,
  `Note` varchar(2048) DEFAULT NULL,
  `Signature` varchar(255) DEFAULT NULL,
  `SingleUserID` int DEFAULT NULL,
  `AlreadySent` tinyint(1) DEFAULT '1',
  `Counter` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `message_scheduler_function`
--

CREATE TABLE `message_scheduler_function` (
  `ID` int NOT NULL,
  `DataInsert` datetime DEFAULT NULL,
  `DataSend` datetime DEFAULT NULL,
  `HowOften` int DEFAULT NULL COMMENT 'Interval hours for repeate',
  `Text` varchar(2048) DEFAULT NULL,
  `Note` varchar(2048) DEFAULT NULL,
  `Signature` varchar(255) DEFAULT NULL,
  `AlreadySent` tinyint(1) DEFAULT '1',
  `Counter` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `message_send`
--

CREATE TABLE `message_send` (
  `ID` int NOT NULL,
  `DataInsert` datetime DEFAULT NULL,
  `Text` varchar(2048) NOT NULL,
  `Signature` varchar(255) DEFAULT NULL,
  `MessageID` int DEFAULT NULL,
  `Utenti_messageID` int DEFAULT NULL,
  `Archive` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `software_config`
--

CREATE TABLE `software_config` (
  `ID` int NOT NULL,
  `SoftDesc` varchar(50) DEFAULT NULL,
  `Code` varchar(20) DEFAULT NULL,
  `Param` varchar(300) DEFAULT NULL,
  `Number` int DEFAULT NULL,
  `Note` varchar(200) DEFAULT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT '1',
  `Log` varchar(50) DEFAULT NULL,
  `DateUpdt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dump dei dati per la tabella `software_config`
--

INSERT INTO `software_config` (`ID`, `SoftDesc`, `Code`, `Param`, `Number`, `Note`, `Active`, `Log`, `DateUpdt`) VALUES
(1, 'Mail', 'mittente', 'XYZ@dominio.com', NULL, 'Indirizzo mail da cui inviare mail', 1, '', '0000-00-00 00:00:00'),
(2, 'Mail', 'nomemittente', 'Bot mittente', NULL, 'Nome del mittente', 1, '', '0000-00-00 00:00:00'),
(3, 'Mail', 'destinatario', 'destinatario@dominio.com', NULL, 'Indirizzo mail di destinazione avvisi', 1, '', '0000-00-00 00:00:00'),
(4, 'Mail', 'nomedestinatario', 'nome destinatario', NULL, 'Nome del destinatario a cui inviare mail', 1, '', '0000-00-00 00:00:00'),
(5, 'Mail', 'serversmtp', 'smtp.dominio.com', NULL, 'Server smtp', 1, '', NULL),
(6, 'Mail', 'username', 'username', NULL, 'Username of server smtp', 1, '', '0000-00-00 00:00:00'),
(7, 'Mail', 'password', 'pwd', NULL, 'Password of sever smtp mail', 1, '', '0000-00-00 00:00:00'),
(19, 'Mail', 'port', '587', NULL, 'Port of smtp', 1, '', NULL),
(20, 'Mail', 'secure', 'tsl', NULL, 'Security service for mail', 1, '', NULL),
(21, 'Demone', 'status', '--', NULL, 'start=1 / stop=0', 1, '', '0000-00-00 00:00:00'),
(22, 'Demone', 'nomebot', 'descrizione nome del bot', NULL, 'Nome del bot che stai gestendo', 1, '', '0000-00-00 00:00:00'),
(24, 'Google', 'key', 'xxxxxxxxxxxxxxxxx', NULL, 'Key per le Api Google - https://console.developers.google.com', 1, '', '0000-00-00 00:00:00'),
(25, 'Message', 'waiting', 'Attendere prego ..............', NULL, 'Messaggio di attesa in caso di elaborazione.', 1, '', '0000-00-00 00:00:00'),
(26, 'Search', 'url', 'http://www.google.it/search?hl=it&ie=UTF-8&q=', NULL, 'Puoi scegliere tra http://www.google.it/search?hl=it&ie=UTF-8&q= oppure http://www.google.com/search?as_sitesearch=www.SITO_INTERNET.COM&as_q=', 1, '', '0000-00-00 00:00:00'),
(27, 'Search', 'text', 'Provo a cercare con Google se può esserti utile:', NULL, 'Messaggio di testo pre URL che invio in caso di mancata risposta del Bot', 1, '', '0000-00-00 00:00:00'),
(28, 'Message', 'error', 'non ho capito cosa ti serva, ma ho comunque ricevuto il messaggio. Magari riformula la richiesta usando un termine! Imparo in fretta e forse la prossima volta saprò risponderti. Grazie.', NULL, 'Messaggio di scuse quando il Bot non sa rispondere', 1, '', '0000-00-00 00:00:00'),
(29, 'Domain', 'name', 'http://www.URL.com/', NULL, 'Indirizzo web di BoT[ti] ( esempio: http://www.example.com/cartella_installazione/ ) ', 1, '', '0000-00-00 00:00:00'),
(30, 'Demone', 'botGroup', '@XXXXXXXX', NULL, 'Nome del bot nei gruppi. Da inserire con la chiocciolina anteposta.', 1, '', '0000-00-00 00:00:00'),
(31, 'Bitly', 'key', 'XXXXXXXXXXXX', NULL, 'Key per le Api BitFly - https://dev.bitly.com/code_libraries.html', 1, '', '0000-00-00 00:00:00'),
(32, 'Message', 'exit', 'Bloccando il Bot non potrai usare appieno delle sue funzioni future e attuali, usa /start per riattivarle, oppure usa /SettaggioBot per settarlo. Se vuoi manda una mail a mail@domain.com per spiegarmi come ........... Grazie.', NULL, 'Messaggio di uscita quando vuoi bloccare il Bot', 1, '', '0000-00-00 00:00:00'),
(33, 'Flickr', 'key', 'XXXXXXXXXXXXXXXX', NULL, 'Key per le Api Flickr', 1, '', '0000-00-00 00:00:00'),
(34, 'Twitter', 'key', 'XXXXXXXXXXXXX', NULL, 'Key per le Api di Twitter', 1, '', '0000-00-00 00:00:00'),
(35, 'Twitter', 'key_secret', 'XXXXXXXXXXX', NULL, 'Key secret per le Api di Twitter', 1, '', '0000-00-00 00:00:00'),
(36, 'Twitter', 'token', 'XXXXXXXXXXX', NULL, 'Token per le Api di Twitter', 1, '', '0000-00-00 00:00:00'),
(37, 'Twitter', 'token_secret', 'XXXXXXXXXXX', NULL, 'Token secret per le Api di Twitter', 1, '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `software_config_button`
--

CREATE TABLE `software_config_button` (
  `ID` int NOT NULL,
  `Number` int DEFAULT NULL,
  `Level` int NOT NULL DEFAULT '1',
  `LevelIndoor` int DEFAULT NULL,
  `IdButtonSource` int DEFAULT NULL,
  `SoftDesc` varchar(50) DEFAULT NULL,
  `Type` varchar(8) NOT NULL DEFAULT 'Normal',
  `Titolo` varchar(25) DEFAULT NULL,
  `Param` varchar(8190) DEFAULT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT '1',
  `Log` varchar(50) DEFAULT NULL,
  `DateUpdt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dump dei dati per la tabella `software_config_button`
--

INSERT INTO `software_config_button` (`ID`, `Number`, `Level`, `LevelIndoor`, `IdButtonSource`, `SoftDesc`, `Type`, `Titolo`, `Param`, `Active`, `Log`, `DateUpdt`) VALUES
(1, 7, 1, 1, 1, 'Button', 'Normal', 'Lavoro', 'Cerca lavoro FACTORY usando questi canali:\r\n\r\n/FormazioneLavoroRegione (formazione e lavoro sito della Regione Fvg)\r\n/annunciRegionali\r\n/Lavoro_FVG\r\n', 1, '', '0000-00-00 00:00:00'),
(2, 1, 1, NULL, 0, 'Button', 'Normal', 'Meteo', '-Previsioni Meteo del Friuli V.G.-\r\nBLACK SUN WITH RAYS Temperature dell\'Osmer:\r\n/Temperature \r\nSUN WITH FACE Dal meteo dell\'Osmer:\r\n/TempoOggi (oggi)\r\n/TempoDomani (domani)\r\n/TempoA_2_giorni (a 2 gg)\r\n/TempoA_3_giorni (a 3 gg)\r\n/TempoA_4_giorni (a 4 gg)\r\n/TempoProssimiGiorni (tutte)\r\n\r\n', 1, '', '0000-00-00 00:00:00'),
(3, 4, 1, NULL, 0, 'Button', 'Normal', 'Eventi', 'PARTY POPPER Eventi in Friuli:\r\n/SentieriNatura\r\n\r\n/TuttiGliEventi (Tutti gli eventi)\r\n/EventiOggi (Eventi di oggi)\r\n', 1, '', '0000-00-00 00:00:00'),
(4, 8, 1, NULL, 0, 'Button', 'Normal', 'Servizi', 'Privacy del BOT:\r\nINFORMATION /Info \r\nDisattiva notifiche:\r\nKEY /SettaggioBot\r\n\r\nFilm e orari dei cinema:\r\nCINEMA /infoCinema\r\n\r\nN. parcheggi liberi a Udine:\r\nPUSHPIN /ParcheggiLiberiUDINE\r\nServizi avanzati:\r\nPUSHPIN /ServiziConPosizione\r\nPronto Soccorso:\r\nAMBULANCE /ProntoSoccorso', 1, '', '0000-00-00 00:00:00'),
(12, 6, 1, NULL, 0, 'Button', 'Normal', 'News', 'Potete leggere le news:\r\n/NewsRegioneFvg (Notizie dalla Regione F.V.G.)\r\n', 1, '', '0000-00-00 00:00:00'),
(13, 2, 1, NULL, 0, 'Button', 'Normal', 'Webcam', 'Scegli il panorama da visualizzare:\r\nSUNRISE OVER MOUNTAINS MONTAGNA:\r\n/webSauris\r\n \r\nSUNSET OVER BUILDINGS CITTA\':\r\n/webCividalePiazzaDuomo\r\n\r\nSUNRISE MARE:\r\n/webGradoPorto', 1, '', '0000-00-00 00:00:00'),
(15, 0, 0, NULL, 0, 'Hello', 'Normal', 'Benvenuto', 'Benvenuto nel Bot .......\r\nUtilizzando il Bot acconsenti al trattamento dei tuoi dati personali secondo quanto disposto dal Regolamento Euoropeo  Ue 2016/679, leggi come vengono trattati i tuoi dati: link_privacy.\r\nSe non acconsenti al trattamento dati clicca su /stop , puoi settare se ricevere aggiornamenti dal Bot usando il comando /SettaggioBot .\r\nPer ogni problema su come utilizzarmi scrivi /help , per domande generiche non esitare a scrivermi direttamente da qui, ti risponderà  appena possibile. Se vuoi avere una copia dei tuoi dati scrivi un messaggio a: ........ e quanto prima ti verranno forniti.\r\nNon abbandonarmi dopo pochi minuti, lasciami il tempo di farti vedere di cosa sono capace. Grazie!\r\n\r\n', 1, '', '0000-00-00 00:00:00'),
(21, 70, 1, NULL, 0, 'Orario', 'Function', '/OraEsatta', 'Ore', 1, '', '0000-00-00 00:00:00'),
(32, 99, 1, NULL, 0, 'Help', 'Normal', '/help', 'Per lanciare dei comandi usa la tastiera del Bot. Ti guiderÃ  nelle funzioni messe a disposizione. Per ulteriori domande e suggerimenti puoi scrivere specifiche richieste che verranno prese in carico. Grazie.', 1, '', '0000-00-00 00:00:00'),
(54, 86, 1, NULL, 0, 'Protezione', 'Function', '/AllertaMeteo', 'AlertProtezione|http://www.protezionecivile.fvg.it/it/allerte', 1, '', '0000-00-00 00:00:00'),
(57, 100, 1, NULL, 0, 'Meteo Fvg', 'Function', '/TempoOggi', 'linkImgOutput|http://www.meteo.fvg.it/previ/oggi.png', 1, '', '0000-00-00 00:00:00'),
(58, 101, 1, NULL, 0, 'Meteo Fvg', 'Function', '/TempoDomani', 'linkImgOutput|http://m.meteo.fvg.it/previ/domani.png', 1, '', '0000-00-00 00:00:00'),
(59, 103, 1, NULL, 0, 'Meteo Fvg', 'Function', '/TempoA_2_giorni', 'linkImgOutput|http://m.meteo.fvg.it/previ/dopodomani.png', 1, '', '0000-00-00 00:00:00'),
(60, 104, 1, NULL, 0, 'Meteo Fvg', 'Function', '/FvgTemperatureAttuale', 'linkImgOutput|http://www.meteo.fvg.it/osserv/temperatura.png', 1, '', '0000-00-00 00:00:00'),
(72, 130, 1, NULL, 0, 'Lavoro', 'Function', '/OfferteLavoroRegioneFVG', 'Read|http://www.regione.fvg.it/rafvg/cms/RAFVG/formazione-lavoro/servizi-lavoratori/news/?rss=y', 0, '', '0000-00-00 00:00:00'),
(76, 134, 1, NULL, 0, 'Lavoro', 'Function', '/FormazioneLavoroRegione', 'Read|http://www.regione.fvg.it/rafvg/cms/RAFVG/rss/formazione-lavoro', 1, '', '0000-00-00 00:00:00'),
(79, 105, 1, NULL, 0, 'Meteo Fvg', 'Function', '/TempoA_3_giorni', 'linkImgOutput|http://www.meteo.fvg.it/previ/piu3.png', 1, '', '0000-00-00 00:00:00'),
(80, 106, 1, NULL, 0, 'Meteo Fvg', 'Function', '/TempoA_4_giorni', 'linkImgOutput|http://www.meteo.fvg.it/previ/piu4.png', 1, '', '0000-00-00 00:00:00'),
(81, 107, 1, NULL, 0, 'Meteo Fvg', 'Function', '/FvgTemperatureMIN', 'linkImgOutput|http://www.meteo.fvg.it/osserv/temperatura_min.png', 1, '', '0000-00-00 00:00:00'),
(82, 108, 1, NULL, 0, 'Meteo Fvg', 'Function', '/FvgTemperatureMAX', 'linkImgOutput|http://www.meteo.fvg.it/osserv/temperatura_max.png', 1, '', '0000-00-00 00:00:00'),
(84, 200, 1, NULL, 0, 'Setting', 'Normal', '/SettaggioBot', 'Per non ricevere nessuna informazione dal Bot (tranne avvisi di aggiornamento delle funzionalitÃ ):\r\n/zeroAggiornamenti\r\n\r\nPer ricevere tutti gli aggiornamenti:\r\n/tuttiAggiornamenti\r\n\r\n==\r\nA breve altre tipologie di settaggio per le news del Bot.\r\n\r\n ', 1, '', '0000-00-00 00:00:00'),
(85, 201, 1, NULL, 0, 'Setting', 'Normal', '/Aggiornamenti', 'Per non ricevere nessuna informazione dal Bot (tranne avvisi di aggiornamento delle funzionalitÃ ):\r\n/zeroAggiornamenti\r\n\r\nPer ricevere tutti gli aggiornamenti:\r\n/tuttiAggiornamenti\r\n\r\n==\r\nA breve altre tipologie di settaggio per le news del Bot.\r\n\r\n', 0, '', '0000-00-00 00:00:00'),
(86, 202, 1, NULL, 0, 'Setting', 'Function', '/zeroAggiornamenti', 'Setting|Message|0', 1, '', '0000-00-00 00:00:00'),
(87, 203, 1, NULL, 0, 'Setting', 'Function', '/tuttiAggiornamenti', 'Setting|Message|1', 1, '', '0000-00-00 00:00:00'),
(89, 251, 1, NULL, 0, 'Servizi', 'Normal', '/ServiziConPosizione', 'Al momento sono disponibili questi servizi basati sulla TUA posizione:\r\n\r\nStazioni di rifornimento con prezzo, nelle tue vicinanze:\r\nFUEL PUMP /DistributoriLocali\r\nBar/Osterie/Trattorie/locali/Gelaterie nelle vicinanze:\r\nFRENCH FRIES /Commercianti\r\nBeni culturali nelle vicinanze:\r\nCHURCH /Arte\r\nPunti Wifi gratis\r\nANTENNA WITH BARS /Wifi\r\nFarmacie nelle vicinanze:\r\nPILL /FarmacieLocali \r\nFarmacie APERTE ora:\r\nPILL /FarmaciaAperta\r\n\r\ndopo aver selezionato il servizio puoi inviare la posizione con il pulsante PAPERCLIP.', 1, '', '0000-00-00 00:00:00'),
(92, 109, 1, NULL, 0, 'Meteo Fvg', 'Normal', '/Temperature', 'Temperature:\r\n\r\n/FvgTemperatureAttuale (attuale)\r\n\r\n/FvgTemperatureMIN (minima)\r\n\r\n/FvgTemperatureMAX (massima)', 1, '', '0000-00-00 00:00:00'),
(93, 253, 1, NULL, 0, 'Servizi', 'Function', '/FarmacieLocali', 'serviceTmp|Farmacia', 1, '', '0000-00-00 00:00:00'),
(94, 254, 1, NULL, 0, 'Servizi', 'Function', '/DistributoriLocali', 'serviceTmp|Gasoline', 1, '', '0000-00-00 00:00:00'),
(104, 255, 1, NULL, 0, 'servizi', 'Function', '/Commercianti', 'serviceTmp|Commerce', 1, '', '0000-00-00 00:00:00'),
(105, 5, 1, NULL, 0, 'Button', 'Normal', 'Svago', 'CLOCK FACE ONE-THIRTY Ora esatta:\r\n/OraEsatta\r\n\r\nOsmize aperte:\r\nHAMBURGER http://bit.ly/2mwtpny\r\n\r\nCAMERA PHOTO Visualizza una bella foto di:\r\n/fotoCividaleDelFriuli (Cividale)', 1, '', '0000-00-00 00:00:00'),
(115, 401, 1, NULL, 0, 'Albo Pretorio Fvg', 'Function', '/AlboPretorio', 'AlboPretorioFvg|http://albopretorio.regione.fvg.it/ap/udine?avanzata=&testo=&ordinamento=0&nxpag=50', 1, '', '0000-00-00 00:00:00'),
(116, 90, 1, NULL, 0, 'News', 'Function', '/NewsRegioneFvg', 'Read|http://www.regione.fvg.it/rafvg/cms/RAFVG/rss/notizie-in-evidenza', 1, '', '0000-00-00 00:00:00'),
(124, -1, 0, NULL, 0, 'Altro', 'Normal', 'Di servizio', 'Pulsante di servizio. NON CANCELLARE MAI.', 1, '', '0000-00-00 00:00:00'),
(161, 3, 1, NULL, 0, 'Button', 'Normal', 'Allerte', 'SNOWFLAKE Info Neve in Friuli V.G.:\r\nhttp://bit.ly/2mGQB59\r\nRischio valanghe:\r\nSNOWFLAKE /Valanghe\r\n\r\nInfo Traffico di Autovie Venete\r\nINFORMATION /InfoTrafficoAutostrada\r\n\r\nAllerte meteo della Protezione Regionale FVG:\r\nDOUBLE EXCLAMATION MARK /AllertaMeteo', 1, '', '0000-00-00 00:00:00'),
(168, 301, 1, NULL, 0, 'Cinema', 'Function', '/CinemaVisionario', 'Read|http://visionario.movie/feed/', 1, '', '0000-00-00 00:00:00'),
(169, 60, 1, NULL, 0, 'Meteo', 'Normal', '/Valanghe', 'http://www.osmer.fvg.it/valanghe/bollettini/last/bollettino_ITALIANO.pdf', 1, '', '0000-00-00 00:00:00'),
(170, 285, 1, NULL, 0, 'Allerte', 'Function', '/InfoTrafficoAutostrada', 'InfoTrafficoAutostradaFvg', 1, '', '0000-00-00 00:00:00'),
(171, 127, 1, NULL, 0, 'Foto', 'Function', '/fotoCividaleDelFriuli', 'Photo|Cividale', 1, '', '0000-00-00 00:00:00'),
(178, 255, 1, NULL, 0, 'Servizi', 'Function', '/ParcheggiLiberiUDINE', 'Ssm', 1, '', '0000-00-00 00:00:00'),
(190, 255, 1, NULL, 0, 'Servizi', 'Function', '/Arte', 'serviceTmp|Art', 1, '', '0000-00-00 00:00:00'),
(203, 45, 1, NULL, NULL, 'Eventi', 'Function', '/EventiOggi', 'EventIcs|day|all', 1, '', '0000-00-00 00:00:00'),
(206, 255, 1, NULL, NULL, 'Servizi', 'Function', '/Wifi', 'serviceTmp|Wifi', 1, '', '0000-00-00 00:00:00'),
(207, 255, 1, NULL, NULL, 'Servizi', 'Function', '/ProntoSoccorso', 'PsOnlineFVG', 1, '', '0000-00-00 00:00:00'),
(209, 253, 1, NULL, NULL, 'Servizi', 'Function', '/FarmaciaAperta', 'serviceTmp|FarmaciaFvg', 1, '', '0000-00-00 00:00:00'),
(215, 140, 1, NULL, NULL, 'Lavoro', 'Function', '/ConcorsiPubbliciFVG', 'ConcorsiFvg|http://www.regione.fvg.it/rafvg/concorsi/concorsiInt.act?dir=/rafvg/cms/RAFVG/Concorsi/', 1, '', '0000-00-00 00:00:00'),
(224, 300, 1, NULL, NULL, 'Cinema', 'Normal', '/infoCinema', '(Cinema del Visionario di Udine)\r\n/CinemaVisionario', 1, '', '0000-00-00 00:00:00'),
(225, 500, 1, NULL, NULL, 'Webcam', 'Function', '/webGrado', 'linkImgOutput|http://www.lnigrado.it/webcam/marina1_6/cam5.jpg', 1, '', '0000-00-00 00:00:00'),
(226, 501, 1, NULL, NULL, 'WebCam', 'Function', '/webCividalePiazzaDuomo', 'linkImgOutput|http://www.turismofriuliveneziagiulia.it/webcam/cividale_piazzaduomo.jpg', 1, '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `software_config_button_tag`
--

CREATE TABLE `software_config_button_tag` (
  `ID` int NOT NULL,
  `IdButton` int NOT NULL,
  `Tag` varchar(1000) NOT NULL,
  `Description` varchar(4096) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `software_documents`
--

CREATE TABLE `software_documents` (
  `IdPhoto` int NOT NULL,
  `Description` text NOT NULL,
  `Note` varchar(4000) DEFAULT NULL,
  `Link` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `software_log`
--

CREATE TABLE `software_log` (
  `UserID` varchar(200) NOT NULL,
  `FirstName` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `LastName` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `Username` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `Message` text,
  `DataInsert` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `software_service`
--

CREATE TABLE `software_service` (
  `ServiceID` int NOT NULL,
  `Type` varchar(25) NOT NULL,
  `Value` varchar(25) NOT NULL,
  `Descriptiom` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `tmpUserButton`
--

CREATE TABLE `tmpUserButton` (
  `ID` int NOT NULL,
  `UserID` varchar(200) NOT NULL,
  `IdLevel` int DEFAULT NULL,
  `IdLevelIndoor` int DEFAULT NULL,
  `IdButton` int NOT NULL,
  `DataInsert` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `tmpUserService`
--

CREATE TABLE `tmpUserService` (
  `ID` int NOT NULL,
  `UserID` varchar(200) NOT NULL,
  `Service` varchar(200) NOT NULL,
  `DataInsert` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `UserID` varchar(200) NOT NULL,
  `FirstName` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `LastName` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `Username` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `DataInsert` date DEFAULT NULL,
  `DataDelete` date DEFAULT NULL,
  `StatoUtente` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti_log`
--

CREATE TABLE `utenti_log` (
  `IdOperation` int NOT NULL,
  `ChatID` varchar(200) DEFAULT NULL,
  `UserID` varchar(200) NOT NULL,
  `LogDate` datetime NOT NULL,
  `Operation` text NOT NULL,
  `Result` varchar(4000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti_message`
--

CREATE TABLE `utenti_message` (
  `ID` int NOT NULL,
  `UserID` int NOT NULL,
  `FirstName` text,
  `DataInsert` datetime DEFAULT NULL,
  `Message` int NOT NULL,
  `Text` varchar(5000) DEFAULT NULL,
  `Document` blob,
  `FileName` varchar(255) DEFAULT NULL,
  `MimeType` varchar(50) DEFAULT NULL,
  `FileId` varchar(250) DEFAULT NULL,
  `FileId2` varchar(250) DEFAULT NULL,
  `FileSize` int DEFAULT NULL,
  `Width` int DEFAULT NULL,
  `Height` int DEFAULT NULL,
  `Archive` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti_service`
--

CREATE TABLE `utenti_service` (
  `UserID` varchar(200) NOT NULL,
  `Type` varchar(25) NOT NULL,
  `Value` varchar(25) NOT NULL,
  `DataInsert` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `viewCommerce`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `viewCommerce` (
`AnnoInserimento` text
,`Comune` text
,`Data` text
,`ID` int
,`Identificatore` varchar(40)
,`Latitudine` text
,`Longitudine` text
,`Nome` varchar(200)
,`Provincia` text
,`Regione` text
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `viewGasolinePriceRegistry`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `viewGasolinePriceRegistry` (
`Bandiera` varchar(24)
,`Comune` varchar(34)
,`descCarburante` varchar(21)
,`dtComu` date
,`Gestore` varchar(247)
,`Indirizzo` varchar(106)
,`isSelf` int
,`Latitudine` decimal(30,12)
,`Longitudine` decimal(30,12)
,`prezzo` decimal(4,3)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `viewPharmacies`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `viewPharmacies` (
`DESCRIZIONECOMUNE` varchar(100)
,`DESCRIZIONEFARMACIA` varchar(400)
,`INDIRIZZO` varchar(400)
,`LATITUDINE` varchar(255)
,`LONGITUDINE` varchar(255)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `viewPharmaciesFvg`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `viewPharmaciesFvg` (
`address` varchar(500)
,`businessName` varchar(500)
,`city` char(200)
,`from0` datetime
,`latitude` decimal(30,12)
,`longitude` decimal(30,12)
,`name` varchar(500)
,`phone` text
,`type0` text
,`until0` datetime
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `viewTweet`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `viewTweet` (
`DescTweet` varchar(600)
,`IdTweet` int
,`OnOff` tinyint(1)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `viewUserActive`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `viewUserActive` (
`FirstName` text
,`total` bigint
,`UserID` varchar(200)
,`username` text
);

-- --------------------------------------------------------

--
-- Struttura per vista `viewCommerce`
--
DROP TABLE IF EXISTS `viewCommerce`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viewCommerce`  AS SELECT `Ext_commerce_registry`.`ID` AS `ID`, `Ext_commerce_registry`.`Comune` AS `Comune`, `Ext_commerce_registry`.`Provincia` AS `Provincia`, `Ext_commerce_registry`.`Regione` AS `Regione`, `Ext_commerce_registry`.`Nome` AS `Nome`, `Ext_commerce_registry`.`AnnoInserimento` AS `AnnoInserimento`, `Ext_commerce_registry`.`Data` AS `Data`, `Ext_commerce_registry`.`Identificatore` AS `Identificatore`, `Ext_commerce_registry`.`Longitudine` AS `Longitudine`, `Ext_commerce_registry`.`Latitudine` AS `Latitudine` FROM `Ext_commerce_registry` WHERE (`Ext_commerce_registry`.`Nome` <> '') ;

-- --------------------------------------------------------

--
-- Struttura per vista `viewGasolinePriceRegistry`
--
DROP TABLE IF EXISTS `viewGasolinePriceRegistry`;

CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viewGasolinePriceRegistry`  AS SELECT `Ext_gasoline_price`.`prezzo` AS `prezzo`, `Ext_gasoline_price`.`descCarburante` AS `descCarburante`, `Ext_gasoline_price`.`isSelf` AS `isSelf`, `Ext_gasoline_price`.`dtComu` AS `dtComu`, `Ext_gasoline_registry`.`Gestore` AS `Gestore`, `Ext_gasoline_registry`.`Bandiera` AS `Bandiera`, `Ext_gasoline_registry`.`Indirizzo` AS `Indirizzo`, `Ext_gasoline_registry`.`Comune` AS `Comune`, `Ext_gasoline_registry`.`Latitudine` AS `Latitudine`, `Ext_gasoline_registry`.`Longitudine` AS `Longitudine` FROM (`Ext_gasoline_price` join `Ext_gasoline_registry`) WHERE (`Ext_gasoline_price`.`idImpianto` = `Ext_gasoline_registry`.`idImpianto`) ;

-- --------------------------------------------------------

--
-- Struttura per vista `viewPharmacies`
--
DROP TABLE IF EXISTS `viewPharmacies`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viewPharmacies`  AS SELECT `tempFARM`.`DESCRIZIONEFARMACIA` AS `DESCRIZIONEFARMACIA`, `tempFARM`.`INDIRIZZO` AS `INDIRIZZO`, `tempFARM`.`DESCRIZIONECOMUNE` AS `DESCRIZIONECOMUNE`, `tempFARM`.`LATITUDINE` AS `LATITUDINE`, `tempFARM`.`LONGITUDINE` AS `LONGITUDINE` FROM (select `Ext_pharmacies_registry`.`DESCRIZIONEFARMACIA` AS `DESCRIZIONEFARMACIA`,`Ext_pharmacies_registry`.`INDIRIZZO` AS `INDIRIZZO`,`Ext_pharmacies_registry`.`DESCRIZIONECOMUNE` AS `DESCRIZIONECOMUNE`,`Ext_pharmacies_registry`.`LATITUDINE` AS `LATITUDINE`,`Ext_pharmacies_registry`.`LONGITUDINE` AS `LONGITUDINE` from `Ext_pharmacies_registry` where (`Ext_pharmacies_registry`.`DATAFINEVALIDITA` = '-') union select `Ext_paraPharmacies_registry`.`DENOMINAZIONESITOLOGISTICO` AS `DENOMINAZIONESITOLOGISTICO`,`Ext_paraPharmacies_registry`.`INDIRIZZO` AS `INDIRIZZO`,`Ext_paraPharmacies_registry`.`DESCRIZIONECOMUNE` AS `DESCRIZIONECOMUNE`,`Ext_paraPharmacies_registry`.`LATITUDINE` AS `LATITUDINE`,`Ext_paraPharmacies_registry`.`LONGITUDINE` AS `LONGITUDINE` from `Ext_paraPharmacies_registry` where (`Ext_paraPharmacies_registry`.`DATAFINEVALIDITA` = '-')) AS `tempFARM` ;

-- --------------------------------------------------------

--
-- Struttura per vista `viewPharmaciesFvg`
--
DROP TABLE IF EXISTS `viewPharmaciesFvg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viewPharmaciesFvg`  AS SELECT `Ext_fvg_pharmacies`.`name` AS `name`, `Ext_fvg_pharmacies`.`businessName` AS `businessName`, `Ext_fvg_pharmacies`.`address` AS `address`, `Ext_fvg_pharmacies`.`city` AS `city`, `Ext_fvg_pharmacies`.`phone` AS `phone`, `Ext_fvg_pharmacies`.`longitude` AS `longitude`, `Ext_fvg_pharmacies`.`latitude` AS `latitude`, `Ext_fvg_pharmacies_hour`.`from0` AS `from0`, `Ext_fvg_pharmacies_hour`.`until0` AS `until0`, `Ext_fvg_pharmacies_hour`.`type0` AS `type0` FROM (`Ext_fvg_pharmacies` join `Ext_fvg_pharmacies_hour` on((`Ext_fvg_pharmacies`.`idPharmFvg` = `Ext_fvg_pharmacies_hour`.`idPharmFvg`))) ;

-- --------------------------------------------------------

--
-- Struttura per vista `viewTweet`
--
DROP TABLE IF EXISTS `viewTweet`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viewTweet`  AS SELECT `Ext_Tweet`.`IdTweet` AS `IdTweet`, `Ext_Tweet`.`DescTweet` AS `DescTweet`, `Ext_Tweet`.`OnOff` AS `OnOff` FROM `Ext_Tweet` ;

-- --------------------------------------------------------

--
-- Struttura per vista `viewUserActive`
--
DROP TABLE IF EXISTS `viewUserActive`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viewUserActive`  AS SELECT `utenti`.`FirstName` AS `FirstName`, `utenti`.`Username` AS `username`, `utenti_log`.`UserID` AS `UserID`, count(`utenti_log`.`UserID`) AS `total` FROM (`utenti` join `utenti_log`) WHERE (`utenti`.`UserID` = `utenti_log`.`UserID`) GROUP BY `utenti_log`.`UserID` ORDER BY `total` DESC ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indici per le tabelle `Ext_Art`
--
ALTER TABLE `Ext_Art`
  ADD PRIMARY KEY (`CODICE_UNIVOCO`);

--
-- Indici per le tabelle `Ext_commerce_registry`
--
ALTER TABLE `Ext_commerce_registry`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `Ext_fresh_fvg`
--
ALTER TABLE `Ext_fresh_fvg`
  ADD PRIMARY KEY (`idPoint`);

--
-- Indici per le tabelle `Ext_fvg_pharmacies`
--
ALTER TABLE `Ext_fvg_pharmacies`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `Ext_fvg_pharmacies_hour`
--
ALTER TABLE `Ext_fvg_pharmacies_hour`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `Ext_fvg_wifi`
--
ALTER TABLE `Ext_fvg_wifi`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `Ext_gasoline_price`
--
ALTER TABLE `Ext_gasoline_price`
  ADD PRIMARY KEY (`idImpianto`,`descCarburante`,`isSelf`);

--
-- Indici per le tabelle `Ext_gasoline_registry`
--
ALTER TABLE `Ext_gasoline_registry`
  ADD PRIMARY KEY (`idImpianto`);

--
-- Indici per le tabelle `Ext_ics_event`
--
ALTER TABLE `Ext_ics_event`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `Ext_Tweet`
--
ALTER TABLE `Ext_Tweet`
  ADD PRIMARY KEY (`IdTweet`);

--
-- Indici per le tabelle `message_scheduler`
--
ALTER TABLE `message_scheduler`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `message_scheduler_function`
--
ALTER TABLE `message_scheduler_function`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `message_send`
--
ALTER TABLE `message_send`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `software_config`
--
ALTER TABLE `software_config`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `SoftDesc` (`SoftDesc`,`Code`);

--
-- Indici per le tabelle `software_config_button`
--
ALTER TABLE `software_config_button`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `software_config_button_tag`
--
ALTER TABLE `software_config_button_tag`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Tag` (`Tag`);

--
-- Indici per le tabelle `software_documents`
--
ALTER TABLE `software_documents`
  ADD PRIMARY KEY (`IdPhoto`);

--
-- Indici per le tabelle `software_log`
--
ALTER TABLE `software_log`
  ADD PRIMARY KEY (`UserID`);

--
-- Indici per le tabelle `software_service`
--
ALTER TABLE `software_service`
  ADD PRIMARY KEY (`ServiceID`);

--
-- Indici per le tabelle `tmpUserButton`
--
ALTER TABLE `tmpUserButton`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UserID` (`UserID`,`IdButton`),
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indici per le tabelle `tmpUserService`
--
ALTER TABLE `tmpUserService`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UserID` (`UserID`,`Service`),
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`UserID`);

--
-- Indici per le tabelle `utenti_log`
--
ALTER TABLE `utenti_log`
  ADD PRIMARY KEY (`IdOperation`),
  ADD KEY `IdOperation` (`IdOperation`);

--
-- Indici per le tabelle `utenti_message`
--
ALTER TABLE `utenti_message`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `utenti_service`
--
ALTER TABLE `utenti_service`
  ADD PRIMARY KEY (`UserID`,`Type`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT per la tabella `Ext_commerce_registry`
--
ALTER TABLE `Ext_commerce_registry`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Ext_fvg_pharmacies`
--
ALTER TABLE `Ext_fvg_pharmacies`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Ext_fvg_pharmacies_hour`
--
ALTER TABLE `Ext_fvg_pharmacies_hour`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Ext_fvg_wifi`
--
ALTER TABLE `Ext_fvg_wifi`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Ext_ics_event`
--
ALTER TABLE `Ext_ics_event`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `message_scheduler`
--
ALTER TABLE `message_scheduler`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `message_scheduler_function`
--
ALTER TABLE `message_scheduler_function`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `message_send`
--
ALTER TABLE `message_send`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `software_config`
--
ALTER TABLE `software_config`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT per la tabella `software_config_button`
--
ALTER TABLE `software_config_button`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT per la tabella `software_config_button_tag`
--
ALTER TABLE `software_config_button_tag`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT per la tabella `software_documents`
--
ALTER TABLE `software_documents`
  MODIFY `IdPhoto` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `tmpUserButton`
--
ALTER TABLE `tmpUserButton`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `tmpUserService`
--
ALTER TABLE `tmpUserService`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `utenti_log`
--
ALTER TABLE `utenti_log`
  MODIFY `IdOperation` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `utenti_message`
--
ALTER TABLE `utenti_message`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
