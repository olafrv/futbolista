<h1>Galer&iacute;a de Fotos</h1>
<div align="center">
<?php
  echo $this->Form->create('Photo', array('action' => 'show', 'id'=>'PhotoForm'));

  $fields = array();

  $fields['Photo.competition_id'] = array(
    'type'=>'select', 
		'empty'=>false,
    'options'=>$competitions,
  );

  echo $this->Form->select('competition_id', $competitions, $competition_id, 
		array(
      'empty'=>false,
    	'onchange'=>"javascript:f=Jolaf.gebi('PhotoForm'); f.submit();"
		)
	);
	echo $this->Form->end();

	foreach ($photos as $photo){
		$photo = $photo["Photo"];
		echo "<h3>";
		echo $this->Html->image("/img/". $photo["icon"], array("style"=>"background: none; border: none;"));
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
		echo "<b><br>" . $photo["description"] . "</b>";
		echo "<br/>";
		echo "<br/>";
		echo "<hr/>";
		echo "<br/>";
	}


?>
</div>
