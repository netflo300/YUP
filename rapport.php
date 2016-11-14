<?php
session_start();
require_once ("class/db.class.php");
require_once ("lib/fonctions.php");
travaux();
if (!auth()) {
  Header("Location:login.php");
  die;
}
$db = new Db();
entete("Rapport");
include ("header.php");
?>
<h1>Rapport d'&eacute;tapes</h1>
<form method="GET" action="rapport_listing_itineraire.php">
<table>
<tr><td><label>Debut :</label></td><td><input type="text" name="min" /></td></tr>
<tr><td><label>Fin :</label></td><td><input type="text" name="max" /><br /></td></tr>
<tr><td><label>Nombre de lignes par feuille :</label></td><td><input type="text" name="nb_ligne" value="15" /><br /></td></tr>
<tr><td><label>Vue HTML</label></td><td><input type="checkbox" value="1" name="vuehtml" /></td></tr>
<tr><td><label>Mode Debug</label></td><td><input type="checkbox" name="debugMode" /></td></tr>
<tr><td colspan="2"><input type="submit" value="Exporter" /></td></tr>
</table>
</form>
<h1>Ordre de Mission</h1>
<form method="GET" action="rapport_odm.php">
<table>
<tr><td><label>id min :</label></td><td><input type="text" name="min" /></td></tr>
<tr><td><label>id max :</label></td><td><input type="text" name="max" /></td></tr>
<tr><td><label>Vue HTML</label></td><td><input type="checkbox" value="1" name="vuehtml" /></td></tr>
<tr><td><label>Mode Debug</label></td><td><input type="checkbox" name="debugMode" /></td></tr>
<tr><td colspan="2"><input type="submit" value="Exporter" /></td></tr>
</table>
</form>
<h1>Fiches chefs</h1>
<form method="GET" action="rapport_fiche_chef.php">
<table>
<tr><td><label>id min :</label></td><td><input type="text" name="min" /></td></tr>
<tr><td><label>id max :</label></td><td><input type="text" name="max" /></td></tr>
<tr><td><label>Nombre de ligne pour le tableau : </label></td><td><input type="text" name="nb_ligne" value="10" /></td></tr>
<tr><td><label>Vue HTML</label></td><td><input type="checkbox" value="1" name="vuehtml" /></td></tr>
<tr><td><label>Mode Debug</label></td><td><input type="checkbox" name="debugMode" /></td></tr>
<tr><td colspan="2"><input type="submit" value="Exporter" /></td></tr>
</table>
</form>

<h1>Rapport personalisable</h1>
<form method="GET" action="rapport_perso.php">
<table>
<tr><td><label>SELECT :</label></td><td><textarea name="select"></textarea></tr>
<tr><td><label>FROM :</label></td><td><textarea name="from"></textarea></td></tr>
<tr><td><label>WHERE : </label></td><td><textarea name="where"></textarea></td></tr>
<tr><td><label>GROUP BY :</label></td><td><textarea name="groupBy"></textarea></td></tr>
<tr><td><label>ORDER BY :</label></td><td><textarea name="orderBy"></textarea></td></tr>
<tr><td><label>Afficher entete</label></td><td><input type="checkbox" name="enTete" /></td></tr>
<tr><td><label>Afficher pied</label></td><td><input type="checkbox" name="pied" /></td></tr>
<tr><td><label>Nombre de lignes par pages</label></td><td><input type="text" name="nb_ligne" /></td></tr>
<tr><td colspan="2"><input type="submit" value="Exporter" /></td></tr>
</table>
</form>

<?php 
footer();
?>