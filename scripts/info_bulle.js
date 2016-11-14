
//La variable globale i nous dit si la bulle est visible
var i=false; 
var mouse_x,mouse_y;
function GetId(id)
{
	return document.getElementById(id);
}


function move(e)
{
    if (navigator.appName!="Microsoft Internet Explorer") // Si on est pas sous IE
    {
	    GetId("curseur").style.left=e.pageX + 5+"px";
	    GetId("curseur").style.top=e.pageY + 10+"px";
	    mouse_x=e.pageX;
	    mouse_y=e.pageY;
    }
    else  // Modif proposé par TeDeum, merci à lui
    {
    	if(document.documentElement.clientWidth>0)
    	{
	        GetId("curseur").style.left= event.clientX + document.documentElement.scrollLeft+"px";
	        GetId("curseur").style.top= event.clientY + document.documentElement.scrollTop+"px";
    	}
    	else 
    	{
	        GetId("curseur").style.left = event.clientX + document.body.scrollLeft + "px";
	        GetId("curseur").style.top = event.clientY + document.body.scrollTop + "px";
    	}
    	mouse_x=event.clientX;
	    mouse_y=event.clientY;
    }
}

function montre(text)
{
  if(i==false)
  {
	  GetId("curseur").style.visibility="visible"; // Si il est cacher (la verif n'est qu'une securité) on le rend visible.
	  GetId("curseur").innerHTML = text; // Cette fonction est a améliorer, il parait qu'elle n'est pas valide (mais elle marche)
	  i=true;
  }
}
function cache()
{
	if(i==true)
	{
		GetId("curseur").style.visibility="hidden"; // Si la bulle etais visible on la cache
		i=false;
	}
}

document.onmousemove=move; // des que la souris bouge, on appelle la fonction move pour mettre a jour la position de la bulle.
