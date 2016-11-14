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

if (isset($_POST['valid_texte_odm'])) {
	$db->query("REPLACE INTO parameters(id, value) VALUES ('texte_odm', '".str_replace("\n", '<br />', addslashes(htmlentities($_POST['texte_odm'])))."'); ");
	Header("Location:parameters.php");
}



entete("Parametes");
include ("header.php");


?>
<h1>Parametres</h1>
<form method="POST" action="">
<table>
<tr><td>Texte d'imaginaire dans l'orde de mission des jeunes : </td></tr>
<?php 
$db->query("SELECT * FROM parameters where id = 'texte_odm' ;");
$resultat = '';
if ($db->get_num_rows() >0) {
	$o = $db->fetchNextObject();
	$resultat = $o->value;
}
?>

<tr><td><textarea name="texte_odm" cols="100" rows="10"><?php echo str_replace('<br />',"\n", stripslashes(html_entity_decode($resultat))) ;?></textarea></td></tr>
<tr><td><input type="submit" value="Valider" name="valid_texte_odm" /></td></tr>
</table>
</form>


<?php 
footer();
?>