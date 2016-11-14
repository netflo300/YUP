<?php
session_start();
require_once ("class/db.class.php");
require_once ("lib/fonctions.php");
if (!auth()) {
  Header("Location:login.php");
  die;
}
$db = new Db();
if (isset($_GET['id_delete'])) {
	$db->query("DELETE FROM etapes_rw WHERE id_itineraire = ".$_GET['id_delete']." ;");
	$db->query("DELETE FROM itineraire_rw WHERE id = ".$_GET['id_delete']." ;");
	header("Location:itineraire_rw.php");
}

entete("Itineraires");
include ("header.php");
echo '<table border="1" id="table1">';
echo '<caption>Liste des Itineraires</caption>';
echo '<tr><th>Id</th><th>Origine</th><th>Nom</th><th>distance</th><th>Etape 1</th><th>Etape 2</th><th>Etape 3</th><th>Etape 4</th><th>Etape 5</th><th>Etape 6</th><th>Etape 7</th><th>Etape 8</th><th>Etat</th><th>Cr&eacute; Par</th><th>Valid&eacute; Par</th><th></th></tr>';

$db->query("SELECT id, id_origine, nom, distance, etat, created_by, validated_by	FROM itineraire_rw");
$tab = $db->fetch_array();
if(!empty($tab)) {
	foreach ($tab as $k => $v) {
		echo '<tr><td>'.$v['id'].'</td><td>'.$v['id_origine'].'</td><td>'.$v['nom'].'</td><td>'.$v['distance'].'</td>';
		$db->query("SELECT e.position as position, checkpoint.*
	FROM etapes_rw e, checkpoint
	WHERE e.id_checkpoint = checkpoint.id
	AND e.id_itineraire = ".$v['id']."
	ORDER BY e.position;");
		$nb_etapes = 8;
		foreach ($db->fetch_array() as $k2 => $v2) {
			echo '<td>'.display_checkpoint(array('id'=>$v2['id'],'name'=>$v2['name'], 'titre'=>$v2['titreActivite'], 'cid'=>$v2['cid'])).'</td>';
			$nb_etapes--;
		}
		while($nb_etapes>0) {
			echo '<td></td>';
			$nb_etapes--;
		}
		echo'<td>'.$v['etat'].'</td>';
		echo'<td>'.$v['created_by'].'</td>';
		echo'<td>'.$v['validated_by'].'</td>';
		echo'<td><a href="edit_itineraire.php?id_itineraire='.$v['id'].'"><img src="img/edit.png" /></a> <a href="rapport_odm.php?id='.$v['id'].'"><img class="icone" src="img/file_pdf.png" /></a> <a href="?id_delete='.$v['id'].'" onclick="return confirm(\'Est tu sur ?\');"><img src="img/fermer.png" alt="supprimer"/></a></td>';
		echo'</tr>';
	}
}
echo '</tr>';
echo '</table>';
?>
<script language="javascript" type="text/javascript">  
    var table3Filters = {  
        col_0: "select",  
        col_1: "select", 
        col_4: "select",  
        col_5: "select",
        col_6: "select",
        col_7: "select",
        col_8: "select",
        col_9: "select",
        col_10: "select",
        col_11: "select",
        col_12: "select",
        col_13: "select",
        col_14: "select",
        col_15: "none",
        btn: true  
    }  
    var tf03 = setFilterGrid("table1",1,table3Filters);  
</script>


<?php 
footer();
?>
