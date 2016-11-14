<style type="text/Css">
<!--
.test1
{
    border: solid 1px #FF0000;
    background: #FFFFFF;
    border-collapse: collapse;
}

.header  {
	width:210mm;

}

.footer  {
	width:210mm;
	margin:auto;
	text-align:center;

}

.tableau  {
	width:200mm;
	margin:auto;
	font-size:6pt;
	height:100mm;
	max-height:100mm;
}

table {
border-collapse: collapse;;
}
-->
</style>
<?php 
$query = "SELECT id FROM ".TABLE_NAME_ITINERAIRE." WHERE 1=1" ;
if (isset($_GET['min'])) {
	$query .= " AND id >= ".$_GET['min'];
}
if (isset($_GET['max'])) {
	$query .= " AND id <= ".$_GET['max'];
}
$db->query($query);
$tags_open = false;
if($db->get_num_rows() > 0) {
	$count = 0;
	$tab = $db->fetch_array();
	foreach($tab as $k => $v) {
		if($count%$nb_ligne ==0) {
			$tags_open = true;
			?>
			<page>
			<div style="margin: auto; text-align: center;font-size:5pt;">
			<?php
			echo'<table border="1">';
			echo '<tr><th>Id</th><th style="width:2mm;">Aller</th><th style="width:2mm;">Etape 1</th><th style="width:30mm;">Etape 2</th><th style="width:30mm;">Etape 3</th><th style="width:30mm;">Etape 4</th><th style="width:30mm;">Etape 5</th><th style="width:30mm;">Etape 6</th><th style="width:30mm;">Etape 7</th><th style="width:2mm;">Retour</th></tr>';
		}
		$count++;
		echo '<tr><td style="width:2mm;">'.$v['id'].'</td>';
		
		$db->query("SELECT t.nom as nom FROM ".TABLE_NAME_ITINERAIRE." i, transport t WHERE t.id = i.id_depart and i.id = ".$v['id']);
		$o = $db->fetchNextObject();
		echo '<td style="width:25mm;">'.$o->nom.'</td>';
		
		$db->query("SELECT etapes.position as position, checkpoint.*  
		FROM ".TABLE_NAME_ETAPE." etapes , checkpoint
		WHERE etapes.id_checkpoint = checkpoint.id
		AND etapes.id_itineraire = ".$v['id']."
		ORDER BY etapes.position;");
		foreach ($db->fetch_array() as $k2 => $v2) {
			echo '<td style="width:30mm;">'.display_checkpoint(array('id'=>$v2['id'],'name'=>$v2['name'], 'titre'=>$v2['titreActivite'], 'cid'=>$v2['cid'])).'</td>';
		}
		
		$db->query("SELECT t.nom as nom FROM ".TABLE_NAME_ITINERAIRE." i, transport t WHERE t.id = i.id_arrivee and i.id = ".$v['id']);
		$o = $db->fetchNextObject();
		echo '<td style="width:25mm;">'.$o->nom.'</td>';
		
		echo'</tr>';
		
		if($count%$nb_ligne ==0) {
			$tags_open = false;
			echo'</table>';
			?>
			</div>
			</page>
			<?php 
		}
	}
}
if($tags_open===true) {
	echo'</table>';
	echo '</div></page>';
	$tags_open = false;
}?>