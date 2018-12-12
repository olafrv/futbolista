<?php
	echo $this->Html->link('[Política de Privacidad]', Configure::read('Futbol.serverSslUrl') . '/Users/privacy');
  echo "<br><br>";
	echo $this->Html->link('[Cancelar Subscripción]', 
		Configure::read('Futbol.serverSslUrl') . '/Users/unsubscribe')
			. ' de esta lista de correo electrónico.';
?>	
