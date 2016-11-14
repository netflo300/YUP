<?php
session_start();
require_once ("class/db.class.php");
require_once ("lib/fonctions.php");
if (!auth()) {
  Header("Location:login.php");
  die;
}
$db = new Db();
entete("Accueil");
include ("header.php");
?>
<table border="1" id="table1">
<caption>Suivi des modifications</caption>
<tr><th>Version</th><th>Date</th><th>R&eacute;sum&eacute;</th></tr>
<tr><td>1.00</td><td>05/04/2015</td><td>Version initiale</td></tr>
<tr><td>1.01</td><td>24/04/2015</td><td>Ajout des points de transport sur les carte
<br>Ajout du module des itineraires finaux <b>EN BETA</b></td></tr>
<tr><td>1.02</td><td>15/05/2015</td><td>Mise en evidence des checkpoint sans passage en <span class="bleu">bleu</span>
<br/>Ajout d'une popup "configuration" pour regler l'affichage des checkpoints sur la page des itineraire. Doit se g&eacute;n&eacute;raliser sur les autres pages.</td></tr>
<tr><td>1.03</td><td>25/05/2015</td><td>Ajout de la focntionalité "Code"</td></tr>
<tr><td>1.04</td><td>30/05/2015</td><td>Ajout des colonnes CID et Etat sur la page des checkpoint. Ajout du mode Maintenance</td></tr>
<tr><td>1.05</td><td>03/06/2015</td><td>Switchage de l'affichage booking possible
<br />G&eacute;n&eacute;ration des rapports chef depuis la pages des checkpoint
<br />Page de g&eacute;n&eacute; ration des rapports
<br />Evolution et correction d'anomalie pour l'edition d'un itineraire
</td></tr>
<tr><td>1.06</td><td>21/06/2015</td><td>Ajout de la page "parametres" destiner à parametrer des connées pour les editin de rapports.
</td></tr>
</table>

<?php 
footer();
?>
