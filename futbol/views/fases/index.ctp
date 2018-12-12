<?php

	echo '<h1>Administración de Fases</h1>';

	echo $this->Form->create('Fase', array('action' => '/index', 'id'=>'FaseForm'));
  echo $this->Form->input('competition_id', array(
      'type'=>'select',
      'empty'=>false,
      'options'=>$competitions,
      'onchange'=>"javascript:f=Jolaf.gebi('FaseForm');f.action+='/onchange:competition'; f.submit();",
      'label'=>'Competencia'
  ));
	echo $this->Form->input('title', array(	
		'type'=>'text',
		'label'=>'Título'
	));
	echo $this->Form->input('begins', array(	
		'type'=>'date',
		'dateFormat'=>'MDY',
		'label' => 'Inicio'
		
	));
	echo $this->Form->input('ends', array(	
		'type'=>'date',
		'dateFormat'=>'MDY',
		'label' => 'Fin'
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
			'onclick' =>	"javascript:f=Jolaf.gebi('FaseForm');f.action='".Router::url($action)."'; f.submit();",
	));
	echo $this->Form->button('Deshacer Cambios', array('type'=>'reset'));
	$url = Router::url(array('controller' => 'Fases', 'action' => 'index', 'todo'=>'create'));
	echo $this->Form->button('Guardar Como Nuevo', array(
		'type'=>'button',
		'onclick' =>	"javascript:f=Jolaf.gebi('FaseForm');f.action='$url'; f.submit();",
	));
	echo $this->Form->end();	
	
	if (!empty($fases)){
		echo "<table><tbody>";
	  echo "<tr>";
	  echo "<th>Título (Id)</th>";
	  echo "<th>Inicio</th>";
		echo "<th>Fin</th>";
    echo "<th>¿Eliminación?</th>";
	  echo "<th>Operaciones</th>";
	  echo "</tr>";		
		foreach($fases as $fase){
			$Fase = $fase["Fase"];
			echo "<tr>";
			echo "<td>" . $Fase["title"] . " (" . $Fase["id"] . ")" . "</td>";
			echo "<td>" . $Fase["begins"] . "</td>";
			echo "<td>" . $Fase["ends"] . "</td>";
      echo "<td align='center'>" . __($Fase["is_elimination"]==1 ? "Sí" : "No", true) . "</td>";
			echo "<td>";
			$url = Router::url(array('controller' => 'Fases', 'action' => 'index', 'todo'=>'edit','id' => $Fase["id"]));
			echo $this->Form->button('Editar', array(
					'type'=>'button',
					'onclick' =>	"javascript:f=Jolaf.gebi('FaseForm');f.action='$url'; f.submit();",
			));
			$url = Router::url(array('controller' => 'Fases', 'action' => 'index', 'todo'=>'delete','id' => $Fase["id"]));			
			echo $this->Form->button('Eliminar', array(
					'type'=>'button',
					'onclick' =>	"javascript:if(prompt('Escriba ELIMINAR y presione enter')=='ELIMINAR') f=Jolaf.gebi('FaseForm');f.action='$url'; f.submit();",
			));
			echo "</td>";
			echo "</tr>";
		}
		echo "</tbody></table>";
	}else{	
		echo $this->element('flash_ok', array('message'=>'No hay fases registradas.'));
	}

