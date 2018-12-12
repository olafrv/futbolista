var Jolaf =
{
   gebi : function(id, doc)
   {
      if (doc == undefined) doc = document;
		return doc.getElementById(id);
   },

	// Based on http://www.forosdelweb.com/f13/innerhtml-448101/
	// Based on http://www.ajaxhispano.com/tutorial-manual-ajax-ejemplos-metodo-GET-POST-principiantes.html
	lajax : function (divObj, url, qs)
	{
		var xmlHttpReq = false;
		// Mozilla/Safari
		if (window.XMLHttpRequest) {
      	ajax = new XMLHttpRequest();
		}
		// IE
	   else if (window.ActiveXObject) {
			self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
		}
		if (qs!=null){
			ajax.open("POST",url,true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		}else{
			ajax.open("GET",url,true);          
		}
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				if (ajax.status!=200) alert("HTTP Ajax Response = "+ajax.status);
				divObj.innerHTML = ajax.responseText;
				//divObj.style.display = "block";
			}
		}
		ajax.send(qs);
	},

	//http://www.desarrolloweb.com/articulos/763.php
	random : function(inferior, superior){
		var numPosibilidades = (superior + 1) - inferior;
		var aleat = Math.random() * numPosibilidades;
		aleat = Math.floor(aleat);
		aleat = (inferior + aleat);
		return aleat;
	}
}
