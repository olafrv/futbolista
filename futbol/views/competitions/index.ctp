<?php

	echo '<h1>Administración de Competencias</h1>';

	echo $this->Form->create('Competition', array('action' => '/index', 'id'=>'CompetitionForm'));
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
	echo $this->Form->input('rss', array(	
		'type' => 'text',
		'label' => 'RSS'
	));
	echo $this->Form->input('resume', array(	
		'type' => 'textarea',
		'label' => 'Resumen'
	));
	echo $this->Form->input('cost', array(	
		'type' => 'text',
		'label' => 'Costo'
	));
	echo $this->Form->input('sport', array(	
		'type' => 'select',
		'empty' => false,
		'options' => array('Futbol'=>'Fútbol', 'Beisbol'=>'Béisbol'),
		'label' => 'Deporte'
	));
	echo "<br>";
	echo $this->Form->button('Guardar', array(
			'type'=>'button',
			'onclick' =>	"javascript:f=Jolaf.gebi('CompetitionForm');f.action='".Router::url($action)."'; f.submit();",
	));
	echo $this->Form->button('Deshacer Cambios', array('type'=>'reset'));
	$url = Router::url(array('controller' => 'Competitions', 'action' => 'index', 'todo'=>'create'));
	echo $this->Form->button('Guardar Como Nuevo', array(
		'type'=>'button',
		'onclick' =>	"javascript:f=Jolaf.gebi('CompetitionForm');f.action='$url'; f.submit();",
	));
	echo $this->Form->end();	
	
	if (!empty($competitions)){
		echo "<table><tbody>";
	  echo "<tr>";
	  echo "<th>Título (Id)</th>";
	  echo "<th>Inicio</th>";
		echo "<th>Fin</th>";
		echo "<th>RSS</th>";
		echo "<th>Resumen</th>";
		echo "<th>Costo</th>";
	  echo "<th>Operaciones</th>";
	  echo "</tr>";		
		foreach($competitions as $competition){
			$Competition = $competition["Competition"];
			echo "<tr>";
			echo "<td>" . $Competition["title"] . " (" . $Competition["id"] . ")" . "</td>";
			echo "<td>" . $Competition["begins"] . "</td>";
			echo "<td>" . $Competition["ends"] . "</td>";
			echo "<td><a href='" . $Competition["rss"] . "'>RSS</a></td>";
			echo "<td><font size='1px'>" . $Competition["resume"] . "</font></td>";
			echo "<td>" . $Competition["cost"] . "</td>";
			echo "<td>";
			$url = Router::url(array('controller' => 'Competitions', 'action' => 'index', 'todo'=>'edit','id' => $Competition["id"]));
			echo $this->Form->button('Editar', array(
					'type'=>'button',
					'onclick' =>	"javascript:f=Jolaf.gebi('CompetitionForm');f.action='$url'; f.submit();",
			));
			$url = Router::url(array('controller' => 'Competitions', 'action' => 'index', 'todo'=>'delete','id' => $Competition["id"]));			
			echo $this->Form->button('Eliminar', array(
					'type'=>'button',
					'onclick' =>	"javascript:if(prompt('Escriba ELIMINAR y presione enter')=='ELIMINAR') f=Jolaf.gebi('CompetitionForm');f.action='$url'; f.submit();",
			));
			$url = Router::url(array('controller' => 'Competitions', 'action' => 'duplicate', $Competition["id"]));
			echo $this->Form->button('Duplicar', array(
					'type'=>'button',
					'onclick' =>	"javascript:if(prompt('Escriba DUPLICAR y presione enter')=='DUPLICAR') f=Jolaf.gebi('CompetitionForm');f.action='$url'; f.submit();",
			));
			echo "</td>";
			echo "</tr>";
		}
		echo "</tbody></table>";
	}else{	
		echo $this->element('flash_ok', array('message'=>'No hay competencias registradas.'));
	}
	


