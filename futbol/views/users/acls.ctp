<?php

echo "<h1>Administración de ACLs</h1>";

$message = "<br>";
if (!is_null($lines)){
	foreach($lines as $line){
		$message .= "<br>" . $line;
	}
}
if (!empty($message)) echo $this->element('flash_ok', array('message'=>$message));

echo $this->Form->create('User', array('action'=>'acls'));
echo $this->Form->input('controller', array(
	'type'=>'text',
	'label'=>'Controller'
));
echo $this->Form->input('actions', array(
	'type'=>'text',
	'label'=>'Action'
));
echo $this->Form->input('groups', array(
	'options'=>$groups, 
  'multiple' => 'multiple',
	'empty'=>false
));
echo $this->Form->end('Aplicar');


