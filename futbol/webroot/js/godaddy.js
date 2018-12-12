	function godaddyLoad(){
      var href = Jolaf.gebi("godaddy_link").getAttribute("href");
		var random = Jolaf.random(0,1);
      if (random == 1){
			Jolaf.gebi("godaddy_url").src = href;
		}
   }

