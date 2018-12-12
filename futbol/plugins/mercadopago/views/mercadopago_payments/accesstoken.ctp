<?php echo $this->element('menu'); ?>

<h2>MercadoPago Auth and Access Values</h2>

<p>Authentication values and access token used with the
<?php echo $this->Html->link('MercadoPago API', 'https://developers.mercadopago.com'); ?>.
The <i>CLIENT_ID</i> and <i>CLIENT_SECRET</i> can be modified in configuration file
<b>mercadopago/config/mercadopago.php</b>
</p>

<?php

echo "<p><b>CLIENT_ID:</b> " . Configure::read('Mercadopago.CLIENT_ID') . "</p>";
echo "<p><b>CLIENT_SECRET:</b> " . Configure::read('Mercadopago.CLIENT_SECRET') . "</p>";
echo "<p><b>ACCESS_TOKEN:</b> " . $access_token . "</p>";

?>

