<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
   <meta http-equiv="EXPIRES" content="<?php echo date('r'); ?>"/>
   <meta http-equiv="CACHE-CONTROL" content="NO-CACHE"/>
   <meta http-equiv="PRAGMA" content="NO-CACHE"/>
   <meta http-equiv="content-language" content="es">
	<meta name="robots" content="index,follow,noarchive" /> 
	<meta name="keywords" content="futbol,partidos,equipos,grupos,copa,clásico,derby" />
	<meta name="description" content="futbol,partidos,equipos,grupos,copa,clásico,derby" /> 
	<?php echo $html->charset(); ?>
	<title>
	<?php echo Configure::read('Futbol.siteName') . ' ::'; ?>
	<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon', $this->webroot . '/img/balon.icon.png');
		echo $this->Html->css('envision1.0.css'); //Modified Envision CSS for Futbol
		echo $this->Html->css('futbol'); //Own CSS for Futol
		echo $this->Html->css('cake.futbol'); //Modified Cake CSS for Futbol
  	echo $this->Html->css('/css/redmond/jquery-ui-1.8.12.custom'); // Include jQuery UI library
		//echo $this->Html->script('jolaf'); // Include jOlaf library
    echo $this->Html->script('jquery-1.5.1.min'); // Include jQuery library
    echo $this->Html->script('jquery-ui-1.8.12.custom.min'); // Include jQuery UI library
		echo $scripts_for_layout;
   ?>
</head>
<body>
	<?php echo $content_for_layout; ?>
</body>
</html>
