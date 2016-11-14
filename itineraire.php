<?php
session_start();
require_once ("class/db.class.php");
require_once ("lib/fonctions.php");
if (!auth()) {
  Header("Location:login.php");
  die;
}
$db = new Db();

if (isset($_GET['id_itineraire'])){
	$db->query("INSERT INTO itineraire_rw (id_origine, nom, distance, etat, created_by)
	SELECT id, nom, distance, 'Copi&eacute;', '".$_SESSION['login']."' FROM itineraire where id = '".$_GET['id_itineraire']."' ;");
	$last_id = $db->lastInsertedId();
	$db->query("INSERT INTO etapes_rw (id_itineraire, id_checkpoint, position)
	SELECT ".$last_id.", id_checkpoint, position FROM etapes where id_itineraire = '".$_GET['id_itineraire']."' ;");
	determiner_point_transport($last_id);
	Header("Location:itineraire_rw.php");
  die;
}



entete("Itineraires");
include ("header.php");
echo '<table border="1" id="table1">';
echo '<caption>Liste des Itineraires</caption>';
echo '<tr><th>Id</th><th>Nom</th><th>distance</th><th>Etape 1</th><th>Etape 2</th><th>Etape 3</th><th>Etape 4</th><th>Etape 5</th><th>Etape 6</th><th>Etape 7</th><th></th></tr>';

$db->query("SELECT id, nom, distance
	FROM itineraire");
$tab = $db->fetch_array();
foreach ($tab as $k => $v) {
	echo '<tr><td><a href="detailsItineraire.php?id_itineraire='.$v['id'].'" target="blank">'.$v['id'].'<a></td><td>'.$v['nom'].'</td><td>'.$v['distance'].'</td>';
	$db->query("SELECT etapes.position as position, checkpoint.*  
	FROM etapes, checkpoint
	WHERE etapes.id_checkpoint = checkpoint.id
	AND etapes.id_itineraire = ".$v['id']."
	ORDER BY etapes.position;");
	foreach ($db->fetch_array() as $k2 => $v2) {
		echo '<td>'.display_checkpoint(array('id'=>$v2['id'],'name'=>$v2['name'], 'titre'=>$v2['titreActivite'],'cid'=>$v2['cid'])).'</td>';
	}
	echo'<td><a href="?id_itineraire='.$v['id'].'"><img src="img/ajouter.png" /></a></td>';
	echo'</tr>';
}
echo '</tr>';
echo '</table>';

?>
<script language="javascript" type="text/javascript">  
    var table3Filters = {  
        col_0: "select",  
        col_3: "select",  
        col_4: "select",
        col_5: "select",
        col_6: "select",
        col_7: "select",
        col_8: "select",
        col_9: "select",
        col_10: "none",
        btn: true  
    }  
    var tf03 = setFilterGrid("table1",1,table3Filters);  
</script>


<?php 
footer();
?>
