<?php
require_once ("lib/fonctions.php");

$chaine1 = convert_grenouille('grenouille', 'bonjour', 'encode');
echo 'test clef = GRENOUILLE ; mot = BONJOUR'."\n".$chaine1."\n".convert_grenouille('grenouille', $chaine1, 'decode')."\n table :".convert_grenouille('grenouille', 'bonjour', 'view');



$chaine2 = convert_grenouille('pionnier', 'salut comment ca va ?', 'encode');
echo 'test clef = pionnier ; mot = salut comment ca va ?'."\n".$chaine2."\n".convert_grenouille('pionnier', $chaine2, 'decode')."\n table :".convert_grenouille('pionnier', 'salut comment ca va ?', 'view');;





