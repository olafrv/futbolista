<?php

	$this->Html->scriptStart();
	echo "
		function registerCheck(){
			if (Jolaf.gebi('UserAgree').checked){
				Jolaf.gebi('UserRegisterForm').submit();			
			}else{
				alert('Debe aceptar (chequear) los términos, condiciones y políticas de privacidad');
			}
		}
	";
	echo $this->Html->scriptEnd();
	echo "<h1>" . __("Registro de Nuevo Usuario",true) . "</h1>";
  echo "<p>";
	echo "<font color='red' size='3px'>Para tener <b>varias quinielas</b> debe
				 registrar varios usuarios (login) diferentes, usando el mismo o 
         diferentes correos electrónicos.<br><br></font>";
  echo $this->Html->image("/img/help.png", array('class'=>'borderless'));
  echo $this->Html->link(__('¿Olvidó su clave o usuario?', true), 'forgot');
  echo "</p>";
	echo $this->Form->create('User', array('action' => 'register'));
  echo $this->Form->input('username', array('label'=>__('Usuario (Login)', true).':'));
	echo "<i>(<strong>i.e.</strong> kpeter44, jamm32, julio23, joserodriguez, etc.)<br><br></i>";
  echo $this->Form->input('password', array('type'=>'password', 'label'=>__('Clave', true).':'));
  echo $this->Form->input('password_confirm', array('type'=>'password', 'label'=>__('Confirmar Clave', true).':'));
  echo $this->Form->input('mail', array('label'=>__('Email', true).':'));
  echo $this->Form->input('mail_confirm', array('label'=>__('Confirmar Email', true).':'));
	echo "<br><b>Imagen de Seguridad</b>:<br>";
 	echo $this->Html->image($securimage_url, array(
  	'onclick'=>"javascript:this.src = '$securimage_url' + Math.random(); return false;",
		'alt' => 'Haga clic para cambiarla.'
	));
	echo $this->Form->input('captcha', array('label'=>__('Código de la Imagen',true).':'));
	echo "<br><input type='checkbox' id='UserAgree'/>";
	echo "He leído y acepto los ";
	echo $this->Html->link('términos, condiciones', '/bets/rules');
	echo " y ";
	echo $this->Html->link('políticas de privacidad','/users/privacy');
  echo " de éste sitio y deseo registrarme.<br><br>";
  echo $this->Form->button(__('Registrar',true), array('class'=>'button-green', 'type'=>'button', 'onclick'=>'javascript:registerCheck();'));
	echo $this->Form->end();
