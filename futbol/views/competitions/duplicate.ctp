<h1>Administración de Competencias</h1>

<?php
	$url = Router::url(array('controller' => 'Competitions', 'action' => 'index'));			
	echo "<p>" . $this->Form->button('Continuar', array(
			'type'=>'button',
			'onclick' =>	"javascript:document.location='$url';"
		)
	) . "</p>";
