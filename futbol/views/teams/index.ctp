<?php

	echo '<h1>Administración de Equipos</h1>';

	echo $this->Form->create('Team', array('action' => '/index', 'id'=>'TeamForm'));
	echo $this->Form->input('title', array('type'=>'text','label'=>'Título'));
	echo $this->Form->input('abreviation', array('type' => 'text','label' => 'Abreviatura', 'maxlength'=>3, 'size'=>3));
	echo $this->Form->input('website', array('type' => 'text','label' => 'Página Web'));
	echo "<br>";
  echo $this->Form->checkbox('has_shield', array('label' => false,'div' => false));
	echo " ¿Tiene imagen de escudo? " . $html->link("img/shields/ABREVIATURA[_big1].png", "/img/shields/");
	echo "<br>";
  echo $this->Form->input('stadium_id', array('type'=>'select','empty'=>true,'label'=>'Estadio'));
  echo $this->Form->input('country_id', array('type'=>'select','empty'=>false,'label'=>'País'));
	echo "<br>";
	echo $this->Form->button('Guardar', array(
			'type'=>'button', 'class' => 'button-green',
			'onclick' =>	"javascript:f=Jolaf.gebi('TeamForm');f.action='".Router::url($action)."'; f.submit();",
	));
	echo "&nbsp;";
	echo $this->Form->button('Deshacer Cambios', array(
		'type'=>'reset', 'class' => 'button-red'
	));
	echo "&nbsp;";
	$url = Router::url(array('controller' => 'Teams', 'action' => 'index', 'todo'=>'create'));
	echo $this->Form->button('Guardar Como Nuevo', array(
					'type'=>'button', 'class' => 'button-green',
		'onclick' =>	"javascript:f=Jolaf.gebi('TeamForm');f.action='$url'; f.submit();",
	));
	echo $this->Form->end();	
	
	if (!empty($teams)){
		echo "<table><tbody>";
	  echo "<tr>";
	  echo "<th>Título (Id)</th>";
	  echo "<th>Abreviatura</th>";
		echo "<th>Website</th>";
		echo "<th>Escudo</th>";
		echo "<th>Estadio</th>";
		echo "<th>País</th>";
	  echo "<th>Operaciones</th>";
	  echo "</tr>";	
		$i = 0;
		foreach($teams as $team){
			$Team = $team["Team"];
			$Stadium = $team["Stadium"];
			$Country = $team["Country"];
			$flag = $shield = "";
		  if (isset($Country["code"])){
				$flag = $this->Html->image('/img/flags/' .  $Country["code"] . '.gif', array('border'=>0, 'class'=>'borderless')) . "&nbsp;";
			} 
	    if ($Team["has_shield"]==1){
	    	$shield = $this->Html->image('/img/shields/' .  $Team["abreviation"] . '.png', array('border'=>0, 'class'=>'borderless')) . "&nbsp;";
			}
			if ($i++%2==0){
				echo "<tr class='row-a'>";
			}else{
				echo "<tr class='row-b'>";
			}	
			echo "<td align='center'>" . $Team["title"] . " (" . $Team["id"] . ")"  . "</td>";
			echo "<td>" . $Team["abreviation"] . "</td>";
			echo "<td><a href='" . $Team["website"] . "'>RSS</a></td>";
			echo "<td align='center'>" . ($Team["has_shield"]==1 ? $shield : __("No", true)) . "</td>";
			echo "<td>" . $Stadium["title"] . "</td>";
			echo "<td>" . $flag . $Country["title"] . "</td>";
			echo "<td>";
			$url = Router::url(array('controller' => 'Teams', 'action' => 'index', 'todo'=>'edit','id' => $Team["id"]));
			echo $this->Form->button('Editar', array(
					'type'=>'button', 'class' => 'button-green',
					'onclick' =>	"javascript:f=Jolaf.gebi('TeamForm');f.action='$url'; f.submit();",
			));
			$url = Router::url(array('controller' => 'Teams', 'action' => 'index', 'todo'=>'delete','id' => $Team["id"]));			
			echo $this->Form->button('Eliminar', array(
					'type'=>'button', 'class' => 'button-red',
					'onclick' =>	"javascript:if(prompt('Escriba ELIMINAR y presione enter')=='ELIMINAR') f=Jolaf.gebi('TeamForm');f.action='$url'; f.submit();",
			));
			$url = Router::url(array('controller' => 'Teams', 'action' => 'duplicate', $Team["id"]));
			echo $this->Form->button('Duplicar', array(
					'type'=>'button', 'class' => 'button-blue',
					'onclick' =>	"javascript:if(prompt('Escriba DUPLICAR y presione enter')=='DUPLICAR') f=Jolaf.gebi('TeamForm');f.action='$url'; f.submit();",
			));
			echo "</td>";
			echo "</tr>";
		}
		echo "</tbody></table>";
	}else{	
		echo $this->element('flash_ok', array('message'=>'No hay competencias registradas.'));
	}
	


