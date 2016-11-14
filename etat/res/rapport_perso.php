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
	height:190mm;
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
if (isset($_GET['nb_ligne'])) {
	$nb_ligne = $_GET['nb_ligne'];
} else {
	$nb_ligne = 10;
	
}

$query = "SELECT ".$_GET['select']." from ".$_GET['from'];
if (isset($_GET['where']) && !empty($_GET['where']))	{
	$query .= " WHERE ".$_GET['where'];
}
if (isset($_GET['groupBy']) && !empty($_GET['groupBy']))	{
	$query .= " GROUP BY ".$_GET['groupBy'];
}
if (isset($_GET['orderBy']) && !empty($_GET['orderBy']))	{
	$query .= " ORDER BY ".$_GET['orderBy'];
}		
$db->query($query);
$tab = $db->fetch_array();
$count = 0;
$tags_open = false;

foreach($tab as $k => $v) {
	if($count%$nb_ligne ==0) {
				$tags_open = true;
	?>
	<page>
		<div style="margin: auto; text-align: center;">
			<?php if(isset($_GET['enTete'])) {?>
				<img src="img/etat/header.png" class="header" />
			<?php 
			}
			echo '<h1>'.$query.'</h1>';
			echo'<table border="1"><tr>';
			foreach (array_keys($v) as $cols) {
				echo'<th>'.$cols.'</th>';
			}
			echo'</tr>';
		}
		$count++;
		echo'<tr>';
		foreach (array_values($v) as $cols) {
			echo'<td>'.$cols.'</td>';
		}
		echo'</tr>';
		if($count%$nb_ligne ==0) {
			$tags_open = false;
			echo'</table>';
		 if(isset($_GET['pied'])) {?>
				<img src="img/etat/footer.png" style="width:210mm;margin:auto;text-align:center;"/>
			<?php 
			}
			?>
			</div>
			</page>
			<?php 
		}
}	

if($tags_open==true) {
	echo'</table>';
	if(isset($_GET['pied'])) {?>
			<img src="img/etat/footer.png" style="width:210mm;margin:auto;text-align:center;"/>
			<?php 
		}
	echo '</div></page>';
	$tags_open = false;
}
	?>
