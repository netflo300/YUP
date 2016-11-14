function deplace()
{
	new Draggable('modal');
}

function close_modal()
{
	$("modal").style.visibility="hidden";
	$("overlay").style.visibility="hidden";
}

function modal (titre)
{
	$("overlay").style.visibility="visible";
	$("modal").getElementsByTagName("div")[0].innerHTML = titre ;
	$("modal").style.visibility="visible";
}

function message (titre,contenu,time)
{
	$("message").getElementsByTagName("fieldset")[0].innerHTML = "<legend>&nbsp;"+titre+"</legend>"+contenu ;
	$("message").style.visibility="visible";
	if (time!=0)
		setTimeout('Effect.Fade("message");',1000*time);
}

function context_menu(i,j)
{ 
	$("context_menu").style.left=mouse_x+"px";
	$("context_menu").style.top=mouse_y+"px";
	contenu_menu="<ul>";
	if (doing!="calculer")
		contenu_menu+="<li><a onclick=\"set_doing(\'calculer\'); hide_contextmenu(); \">Mode calcul de deplacement</a></li>";
	if (doing!="selectionner")	
		contenu_menu+="<li><a onclick=\"set_doing(\'selectionner\');hide_contextmenu();\">Mode Selection</a></li>";
	if (selected)
		contenu_menu+="<li><a onclick=\"deselectionner();hide_contextmenu();\">Tout deselectionner</a></li>";
	contenu_menu+="<li><a onclick=\"add_pointer("+j+","+i+");hide_contextmenu();\">Ajouter un item</a></li>";
	contenu_menu+="</ul>";
	
	
	$("context_menu").innerHTML=contenu_menu ;
	$("context_menu").style.visibility="visible";
}

function hide_contextmenu()
{
	$("context_menu").style.visibility="hidden";
}
 
function open_modal_configuration(defaut, def_booking) {
	modal('Configuration');
	contenu = '<form action="change_format.php" method="POST">';
	contenu += '<label>Affichage des checkpoints : </label>';
	contenu += '<select name="format">';
	contenu += '<option value="1" '+(defaut==1?'selected="selected"':'')+'>cid + titre</option>';
	contenu += '<option value="2" '+(defaut==2?'selected="selected"':'')+'>cid + titre + name</option>';
	contenu += '<option value="3" '+(defaut==2?'selected="selected"':'')+'>id + titre</option>';
	contenu += '<option value="4" '+(defaut==2?'selected="selected"':'')+'>id + titre + name</option>';
	contenu += '</select>';
	contenu += '<input type="submit" value="valider"/>';
	contenu += '</form>';
	contenu += '<form action="change_booking.php" method="POST">';
	contenu += '<label>Gestion du booking : </label>';
	contenu += '<select name="gestion_booking">';
	contenu += '<option value="1" '+(def_booking==1?'selected="selected"':'')+'>Par itineraires finaux</option>';
	contenu += '<option value="2" '+(def_booking==2?'selected="selected"':'')+'>Par itineraires g&eacute;n&eacute;r&eacute;s</option>';
	contenu += '</select>';
	contenu += '<input type="submit" value="valider"/>';
	contenu += '</form>';
	$("modal_contenu").innerHTML = contenu;
}

function display_debug() {
	alert($("debug").style.visibility);
	/*if ($("debug").style.display == "none") {
		$("debug").style.display = "";
	} else {
		$("debug").style.display = "none";
	}*/
}

function display_modal_map() {
	modal('Carte');
	ajax("detailsItineraire.php","id_itineraire=42","modal");
}

function afficher_modal_transport(id, nom, positionX, positionY) {
	modal('Configuration');
	contenu = '<form action="transport.php" method="POST">';
	contenu += '<label>nom : </label><input type="text" name="nom" value="' + nom + '" size="43"/><br/>';
	contenu += '<label>coordonn&eacute;es : </label><input size="15" type="text" name="poistionY" value="' + positionY + '" /><input size="15" type="text" name="poistionX" value="' + positionX + '" /><br/>';
	contenu += '<input type="submit" value="valider"/>';
	contenu += '</form>';
	$("modal_contenu").innerHTML = contenu;
}