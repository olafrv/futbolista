<?php

	echo '<h1>Administración de Fotos</h1>';

	echo $this->Form->create('Photo', array('action' => '/add', 'id'=>'PhotoForm', 'enctype' => 'multipart/form-data'));
	echo $this->Form->input('title', array('type' => 'text','label'=>__('Título',true)));
	echo $this->Form->input('description', array('type' => 'text', 'label'=>__('Descripción',true)));
	echo "En la pagina de inicio solo se muestra la descripción no el título<br>";
	echo $this->Form->input('icon', array('type' => 'text', 'label'=>__('Ícono (/img/*)  - Ej. medal_{gold,silver,bronze}.png',true)));
  echo $this->Form->input('competition_id', array(
      'type'=>'select',
      'empty'=>false,
      'options'=>$competitions,
      'onchange'=>"javascript:f=Jolaf.gebi('PhotoForm');f.action+='/onchange:competition'; f.submit();",
      'label'=>'Competencia'
  ));
	echo $this->Form->file('content', array('label'=>__('Archivo - 700x300 px (Aprox.)',true)));
	echo "<b>Público</b> " . $this->Form->checkbox('public', array('type' => 'checkbox', 'label'=>false, 'div'=>false));
	echo "<br><b>Página de Inicio</b> " . $this->Form->checkbox('homepage', array('type' => 'checkbox', 'label'=>false, 'div'=>false));
	echo "<br>";
	echo "<br>";
	echo $this->Form->button('Guardar', array(
			'type'=>'button',
			'onclick' =>	"javascript:f=Jolaf.gebi('PhotoForm');f.action='".Router::url($action)."'; f.submit();",
	));
	echo $this->Form->button('Deshacer Cambios', array('type'=>'reset'));
	$url = Router::url(array('controller' => 'Photos', 'action' => 'add', 'todo'=>'create'));
	echo $this->Form->button('Guardar Como Nuevo', array(
		'type'=>'button',
		'onclick' =>	"javascript:f=Jolaf.gebi('PhotoForm');f.action='$url'; f.submit();",
	));
	echo $this->Form->end();
	
	if (!empty($photos)){
		echo "<table><tbody>";
	  echo "<tr>";
	  echo "<th>Archivo (Id)</th>";
	  echo "<th>Competencia</th>";
	  echo "<th>Operaciones</th>";
	  echo "</tr>";		
		foreach($photos as $photo){
			$Photo = $photo["Photo"];
			$Competition = $photo["Competition"];
			echo "<tr class='row-b'>";
			echo "<td><b>" . $Photo["name"] . "</b> (" . $Photo["id"] . ")";
			if (!empty($Photo["title"])) echo "<br><b>Titulo:</b> " . $Photo["title"];
				if (!empty($Photo["description"])) echo "<br><b>Descripci&oacute;n:</b> " . $Photo["description"];
			echo "</td>";
			echo "<td>";
			if (!empty($Photo["icon"])) echo $this->Html->image('/img/' . $Photo["icon"]);
			echo $Competition["title"];
			echo "<div align='right'>" . ($Photo["public"] == "1" ? '[P&uacute;blico]' : '')  . ($Photo["homepage"] == "1" ? '[Inicio]' : '') . "</div>";
			echo "</td>";		
			echo "<td>";
			$url = Router::url(array('controller' => 'Photos', 'action' => 'add', 'todo'=>'edit','id' => $Photo["id"]));
			echo $this->Form->button('Editar', array(
					'type'=>'button',
					'onclick' =>	"javascript:f=Jolaf.gebi('PhotoForm');f.action='$url'; f.submit();",
			));
			echo "<br>";
			$url = Router::url(array('controller' => 'Photos', 'action' => 'add', 'todo'=>'delete','id' => $Photo["id"]));			
			echo $this->Form->button('Eliminar', array(
					'type'=>'button',
					'onclick' =>	"javascript:if(prompt('Escriba ELIMINAR y presione enter')=='ELIMINAR') f=Jolaf.gebi('PhotoForm');f.action='$url'; f.submit();",
			));
			echo "</td>";
			echo "</tr>";
		}
		echo "</tbody></table>";
	}else{	
		echo $this->element('flash_ok', array('message'=>'No hay fotos registradas.'));
	}
	
