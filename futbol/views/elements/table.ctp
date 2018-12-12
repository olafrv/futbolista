<?php

	echo "<table>";
	foreach($arreglo[0] as $key => $val){
				echo "<th>$key</th>";
	}
  echo $this->Html->tableCells($arreglo, array('class'=>'row-a'), array('class'=>'row-b'));
	echo "</table>";

?>
