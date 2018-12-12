<?php

echo "<h1>Envío Masivo de Correos Electrónico</h1>";

if (count($sent_list)>0){

	$i=0;
	echo "<table>";
	echo "<tr>";
	echo "<th>Número</th>";
	echo "<th>Usuario</th>";
	echo "<th>Mail (Id)</th>";
	echo "</tr>";
	foreach($sent_list as $sent_item){
		echo "<tr class='".($i%2==0?'row-a':'row-b')."'>";
		echo "<td>".(++$i)."</td><td>$sent_item[0]</td><td> $sent_item[1]</td></tr>";
	}
	echo "</table>";

}else{
		echo "<br>";
		echo $this->element('flash_ok', array('message'=>'No hay correos pendientes por enviar.'));
}
