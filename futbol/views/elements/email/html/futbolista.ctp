<?php //Forzar coloreado PHP en el editor ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
	<meta http-equiv="content-language" content="es">
	<meta name="description" content="futbol,partidos,equipos,grupos,copa,clásico,derby" />
	<?php echo $html->charset(); ?>
	<title><?php echo Configure::read('Futbol.siteName'); ?></title>
</head>
<body>
<p>
Buen dia <b>Futboleros</b>,
<br>
<br>
Agregue al  
<?php echo $this->Html->link('remitente', "mailto:" . Configure::read('Futbol.smtpFrom')); ?> 
de este correo a su <b>lista de contactos</b> si desea recibir este correo electronico.
<br>
<br>
Recuerde <b>ingresar sus predicciones</b> hasta las <b>23:59 P.M.</b> del dia previo
a cada partido en la pagina oficial <?php echo $this->Html->link(Configure::read('Futbol.serverSslUrl')); ?>, 
tal y como, lo establecen las reglas de uso.
<br>
<br>
</p>

<h1>Posiciones de los Usuarios</h1>

<?php

if (!empty($top10_data)){
	foreach ($top10_data as $top10_data_row):
		$competition = $top10_data_row['competition'];
		$top10_table = $top10_data_row['top10_table'];
    echo "<h2>" . __('Competencia', true) . " (". $competition['id'] . "): " . $competition['title'] ."</h2>";
		if (!empty($top10_table)){
			echo $top10_table;
		}else{
			echo "No hay posiciones registradas.";
		}
   endforeach;
}else{
	echo "No hay posiciones registradas en el sistema.";
}

?>

<p>Para ver el listado completo visite <?php echo $this->Html->link(Configure::read('Futbol.serverSslUrl') . '/bets/top10'); ?></p>

<h1>Predicciones para Hoy (Porcentaje)</h1>

<?php

if (!empty($forecast_data)){
	foreach ($forecast_data as $forecast_row) {
		$bet_count = $forecast_row['bet_count'];
		$bet_table = $forecast_row['bet_table'];
		$bet_match = $forecast_row['bet_match'];
			
		echo "<h2>" .  __('Partido', true) . ' (' . $bet_match['id'] . ')' . ': ' . $bet_match['title'] . "</h2>";
		echo "<h3>" . __('Total de predicciones',true) . ': ' . $bet_count . "</h3>";
		if (!empty($bet_table)) echo $bet_table;
	}
}else{
	echo "No hay predicciones registradas en el sistema.";
}

?>

<h1>Resultados de Ayer</h1>

<?php

if ($yesterday_data["match_count"]>0){
	echo $yesterday_data["match_table"];
}else{
	echo "No hay resultados registrados de ayer";
}

?>

<h1>Predicciones para Hoy (Por Usuario)</h1>

<h2>Listado de Predicciones</h2>
<?php
	echo "<p>Descargue aquí el listado de las predicciones: ";
	echo "<b>" . $this->Html->link($auditdataurl) . "</b>";
	echo "</p>";
	//echo "<p><pre>$auditdata</pre></p>";
?>
<h2>Firma Electrónica de Predicciones</h2>
<?php
	echo "<p><b>SHA-256:</b> " . $signature . "</p>"; 
?>

<?php
/*
// Solo se ven en la página Web
echo "<h1>Usuario Inactivos</h1>";
if (!empty($free_users_data)){
  foreach ($free_users_data as $free_users_data_row):
    $competition = $free_users_data_row['competition'];
    $free_users_table = $free_users_data_row['free_users_table'];
    echo "<h2>" . __('Competencia', true) . " (". $competition['id'] . "): " . $competition['title'] ."</h2>";
    if (!empty($free_users_table)){
      echo $free_users_table;
    }else{
      echo "No hay usuarios insolventes.";
    }
   endforeach;
}else{
  echo "No hay usuarios insolventes en el sistema.";
}
*/	
echo "<p align='center'>" . $this->element('unsubscribe-html') . "</p>"; 

echo "<p align='center'>" . $this->element('firma-html') . "</p>"; 

?>
<p align="center">Este correo va sin acentos para mayor compatibilidad con los navegadores Web.</p>

<p align="center">&copy; 2011 - <?php echo date("Y"); ?> Olaf Reitmaier Veracierta</p>
</body>
</html>
