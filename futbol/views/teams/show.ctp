<?php

	//debug($Team);

	if ($Team['Team']['has_shield']){
		echo "<strong>" . __($Team['Team']['title'], true);
		echo " (" . $Team['Team']['abreviation'] . ")</strong>";
		echo "<p>".$this->Html->image('shields/' . $Team['Team']['abreviation'] . "_big1.png", array('class'=>'borderless','width'=>120))."</p>";
		echo "<hr/><br/>";	
	}
	echo "<strong>" . __($Team['Country']['title'], true) . "</strong>";
	echo "<p>".$this->Html->image('flags/' . $Team['Country']['code'] . "_big1.gif", array('class'=>'borderless','width'=>100))."</p>";
