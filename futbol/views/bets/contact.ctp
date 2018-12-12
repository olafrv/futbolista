<h1>Contáctenos</h1>

<p>
	<font size="3px">
	  Comun&iacute;quese todos los <b>d&iacute;as de 8:30 a.m. - 7:30p.m.</b> con los
	 	<b><?php echo $this->Html->link('Administradores','/payments/show'); ?></b>
	</font>
</p>

<h1>Términos, Condiciones y Políticas</h1>
<p>
	<font size="3px">
  	<ul>
	 	 <li><?php echo $this->Html->link('Políticas de Privacidad','/users/privacy'); ?></li>
	   <li><?php echo $this->Html->link('Términos y Condiciones de Uso','/bets/rules'); ?></li>
		</ul>
	</font>
	<p align="center">
			<?php echo $this->Html->link(
      	$this->Html->image("/img/dummies.png", array('class'=>'borderless')),
	      "/Bets/rules", array('escape' => false)
		   );
			?>
	</p>
</p>
