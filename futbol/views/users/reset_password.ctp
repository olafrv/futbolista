<h1>Reinicio de Clave de Acceso</h1>

<?php if (!is_null($mensaje)) echo "<p><font size='3px'>$mensaje</font></p>"; ?>

<?php if ($error) { 
  echo "<p style='font-size: 15px; font-weight: bold;'>";
  echo $this->Html->image("/img/help.png", array('class'=>'borderless'));
  echo $this->Html->link(__(' Intente solicitar nuevamente el reinicio de clave de acceso', true), 'forgot');
  echo ".</p>";
?>
<p>
	<font size="3px">
	Si el <font color='red'><b>problema persiste</b></font> y no ha podido reiniciar su clave comuníquese con nosotros visitando <b><?php echo $this->Html->link('Ayuda', '/Bets/contact'); ?></b>.
	</font>
<p>
<?php }else{ ?>
<p>
	<font size="3px">
	Si tiene cualquier inconveniente comuníquese con nosotros visitando <b><?php echo $this->Html->link('Ayuda', '/Bets/contact'); ?></b>.
	</font>
<p>
<?php } ?>
