<?php
//Lougout puis retour � la page d'index
session_start();

$_SESSION = array();
header("Location: index.php");

?>