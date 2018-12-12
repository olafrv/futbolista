<?php

	echo "<h1>Administración de Partidos</h1>";
	
	echo $this->Form->create('Match', array('action' => '/index', 'id'=>'MatchForm'));

	echo $this->Form->input('competition_id', array(
			'type'=>'select', 
			'empty'=>false,
			'options'=>$competitions,
			'onchange'=>"javascript:f=Jolaf.gebi('MatchForm');f.action+='/onchange:competition'; f.submit();"
	));

	if (isset($fases)) echo $this->Form->input('fase_id', array(
		'type'=>'select',			
		'empty'=>false,
		'options'=>$fases,
	  'onchange'=>"javascript:f=Jolaf.gebi('MatchForm');f.action+='/onchange:fase'; f.submit();"
	));

	if (isset($grouppings)) 	echo $this->Form->input('groupping_id', array(
		'type'=>'select',
		'empty'=>false,
		'options'=>$grouppings,
		'onchange'=>"javascript:f=Jolaf.gebi('MatchForm');f.action+='/onchange:groupping'; f.submit();"
	));

	echo $this->Form->input('stadium_id', array(
		'type'=>'select', 'empty'=>true,
		'options'=>$stadiums
	));
	
	echo $this->Form->input('kickoff', array(
		'type'=>'datetime',
		'timeFormat'=>12,
		'dateFormat'=>'MDY',
	));

	if (isset($teams)){
		echo $this->Form->input('host_id', array(
			'type'=>'select',
			'options'=>$teams,
		));
		echo $this->Form->input('host_goals', array(
			'type'=>'text', 'size'=>'3', 'maxlength' => '3'
		));
		echo $this->Form->input('guest_id', array(
			'type'=>'select',
			'options'=>$teams,
		));
		echo $this->Form->input('guest_goals', array(
			'type'=>'text', 'size'=>'4', 'maxlength' => '3'
		));
	}
	
	echo "<br>";
	echo $this->Form->button('Guardar', array(
			'type'=>'button',
			'onclick' =>	"javascript:f=Jolaf.gebi('MatchForm');f.action='".Router::url($action)."'; f.submit();",
	));
	echo $this->Form->button('Deshacer Cambios', array('type'=>'reset'));
	$url = Router::url(array('controller' => 'Matches', 'action' => 'index', 'todo'=>'create'));
	echo $this->Form->button('Guardar Como Nuevo', array(
		'type'=>'button',
		'onclick' =>	"javascript:f=Jolaf.gebi('MatchForm');f.action='$url'; f.submit();",
	));
	echo $this->Form->end();

  if (count($matches)>0){
    echo "<table><tbody>";
    echo "<tr>";
    echo "<th>Fecha / Equipos / Resultado / Estadio (Ciudad)</th>";
    echo "<th>Operaciones</th>";
    echo "</tr>";
    foreach($matches as $id => $match){
      echo "<tr class='row-b'>";
      echo "<td>" . $match . "</td>";
      echo "<td>";
      $url = Router::url(array('controller' => 'Matches', 'action' => 'index', 'todo'=>'edit','id' => $id));
      echo $this->Form->button('Editar', array(
          'type'=>'button',
          'onclick' =>  "javascript:f=Jolaf.gebi('MatchForm');f.action='$url'; f.submit();",
      ));
      $url = Router::url(array('controller' => 'Matches', 'action' => 'index', 'todo'=>'delete', 'id' => $id));
      echo $this->Form->button('Eliminar', array(
          'type'=>'button',
          'onclick' =>  "javascript:if(prompt('Escriba ELIMINAR y presione enter')=='ELIMINAR') f=Jolaf.gebi('MatchForm');f.action='$url'; f.submit();",
      ));
      echo "</td>";
      echo "</tr>";
    }
    echo "</tbody></table>";
  }else{
    echo $this->element('flash_ok', array('message'=>'No hay partidos registrados para las opciones seleccionadas.'));
  }

