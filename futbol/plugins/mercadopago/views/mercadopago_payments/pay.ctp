<?php echo $this->element('menu'); ?>

<h2>Payment (Example)</h2>

<!-- Mercadopago Payment Button -->
<div align="center">
<?php
	echo $this->Mercadopago->getOnReturnJs();
	echo $this->Mercadopago->getPayUI('button', $init_point_href, 'Pagar', $properties);
?>
<br><br>
<!-- MercadoPago Good Practices - Promote MP benefits with this banners -->
<p>
<!-- <img src="http://imgmp.mlstatic.com/org-img/banners/ve/medios/735X40.jpg" title="MercadoPago - Medios de pago" alt="MercadoPago - Medios de pago" width="735" height="40"/>-->
<img src="http://imgmp.mlstatic.com/org-img/banners/ve/medios/575X40.jpg" title="MercadoPago - Medios de pago" alt="MercadoPago - Medios de pago" width="575" height="40"/>
<!--
<img src="http://imgmp.mlstatic.com/org-img/banners/ve/medios/468X60.jpg" title="MercadoPago - Medios de pago" alt="MercadoPago - Medios de pago" width="468" height="60"/>
<img src="http://imgmp.mlstatic.com/org-img/banners/ve/medios/125X125.jpg" title="MercadoPago - Medios de pago" alt="MercadoPago - Medios de pago" width="125" height="125"/>
<img src="http://imgmp.mlstatic.com/org-img/banners/ve/medios/120X240.jpg" title="MercadoPago - Medios de pago" alt="MercadoPago - Medios de pago" width="120" height="240"/>
<img src="http://imgmp.mlstatic.com/org-img/banners/ve/medios/120X600.jpg" title="MercadoPago - Medios de pago" alt="MercadoPago - Medios de pago" width="120" height="600"/>-->
</p>
</div>
