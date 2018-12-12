<?php
	 echo "<h1>" . __("Cambio de Clave",true) . "</h1>";
   echo $this->Form->create('User', array('action' => 'changePassword'));
   echo $this->Form->input('password', array('label'=>__('Nueva Clave', true).':'));
 	 echo $this->Form->input('password_confirm', array('type'=>'password', 'label'=>__('Confirmar Nueva Clave', true).':'));
	 echo "<br>";
   echo $this->Form->end(__('Cambiar Clave',true));
