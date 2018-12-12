<h1>Cancelar Subscripción a la Lista de Correo Electrónico</h1>

<?php if ($manual) {

  echo $this->Form->create('User', array('action' => 'unsubscribe'));
  echo $this->Form->input('username', array('label'=>__('Usuario (Login)', true).':'));
  echo "<i>(<strong>i.e.</strong> kpeter44, jamm32, julio23, joserodriguez, etc.)<br><br></i>";
  echo $this->Form->input('password', array('type'=>'password', 'label'=>__('Clave', true).':'));
  echo "<br><b>Imagen de Seguridad</b>:<br>";
  echo $this->Html->image($securimage_url, array(
    'onclick'=>"javascript:this.src = '$securimage_url' + Math.random(); return false;",
    'alt' => 'Haga clic para cambiarla.'
  ));
  echo $this->Form->input('captcha', array('label'=>__('Código de la Imagen',true).':'));
	echo $this->Form->button(__('Cancelar Subscripción',true), array('class'=>'button-green', 'type'=>'submit'));
	echo $this->Form->end();
	} 
?>
<p>
	<font size="3px">
	Si tiene cualquier inconveniente comuníquese con nosotros visitando <b><?php echo $this->Html->link('Ayuda', '/Bets/contact'); ?></b>.
	</font>
<p>
