<style type="text/Css">
<!--
.test1
{
    border: solid 1px #FF0000;
    background: #FFFFFF;
    border-collapse: collapse;
}

.header {
 top:0px;
 left:0px;
	width:210mm;

}

@font-face {
	font-family: codestrabourg;
  src: url(styles/CodeStrasbourg.ttf);
}

.footer  {
	width:210mm;
	margin:auto;
	text-align:left;

}

.tableau  {
	width:200mm;
	margin:auto;
	font-size:6pt;
	height:160mm;
	max-height:160mm;
	overflow: hidden;
}

h1 {
	font-size:8pt;
	margin:0 0 2px 0;
}
table {
border-collapse: collapse;;
}

.blue {
color:blue;
}

.red  {
color:red;
}

.green {
color:green;
}
-->
</style>
<?php 
if (isset($_GET['nb_ligne'])) {
	$nb_ligne = $_GET['nb_ligne'];
} else {
	$nb_ligne = 10;
	
}

if (isset($_GET['id'])) {
	$res = $db->query("SELECT * from checkpoint where id = ".$_GET['id'].";");
} else {
	$res = $db->query("SELECT * from checkpoint where id between ".$_GET['min']." and ".$_GET['max']." ;");
}

$tab = $db->fetch_array();
foreach($tab as $k => $v) {
	?>
	<page>
		<div style="margin: auto; text-align: center;">
			<img src="img/etat/header.png" class="header" />
			<?php 
			echo '<h1>Ordre de mission animateur pour le checkpoint '.$v['cid'].', '.$v['name'].' '.$v['titreActivite'].'</h1>';
			?>
			<p>RDV au point : <?php echo $v['adresse_postale'] ;?></p>
			<br />
			<img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $v['positionY'].','.$v['positionX'] ;?>&zoom=16&size=600x600&markers=color:green%7Clabel:G%7C<?php echo $v['positionY'].','.$v['positionX'] ;?>&sensor=true" />
	<br />
	<br />
	<br />
	<br />
	</div>
	</page>
	<?php 
		$db->query("SELECT c.cid, e.id_itineraire as id_itineraire, e.position as position, c2.cid as cid_suivant
			FROM checkpoint c, ".TABLE_NAME_ETAPE." e 
					join ".TABLE_NAME_ETAPE." e2 on e2.id_itineraire = e.id_itineraire and e2.position = (e.position+1) 
					left join checkpoint c2 on c2.id = e2.id_checkpoint 
					left join code co on co.id_checkpoint = c2.id 
			WHERE e.id_checkpoint = c.id and c.id=".$v['id']."  
			ORDER BY c.id,e.position asc");
		$tab3 = $db->fetch_array();
	
		$db->query("SELECT c.cid, e.id_itineraire as id_itineraire, e.position as position, 'TRANSPORT' as cid_suivant
			FROM checkpoint c, ".TABLE_NAME_ETAPE." e 
					left join ".TABLE_NAME_ETAPE." e2 on e2.id_itineraire = e.id_itineraire and e2.position = (e.position+1) 
					join ".TABLE_NAME_ITINERAIRE." i on i.id = e.id_itineraire
					join transport t on t.id = i.id_arrivee
	 		WHERE e.id_checkpoint = c.id  
					and c.id=".$v['id']."
					and e2.id_checkpoint is null
			ORDER BY c.id,e.position desc");
		$tab2 = $db->fetch_array();
		if (!empty($tab2)) {
			$tab3 = array_merge($tab3, $tab2);
		}
		$count=0;
		$tags_open = false;
		$big_tags_open = false;
		if (!empty($tab3)) {
			foreach($tab3 as $k2 => $v2) {
				if ($count%120==0) {
					?>
					<page>
					<div style="margin: auto; text-align: center;">
						<?php echo '<h1>Ordre de mission animateur pour le checkpoint '.$v['cid'].', '.$v['name'].' '.$v['titreActivite'].'</h1>';?>
						<br />
							<br />
							<div class="tableau">
								<table><tr>
								<?php 
								$big_tags_open = true;
				}
								if ($count%40==0) {
								?>
								<td>
								<table border="1" id="table1">
									<tr>
										<th style="width: 10mm;">Id de l'Equipe</th>
										<th style="width: 10mm;">Rang</th>
										<th style="width: 30mm;">CID du Checkpoint Suivant</th>
									</tr>
					<?php
					$tags_open = true;
				}
				echo '<tr><td style="width:10mm;">'.$v2['id_itineraire'].'</td><td style="width:10mm;">'.$v2['position'].'</td><td style="width:30mm;">'.$v2['cid_suivant'].'</td></tr>';
				$count++;
				if ($count%40==0) {
					?>
					</table></td>
					<?php 
					
					$tags_open = false;
				}
				
				if ($count%120==0) {
					?>
					</tr></table>
					</div>
					<br/>
					</div>
					</page>
					<?php 
						$big_tags_open = false;
					
				}
			}
		}
		if($tags_open==true) {
			echo'</table></td>';
		}
		if ($big_tags_open == true) {
			echo'</tr></table></div><br /></div></page>';
			$tags_open = false;
		}
		
		
		$db->query("SELECT c.cid, co.phrase_claire as phrase_claire, co.phrase_intermediaire as phrase_intermediaire , co.phrase_azimut as phrase_azimut, co.phrase_code as phrase_code, co.clef_grenouille as clef_grenouille, co.clef_strasbourg as clef_strasbourg, e.position as position, e.id_itineraire as id_itineraire, c2.cid as cid_suivant
			FROM checkpoint c, ".TABLE_NAME_ETAPE." e 
					join ".TABLE_NAME_ETAPE." e2 on e2.id_itineraire = e.id_itineraire and e2.position = (e.position+1) 
					left join checkpoint c2 on c2.id = e2.id_checkpoint 
					left join code co on co.id_checkpoint = c2.id 
					WHERE e.id_checkpoint = c.id and c.id=".$v['id']."  ORDER BY c.id,e.position asc");
		$tab3 = $db->fetch_array();
	
		$db->query("SELECT c.cid, t.indication as phrase_claire, 'TRANSPORT' as phrase_intermediaire , '' as phrase_azimut, '' as phrase_code, '' as clef_grenouille, '' as clef_strasbourg, e.position as position, e.id_itineraire as id_itineraire, 'TRSP' as cid_suivant
			FROM checkpoint c, ".TABLE_NAME_ETAPE." e 
					left join ".TABLE_NAME_ETAPE." e2 on e2.id_itineraire = e.id_itineraire and e2.position = (e.position+1) 
					join ".TABLE_NAME_ITINERAIRE." i on i.id = e.id_itineraire
					join transport t on t.id = i.id_arrivee
	 
					WHERE e.id_checkpoint = c.id  
					and c.id=".$v['id']."
					and e2.id_checkpoint is null
			ORDER BY c.id,e.position desc");
		$tab2 = $db->fetch_array();
		if (!empty($tab2)) {
			$tab3 = array_merge($tab3, $tab2);
		}
		$count=0;
		$tags_open = false;
		if (!empty($tab3)) {
			foreach($tab3 as $k2 => $v2) {
				if ($count%$nb_ligne==0) {
					?>
					<page>
					<div style="margin: auto; text-align: center;">
						<?php echo '<h1>Ordre de mission animateur pour le checkpoint '.$v['cid'].', '.$v['name'].' '.$v['titreActivite'].'</h1>';?>
						<br />
							<br />
							<div class="tableau">
								<table border="1" id="table1">
									<tr>
										<th style="width: 10mm;">CID</th>
										<th style="width: 10mm;">Equipe</th>
										<th style="width: 10mm;">Point suivant</th>
										<th style="width: 160mm;">Instructions</th>
									</tr>
					<?php
					$tags_open = true;
				}
				if ($v2['position'] == 0) {
					if ($v2['phrase_azimut'] != '') {
						$phrase = $v2['phrase_intermediaire'] . '<br />PUIS : <br /><span class="blue">'.$v2['phrase_azimut'].'</span>';
					} else if ($v2['phrase_code'] !='' && $v2['clef_grenouille'] !='') {
						$phrase = $v2['phrase_intermediaire'] . '<br />PUIS : <br /><span class="green">'.convert_grenouille($v2['clef_grenouille'],stripslashes(html_entity_decode($v2['phrase_code']))).'</span>';
					}	else if ($v2['phrase_code'] !='' && $v2['clef_strasbourg'] !='') {
						$phrase = $v2['phrase_intermediaire'] . '<br />PUIS : <br /><span style="color:red;font-family:codestrabourg;font-size:24pt;" class="red">'.convert_avocat(stripslashes(html_entity_decode($v2['phrase_code'])),$v2['clef_strasbourg']).'</span>';
					} else {
						$phrase = $v2['phrase_claire'];
					}
				}	else if ($v2['position'] == 2) {
					if ($v2['phrase_code'] !='' && $v2['clef_grenouille'] !='') {
						$phrase = $v2['phrase_intermediaire'] . '<br />PUIS : <br /><span class="green">'.convert_grenouille($v2['clef_grenouille'],stripslashes(html_entity_decode($v2['phrase_code']))).'</span>';
					}	else if ($v2['phrase_code'] !='' && $v2['clef_strasbourg'] !='') {
						$phrase = $v2['phrase_intermediaire'] . '<br />PUIS : <br /><span style="color:red;font-family:codestrabourg;font-size:24pt;" class="red">'.convert_avocat(stripslashes(html_entity_decode($v2['phrase_code'])),$v2['clef_strasbourg']).'</span>';
					} else if ($v2['phrase_azimut'] != '') {
						$phrase = $v2['phrase_intermediaire'] . '<br />PUIS : <br /><span class="blue">'.$v2['phrase_azimut'].'</span>';
					} else {
						$phrase = $v2['phrase_claire'];
					}
				}	else if ($v2['position'] == 4) {
						if ($v2['phrase_code'] !='' && $v2['clef_strasbourg'] !='') {
						$phrase = $v2['phrase_intermediaire'] . '<br />PUIS : <br /><span style="color:red;font-family:codestrabourg;font-size:24pt;" class="red">'.convert_avocat(stripslashes(html_entity_decode($v2['phrase_code'])),$v2['clef_strasbourg']).'</span>';
					} else if ($v2['phrase_azimut'] != '') {
						$phrase = $v2['phrase_intermediaire'] . '<br />PUIS : <br /><span class="blue">'.$v2['phrase_azimut'].'</span>';
					} else if ($v2['phrase_code'] !='' && $v2['clef_grenouille'] !='') {
						$phrase = $v2['phrase_intermediaire'] . '<br />PUIS : <br /><span class="green">'.convert_grenouille($v2['clef_grenouille'],stripslashes(html_entity_decode($v2['phrase_code']))).'</span>';
					} else {
						$phrase = $v2['phrase_claire'];
					}
				} else {
					$phrase = $v2['phrase_claire'];
				}
				
				echo '<tr><td style="width:10mm;">'.$v2['cid'].'</td><td style="width:10mm;">'.$v2['id_itineraire'].'</td><td style="width:10mm;">'.$v2['cid_suivant'].'</td><td style="width:160mm;">'.$phrase.'</td></tr>';
				$count++;
				if ($count%$nb_ligne==0) {
					?>
					</table>
					</div>
					<br/>
					</div>
					</page>
					<?php
					$tags_open = false;
				}
			}
		}
		if($tags_open==true) {
			echo'</table></div><br /></div></page>';
			$tags_open = false;
		}
}
?>
