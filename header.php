<div style="visibility:hidden;" id="message">
<fieldset>
<legend>Legende</legend>
contenu fieldset
</fieldset>
</div>
<div id="header">
	<ul>
	<li><a href="index.php">Liste des checkpoints</a></li>
	<li><a href="itineraire.php">Liste des itineraires g&eacute;n&eacute;r&eacute;s</a></li>
	<li><a href="itineraire_rw.php">Liste des itineraires finaux</a></li>
	<li><a href="transport.php">Transport</a></li>
	<li><a href="code.php">Codes</a></li>
	<li><a href="rapport.php">Rapport</a></li>
	<li><a href="version.php">Suivi des modifications</a></li>
	<li><a href="#" onclick="open_modal_configuration(<?php echo (isset($_SESSION['format'])?$_SESSION['format']:'1').','.(isset($_SESSION['gestion_booking'])?$_SESSION['gestion_booking']:'1');?>);">Configuration</a></li>
	<li><a href="parameters.php">Parametres</a><li>
	<li><a href="stat.php">Stat</a><li>
	<li><a href="maintenance.php">Maintenance</a><li>
	<li><a href="logout.php">Logout</a></li>
	</ul>
</div>