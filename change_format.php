<?php
session_start();
switch ($_POST['format']) {
	case 1:
		$_SESSION['format'] = 1;
		break;
	case 2: 
		$_SESSION['format'] = 2;
	break;
	case 3: 
		$_SESSION['format'] = 3;
	break;
	case 4: 
		$_SESSION['format'] = 4;
	break;
}
header("Location:".$_SERVER['HTTP_REFERER']);