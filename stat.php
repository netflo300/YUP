<?php
session_start();
require_once ("class/db.class.php");
require_once ("lib/fonctions.php");
if (!auth()) {
  Header("Location:login.php");
  die;
}
$db = new Db();
entete("Stat");
include ("header.php");

$db->query("SELECT etat, count(*) as count FROM itineraire_rw GROUP BY etat ;");
?>

<table border="1">
  <caption>Avancement des parcours</caption>
  <tr>
    <th>Etat</th>
    <th>Nombre de parcours</th>
  </tr>
  <?php 
  foreach($db->fetch_array() as $k => $v) {
  	echo '<tr><td>'.$v['etat'].'</td><td>'.$v['count'].'</td></tr>'."\n";
  }
  ?>
</table>

<?php 
$db->query("SELECT  c.* FROM checkpoint c ;");
$tableau =  array('purple'=>0, 'blue'=>0, 'lightblue'=>0, 'green'=>0, 'yellow'=>0, 'orange'=>0, 'red'=>0);
$tab = $db->fetch_array();
foreach ($tab as $k => $v) {
	$icone='';
	$db->query("SELECT e.position as position, count(*) as count FROM ".TABLE_NAME_ETAPE." e WHERE id_checkpoint = ".$v['id']." GROUP BY position ;");
	$tab3 = $db->fetch_array();
	if(empty($tab3)) {
		$tableau['purple']++;
		$icone=1;
	} else {
			foreach($tab3 as $k2 => $v2) {
				if($v2['count']>$v['absorption']) {
					$tableau['red']++;
					$icone=1;
					
				}
			}
			
			if ($icone=='') {
				foreach($tab3 as $k2 => $v2) {
					if((100*$v2['count']/$v['absorption'])>75) {
						$tableau['orange']++;
						$icone=1;
					}
				}
			}	
			if ($icone=='') {
				foreach($tab3 as $k2 => $v2) {
					if((100*$v2['count']/$v['absorption'])>50) {
						$tableau['yellow']++;
						$icone=1;
					}
				}
			}
			
			if ($icone=='') {
				if (count($tab3)<7) {
					$tableau['purple']++;
						$icone=1;
				}
			}
			
			if ($icone=='') {
				foreach($tab3 as $k2 => $v2) {
					if((100*$v2['count']/$v['absorption'])<25) {
						$tableau['blue']++;
						$icone=1;
					}
				}
			}
			
			if ($icone=='') {
				foreach($tab3 as $k2 => $v2) {
					if((100*$v2['count']/$v['absorption'])<45) {
						$tableau['lightblue']++;
						$icone=1;
					}
				}
			}
			if ($icone=='') {
						$tableau['green']++;
			}
		}
	}
?>
<br />
<table border="1">
	<caption>Nombre de checkpoint</caption>
	<tr><th>Point</th><th>Nombre</th></tr>
	<tr><td><img src="img/marker/purplecirclemarker.png" /></td><td><?php echo $tableau['purple'] ;?></td></tr>
	<tr><td><img src="img/marker/bluecirclemarker.png" /></td><td><?php echo $tableau['blue'] ;?></td></tr>
	<tr><td><img src="img/marker/lightbluecirclemarker.png" /></td><td><?php echo $tableau['lightblue'] ;?></td></tr>
	<tr><td><img src="img/marker/greencirclemarker.png" /></td><td><?php echo $tableau['green'] ;?></td></tr>
	<tr><td><img src="img/marker/yellowcirclemarker.png" /></td><td><?php echo $tableau['yellow'] ;?></td></tr>
	<tr><td><img src="img/marker/orangecirclemarker.png" /></td><td><?php echo $tableau['orange'] ;?></td></tr>
	<tr><td><img src="img/marker/redcirclemarker.png" /></td><td><?php echo $tableau['red'] ;?></td></tr>
	
	
</table>


<?php 
footer();
?>

