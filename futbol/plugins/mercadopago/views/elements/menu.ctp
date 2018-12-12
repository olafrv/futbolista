<h1>MercadoPago Plugin</h1>
<p align="center">
<?php
	echo $this->Html->link('[Payment Example]','/mercadopago/MercadopagoPayments/pay');
	echo "&nbsp;";
	echo $this->Html->link('[Access Token]','/mercadopago/MercadopagoPayments/accesstoken');
	echo "&nbsp;";
	echo $this->Html->link('[Notify Payment]','/mercadopago/MercadopagoPayments/notify');
	echo "&nbsp;";
	echo $this->Html->link('[Payment Success]','/mercadopago/MercadopagoPayments/sucess');
	echo "&nbsp;";
	echo $this->Html->link('[Payment Pending]','/mercadopago/MercadopagoPayments/pending');
	echo "&nbsp;";
	echo $this->Html->link('[Payment Failure]','/mercadopago/MercadopagoPayments/failure');
?>
</p>
