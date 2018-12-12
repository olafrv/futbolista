<?php

	echo $this->element('mobile');
	 
	echo "<h1>" . __("Entrada",true) . "</h1>";

	echo "<p>Para acceder al sistema rellene el formulario y presione <b>Entrar</b></p>";

	echo "<table align='center'>";
	echo "<tr>"; 
	echo "<th colspan='2'>Datos de Acceso</th>"; 
	echo "</tr>"; 
	echo "<tr class='row-a'>"; 
	echo "<td>";
	echo $this->Form->create('User', array('action' => 'login'));
	echo $this->Form->input('username', array('label'=>__('Usuario', true).':'));
	echo "<br/>";
	echo $this->Form->input('password', array('label'=>__('Clave/Contraseña', true).':'));
	echo '<br><b>Imagen de Seguridad:<br>';
	echo $this->Html->image($securimage_url, array(
		'onclick'=>"javascript:this.src = '$securimage_url' + Math.random(); return false;",
	 	'alt' => 'Haga clic para cambiarla.'
	));
	echo '<br>';
	echo '<br>';
	echo $this->Form->input('captcha', array('label'=>__('Código de la Imagen de Seguridad',true).':'));
	echo "<div align='center'><br>";
	echo $this->Form->button(__('Entrar',true), array('class'=>'button-green'));
	echo $this->Form->end();
	echo "</div>";
	echo "</td>";
	echo "<td>";
	echo "<p style='font-size: 14px; font-weight: bold;'>";
	echo $this->Html->image("/img/help.png", array('class'=>'borderless'));
	echo $this->Html->link(__('¿Olvidó su clave o usuario?', true), 'forgot');
	echo "</p>";
	echo "<br>";
	echo "<p style='font-size: 14px; font-weight: bold;'>";
  echo $this->Html->image("/img/preppy.png", array('class'=>'borderless'));
  echo $this->Html->link(__('¿Usuario Nuevo? ¡Regístrese aquí!', true), '/users/register', array('style'=>'color: #2180BC;'));
	echo "</p>";
	echo "</td>";
	echo "</tr>"; 
	echo "</table>";
	
