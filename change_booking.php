<?php
session_start();
switch ($_POST['gestion_booking']) {
	case 1:
		$_SESSION['gestion_booking'] = 1;
		break;
	case 2: 
		$_SESSION['gestion_booking'] = 2;
	break;
	break;
}
header("Location:".$_SERVER['HTTP_REFERER']);