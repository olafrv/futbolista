<?php
	echo "<h1>" . __("¿Olvidó su Usuario o Clave de Acceso?",true) . "</h1>";
?>
<table>
 <tr>
  <th colspan="2">
		Solicitud de Reinicio de Clave
	</th>
 </tr>
 <tr>
   <td>
<?php
	echo $this->Form->create('User', array('action' => 'forgot'));
	echo $this->Form->input('usermail', array('label'=>__('Usuario (Login) o Correo Electrónico', true).':'));
  echo "<br><b>Imagen de Seguridad</b>:<br>";
	echo $this->Html->image($securimage_url, array(
		'onclick'=>"javascript:this.src = '$securimage_url' + Math.random(); return false;",
	 	'alt' => 'Haga clic para cambiarla.'
	));
  echo "<br>";
	echo $this->Form->input('captcha', array('label'=>__('Código de Imagen',true).':'));
	echo $this->Form->button(__('Enviar',true), array('class'=>'button-green'));
?>
		</td>
		<td>
			<p align="left">
				<font size="3px">
				<ol>
					<li>El sistema le enviará un correo electrónico, revise su bandeja de <b>correo no deseado (SPAM)</b>.<br><br></li>
					<li>Si tiene cualquier inconveniente para reiniciar su clave comuníquese con nosotros visitando <b><?php echo $this->Html->link('Ayuda', '/Bets/contact'); ?></b>.<br><br></li>
					<li>Ingrese sus datos y presione <strong>enviar</strong> para recibir un <b>correo electrónico con las instrucciones</b> para reiniciar su clave.<br><br></li>
					</li>
				</ol>
				</font>
			</p>
		</td>
	</tr>
</table>
