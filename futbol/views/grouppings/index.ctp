<?php

	echo '<h1>Administración de Grupos</h1>';

	echo $this->Form->create('Groupping', array('action' => '/index', 'id'=>'GrouppingForm'));
	echo $this->Form->input('competition_id', array(
			'type'=>'select', 
			'empty'=>false,
			'options'=>$competitions,
			'onchange'=>"javascript:f=Jolaf.gebi('GrouppingForm');f.action+='/onchange:competition'; f.submit();",
			'label'=>'Competencia'
	));
	echo $this->Form->input('fase_id', array(
		'type'=>'select', 
		'empty'=>false,
		'options'=>$fases,
	  'onchange'=>"javascript:f=Jolaf.gebi('GrouppingForm');f.action+='/onchange:fase'; f.submit();",
		'label'=>'Fase'
	));
	echo $this->Form->input('title', array(	
		'type'=>'text',
		'label'=>'Título'
	));
	echo '<br>';
	echo $this->Form->checkbox('is_elimination', array(	
		'label' => false,
		'div' => false
	));
	echo ' <b>Es una eliminatoria (Todos contra todos)</b><br>';

	echo "<br>";
	echo $this->Form->button('Guardar', array(
			'type'=>'button',
			'onclick' =>	"javascript:f=Jolaf.gebi('GrouppingForm');f.action='".Router::url($action)."'; f.submit();",
	));
	echo $this->Form->button('Deshacer Cambios', array('type'=>'reset'));
	$url = Router::url(array('controller' => 'Grouppings', 'action' => 'index', 'todo'=>'create'));
	echo $this->Form->button('Guardar Como Nuevo', array(
		'type'=>'button',
		'onclick' =>	"javascript:f=Jolaf.gebi('GrouppingForm');f.action='$url'; f.submit();",
	));
	echo $this->Form->end();	
	
	if (!empty($grouppings)){
		echo "<table><tbody>";
	  echo "<tr>";
	  echo "<th>Título (Id)</th>";
	  echo "<th>¿Eliminación?</th>";
	  echo "<th>Operaciones</th>";
	  echo "</tr>";				
		foreach($grouppings as $groupping){
			$Groupping = $groupping["Groupping"];
			echo "<tr>";
			echo "<td>" . $Groupping["title"] . " (" . $Groupping["id"] . ")" . "</td>";
			echo "<td align='center'>" . __($Groupping["is_elimination"]==1 ? "Sí" : "No", true) . "</td>";
			echo "<td>";
			$url = Router::url(array('controller' => 'Grouppings', 'action' => 'index', 'todo'=>'edit','id' => $Groupping["id"]));
			echo $this->Form->button('Editar', array(
					'type'=>'button',
					'onclick' =>	"javascript:f=Jolaf.gebi('GrouppingForm');f.action='$url'; f.submit();",
			));
			$url = Router::url(array('controller' => 'Grouppings', 'action' => 'index', 'todo'=>'delete', 'id' => $Groupping["id"]));
			echo $this->Form->button('Eliminar', array(
					'type'=>'button',
					'onclick' =>	"javascript:if(prompt('Escriba ELIMINAR y presione enter')=='ELIMINAR') f=Jolaf.gebi('GrouppingForm');f.action='$url'; f.submit();",
			));
			echo "</td>";
			echo "</tr>";
		}
		echo "</tbody></table>";
	}else{	
		echo $this->element('flash_ok', array('message'=>'No hay grupos registrados para la fase seleccionada.'));
	}
	


