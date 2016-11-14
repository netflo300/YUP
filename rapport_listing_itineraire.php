<?php
session_start();
require_once ("class/db.class.php");
require_once ("lib/fonctions.php");
$db = new Db();
if (isset($_GET['nb_ligne'])) {
	$nb_ligne = $_GET['nb_ligne'];
} else {
	$nb_ligne = 15;
}

define("NB_LIGNES_PAR_PAGE", $nb_ligne );
    // get the HTML
    ob_start();
    include(dirname(__FILE__).'/etat/res/rapport_listing_itineraire.php');
    $content = ob_get_clean();

    // convert in PDF
    require_once(dirname(__FILE__).'/class/html2pdf/html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('L', 'A4', 'fr');
//      $html2pdf->setModeDebug();
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('exemple00.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
 ?>