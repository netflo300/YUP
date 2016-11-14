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
-->
</style>
<?php 
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
			echo '<h1>Ordre de mission animateur pour le checkpoint '.$v['cid'].', intitul&eacute; '.$v['titreActivite'].'</h1>';
			?>
			<p>RDV au point : <?php echo $v['adresse_postale'] ;?></p>
			<br />
			<img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $v['positionY'].','.$v['positionX'] ;?>&zoom=16&size=600x600&markers=color:green%7Clabel:G%7C<?php echo $v['positionY'].','.$v['positionX'] ;?>&sensor=true" />
	<br />
	Adresse : 
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
		if (!empty($tab3)) {
			foreach($tab3 as $k2 => $v2) {
				if ($count%10==0) {
					?>
					<page>
					<div style="margin: auto; text-align: center;">
						<?php echo '<h1>Ordre de mission animateur pour le checkpoint '.$v['cid'].', intitul&eacute; '.$v['titreActivite'].'</h1>';?>
						<br />
							<br />
							<div class="tableau">
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
				if ($count%10==0) {
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
		
		
		$db->query("SELECT c.cid, co.phrase_claire as phrase_claire, co.phrase_crypte as phrase_crypte, e.position as position
			FROM checkpoint c, ".TABLE_NAME_ETAPE." e 
					join ".TABLE_NAME_ETAPE." e2 on e2.id_itineraire = e.id_itineraire and e2.position = (e.position+1) 
					left join checkpoint c2 on c2.id = e2.id_checkpoint 
					left join code co on co.id_checkpoint = c2.id 
					WHERE e.id_checkpoint = c.id and c.id=".$v['id']."  ORDER BY c.id,e.position asc");
		$tab3 = $db->fetch_array();
	
		$db->query("SELECT c.cid, t.indication as phrase_claire, 'TRANSPORT' as phrase_crypte, e.position as position
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
				if ($count%10==0) {
					?>
					<page>
					<div style="margin: auto; text-align: center;">
						<?php echo '<h1>Ordre de mission animateur pour le checkpoint '.$v['cid'].', intitul&eacute; '.$v['titreActivite'].'</h1>';?>
						<br />
							<br />
							<div class="tableau">
								<table border="1" id="table1">
									<tr>
										<th style="width: 10mm;">CID</th>
										<th style="width: 60mm;">Instructions</th>
									</tr>
					<?php
					$tags_open = true;
				}
				if ($v2['position'] == 1 || $v2['position'] == 4) {
					$phrase = $v2['phrase_crypte'];
				} else {
					$phrase = $v2['phrase_claire'];
				}
				
				echo '<tr><td style="width:10mm;">'.$v2['cid'].'</td><td style="width:60mm;">'.$phrase.'</td></tr>';
				$count++;
				if ($count%10==0) {
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
