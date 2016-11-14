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
	$query = "REPLACE INTO transport (id,nom,positionX,positionY,indication,icone) VALUES (".$_POST['id'].",'".$_POST['nom']."',".$_POST['positionX'].",".$_POST['positionY'].",'".$_POST['indication']."','".$_POST['icone']."');";
	$db->query($query);
} else if (isset($_POST['new'])) {
	$query = "INSERT INTO transport (nom,positionX,positionY,indication,icone) VALUES ('".$_POST['nom']."',".$_POST['positionX'].",".$_POST['positionY'].",'".$_POST['indication']."','".$_POST['icone']."');";
	$db->query($query);
}
entete("Transport");
include ("header.php");
if(isset($_GET['id_delete'])) {
	$db->query("DELETE FROM transport WHERE id = ".$_GET['id_delete'].";");
	header("Location:transport.php");
}
if (isset($_GET['id'])) {
	$db->query("SELECT * FROM transport WHERE id = ".$_GET['id'].";");
	$o = $db->fetchNextObject();
	echo '<form action="transport.php" method="POST">';
	echo '<input type="hidden" name="id" value="'.$o->id.'" />';
	echo '<label>nom : </label><input type="text" name="nom" value="'.$o->nom.'" size="43"/><br/>';
	echo '<label>coordonn&eacute;es : </label><input size="15" type="text" name="positionY" value="'.$o->positionY.'" />;<input size="15" type="text" name="positionX" value="'.$o->positionX.'" /><br/>';
	echo '<textarea name="indication" cols="60" rows="5">'.$o->indication.'</textarea><br />';
	echo '<select name="icone">
			<option '.($o->icone=='img/tram.png'?'selected="selected"':'').' value="img/tram.png">Tram</option>
			<option '.($o->icone=='img/car.png'?'selected="selected"':'').' value="img/car.png">Car</option>
			</select>';
	echo '<br /><input type="submit" value="valider" />';
	echo '</form>';

} else if (isset($_GET['new'])) {
	echo '<form action="transport.php" method="POST">';
	echo '<input type="hidden" name="new" value="1" />';
	echo '<label>nom : </label><input type="text" name="nom" value="" size="43"/><br/>';
	echo '<label>coordonn&eacute;es : </label><input size="15" type="text" name="positionY" value="" />;<input size="15" type="text" name="positionX" value="" /><br/>';
	echo '<textarea name="indication" cols="60" rows="5"></textarea><br />';
	echo '<select name="icone">
			<option value="img/tram.png">Tram</option>
			<option value="img/car.png">Car</option>
			</select>';
	echo '<br /><input type="submit" value="valider" />';
	echo '</form>';

} else {
	$db->query ( "SHOW COLUMNS FROM transport;" );
	echo '<table border="1" id="table1">';
	echo '<caption>Liste des points de transports</caption>';
	echo '<tr>';
	foreach ( $db->fetch_array () as $k => $v ) {
		echo '<th>' . $v ['Field'] . '</th>';
	}
	echo '<th>Nombre de d&eacute;parts</th><th>Nombre d\'arriv&eacute;es</th><th><a href="?new=1"><img src="img/ajouter.png" /></a></th></tr>';
	
	$db->query ( "SELECT * FROM transport t;" );
	$tab = $db->fetch_array ();
	
	foreach ( $tab as $k => $v ) {
		$db->query("SELECT count(*) as count_depart from itineraire_rw where id_depart = ".$v['id'].";");
		$o = $db->fetchNextObject();
		$nb_depart = $o->count_depart;
		$db->query("SELECT count(*) as count_arrivee from itineraire_rw where id_arrivee = ".$v['id'].";");
		$o = $db->fetchNextObject();
		$nb_arrivee = $o->count_arrivee;
		
		echo '<tr><td>' . $v ['id'] . '</td><td>' . $v ['nom'] . '</td><td>' . $v ['positionX'] . '</td><td>' . $v ['positionY'] . '</td><td>' . $v ['indication'] . '</td><td><img src="' . $v ['icone'] . '" /></td><td>' . $v ['capacite'] . '</td><td>' . $nb_depart . '</td><td>' . $nb_arrivee . '</td><td><a href="?id=' . $v ['id'] . '"><img src="img/edit.png" /></a><a href="?id_delete=' . $v ['id'] . '"><img src="img/fermer.png" /></a></td></tr>';
	}
	echo '</tr>';
	echo '</table>';
	
	?>
<script language="javascript" type="text/javascript">  
    var table3Filters = {  
        col_0: "select",  
        col_1: "select",
        col_2: "select",
        col_3: "select",
        col_4: "none",
        col_5: "none",
        col_6: "none",
        col_7: "none",
        col_8: "none",
        col_9: "none",
        btn: true  
    }  
    var tf03 = setFilterGrid("table1",1,table3Filters);  
</script>


<?php
}
footer();
?>
