<?php
session_start();
require_once ("class/db.class.php");
require_once ("lib/fonctions.php");
if (!auth()) {
  Header("Location:login.php");
  die;
}
entete("Travaux");
?>
<h1>Le site en cours de maintenance, merci de revenir plus tard !</h1>
<?php 
footer();
?>