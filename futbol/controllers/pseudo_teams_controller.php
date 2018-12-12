<?php

class PseudoTeamsController extends AppController {

	var $uses = array('Competition', 'Fase', 'Groupping', 'PseudoTeam');
  //var $helpers = array('Js' => array('Jquery'));
	var $layout = 'cake';

	function listAll($groupping_id){ //Users
		if (!empty($this->params['requested'])){
			return $this->PseudoTeam->find('list',
				array('conditions'=>array('PseudoTeam.groupping_id =' =>$groupping_id))
			);
		}
	}

	function duplicate($pseudoteam_id, $groupping_id){
		$PseudoTeam = $this->PseudoTeam->findById($pseudoteam_id);
		$Groupping = $this->Groupping->findById($groupping_id);
		if (!empty($PseudoTeam) && !empty($Groupping)){
			$this->PseudoTeam->create();
			$PseudoTeam = $PseudoTeam["PseudoTeam"];
			$PseudoTeam["id"] = NULL;
			$PseudoTeam["groupping_id"] = $groupping_id;
			if ($this->PseudoTeam->save($PseudoTeam)){
				$new_id = $this->PseudoTeam->getLastInsertID();
				$msg = "Registro duplicado correctamente (Id=$new_id)";
				$error = 0;
			}else{
				$msg = "Error al guardar el duplicado";
				$error = -1;
			}
		}else{
			$msg = "No existe el registro a duplicar o su registro asociado";
			$error = -2;	
		}		
		if (!empty($this->params['requested'])){
			if ($error < 0){
				return $error;
			}else{
				return $new_id;
			}
		}else{
    	if ($error < 0) {
				$this->Session->setFlash($msg, 'flash_error');
    	}else{
				$this->Session->setFlash($msg, 'flash_ok');    	
    	}
		}
	}

	function index(){

		$this->layout = 'default';

		// BEGIN - ACTION MANAGEMENT
		//Select onchange? /PseudoTeams/onchange:[competition|fase|groupping]
		$onchange = isset($this->params['named']['onchange']) ? $this->params['named']['onchange'] : NULL;
		//Form button default action
		$action = array('controller'=> 'PseudoTeams', 'action' => 'index', 'todo'=>'create'); 
		// Todo?
		$todo = isset($this->params["named"]["todo"]) ? $this->params["named"]["todo"] : NULL;
		$save = false;
		switch($todo){
			case "create":
				$this->PseudoTeam->create();
				$save = true;
				break;
			case "edit":
				$row = $this->PseudoTeam->findById($this->params["named"]["id"]);
				$this->data["PseudoTeam"] = $row["PseudoTeam"];
				$this->data["PseudoTeam"]["fase_id"] = $row["Groupping"]["fase_id"];
				$row = $this->Fase->findById($this->data["PseudoTeam"]["fase_id"]);
				$this->data["PseudoTeam"]["competition_id"] = $row["Fase"]["competition_id"];
				$action = array('controller'=> 'PseudoTeams', 'action' => 'index', 'todo'=>'save', 'id'=>$this->params["named"]["id"]);
				break;
			case "save":
				if (isset($this->params["named"]["id"])) $this->PseudoTeam->set('id',$this->params["named"]["id"]);
				$save = true;
				break;
			case "delete":
				$this->layout = 'default';
				if ($this->PseudoTeam->delete($this->params["named"]["id"])){
					$this->Session->setFlash('Registro eliminado', 'flash_ok');
				}else{
					$this->Session->setFlash('Error al eliminar el registro', 'flash_error');
				}
				break;
		}
		if ($save){
			if (!empty($this->params["data"])){
				if ($this->PseudoTeam->save($this->params["data"]["PseudoTeam"])){
					$this->Session->setFlash('Datos guardados correctamente.', 'flash_ok');
					// CLEAN FIELDS!!!
					unset($this->data["PseudoTeam"]["id"]);
					unset($this->data["PseudoTeam"]["abreviation"]);
					unset($this->data["PseudoTeam"]["team_id"]);
				}else{
					$this->Session->setFlash(__('Error al guardar los datos', true) , 'flash_error');	
				}	
			}else{
				$this->Session->setFlash(__('Error datos inválidos', true) , 'flash_error');	
			}
		}
		$this->set('action', $action);
		// END - ACTION MANAGEMENT

		//Competition selection
	  $competitions = $this->requestAction('/competitions/listAll');
		$competition_id = 
			isset($this->data["PseudoTeam"]["competition_id"]) ? 
				$this->data["PseudoTeam"]["competition_id"] : array_shift(array_keys($competitions));
		$this->set('competitions', $competitions);
		$this->set('competition_id', $competition_id);

		//Fase selection
		$fases = $this->requestAction('/fases/listAll/'.$competition_id);	
		$fase_id = NULL;
		if ($competition_id!=NULL){
			$fase_id = 
				isset($this->data["PseudoTeam"]["fase_id"]) && $onchange!='competition' ? 
					$this->data["PseudoTeam"]["fase_id"] : array_shift(array_keys($fases));
		}
		$this->set('fases', $fases);
		$this->set('fase_id', $fase_id);

		//Groupping selection
		$grouppings = $this->requestAction('/Grouppings/listAll/'.$fase_id);	
		$groupping_id = NULL;
		if ($fase_id!=NULL){
			$groupping_id = 
				isset($this->data["PseudoTeam"]["groupping_id"]) && $onchange!='fase' && $onchange!='competition' ? 
					$this->data["PseudoTeam"]["groupping_id"] : array_shift(array_keys($grouppings));
		}
		$this->set('grouppings', $grouppings);
		$this->set('groupping_id', $groupping_id);
    
		//Pseudoteam selection
    $this->set("pseudoteams", $this->PseudoTeam->find('all', array(
			'conditions'=>array('PseudoTeam.groupping_id' => $groupping_id)
		)));

		//Team selection
		$this->set('teams',$this->requestAction('/Teams/listAll'));

	}

}
