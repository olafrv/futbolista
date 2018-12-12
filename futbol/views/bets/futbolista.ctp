<?php
	if (is_null($text)){
		echo $this->element('flash_ok', array('message'=>'Por ahora no es necesario generar ningún correo electrónico.'));
	}else{
		echo "<h1>Contenido del Mensaje Masivo (Generado)</h1>";
		if ($htmlOutput){
				echo $text;	
		}else{
				echo "<table><tr class='row-a'><td><pre>" . $text . "</pre></td><tr></table>";	
		}
	}
?>
<hr>
