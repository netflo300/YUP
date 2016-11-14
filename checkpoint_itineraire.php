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
echo '<table border="1" id="table1">';
echo '<caption>Occupation des CheckPoint</caption>';
echo '<tr>';
foreach ($db->fetch_array() as $k => $v) {
	echo '<th>'.$v['Field'].'</th>';
}

$db->query("SELECT c.id, c.titreActivite, e.position, count(*) FROM etapes e, checkpoint c 
where etapes.id_checkpoint = checkpoint.id
group by c.id, c.titreActivite, e.position;");
foreach ($db->fetch_array() as $k => $v) {
	echo '<tr><td>'.$v['id'].'</td><td>'.$v['titreActivite'].'</td><td>'.$v['nombreEquipe'].'</td><td>'.$v['absorption'].'</td><td>'.$v['positionX'].'</td><td>'.$v['positionY'].'</td><td>'.$v['duree'].'</td>
	<td>'.$v['originalite'].'</td><td>'.$v['obligatoire'].'</td><td>'.$v['regionnal'].'</td><td>'.$v['europeen'].'</td><td>'.$v['interaction'].'</td><td>'.$v['vivreEnsemble'].'</td><td>'.$v['creatif'].'</td>
	<td>'.$v['intellectuel'].'</td><td>'.$v['physique'].'</td><td>'.$v['theatre'].'</td><td>'.$v['stimulante'].'</td><td>'.$v['fierte'].'</td><td>'.$v['funitude'].'</td><td>'.$v['confrontation'].'</td>
	<td>'.$v['mobilisation'].'</td><td>'.$v['proximite'].'</td><td>'.$v['institution'].'</td><td>'.$v['kehl'].'</td><td>'.$v['groupe'].'</td><td>'.$v['name'].'</td>
	</tr>';
}
echo '</tr>';
echo '</table>';

?>
<script language="javascript" type="text/javascript">  
    var table3Filters = {  
        col_0: "select",  
        col_6: "select",  
        col_7: "select",
        col_8: "select",
        col_9: "select",
        col_10: "select",
        col_11: "select",
        col_12: "select",
        col_13: "select",
        col_14: "select",
        col_15: "select",
        col_16: "select",
        col_17: "select",
        col_18: "select",
        col_19: "select",
        col_20: "select",
        col_21: "select",
        col_22: "select",
        col_23: "select",
        col_24: "select",
        btn: true  
    }  
    var tf03 = setFilterGrid("table1",1,table3Filters);  
</script>


<?php 
footer();
?>
