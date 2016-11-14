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
	width:200mm;

}

.footer  {
	width:200mm;
	margin:auto;
	text-align:center;

}

.tableau  {
	width:190mm;
	margin:auto;
	font-size:10pt;
	height:170mm;
	max-height:170mm;
}

p {
overflow: auto;
}

h1 {
	font-size:14pt;
	margin:0 0 2px 0;
}
table {
border-collapse: collapse;;
}
-->
</style>
<?php 
$continue = true;

	if(isset($_GET['id'])) {
		$db->query("SELECT c.cid as cid, c.titreActivite as titre, t.nom as nom_transport, t.indication as indication, i.id as id_itineraire FROM etapes_rw e, checkpoint c, itineraire_rw i left join transport t on t.id = i.id_depart WHERE i.id = ".$_GET['id']." and e.id_itineraire = i.id and e.id_checkpoint = c.id and e.position = 0;");
	} else {
		$db->query("SELECT c.cid as cid, c.titreActivite as titre, t.nom as nom_transport, t.indication as indication, i.id as id_itineraire FROM etapes_rw e, checkpoint c, itineraire_rw i left join transport t on t.id = i.id_depart WHERE e.id_itineraire = i.id and e.id_checkpoint = c.id and e.position = 0 and i.id between ".$_GET['min']." and ".$_GET['max']." ORDER BY i.id asc ");
	}
if ($db->get_num_rows() >0) {
$o = $db->fetchNextObject();
	while($o != false) {
	
		?>
	
	<page>
	<div style="margin: auto; text-align: center;">
		<img src="img/etat/head_imaginaire.png" class="header" />
	
		<?php
		$db2 = new DB();
		$db2->query("SELECT * FROM parameters where id='texte_odm'");
		$resultat = '';
		if ($db2->get_num_rows() > 0) {
			$o2 = $db2->fetchNextObject();
			$resultat = stripslashes($o2->value);
		}
		
		echo'<h1>Ordre de Mission : Equipe '.$o->id_itineraire.'</h1>';
		echo'<div class="tableau"><table><tr><td>';
		echo '<p>'.$resultat.'</p>';
		echo'<p>La premi&egrave;re &eacute;tape de votre parcours sera : '.$o->cid.'</p>';
		echo'<p>Pour pouvoir vous rendre &agrave; cette &eacute;tape, vous devrez emprunter le moyen de transport suivant : '.$o->nom_transport.' Pour cela vous devrez suivre les indications suivantes : <br />'.$o->indication.'</p>';
		echo'</td></tr></table></div>';
		?>
		<img src="img/etat/footer_imaginaire.png"
			style="width: 210mm; margin: auto; text-align: left;" />
	</div>
	</page>
		<?php
		$o = $db->fetchNextObject();
	}
} else {
	?>
	<page>Requete vide !</page>
	<?php 
}


?>