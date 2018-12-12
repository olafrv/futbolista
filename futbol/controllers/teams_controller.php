<?php

class TeamsController extends AppController {

	var $layout = 'cake';

	function show(){ //Users
			$this->layout = 'popup';
			$this->Team->recursive = 0; //Country
			$Team = $this->Team->findById($this->params['named']['id']);
			$this->set("Team", $Team);
		}

	function listAll(){ //Users
		return $this->Team->find('list');
	}

	function index(){
		$this->layout = 'default';

		// BEGIN - ACTION MANAGEMENT
		//Form button default action
		$action = array('controller'=> 'Teams', 
			'action' => 'index', 'todo'=>'create'); 
		// Todo?
		$todo = isset($this->params["named"]["todo"]) ? 
			$this->params["named"]["todo"] : NULL;
		$save = false;
		switch($todo){
			case "create":
				$this->Team->create();
				$save = true;
				break;
			case "edit":
				$row = $this->Team->findById($this->params["named"]["id"]);
				$this->data["Team"] = $row["Team"];
				$action['todo'] = 'save';
				$action['id'] = $this->params["named"]["id"];
				break;
			case "save":
				if (isset($this->params["named"]["id"])) 
					$this->Team->set('id',$this->params["named"]["id"]);
				$save = true;
				break;
			case "delete":
				$this->layout = 'default';
				if ($this->Team->delete($this->params["named"]["id"])){
					$this->Session->setFlash('Registro eliminado', 'flash_ok');
				}else{
					$this->Session->setFlash('Error al eliminar el registro',	'flash_error');
				}
				break;
		}
		if ($save){
			if (!empty($this->params["data"])){
				if ($this->Team->save($this->params["data"]["Team"])){
					$this->Session->setFlash('Datos guardados correctamente.', 'flash_ok');
					// CLEAN FIELDS!!!
					unset($this->data["Team"]["id"]);
					unset($this->data["Team"]["abreviation"]);
					unset($this->data["Team"]["title"]);
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
	  $teams = $this->Team->find('all');
		$this->set('teams', $teams);

		//Stadium selection
	  $stadia = $this->Team->Stadium->find('list');
		$this->set('stadia', $stadia);

		//Country selection
	  $countries = $this->Team->Country->find('list');
		$this->set('countries', $countries);



	}


}

