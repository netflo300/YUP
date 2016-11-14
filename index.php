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
entete("Accueil");
include ("header.php");
$db->query("SHOW COLUMNS FROM checkpoint;");
echo '<table border="1" id="table1">';
echo '<caption>Liste des CheckPoint</caption>';
echo '<tr>';
foreach ($db->fetch_array() as $k => $v) {
	echo '<th>'.$v['Field'].'</th>';
}
echo'<th>Etat</th><th>Action</th></tr>';

$db->query("SELECT * FROM checkpoint;");
$tab = $db->fetch_array();

foreach ($tab as $k => $v) {
	$overbooke = false;
	$fullbooked = true;
	$no_booked = true;
	$info='';
	$db->query("SELECT count(*) as count, c.absorption as absorption FROM ".TABLE_NAME_ETAPE." e, checkpoint c where c.id = e.id_checkpoint and id_checkpoint = ".$v['id']." and position = 0;");
	$o = $db->fetchNextObject();
	$info .= 'Position 0 : '.$o->count.' / '.$o->absorption.'<br/>';
	$overbooke = ($overbooke===true || $o->count > $o->absorption);
	$fullbooked = ($fullbooked===true && $o->count == $o->absorption);
	$no_booked = ($no_booked===true && $o->count == 0);
	
	$db->query("SELECT count(*) as count, c.absorption as absorption FROM ".TABLE_NAME_ETAPE." e, checkpoint c where c.id = e.id_checkpoint and id_checkpoint = ".$v['id']." and position = 1;");
	$o = $db->fetchNextObject();
	$info .= 'Position 1 : '.$o->count.' / '.$o->absorption.'<br/>';
	$overbooke = ($overbooke===true || $o->count > $o->absorption);
	$fullbooked = ($fullbooked===true && $o->count == $o->absorption);
	$no_booked = ($no_booked===true && $o->count == 0);
	
	$db->query("SELECT count(*) as count, c.absorption as absorption FROM ".TABLE_NAME_ETAPE." e, checkpoint c where c.id = e.id_checkpoint and id_checkpoint = ".$v['id']." and position = 2;");
	$o = $db->fetchNextObject();
	$info .= 'Position 2 : '.$o->count.' / '.$o->absorption.'<br/>';
	$overbooke = ($overbooke===true || $o->count > $o->absorption);
	$fullbooked = ($fullbooked===true && $o->count == $o->absorption);
	$no_booked = ($no_booked===true && $o->count == 0);
	
	$db->query("SELECT count(*) as count, c.absorption as absorption FROM ".TABLE_NAME_ETAPE." e, checkpoint c where c.id = e.id_checkpoint and id_checkpoint = ".$v['id']." and position = 3;");
	$o = $db->fetchNextObject();
	$info .= 'Position 3 : '.$o->count.' / '.$o->absorption.'<br/>';
	$overbooke = ($overbooke===true || $o->count > $o->absorption);
	$fullbooked = ($fullbooked===true && $o->count == $o->absorption);
	$no_booked = ($no_booked===true && $o->count == 0);
	
	$db->query("SELECT count(*) as count, c.absorption as absorption FROM ".TABLE_NAME_ETAPE." e, checkpoint c where c.id = e.id_checkpoint and id_checkpoint = ".$v['id']." and position = 4;");
	$o = $db->fetchNextObject();
	$info .= 'Position 4 : '.$o->count.' / '.$o->absorption.'<br/>';
	$overbooke = ($overbooke===true || $o->count > $o->absorption);
	$fullbooked = ($fullbooked===true && $o->count == $o->absorption);
	$no_booked = ($no_booked===true && $o->count == 0);
	
	$db->query("SELECT count(*) as count, c.absorption as absorption FROM ".TABLE_NAME_ETAPE." e, checkpoint c where c.id = e.id_checkpoint and id_checkpoint = ".$v['id']." and position = 5;");
	$o = $db->fetchNextObject();
	$info .= 'Position 5 : '.$o->count.' / '.$o->absorption.'<br/>';
	$overbooke = ($overbooke===true || $o->count > $o->absorption);
	$fullbooked = ($fullbooked===true && $o->count == $o->absorption);
	$no_booked = ($no_booked===true && $o->count == 0);
	
	$db->query("SELECT count(*) as count, c.absorption as absorption FROM ".TABLE_NAME_ETAPE." e, checkpoint c where c.id = e.id_checkpoint and id_checkpoint = ".$v['id']." and position = 6;");
	if ($db->get_num_rows()>0) {
		$o = $db->fetchNextObject();
		$info .= 'Position 6 : '.$o->count.' / '.$o->absorption.'<br/>';
		$overbooke = ($overbooke===true || $o->count > $o->absorption);
		$fullbooked = ($fullbooked===true && $o->count == $o->absorption);	
		$no_booked = ($no_booked===true && $o->count == 0);
	}
	echo '<tr class="'.($overbooke?'rouge':($fullbooked?'orange':($no_booked?'bleu':''))).'"><td onmouseover="montre(\''.$info.'\');"  onmouseout="cache();">'.$v['id'].'</td><td>'.$v['cid'].'</td><td>'.$v['titreActivite'].'</td><td>'.$v['nombreEquipe'].'</td><td>'.$v['absorption'].'</td><td>'.$v['positionX'].'</td><td>'.$v['positionY'].'</td><td>'.$v['duree'].'</td>
	<td>'.$v['originalite'].'</td><td>'.$v['obligatoire'].'</td><td>'.$v['regionnal'].'</td><td>'.$v['europeen'].'</td><td>'.$v['interaction'].'</td><td>'.$v['vivreEnsemble'].'</td><td>'.$v['creatif'].'</td>
	<td>'.$v['intellectuel'].'</td><td>'.$v['physique'].'</td><td>'.$v['theatre'].'</td><td>'.$v['stimulante'].'</td><td>'.$v['fierte'].'</td><td>'.$v['funitude'].'</td><td>'.$v['confrontation'].'</td>
	<td>'.$v['mobilisation'].'</td><td>'.$v['proximite'].'</td><td>'.$v['institution'].'</td><td>'.$v['kehl'].'</td><td>'.$v['groupe'].'</td><td>'.$v['name'].'</td>
	<td>'.($overbooke?'over-plein':($fullbooked?'plein':($no_booked?'vide':'partiellement plein'))).'</td><td><a href="rapport_fiche_chef.php?id='.$v['id'].'" target="blank"><img src="img/file_pdf.png" alt="exporter le rapport" class="icone" /></a></td>
	</tr>';
}
echo '</tr>';
echo '</table>';

?>
<script language="javascript" type="text/javascript">  
    var table3Filters = {  
        col_0: "select",  
        col_1: "select",
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
        col_25: "select",
        col_28: "select",
        btn: true  
    }  
    var tf03 = setFilterGrid("table1",1,table3Filters);  
</script>


<?php 
footer();
?>
