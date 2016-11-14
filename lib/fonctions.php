<?php 
function entete($titre) {
	$debug = 'POST : <br />';
	$debug.= str_replace("\n", '<br />', print_r($_POST, true)); 
	$debug.= '<br />GET : <br />';
	$debug.= str_replace("\n", '<br />', print_r($_GET, true)); 
	$debug.= '<br />SESSION : <br />';
	$debug.= str_replace("\n", '<br />', print_r($_SESSION, true)); 
	
	
  $res='';
  $res.='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
	$res.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">'."\n";
	$res.='<head>'."\n";
	$res.='<title>'.$titre.'</title>'."\n";
	$res.='<meta name="Robots" content="Index,Follow" />'."\n";
	$res.='<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />'."\n";
	$res.='<meta http-equiv="Content-Language" content="fr" />'."\n";
	$res.='<meta name="Author" content="Florian Royal" />'."\n";
	$res.='<meta name="Expires" content="never" />'."\n";
	$res.='<script type="text/javascript" src="scripts/prototype.js" ></script>'."\n";
	//$res.='<script type="text/javascript" src="scripts/builder.js" ></script>'."\n";
	//$res.='<script type="text/javascript" src="scripts/effects.js" ></script>'."\n";
	//$res.='<script type="text/javascript" src="scripts/controls.js" ></script>'."\n";
	$res.='<script type="text/javascript" src="scripts/dragdrop.js" ></script>'."\n";
	$res.='<script type="text/javascript" src="scripts/ajax.js" ></script>'."\n";
	$res.='<script type="text/javascript" src="scripts/scriptaculous.js" ></script>'."\n";
	//$res.='<script type="text/javascript" src="scripts/slider.js" ></script>'."\n";
	$res.='<script type="text/javascript" src="scripts/script.js" ></script>'."\n";
	//$res.='<script type="text/javascript" src="scripts/sound.js" ></script>'."\n";
	$res.='<script type="text/javascript" src="scripts/info_bulle.js" ></script>'."\n";
	$res.='<script type="text/javascript" src="scripts/tablefilter_all_min.js" ></script>'."\n";
	$res.='<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key='.GMAP_KEY.'"></script>'."\n";
	$res.=''."\n";
	
	$res.='<link href="styles/style.css" rel="stylesheet" type="text/css" />'."\n";
	$res.='<link href="styles/filtergrid.css" rel="stylesheet" type="text/css" />'."\n";
	$res.='<style type="text/css">
	body {
	 background-color:'.couleur_fond().';
	}
	</style>';
	$res.='</head>'."\n";
	$res.='<body style="">'."\n";
	$res.='<div id="modal"><span style="display:inline;float:right;cursor:pointer;"><img onclick="close_modal();" src="img/fermer.png" /></span><div class="titre" onmousemove="deplace();"></div><div id="modal_contenu" class="contenu"></div></div>'."\n";
	$res.='<div id="debug">'.$debug.'</div>'."\n";
	$res.='<div id="overlay"></div>'."\n";
	$res.='<div id="curseur" class="infobulle"></div>';
	echo $res ;
}

function message ($titre,$message,$time)
{
	$res='';
	$res.='<script>';
	$res.='  message(\''.$titre.'\',\''.$message.'\',\''.$time.'\');';
	$res.='</script>';
	return $res ;	
}

function couleur_fond() {
	return 'lightblue';
}

function clean_entry($var) {
	$resultat = strip_tags($var);
	$resultat = stripslashes($resultat);
	$resultat = str_replace('-', '', $resultat);
	$resultat = str_replace('*', '', $resultat);
	$resultat = str_replace('-', '', $resultat);
}

function footer() {
	$res='';
	$res.='</body>'."\n";
	$res.='</html>'."\n";
	echo $res ;
}

function auth(){	
  return isset($_SESSION['id']) ;
}

function display_checkpoint($v) {
	if (!isset($_SESSION['format'])) {
		$_SESSION['format'] = 1;
	}
	switch ($_SESSION['format']) {
		case 1:
			return $v['cid'].' - '.$v['titre'];
			break;	
		case 2:
			return $v['cid'].' - '.$v['titre'];
			break;	
		case 3:
			return $v['id'].' - '.$v['titre'];
			break;
		case 4:
			return $v['id'].' - '.$v['titre'].' - '.$v['name'];
			break;	
		
	}
}

function determiner_point_transport($id,$booking = false, $mode_simulation=false) {
	global $db;
	// depart
	$query = "SELECT positionX, positionY FROM etapes_rw e, checkpoint c where e.id_checkpoint = c.id and e.id_itineraire = ".$id." and position = 0";
	$db->query($query);
	$o = $db->fetchNextObject();
	$positionX = $o->positionX;
	$positionY = $o->positionY;
	$db->query("SELECT t.*, count(*) as count FROM transport t left join itineraire_rw i on t.id = i.id_depart group by i.id_depart;");
	$distance_precedente = null;
	$id_retenu = null;
	$nom_retenu = null;
	foreach ($db->fetch_array() as $k => $v) {
		if ($booking===true && $v['count'] >= $v['capacite'] ) {
			continue;
		}
		$distance = calcul_distance($positionX, $positionY, $v['positionX'], $v['positionY']);
		if ($distance_precedente == null || $distance < $distance_precedente) {
			$distance_precedente = $distance;
			$id_retenu = $v['id'];
			$nom_retenu = $v['nom'];
		}
	}
	if($mode_simulation === true) {
		echo 'Itineraire '.$id.' depart : '.$nom_retenu."\n";
	} else {
		$db->query("UPDATE itineraire_rw SET id_depart = '".$id_retenu."' WHERE id = ".$id." ;");
	}
	
	// depart arrivee
	$query = "SELECT positionX, positionY FROM etapes_rw e, checkpoint c where e.id_checkpoint = c.id and e.id_itineraire = ".$id." order by position desc";
	$db->query($query);
	$o = $db->fetchNextObject();
	$positionX = $o->positionX;
	$positionY = $o->positionY;
	$db->query("SELECT t.*, count(*) as count FROM transport t left join itineraire_rw i on t.id = i.id_arrivee group by i.id_arrivee;");
	$distance_precedente = null;
	$id_retenu = null;
	$nom_retenu = null;
	foreach ($db->fetch_array() as $k => $v) {
		if ($booking===true && $v['count'] >= $v['capacite'] ) {
			continue;
		}
		$distance = calcul_distance($positionX, $positionY, $v['positionX'], $v['positionY']);
		if ($distance_precedente == null || $distance < $distance_precedente) {
			//echo $distance_precedente . '->'.$distance;
			$distance_precedente = $distance;
			$id_retenu = $v['id'];
			$nom_retenu = $v['nom'];
		}
	}
	if($mode_simulation === true) {
		echo 'Itineraire '.$id.' arrivee : '.$nom_retenu;
	} else {
		$db->query("UPDATE itineraire_rw SET id_arrivee = '".$id_retenu."' WHERE id = ".$id." ;");
	}
}

function travaux() {
	if (EN_TRAVAUX == "OUI") {
		header("Location:travaux.php");
		die;
	}
}

function calcul_distance($x1, $y1, $x2, $y2) {
	$distance = sqrt(pow(($y2-$y1),2) + pow(($x2-$x1),2));
	return $distance;
}

function get_transport_point() {
	global $db;
	$query = "SELECT * FROM transport";
	$db->query($query);
	$res = '';
	foreach ($db->fetch_array() as $k => $v) {
		$res .= 'var marker'.$v['id'].' = new google.maps.Marker({'."\n";
		$res .= 'position: { lat: '.$v['positionY'].', lng: '.$v['positionX'].'},'."\n";
		$res .= 'map: map,'."\n";
		$res .= 'title: "'.$v['nom'].'",'."\n";
		$res .= 'icon: "'.$v['icone'].'"'."\n";
		$res .= '});'."\n";
	}
	echo $res;
}

function get_transport_select_list($name, $id_defaut) {
	global $db;
	$query = "SELECT * FROM transport";
	$db->query($query);
	$res = '<select name="'.$name.'">';
	foreach ($db->fetch_array() as $k => $v) {
		$res .= '<option '.($id_defaut==$v['id']?'selected="selected"':'').' value="'.$v['id'].'">'.$v['nom'].'</option>';
	}
	$res.='</select>';
	return $res;
}

function reorganise_etapes($id) {
	global $db;
	$query = "SELECT * FROM etapes_rw where id_itineraire = ".$id." ORDER by position ASC";
	$db->query($query);
	$tab = $db->fetch_array();
	foreach ($tab as $k => $v) {
		$db->query("UPDATE etapes_rw SET position = ".$k." WHERE id_itineraire = ".$id." and id_checkpoint = ".$v['id_checkpoint'].";");
	}
}

function generer_tableau_de_bord_etape($id) {
	global $db;
	$db->query("SELECT * FROM etapes_rw e, checkpoint c where e.id_itineraire = ".$id." and c.id = e.id_checkpoint order by position");
	$duree_total = 0;
	$is_original = false;
	$is_interpellation = false;
	$nb_activite_europeen = 0;
	$is_regionale = false;
	$nb_activite_pysique = 0;
	$is_creatif = false;
	$is_theatre = false;
	$is_mobilisation = false;
	$is_confrontation = false;
	$is_institution = false;
	$is_vivre_ensemble = false;
	foreach ($db->fetch_array() as $k => $v) {
		$duree_total += $v['duree'];
		if ($v['originalite'] == 'O2' || $v['originalite'] == 'O3' ) {
			$is_original = true;
		}
		if ($v['interaction'] == '1') {
			$is_interpellation = true;
		}
		if ($v['europeen'] == 'E2' || $v['europeen'] == 'E3' ) {
			$nb_activite_europeen++;
		}
		if ($v['regionnal'] == '1') {
			$is_regionale = true;
		}
		if ($v['physique'] == '1') {
			$nb_activite_pysique++;
		}
		if ($v['creatif'] == '1') {
			$is_creatif = true;
		}
		if ($v['theatre'] == '1') {
			$is_theatre = true;
		}
		if ($v['mobilisation'] == '1') {
			$is_mobilisation = true;
		}
		if ($v['confrontation'] == '1') {
			$is_confrontation = true;
		}
		if ($v['institution'] == '1') {
			$is_institution = true;
		}
		if ($v['vivreEnsemble'] == '1') {
			$is_vivre_ensemble = true;
		}
	}
	$res= '<table border="1" class="tab_right_haut">';
	$res.=  '<caption>Tableau de bord</caption>';
	$res.= '<tr><td>Dur&eacute; Totale</td><td>'.$duree_total.'</td><td>Originale</td><td>'.($is_original==true?'<span class="vert">OUI</span>':'<span class="rouge">NON</span>').'</td></tr>';
	$res.= '<tr><td>Regionale :</td><td>'.($is_regionale==true?'<span class="vert">OUI</span>':'<span class="rouge">NON</span>').'</td><td>Interpellation</td><td>'.($is_interpellation==true?'<span class="vert">OUI</span>':'<span class="rouge">NON</span>').'</td></tr>';
	$res.= '<tr><td>Nombre activité Europ&eacute;ene</td><td>'.$nb_activite_europeen.'</td><td>Nb activit&eacute; Physique</td><td>'.$nb_activite_pysique.'</td></tr>';
	$res.= '<tr><td>Cr&eacute;atif</td><td>'.($is_creatif==true?'<span class="vert">OUI</span>':'<span class="rouge">NON</span>').'</td><td>Theatre</td><td>'.($is_theatre==true?'<span class="vert">OUI</span>':'<span class="rouge">NON</span>').'</td></tr>';
	$res.= '<tr><td>Mobilisation</td><td>'.($is_mobilisation==true?'<span class="vert">OUI</span>':'<span class="rouge">NON</span>').'</td><td>Confrontation</td><td>'.($is_confrontation==true?'<span class="vert">OUI</span>':'<span class="rouge">NON</span>').'</td></tr>';
	$res.= '<tr><td>Institution</td><td>'.($is_institution==true?'<span class="vert">OUI</span>':'<span class="rouge">NON</span>').'</td><td>Vivre Ensemble</td><td>'.($is_vivre_ensemble==true?'<span class="vert">OUI</span>':'<span class="rouge">NON</span>').'</td></tr>';
		$res.= '</table>';
	echo $res;
}

function convert_grenouille($key, $input, $way='encode') {
	$key = $key;
	$alphabet = 'abcdefghijklmnopqrstuvwxyz';

	$tab = str_split($key);
	$tab_alphabet = str_split($alphabet);

	$tab_result_encode = array();
	$tab_result_decode = array();
	$count = 1;
	foreach($tab as $k) {
		if (!isset($tab_result_encode[$k])) {
			$tab_result_encode[$k] = $count.'/';
			$tab_result_decode[$count] = $k;
			$count++;
		}
	}
	
	foreach($tab_alphabet as $k) {
		if (!in_array($k, $tab)) {
			$tab_result_encode[$k] = $count.'/';
			$tab_result_decode[$count] = $k;
			$count++;
		}
	}
	
	if ($way == 'encode') {
		return str_replace(array_keys($tab_result_encode), array_values($tab_result_encode), $input);
	}
	if ($way == 'decode') {
		$res = '';
		$input = str_replace(' ', ' /', $input);
		foreach (explode('/',$input) as $k) {
			if (isset($tab_result_decode[$k])) {
				$res.= $tab_result_decode[$k];
			} else {
				$res.= $k;
			}
		}
		return $res;
	}
	if ($way == 'view') {
		return print_r($tab_result_encode, true);
	}
}

function convert_avocat($input, $clef, $way='encode') {
	$ecart = ord($clef) - ord('a');
	$tableau_encode = array();
	$tableau_decode = array(); 
	for ($c=0;$c<26;$c++) {
		$ecart_reel = ($ecart+$c)%26;
		$tableau_encode[chr(ord('a')+$c)] = chr(ord('a')+$ecart_reel);
		$tableau_decode[chr(ord('a')+$ecart_reel)] = chr(ord('a')+$c);
	}
	
	if ($way == 'encode') {
		$res ='';
		foreach (str_split($input) as $k) {
			if (isset($tableau_encode[$k])) {
				$res .= $tableau_encode[$k];
			} else {
				$res .= $k;
			}
		}
		return $res;
	}
	if ($way == 'decode') {
		$res ='';
		foreach (str_split($input) as $k) {
			$res .= $tableau_decode[$k];
		}
		return $res;
	}
	if ($way == 'view') {
		return print_r($tableau_encode, true);
	}
}
	
?>