<?php
define("EN_TRAVAUX", "NON");
define("MYSQL_HOTE", "localhost");
define("MYSQL_UTILISATEUR", "root");
define("MYSQL_MOT_DE_PASSE", "");
define("MA_DATABASE", "yup");
define("GMAP_KEY", "MaClef");

if (isset($_SESSION['gestion_booking']) ) {
	if ($_SESSION['gestion_booking'] == 1) {
		define("TABLE_NAME_ETAPE","etapes_rw");
		define("TABLE_NAME_ITINERAIRE","itineraire_rw");
	} else if ($_SESSION['gestion_booking'] == 2) {
		define("TABLE_NAME_ETAPE","etapes");
		define("TABLE_NAME_ITINERAIRE","itineraire");
	}
} else {
	define("TABLE_NAME_ETAPE","etapes_rw");
	define("TABLE_NAME_ITINERAIRE","itineraire_rw");
}

?>