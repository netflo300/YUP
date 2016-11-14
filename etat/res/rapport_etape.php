


<style type="text/Css">
<!--
.test1
{
    border: solid 1px #FF0000;
    background: #FFFFFF;
    border-collapse: collapse;
}
-->
</style>
<?php 
$limit_min = 0;
$limit_max = NB_LIGNES_PAR_PAGE;
$continue = true;
while($continue == true) {
?>

	<page>
	<div style="margin:auto;text-align:center;">
	<img src="img/etat/header.png" style="width:210mm; "/>
	<?php
	if(isset($_GET['id'])) {
		$db->query("SELECT id FROM itineraire WHERE id = ".$_GET['id'].";");
		$continue = false;
	} else {
		$db->query("SELECT id FROM itineraire limit ".$limit_min.",".$limit_max.";");
	}
	if ($db->get_num_rows() == 0) {
		$continue = false;
		break;
	}
	echo '<table border="1" id="table1" style="width:200mm; font-size:6pt;margin:auto;">';
	echo '<tr><th>Id</th><th style="width:2mm;">Etape 1</th><th style="width:30mm;">Etape 2</th><th style="width:30mm;">Etape 3</th><th style="width:30mm;">Etape 4</th><th style="width:30mm;">Etape 5</th><th style="width:30mm;">Etape 6</th><th style="width:30mm;">Etape 7</th></tr>';
	
	
	$tab = $db->fetch_array();
	foreach ($tab as $k => $v) {
		echo '<tr><td style="width:2mm;"><a href="detailsItineraire.php?id_itineraire='.$v['id'].'" target="blank">'.$v['id'].'</a></td>';
		$db->query("SELECT etapes.position as position, checkpoint.*  
		FROM etapes, checkpoint
		WHERE etapes.id_checkpoint = checkpoint.id
		AND etapes.id_itineraire = ".$v['id']."
		ORDER BY etapes.position;");
		foreach ($db->fetch_array() as $k2 => $v2) {
			echo '<td style="width:30mm;">'.display_checkpoint(array('id'=>$v2['id'],'name'=>$v2['name'], 'titre'=>$v2['titreActivite'], 'cid'=>$v2['cid'])).'</td>';
		}
		echo'</tr>';
	}
	echo '</table>';
	
	$limit_min += NB_LIGNES_PAR_PAGE;
	$limit_max += NB_LIGNES_PAR_PAGE 
	
	?>
	<img src="img/etat/footer.png" style="width:210mm;margin:auto;text-align:center;"/>
	</div>
	</page>
<?php }?>