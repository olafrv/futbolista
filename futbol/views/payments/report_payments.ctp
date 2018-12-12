<h1><?php echo __('Reporte de Pagos Diarios', true); ?></h1>

<p align="center">
	<b>
	<?php
		echo $this->Html->image("money.png", array('class'=>'borderless','height'=>'24px'));
	  echo $this->Html->link('[Registrar/Listar Pago]','/Payments');
	?>
	</b>
</p>
<?php 
  echo $this->FutbolGui->makeCompetitionForm($competition_list, $competition_id, 'Competition', 'Payments', 'reportPayments');
?>
<div align="center">
	<?php
  	echo $this->element('table', array('arreglo'=>$pagos));
	?>
</div>
