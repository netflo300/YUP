<?php
session_start();
define("ENCOURS_STATUS", "En cours");
define("VALIDE_STATUS", "Valid&eacute;");
define("UNVALIDE_STATUS", "Invalid&eacute;");
define("COPIE_STATUS", "Copi&eacute;");

require_once ("class/db.class.php");
require_once ("lib/fonctions.php");
if (!auth()) {
  Header("Location:login.php");
  die;
}
$db = new Db();
$message = '';
if (isset($_GET['id_delete_etape'])) {
	$db->query("DELETE FROM etapes_rw where id_checkpoint=".$_GET['id_delete_etape']." and id_itineraire=".$_GET['id_itineraire']." ;");
	reorganise_etapes($_GET['id_itineraire']);
	Header("Location:edit_itineraire.php?id_itineraire=".$_GET['id_itineraire']);
}

if (isset($_GET['id_itineraire'])) {
	$id_itineraire = $_GET['id_itineraire'];
}
if (isset($_POST['modif_itineraire'])) {
	$id_itineraire = $_POST['id_itineraire'];
	$query = "REPLACE INTO etapes_rw (id_itineraire, id_checkpoint, position) ";
	$etapes = array();
	$checksum = '';
	foreach ($_POST as $k => $v) {
		if (strpos($k, 'etape') !== FALSE) {
			$etapes['$position']=$v;
			$position = str_replace('etape', '', $k);
			$db->query($query."VALUES (".$id_itineraire.",".$v.",".$position.");");
			$checksum .= $v.';';
		}
	}
	$query = "UPDATE itineraire_rw set checksum='".$checksum."' where id =".$id_itineraire.";";
	$db->query($query);
	$query = "SELECT '1' FROM itineraire_rw i WHERE i.id <> ".$id_itineraire." and checksum = '".$checksum."'";
	$db->query($query);
	if ($db->get_num_rows() > 0) {
		$message = message("Erreur", "itineraire en doublon", 1000);
	}
}
if (isset($_POST['modif_transport'])) {
	$id_itineraire = $_POST['id_itineraire'];
	$query = "UPDATE itineraire_rw SET id_depart = ".$_POST['id_depart'].", id_arrivee = ".$_POST['id_arrivee']." 
			WHERE id = ".$id_itineraire."; ";
	$db->query($query);
}

if (isset($_POST['modif_name'])) {
	$id_itineraire = $_POST['id'];
	$query = "UPDATE itineraire_rw SET nom = '".$_POST['nom']."' WHERE id = ".$id_itineraire."; ";
	$db->query($query);
}

if (isset($_POST['validate'])) {
	$id_itineraire = $_POST['id'];
	$query = "UPDATE itineraire_rw SET validated_by = '".$_SESSION['login']."', etat = '".VALIDE_STATUS."' WHERE id = ".$id_itineraire."; ";
	$db->query($query);
}

if (isset($_POST['invalidate'])) {
	$id_itineraire = $_POST['id'];
	$query = "UPDATE itineraire_rw SET validated_by = NULL, etat = '".UNVALIDE_STATUS."' WHERE id = ".$id_itineraire."; ";
	$db->query($query);
}

if (isset($_POST['pending'])) {
	$id_itineraire = $_POST['id'];
	$query = "UPDATE itineraire_rw SET created_by = '".$_SESSION['login']."', etat = '".ENCOURS_STATUS."' WHERE id = ".$id_itineraire."; ";
	$db->query($query);
}

?>
<!DOCTYPE html>
<html>
  <head>
    <style type="text/css">
      html, body, #map-canvas { height: 100%; margin: 0; padding: 0;}
    </style>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo GMAP_KEY; ?>"></script>
    <script type="text/javascript" src="scripts/prototype.js" ></script>
    <script type="text/javascript" src="scripts/dragdrop.js" ></script>
    <script type="text/javascript" src="scripts/scriptaculous.js" ></script>
    <script type="text/javascript" src="scripts/script.js" ></script>
<script type="text/javascript">
	var maMap = new Map();
	var maMapMarker = new Map();
	var markerT1, markerT2, markerT3, markerT4, markerT5, markerT6;
	var map;
	<?php 
	$db->query("SELECT  c.* FROM checkpoint c ;");
	//$db->query("SELECT max(id_itineraire) as maxi, count(*) as count, absorption, (absorption*7-count(*)) as diff,(count(*)/(absorption*7)*100) as perc, c.* FROM checkpoint c left join ".TABLE_NAME_ETAPE." e on c.id = e.id_checkpoint group by c.id, absorption ");
	$map =  '';
	$points = '';
	$tab = $db->fetch_array();
	foreach ($tab as $k => $v) {
		$db->query("SELECT e.position as position, count(*) as count FROM ".TABLE_NAME_ETAPE." e WHERE id_checkpoint = ".$v['id']." GROUP BY position ;");
		$tab3 = $db->fetch_array();
		$icone = '';
		if(empty($tab3)) {
			$icone = 'img/marker/purplecirclemarker.png';
		} else {
			foreach($tab3 as $k2 => $v2) {
				if($v2['count']>$v['absorption']) {
					$icone = 'img/marker/redcirclemarker.png';
				}
			}
			
			if ($icone=='') {
				foreach($tab3 as $k2 => $v2) {
					if((100*$v2['count']/$v['absorption'])>75) {
						$icone = 'img/marker/orangecirclemarker.png';
					}
				}
			}	
			if ($icone=='') {
				foreach($tab3 as $k2 => $v2) {
					if((100*$v2['count']/$v['absorption'])>50) {
						$icone = 'img/marker/yellowcirclemarker.png';
					}
				}
			}
			
			if ($icone=='') {
				if (count($tab3)<7) {
					$icone = 'img/marker/purplecirclemarker.png';
				}
			}
			
			if ($icone=='') {
				foreach($tab3 as $k2 => $v2) {
					if((100*$v2['count']/$v['absorption'])<25) {
						$icone = 'img/marker/bluecirclemarker.png';
					}
				}
			}
			
			if ($icone=='') {
				foreach($tab3 as $k2 => $v2) {
					if((100*$v2['count']/$v['absorption'])<45) {
						$icone = 'img/marker/lightbluecirclemarker.png';
					}
				}
			}
			if ($icone=='') {
				$icone = 'img/marker/greencirclemarker.png';
			}
		}
		
		$map .= 'maMap.set('.$v['id'].',new google.maps.LatLng('.$v['positionY'].','.$v['positionX'].'));'."\n";
		$points .= 'var etape'.$k.' = new google.maps.Marker({position: { lat: '.$v['positionY'].', lng: '.$v['positionX'].'}, map: map, title: "'.display_checkpoint(array('id'=>$v['id'],'name'=>$v['name'], 'titre'=>$v['titreActivite'], 'cid'=>$v['cid'])).' ('.$v['duree'].')", icon: "'.$icone.'"});'."\n";
		$points.='maMapMarker.set('.$v['id'].',etape'.$k.');';
		$db->query("SELECT position, count( * ) AS count, c.absorption AS absorption FROM checkpoint c join ".TABLE_NAME_ETAPE." e on e.id_checkpoint = c.id  WHERE id_checkpoint = ".$v['id']." GROUP BY position ASC");
		$tab2 = array(0=>array(),1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array());
		if ($db->get_num_rows() > 0) {
			foreach ($db->fetch_array() as $k2 => $v2) {
				$tab2[$v2['position']] = $v2;
			}
		}
		$absorption = '<b>'.display_checkpoint(array('id'=>$v['id'],'name'=>$v['name'], 'titre'=>$v['titreActivite'], 'cid'=>$v['cid'])).'</b> ('.$v['duree'].')<br />';
		foreach ($tab2 as $k2 => $v2) {
			if (empty($v2)) {
				$absorption .= 'Position'.($k2+1). ' : 0 / '.$v['absorption'].'<br />';
			} else {
				$absorption .= 'Position'.($v2['position']+1). ' : '.$v2['count'].' / '.$v2['absorption'].'<br />';
			}
		}
		$points .= 'google.maps.event.addListener(etape'.$k.', \'click\', function() { new google.maps.InfoWindow({content: "'.$absorption.'"}).open(map,etape'.$k.');});'."\n";
	}
	
	echo $map;
	?>
      function initialize() {
        var mapOptions = {
          center: { lat: 48.583333, lng: 7.75},
          zoom: 14
        };
     map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

<?php get_transport_point();?>
        <?php 
    			$db->query("SELECT * FROM etapes_rw e, checkpoint where e.id_itineraire = ".$_GET['id_itineraire']." and e.id_checkpoint = checkpoint.id order by position ;");
    				$listPoint ='';
    				foreach ($db->fetch_array() as $k => $v) {
    					echo 'var marker'.$k.' = new google.maps.Marker({position: { lat: '.$v['positionY'].', lng: '.$v['positionX'].'}, map: map, title: "'.display_checkpoint(array('id'=>$v['id'],'name'=>$v['name'], 'titre'=>$v['titreActivite'], 'cid'=>$v['cid'])).'"});'."\n";
    					echo 'google.maps.event.addListener(marker'.$k.', \'click\', function() { new google.maps.InfoWindow({content: "Etape '.$v['position'].'"}).open(map,marker'.$k.');});'."\n";
    					echo 'var point'.$k.' =	new google.maps.LatLng('.$v['positionY'].', '.$v['positionX'].');';
    					$listPoint.=',point'.$v['position'];
    				}
    				?>
    				var flightPlanCoordinates = [<?php echo substr($listPoint, 1); ?>];
    				var flightPath = new google.maps.Polyline({
    				    path: flightPlanCoordinates,
    				    geodesic: true,
    				    strokeColor: '#FF0000',
    				    strokeOpacity: 1.0,
    				    strokeWeight: 2
    				  });
    				flightPath.setMap(map);
    				<?php echo $points; ?>
       }
      google.maps.event.addDomListener(window, 'load', initialize);

      var new_point;
      function ajouter_point(id) {
    	  new_point = new google.maps.Marker({position: maMap.get(eval(id)), map: map, title: "hello", icon:"http://gmaps-samples.googlecode.com/svn/trunk/markers/green/blank.png"});
      }

      function retirer_point(etape) {
      }

      function tout_cacher() {
    	  for (var valeur of maMapMarker.values()) {
    		  valeur.setMap(null);
    		}
      }

      function tout_afficher() {
    	  for (var valeur of maMapMarker.values()) {
    		  valeur.setMap(map);
    		}
      }
      
	 	 </script>
<link href="styles/style.css" rel="stylesheet" type="text/css" />
<link href="styles/filtergrid.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	body {
	 background-color:lightblue;
	}
	#map-canvas { border:1px solid black;}
	</style></head>
	 	 <body>
	 	 <div id="modal"><span style="display:inline;float:right;cursor:pointer;"><img onclick="close_modal();" src="img/fermer.png" /></span><div class="titre" onmousemove="deplace();"></div><div id="modal_contenu" class="contenu"></div></div>
	<div id="overlay"></div>
	<div id="curseur" class="infobulle"></div>
<?php 
include ("header.php");
echo $message;
echo '<div style="width:1000px;">';
$db->query("SELECT * FROM itineraire_rw where id = ".$id_itineraire);
$itineraire = $db->fetchNextObject();
echo '';
echo '';
echo '<table border="1">';
echo '<caption>Infos itineraires</caption>';
echo '<tr><td>Nom</td><td><form method="POST" action=""><input type="hidden" name="id" value = "'.$id_itineraire.'" /><input type="text" value="'.$itineraire->nom.'" name="nom" /><input type="submit" value="Modifier" name="modif_name" /></form></td></tr>';
echo '<tr><td>Status</td><td>'.$itineraire->etat.'</td></tr>';
echo '<tr><td>Cr&eacute; par</td><td>'.$itineraire->created_by.'</td></tr>';
echo '<tr><td>Valid&eacute; par</td><td>'.$itineraire->validated_by.'</td></tr>';
echo '<tr><td colspan="2"><form method="POST" action=""><input type="hidden" name="id" value = "'.$id_itineraire.'" />'.($itineraire->etat==VALIDE_STATUS?'<input type="submit" value="Invalider" name="invalidate" />':($itineraire->etat==COPIE_STATUS?'<input type="submit" value="Mettre en cours" name="pending" />':'<input type="submit" value="Valider" name="validate" />')).'</form></td></tr>';
echo '</table>';

generer_tableau_de_bord_etape($id_itineraire);

echo '<form method="POST" action="">';
echo '<input type="hidden" name="id_itineraire" value = "'.$id_itineraire.'" />';
echo '<table border="1" id="table1">';
echo '<caption>Details de l\'itineraire</caption>';
echo '<tr><th>Position</th><th>Etape</th><th><img src="img/ajouter.png" alt="" title="ajouter une etape" class="action" onclick="ajouter();"/></th></tr>';
$db->query("SELECT * FROM checkpoint ORDER BY id");
$tab_checkpoint = $db->fetch_array();
$select = '';
foreach ($tab_checkpoint as $k => $v) {
	$select .= '<option value=\''.$v['id'].'\'>'.display_checkpoint(array('id'=>$v['id'],'name'=>$v['name'], 'titre'=>$v['titreActivite'],'cid'=>$v['cid'])).'</option>';
}

$db->query("SELECT * FROM  etapes_rw e, checkpoint c where e.id_itineraire = '".$id_itineraire."' and e.id_checkpoint = c.id ORDER BY position;");
$tab = $db->fetch_array();
foreach ($tab as $k => $v) {
	$select_bis = str_replace('<option value=\''.$v['id'].'\'>'.display_checkpoint(array('id'=>$v['id'],'name'=>$v['name'], 'titre'=>$v['titreActivite'],'cid'=>$v['cid'])).'</option>', '<option selected=\'selected\'  value=\''.$v['id'].'\'>'.display_checkpoint(array('id'=>$v['id'],'name'=>$v['name'], 'titre'=>$v['titreActivite'],'cid'=>$v['cid'])).'</option>', $select);
	echo '<tr><td><span id="position'.$v['position'].'">Position '.($v['position'] + 1).'</span></td><td><select onchange=\'ajouter_point(this.value);\' name="etape'.$v['position'].'">'.$select_bis.'</select></td><td><a href="?id_delete_etape='.$v['id'].'&amp;id_itineraire='.$id_itineraire.'"><img src="img/fermer.png" alt="supprimer" /></a></td></tr>';
	$new_entry = '<tr><td>Position '.($v['position']+2).'</td><td><select onchange=\'ajouter_point(this.value);\' name=\'etape'.($v['position']+1).'\'>'.$select_bis.'</select></td><td></td></tr>';
}

echo '</table>';
echo '<input type="submit" value="Enregistrer" name="modif_itineraire" />';
echo '</form>';

echo '<form method="POST" action="">';
echo '<input type="hidden" name="id_itineraire" value = "'.$id_itineraire.'" />';
echo '<table border="1" id="table2" class="tab_right">';
echo '<caption>Transport</caption>';
$db->query("SELECT td.nom as departure, ta.nom as arrival, td.id as id_dep, ta.id as id_arr FROM itineraire_rw i, transport td, transport ta WHERE i.id = '".$id_itineraire."' and i.id_depart = td.id and i.id_arrivee = ta.id;");
$o = $db->fetchNextObject();
echo '<tr><td>Depart</td><td>'.get_transport_select_list('id_depart',$o->id_dep).'</td></tr>';
echo '<tr><td>Arriv&eacute;e</td><td>'.get_transport_select_list('id_arrivee',$o->id_arr).'</td></tr>';
echo '<tr><td colspan="2"><input type="submit" value="Enregistrer" name="modif_transport" /></td></tr>';
echo '</table>';
echo '</form>';
echo '</div>';



?>
<div id="map-canvas" style="width:1000px; height: 500px;"></div> 	 
<script type="text/javascript"> 
var dejaAjoute = false;
function ajouter() {
	if (dejaAjoute == true) {
		alert("Veuillez enregistrer avant d'ajouter une seconde etape");
		return;
	}
	contenu = "<?php echo stripslashes($new_entry); ?>";
	document.getElementById("table1").innerHTML += contenu;
	dejaAjoute = true;
}
</script>
<input type="button" onclick="tout_cacher();" value="cacher les autres marqueurs"/><input type="button" onclick="tout_afficher();" value="afficher les autres marqueurs"/>
<form method="GET" action="itineraire_rw.php">
<input type="hidden" name="id_delete" value="<?php echo $id_itineraire;?>" />
<input type="submit" value="Supprimer l'itineraire" />
</form>
<?php 
footer();
?>
