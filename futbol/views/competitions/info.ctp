<?php 
	
	echo "<h1>" . __('Competencias', true) . "</h1>";

	echo $this->element("my-menu");

	echo $this->FutbolGui->makeCompetitionForm($competition_list, $competition_id, 'Competition', 'Competitions', 'info');
	echo $this->Form->end();

if (!empty($urls)){

	echo "<h2>" . __('Enlaces de Interés', true) . "</h2>";
	echo "<ul class='sidemenu'>";
 	foreach ($urls as $url) {
		echo '<li>' . $this->Html->link($url['Url']['title'], $url['Url']['url'], array('target'=>'_blank')) . '</li>';
	}
	echo "</ul>";
}

if (!empty($photos)){

	echo "<h2>" . __('Multimedia', true) . "</h2>";
	$i = 0;
	echo "<table>";
	foreach ($photos as $photo){
		if ($i++%3==0) echo "<tr>";
		$photo = $photo["Photo"];
		echo "<td valign='top'>";
		echo "<h3>";
		echo $photo["title"];
		echo "</h3>";
		echo $this->Html->image("/img/photos/". $photo["name"], array('width'=>'200px',
				'url'=>Configure::read("Futbol.serverWwwSsl") . "/img/photos/". $photo["name"]
			)
		);
		//echo "<img src='data:image/jpeg;base64,";
		//echo base64_encode(file_get_contents(WWW_ROOT . DS . "img" . DS ."photos" . DS . $photo["name"]));
		//echo "'>";
		echo "<br/>";
		echo "<b>".$photo["description"] . "</b>";
		echo "</td>";
		if ($i%3==0) echo "</tr>";
	}
	echo "</table>";

}

