<h1><?php echo __('Pagos', true);?></h1>

<p>
Los pagos asociados al uso del sistema <b>para cada Competencia y por cada Usuario</b> 
son en <b>Bol&iacute;vares</b> y efectuar un <?php echo $this->Html->link('pago implica la aceptación de éstos términos y condiciciones de uso', '/bets/rules'); ?> y deben ser en efectivo o en las cuentas bancarias de los administradores.
</p>
<p>
Una vez realizado el pago remita su <b>nombre de usuario (login)</b> y <b>número de recibo/confirmación</b> de la transferencia en línea o depósito en efectivo <font color="red"><b>(NO SE ACEPTAN CHEQUES)</b></font> a la dirección de correo elect&oacute;nicos y o tel&eacute;fonos de los administradores.
</p>

<?php
  if (count($costs)>0){
?>
<br>
	 <table style="margin: auto;">
		<tr>
			<th><?php __('Competencia'); ?></th>
			<th><?php __('Inicio'); ?></th>
			<th><?php __('Fin'); ?></th>
			<th><?php __('Monto x Usuario'); ?></th>
		</tr>    
<?php
	foreach($costs as $cost){
      $cells =
         array(
            array(
               array($cost['Competition']['title'],null)
               ,array($this->Time->niceShort($cost['Competition']['begins']),null)
               ,array($this->Time->niceShort($cost['Competition']['ends']),null)
               ,array($cost['Competition']['cost'], array('style'=>'text-align: center;'))
            )
         );
      echo $this->Html->tableCells($cells, array('class'=>'row-a'), array('class'=>'row-b'));
	}
?>
    </table>
<?php 
  }
?>

<h1>Administradores del Sistema</h1>

<table>
   <tr>
      <td>
         <?php echo $this->Html->image('Carmen.jpg', array('class'=>'borderless', 'width'=>'100px')); ?>
      </td>
      <td>
        <b>Cuenta:</b> Banco Venezuela N° 0102-0106-33-0000121099<br>
        <b>Titular:</b> Carmen Gonz&aacute;lez<br>
				<b>C.I.</b> V-12.054.651<br>
        <b>Tlf:</b> 0414-250.56.52<br>
				<b><?php echo $this->Html->link(Configure::read('Futbol.contactMail2'), 'mailto:'.Configure::read('Futbol.contactMail2')); ?></b>
      </td>
      <td>
         <?php echo $this->Html->image('Olaf.jpg', array('class'=>'borderless')); ?>
      </td>
      <td>
        <b>Cuenta:</b> BBVA Provincial N° 0108-0029-39-0100200226<br>
				<b>Titular:</b> Olaf Reitmaier<br> 
				<b>C.I.</b> V-14.987.511<br>
        <b>Tlf:</b> 0424-287.85.89<br>
        <b><?php echo $this->Html->link(Configure::read('Futbol.contactMail1'), 'mailto:'.Configure::read('Futbol.contactMail1')); ?></b>
      </td>
   </tr>
</table>
<!--
<h2>MercadoPago</h2>
<p>
Puede usar <a href="www.mercadopago.com.ve">MercadoPago</a> aun si no tiene cuenta en MercadoLibre de forma rápida y segura,
para pagar con <b>Tarjeta de Crédito Visa o MasterCard</b> del cualquier entidad financiera
y otras formas de pago de Banco Mercantil y BBVA Provincial, siempre que hayan sido emitidas
en <b>Venezuela</b>.</p>
<p align="center">
<a href="https://www.mercadopago.com/mlv/checkout/pay?pref_id=75985616-d179ce5d-5dec-4bb2-b113-9c629bfb5ad8" name="MP-payButton" class="lightblue-M-Ov-ArOn">Pagar</a><script type="text/javascript">(function(){function $MPBR_load(){window.$MPBR_loaded !== true && (function(){var s = document.createElement("script");s.type = "text/javascript";s.async = true;s.src = ("https:"==document.location.protocol?"https://www.mercadopago.com/org-img/jsapi/mptools/buttons/":"http://mp-tools.mlstatic.com/buttons/")+"render.js";var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);window.$MPBR_loaded = true;})();}window.$MPBR_loaded !== true ? (window.attachEvent ?window.attachEvent('onload', $MPBR_load) : window.addEventListener('load', $MPBR_load, false)) : null;})();</script>
</p>      
-->

<?php if (isset($payments_table) && count($payments_table)) { ?>

<h1>Listado de Pagos</h1>

<p>
	A continuación se muestran los pagos registrados a nombre del usuario 
	<b>"<?php echo $this->Session->read('Auth.User.username'); ?>"</b> en el sistema.
</p>

<table>
	<tr>
		<th>Competencia (id)</th>
		<th>Cantidad</th>
		<!--<th>Creado</th>-->
		<th>Fecha</th>
		<th>Observaciones</th>
	</tr>
<?php
	$cells = array();
	foreach($payments_table as $row){
		$Payment = $row['Payment'];
		$Competition = $row['Competition'];
		$cells = 
			array(
				array(
					array($Competition['title'] . '(' . $Competition['id'] . ')', null)
					,array($Payment['amount'], null)
					//,array($this->Time->nice($Payment['created']), null)
					,array($this->Time->nice($Payment['modified']), null)
					,array($Payment['observations'], null)
				)
			);
		echo $this->Html->tableCells($cells, array('class'=>'row-a'), array('class'=>'row-b'));
	}
?>
</table>

<?php } ?>


