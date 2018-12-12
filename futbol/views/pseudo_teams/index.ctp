<?php

	echo '<h1>Administración de Pseudo Equipos</h1>';

	echo $this->Form->create('PseudoTeam', array('action' => '/index', 'id'=>'PseudoTeamForm'));
	echo $this->Form->input('competition_id', array(
			'label'=>'Competencia',
			'type'=>'select', 
			'empty'=>false,
			'options'=>$competitions,
			'onchange'=>"javascript:f=Jolaf.gebi('PseudoTeamForm');f.action+='/onchange:competition'; f.submit();"
	));
	echo $this->Form->input('fase_id', array(
		'label'=>'Fase',
		'type'=>'select', 
		'empty'=>false,
		'options'=>$fases,
	  'onchange'=>"javascript:f=Jolaf.gebi('PseudoTeamForm');f.action+='/onchange:fase'; f.submit();"
	));
	echo $this->Form->input('groupping_id', array(
		'label'=>'Grupo',
		'type'=>'select', 
		'empty'=>false,
		'options'=>$grouppings,
	  'onchange'=>"javascript:f=Jolaf.gebi('PseudoTeamForm');f.action+='/onchange:groupping'; f.submit();"
	));
	echo $this->Form->input('abreviation', array(	
		'type'=>'text',
		'label'=>'Abreviatura'		
	));
	echo $this->Form->input('team_id', array(
		'label'=>'Equipo',
		'type'=>'select', 
		'empty'=>true,
		'options'=>$teams,
	));
	echo "<br>";
	echo $this->Form->button('Guardar', array(
			'type'=>'button',
			'onclick' =>	"javascript:f=Jolaf.gebi('PseudoTeamForm');f.action='".Router::url($action)."'; f.submit();",
	));
	echo $this->Form->button('Deshacer Cambios', array('type'=>'reset'));
	$url = Router::url(array('controller' => 'PseudoTeams', 'action' => 'index', 'todo'=>'create'));
	echo $this->Form->button('Guardar Como Nuevo', array(
		'type'=>'button',
		'onclick' =>	"javascript:f=Jolaf.gebi('PseudoTeamForm');f.action='$url'; f.submit();",
	));
	echo $this->Form->end();

	if (!empty($pseudoteams)){
		echo "<table><tbody>";
	  echo "<tr>";
	  echo "<th>Pseudo Equipo - Abreviatura</th>";
	  echo "<th>Equipo - Nombre (Abreviatura)</th>";
	  echo "<th>Operaciones</th>";
	  echo "</tr>";				
		//debug($pseudoteams);
		foreach($pseudoteams as $pseudoteam){
			$Pseudoteam = $pseudoteam["PseudoTeam"];
			$Team = $pseudoteam["Team"];
			echo "<tr class='row-b'>";
			echo "<td>" . $Pseudoteam["abreviation"] . "</td>";
				echo "<td>";
			if (is_null($Team["id"])){
				echo "Indefinido";
			}else{
				echo $Team["title"] . " (" . $Team["abreviation"] . ")";
			}
			echo "</td>";
			echo "<td>";
			$url = Router::url(array('controller' => 'PseudoTeams', 'action' => 'index', 'todo'=>'edit','id' => $Pseudoteam["id"]));
			echo $this->Form->button('Editar', array(
					'type'=>'button',
					'onclick' =>	"javascript:f=Jolaf.gebi('PseudoTeamForm');f.action='$url'; f.submit();",
			));
			$url = Router::url(array('controller' => 'PseudoTeams', 'action' => 'index', 'todo'=>'delete', 'id' => $Pseudoteam["id"]));
			echo $this->Form->button('Eliminar', array(
					'type'=>'button',
					'onclick' =>	"javascript:if(prompt('Escriba ELIMINAR y presione enter')=='ELIMINAR') f=Jolaf.gebi('PseudoTeamForm');f.action='$url'; f.submit();",
			));
			echo "</td>";
			echo "</tr>";
		}
		echo "</tbody></table>";
	}else{	
		echo $this->element('flash_ok', array('message'=>'No hay grupos registrados para la fase seleccionada.'));
	}
	


