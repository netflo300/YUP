<?php
session_start();
require_once ("class/db.class.php");
require_once ("lib/fonctions.php");
if (!auth()) {
  Header("Location:login.php");
  die;
}
$db = new Db();

/*for ($i=1001;$i<=1382;$i++) {
	echo "INSERT INTO itineraire_rw (id_origine, nom, distance, etat, created_by)
	SELECT id, nom, distance, 'Copi&eacute;', 'REPRISE' FROM itineraire where id = '".$i."' ;"."<br />";
	$db->query("INSERT INTO itineraire_rw (id_origine, nom, distance, etat, created_by)
	SELECT id, nom, distance, 'Copi&eacute;', 'REPRISE' FROM itineraire where id = '".$i."' ;");
	$last_id = $db->lastInsertedId();
	
	echo"INSERT INTO etapes_rw (id_itineraire, id_checkpoint, position)
	SELECT ".$last_id.", id_checkpoint, position FROM etapes where id_itineraire = '".$i."' ;"."<br />";
	$db->query("INSERT INTO etapes_rw (id_itineraire, id_checkpoint, position)
	SELECT ".$last_id.", id_checkpoint, position FROM etapes where id_itineraire = '".$i."' ;");
	determiner_point_transport($last_id);
}*/

for ($i=0;$i<=2500;$i++) {
	$db->query("SELECT id_checkpoint from etapes_rw where id_itineraire = ".$i." ORDER BY position asc;");
	echo "SELECT id_checkpoint from etapes_rw where id_itineraire = ".$i." ORDER BY position asc;";
	$checksum = '';
	if ($db->get_num_rows() >0) {
		foreach ($db->fetch_array() as $k => $v) {
			$checksum .= $v['id_checkpoint'].';';
		}
		$db->query("UPDATE itineraire_rw set checksum='".$checksum."' where id =".$i." and checksum = '';");
	}
}


?>