<?php
//Lougout puis retour à la page d'index
session_start();

$_SESSION = array();
header("Location: index.php");

?>