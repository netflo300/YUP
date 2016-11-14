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

if (isset($_POST['transport'])) {
	$query = "SELECT id FROM itineraire_rw WHERE 1=1 ";
	if (isset($_POST['min']) && $_POST['min']!= '') {
		$query .= " AND id >= ".$_POST['min'];
	}
	if (isset($_POST['max'])  && $_POST['max']!= '') {
		$query .= " AND id <= ".$_POST['max'];
	}
	
	$db->query($query);
	if($db->get_num_rows() >0) {
		if(isset($_POST['capacite'])) {
			$capacite = true;
		} else {
			$capacite = false;
		}
	if(isset($_POST['simulation'])) {
			$simulation = true;
		} else {
			$simulation = false;
		}
		
		foreach ($db->fetch_array() as $k => $v) {
			if ($simulation === true) {
				echo determiner_point_transport($v['id'],$capacite,$simulation)."\n";
			} else {
				determiner_point_transport($v['id'],$capacite,$simulation);
			}
		}
	} 
	header("Location:maintenance.php");die;
}

if (isset($_POST['duplication'])) {
	$query = "SELECT id FROM itineraire_rw WHERE etat <> 'Copi&eacute;' and id_duplicated = 0 ";
	if (isset($_POST['min']) && $_POST['min']!= '') {
		$query .= " AND id >= ".$_POST['min'];
	}
	if (isset($_POST['max'])  && $_POST['max']!= '') {
		$query .= " AND id <= ".$_POST['max'];
	}
	if (isset($_POST['capacite'])  && $_POST['capacite']!= '') {
		$query .= " LIMIT 0, ".$_POST['capacite'];
	}
	
	$db->query($query);
	if($db->get_num_rows() > 0) {
		$tab = $db->fetch_array();
		foreach ($tab as $k => $v) {
			$db->query("INSERT into itineraire_rw (id_origine, nom, distance, etat, created_by, id_depart, id_arrivee, validated_by, checksum, id_duplicated)
			 (SELECT id_origine, nom, distance, etat, 'duplication', id_depart, id_arrivee, validated_by, checksum, ".$v['id']." FROM itineraire_rw where id = ".$v['id'].");");
			$id = $db->lastInsertedId();
			$db->query("INSERT into etapes_rw (id_itineraire, id_checkpoint, position)
			 (SELECT ".$id.", id_checkpoint, position FROM etapes_rw where id_itineraire = ".$v['id'].");");
		}
	}if ($simulation === true) {
		die;
	} else {
		header("Location:maintenance.php");die;
	}
}


entete("Maintenance");
include ("header.php");
?>
<h1>Maintenance</h1>
<h2>R&eacute;-indexer le transport des parcours</h2>
<form method="POST" action="">
<table>
<tr><td><label>id min :</label></td><td><input type="text" name="min" /></td></tr>
<tr><td><label>id max :</label></td><td><input type="text" name="max" /></td></tr>
<tr><td><label>Tenir compte de la limite de capacit&eacute;</label></td><td><input type="checkbox" value="1" name="capacite" /></td></tr>
<tr><td><label>Mode simulation :</label></td><td><input type="checkbox" value="1" name="simulation" /></td></tr>
<tr><td colspan="2"><input type="submit" value="Executer" name="transport" /></td></tr>
</table>
</form>

<h2>Duplication des parcours</h2>
<form method="POST" action="">
<table>
<tr><td><label>id min :</label></td><td><input type="text" name="min" /></td></tr>
<tr><td><label>id max :</label></td><td><input type="text" name="max" /></td></tr>
<tr><td><label>Nombre max de duplication</label></td><td><input type="text" name="capacite" /></td></tr>
<tr><td colspan="2"><input type="submit" value="Executer" name="duplication" /></td></tr>
</table>
</form>

<?php 
footer();
?>