Buen dia,

Mediante el presente correo deseamos remitirle distintas
informaciones de su interes. 

Recuerde INGRESAR SUS PREDICCIONES hasta las 23:59 P.M. del dia previo
a cada partido en la pagina oficial <?php echo Configure::read('Futbol.serverSslUrl'); ?>, 
tal y como, lo establecen las reglas de uso.

Ventura para su equipo favorito!!!

P.D: Va sin acentos para mayor compatibilidad con los navegadores Web.

----------------------------
 Posiciones de los Usuarios
----------------------------

<?php

if (!empty($top10_data)){
	foreach ($top10_data as $top10_data_row):
		$competition = $top10_data_row['competition'];
		$top10_table = $top10_data_row['top10_table'];
		echo __('Competencia', true) .': "'. $competition['title'] .'" ('. $competition['id'] . ")\n\n";
		if (!empty($top10_table)){
			echo $top10_table . "\n\n";
		}else{
			echo "No hay posiciones registradas.\n\n";
		}
   endforeach;
}else{
	echo "No hay posiciones registradas en el sistema.\n\n";
}

?>

------------------------------------
 Predicciones para Hoy (Porcentaje)
------------------------------------

<?php

if (!empty($forecast_data)){
	foreach ($forecast_data as $forecast_row) {
		$bet_count = $forecast_row['bet_count'];
		$bet_table = $forecast_row['bet_table'];
		$bet_match = $forecast_row['bet_match'];
			
		echo __('Partido', true) . ' (' . $bet_match['id'] . ')' . ': ' . $bet_match['title'] . "\n\n";
		echo __('Total de predicciones',true) . ': ' . $bet_count . "\n\n";
		if (!empty($bet_table)) echo $bet_table . "\n\n";
	}
}else{
	echo "No hay predicciones registradas en el sistema.\n\n";
}

?>

--------------------
 Resultados de Ayer 
--------------------

<?php

if ($yesterday_data["match_count"]>0){
	echo $yesterday_data["match_table"] . "\n\n";
}else{
	echo "No hay resultados registrados de ayer.\n\n";
}

?>

-------------------------------------
 Predicciones para Hoy (Por Usuario)
-------------------------------------

<?php

if (strlen($auditdata)>0){
	echo $auditdata . "\n\n";
}else{
	echo "No hay predicciones registradas para hoy.\n\n";
}
	
if (!empty($forecast_data)){
?>

----------------------------------
 Usuario Inactivos (No Solventes)
----------------------------------

<?php
if (!empty($free_users_data)){
  foreach ($free_users_data as $free_users_data_row):
    $competition = $free_users_data_row['competition'];
    $free_users_table = $free_users_data_row['free_users_table'];
    echo __('Competencia', true) .': "'. $competition['title'] .'" ('. $competition['id'] . ")\n\n";
    if (!empty($free_users_table)){
      echo $free_users_table . "\n\n";
    }else{
      echo "No hay usuarios insolventes.\n\n";
    }
   endforeach;
}else{
  echo "No hay usuarios insolventes en el sistema.\n\n";
}
?>

-------------------
 Firma electronica  
-------------------

<?php

	echo __('Auditoria',true) . ' (SHA-1): ' . $signature . "\n\n"; 
}

	echo $this->element('unsubscribe-text');

	echo $this->element('firma-text'); 

?>
