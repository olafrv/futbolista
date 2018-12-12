<b> El rincon del aficionado </b>
<br>
<?php
	
	echo '<b>[' . __('Email', true) . ']:</b> ' . $this->Html->link(
			Configure::read('Futbol.contactMail'),
			'mailto:'.Configure::read('Futbol.contactMail')
	) . "<br>";
	echo '<b>[' . __('Website', true) . ']:</b> ' . $this->Html->link(Configure::read('Futbol.serverSslUrl')) . "<br>";
?>
