<?php
session_start();
require_once ("class/db.class.php");
require_once ("lib/fonctions.php");
if (!auth()) {
  Header("Location:login.php");
  die;
}
$db = new Db();
if (isset($_POST['id'])) {
	$db->query("REPLACE INTO code (id_checkpoint, phrase_claire, phrase_intermediaire,phrase_azimut,phrase_code,clef_grenouille,clef_strasbourg)
		VALUES (".$_POST['id'].",'".addslashes(htmlentities($_POST['phrase_claire']))."', '".addslashes(htmlentities($_POST['phrase_intermediaire']))."', '".addslashes(htmlentities($_POST['phrase_azimut']))."', '".addslashes(htmlentities($_POST['phrase_code']))."', '".addslashes(htmlentities($_POST['clef_grenouille']))."', '".addslashes(htmlentities($_POST['clef_strasbourg']))."');");
	Header("Location:code.php");
  die;
}
entete("Accueil");
include ("header.php");
define("MAX_LENGTH", 100);

if (isset($_GET['id'])) {
	$db->query("SELECT id, titreActivite, name, phrase_claire, phrase_intermediaire, phrase_azimut, phrase_code, clef_grenouille, clef_strasbourg, cid FROM checkpoint i
		LEFT JOIN code c ON i.id = c.id_checkpoint
		WHERE id='".$_GET['id']."' ;");
	$o = $db->fetchNextObject();
	
	$select_avocat = '<select name="clef_strasbourg">';
	$tableau = array('a'=>'Ancre','b'=>'Cochon','c'=>'Poisson','d'=>'Abeille','e'=>'Elephant','f'=>'Pigeon','g'=>'Egyptien','h'=>'Crocodile','i'=>'Renne',
		'j'=>'Grenouille','k'=>'Koala','l'=>'Agneau','m'=>'Macaque','n'=>'Bison','o'=>'Ecureil','p'=>'Pinguin','q'=>'Oie','r'=>'Lapin','s'=>'Cochon','t'=>'Lion',
	'u'=>'Rhinoceros','v'=>'Vigne','w'=>'Girafe','x'=>'Ours','y'=>'Cocq','z'=>'Ane');
	for ($c = 'a'; $c != 'aa'; $c++ ) {
		$select_avocat .= '<option value="'.$c.'" '.($c==$o->clef_strasbourg?'selected="selected"':'').'>A VAUT '.$tableau[$c].'</option>';
	}
	$select_avocat .= '</select>';
	?>
	<p>Attention pour les parties cod&eacute;es : il faut utiliser uniquement des minuscules sans accentset de mani&egrave;re g&eacute;n&eacute;rale, &eacute;viter les caract&egrave;res de ponctuation</p>
<form method="POST" action="">
<input type="hidden" name="id" value="<?php echo $o->id;?>" />
<table>
<tr><td>Checkpoint</td><td><?php echo display_checkpoint(array('id'=>$o->id,'name'=>$o->name, 'titre'=>$o->titreActivite, 'cid'=>$o->cid))?></td></tr>
<tr><td>Phrase claire</td><td><textarea name="phrase_claire" rows="5" cols="40"><?php echo stripslashes($o->phrase_claire) ;?></textarea></td></tr>
<tr><td>Phrase point intermediaire</td><td><textarea name="phrase_intermediaire" rows="5" cols="40"><?php echo stripslashes($o->phrase_intermediaire) ;?></textarea></td></tr>
<tr><td>Phrase azimut</td><td><textarea name="phrase_azimut" rows="5" cols="40"><?php echo stripslashes($o->phrase_azimut) ;?></textarea></td></tr>
<tr><td>Phrase cod&eacute;e</td><td><textarea name="phrase_code" rows="5" cols="40"><?php echo stripslashes($o->phrase_code) ;?></textarea></td><td><?php echo ($o->clef_grenouille!=''?convert_grenouille($o->clef_grenouille,stripslashes(html_entity_decode($o->phrase_code))):'');?></td><td><?php echo ($o->clef_strasbourg!=''?'<span class="messageCode">'.convert_avocat(stripslashes(html_entity_decode($o->phrase_code)),$o->clef_strasbourg).'</span>':'');?></td></tr>
<tr><td>Clef grenouille</td><td><input type="text" name="clef_grenouille" value="<?php echo stripslashes($o->clef_grenouille) ;?>"></td></tr>
<tr><td>Clef Strasbourg</td><td><?php echo $select_avocat ;?></td></tr>
<tr><td colspan="2"><input type="submit" value="Envoyer" /></td></tr>
</table>
</form>
<?php 	
} else {
?>

<table border="1">
  <caption>Liste des codes</caption>
  <tr>
    <th>Id</th>
    <th>Titre activit&eacute;</th>
  	<th>Name</th>
  	<th>Phrase en claire</th>
  	<th>Phrase point intermediaire</th>
  	<th>Phrase azimut</th>
  	<th>Phrase cod&eacute;e</th>
  	<th>Clef grenouille</th>
  	<th>Clef Strabourg</th>
  	<th>Action</th>
  </tr>
  <?php 
  $db->query("SELECT id, titreActivite, name, phrase_claire, phrase_intermediaire, phrase_azimut, phrase_code, clef_grenouille, clef_strasbourg
				FROM checkpoint i
				LEFT JOIN code c ON i.id = c.id_checkpoint");
  
  foreach($db->fetch_array() as $k => $v) {
  	echo '<tr><td>'.$v['id'].'</td>
  	<td>'.$v['titreActivite'].'</td><td>'.$v['name'].'</td>
  	<td>'.((strlen($v['phrase_claire']) > MAX_LENGTH)?(substr(stripslashes($v['phrase_claire']), 0,100).'...'):stripslashes($v['phrase_claire'])).'</td>
  	<td>'.((strlen($v['phrase_intermediaire']) > MAX_LENGTH)?(substr(stripslashes($v['phrase_intermediaire']), 0,100).'...'):stripslashes($v['phrase_intermediaire'])).'</td>
  	<td>'.((strlen($v['phrase_azimut']) > MAX_LENGTH)?(substr(stripslashes($v['phrase_azimut']), 0,100).'...'):stripslashes($v['phrase_azimut'])).'</td>
  	<td>'.((strlen($v['phrase_code']) > MAX_LENGTH)?(substr(stripslashes($v['phrase_code']), 0,100).'...'):stripslashes($v['phrase_code'])).'</td>
  	<td>'.((strlen($v['clef_grenouille']) > MAX_LENGTH)?(substr(stripslashes($v['clef_grenouille']), 0,100).'...'):stripslashes($v['clef_grenouille'])).'</td>
  	<td>'.((strlen($v['clef_strasbourg']) > MAX_LENGTH)?(substr(stripslashes($v['clef_strasbourg']), 0,100).'...'):stripslashes($v['clef_strasbourg'])).'</td>
  	<td><a href="?id='.$v['id'].'"><img src="img/edit.png" /></a></td></tr>'."\n";
  }
  ?>
</table>


<?php 
}
footer();
?>

