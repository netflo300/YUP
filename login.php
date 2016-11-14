<?php
session_start();
require_once ("lib/fonctions.php");
require_once ("class/db.class.php");

if(isset($_POST['valider']))
{
	$db = new Db();
	$db->query("SELECT * FROM utilisateur
							 WHERE login='".$_POST['login']."';");
	$row = $db->fetch_array();
	if($db->numRows() == 1)
	{
		$_POST['password'] = md5($_POST['password']);
    	$row = $row[0];
		if($_POST['password'] == $row['password'])
		{
			$_SESSION = array();
			$_SESSION['id'] = $row['id'];
			$_SESSION['login'] = $row['login'];
			$db->query("UPDATE `utilisateur` SET derniere_connexion = NOW() 
				WHERE id = ".$_SESSION['id']);	
		}		
	}
	Header("Location:index.php");
}
else
{
	entete("Login");
	echo'<form method="post" action="login.php">';
	echo'<table>';
	echo'<tr><td class="gauche">Login : </td><td class="droite"><input type="text" name="login" /></td></tr>';
	echo'<tr><td class="gauche">Mot de passe : </td><td class="droite"><input type="password" name="password" /></td></tr>';
	echo'<tr><td colspan="2" class="centre"><input type="submit" name="valider" value="valider" /></td></tr>';
	echo'</table>';
	echo'</form>';
	footer();
}

?>
    