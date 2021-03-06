<?php
session_start();
require_once ("class/db.class.php");
require_once ("lib/fonctions.php");
$db = new Db();
define("NB_LIGNES_PAR_PAGE", 40 );
    // get the HTML
    ob_start();
    include(dirname(__FILE__).'/etat/res/rapport_perso.php');
    $content = ob_get_clean();

    // convert in PDF
    require_once(dirname(__FILE__).'/class/html2pdf/html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr');
      	if (isset($_GET['debugMode'])) {
        	$html2pdf->setModeDebug();
      	}
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('rapport_perso_'.time().'.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
 ?>