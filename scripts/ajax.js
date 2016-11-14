
/**
* Methode qui instancie XMLHttpRequest
*/	 
function getXhr(){
	var res=null;
	if(window.XMLHttpRequest) 
		res = new XMLHttpRequest(); 
	else
		if(window.ActiveXObject)
	    { 
				try
				{
					res = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch (e) 
				{
					res = new ActiveXObject("Microsoft.XMLHTTP");
				}
	    }
    else
    { 
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
			res = false; 
    }
	return res;
}

function ajax(file,arg,div){
	// Sablier le temps de chargement du contenu de la div
	document.body.style.cursor = 'wait';
	// instanciation de l'objet XMLHttpRequest
	var xhr = getXhr();
	// On defini ce qu'on va faire quand on aura la reponse
	xhr.onreadystatechange = function()
	{
		// readyState : Représente l'état d'avancement de la requête
		// status : Représente le code HTTP retourné par la requête
			if(xhr.readyState == 4 && xhr.status == 200)
			{
			  reponse = xhr.responseText;
			  if(typeof(div) != 'undefined')
			  {
				  document.getElementById(div).innerHTML = reponse ;
				  var allscript = document.getElementById(div).getElementsByTagName('script');
					for(var i=0;i< allscript.length;i++)
					{
						if (window.execScript)
							window.execScript(allscript[i].text);
						else
							window.eval(allscript[i].text);
					}
			}
		}
	}
	// open(method, url[, asynchrone[, user[, password]]]) : Initialise une requête en spécifiant la méthode (method), l'URL (url), si le mode est asynchrone (asyncFlag vaut true ou false) et en indiquant d'éventuelles informations d'identification (user et password).
	xhr.open("POST",file,true);
    //setRequestHeader(headerName, headerValue) : Spécifie un en-tête HTTP (headerName et headerValue) à envoyer avec la requête.
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// send(data) : Envoie la requête HTTP au serveur en transmettant éventuellement des données (data doit alors être différent de null) sous forme d'une « postable string » (je suis preneur pour une traduction) ou sous forme d'un objet DOM.
	xhr.send(arg);
    // Curseur normal
	document.body.style.cursor = 'auto';
}