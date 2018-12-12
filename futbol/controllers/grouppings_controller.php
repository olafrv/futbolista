<?php

class GrouppingsController extends AppController {
	var $uses = array('Competition', 'Fase', 'Groupping');
  var $helpers = array('Js' => array('Jquery'));
	var $layout = 'cake';

	function listAll($fase_id){ //Users
		if (!empty($this->params['requested'])){
			return $this->Groupping->find('list',
				array('conditions'=>array('Groupping.fase_id =' =>$fase_id))
			);
		}
	}

	function destroy(){
		$this->layout = 'default';
		if ($this->Groupping->delete($this->params["named"]["id"])){
			$this->Session->setFlash('Grupo eliminado', 'flash_ok');
		}else{
			$this->Session->setFlash('Error al eliminar el grupo', 'flash_error');
		}
		$this->redirect('index');
	}

  function duplicate($groupping_id, $fase_id){
    $Groupping = $this->Groupping->findById($groupping_id);
    $Fase = $this->Fase->findById($fase_id);
    if (!empty($Groupping) && !empty($Fase)){
	    $this->Groupping->create();
      $Groupping = $Groupping["Groupping"];
      $Groupping["id"] = NULL;
      $Groupping["fase_id"] = $fase_id;
			$Groupping["title"] .= " Copy";
			$datasource = $this->Groupping->getDataSource();
			$datasource->begin($this->Groupping);
      if ($this->Groupping->save($Groupping)){
        $new_id = $this->Groupping->getLastInsertID();				
				$PseudoTeams = $this->Groupping->PseudoTeam->findAllByGrouppingId($groupping_id);
				$duplicated = true;
				foreach($PseudoTeams as $PseudoTeam){
					$duplicated = $duplicated && 
						($this->requestAction("/PseudoTeams/duplicate/".$PseudoTeam["PseudoTeam"]["id"]."/".$new_id)>0);
				}
				if ($duplicated){
				  $msg = "Registro duplicado correctamente (Id=$new_id)";
        	$error = 0;
				}else{
					$msg = "Error al duplicar los registros asociados";
					$error = -3;
				}
      }else{
        $msg = "Error al guardar el duplicado";
        $error = -1;
      }
    }else{
      $msg = "No existe el registro a duplicar o su registro asociado";
      $error = -2;
    }
		if ($error < 0){
			$datasource->rollback($this->Groupping);
		}else{
			$datasource->commit($this->Groupping);
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
		//Select onchange? /Grouppings/onchange:[competition|fase|groupping]
		$onchange = isset($this->params['named']['onchange']) ? $this->params['named']['onchange'] : NULL;
		//Form button default action
		$action = array('controller'=> 'Grouppings', 'action' => 'index', 'todo'=>'create'); 
		// Todo?
		$todo = isset($this->params["named"]["todo"]) ? $this->params["named"]["todo"] : NULL;
		$save = false;
		switch($todo){
			case "create":
				$this->Groupping->create();
				$save = true;
				break;
			case "edit":
				$row = $this->Groupping->findById($this->params["named"]["id"]);
				$this->data["Groupping"] = $row["Groupping"];
				$this->data["Groupping"]["fase_id"] = $row["Groupping"]["fase_id"];
				$row = $this->Fase->findById($this->data["Groupping"]["fase_id"]);
				$this->data["Groupping"]["competition_id"] = $row["Fase"]["competition_id"];
				$action = array('controller'=> 'Grouppings', 'action' => 'index', 'todo'=>'save', 'id'=>$this->params["named"]["id"]);
				break;
			case "save":
				if (isset($this->params["named"]["id"])) $this->Groupping->set('id',$this->params["named"]["id"]);
				$save = true;
				break;
			case "delete":
				$this->layout = 'default';
				if ($this->Groupping->delete($this->params["named"]["id"])){
					$this->Session->setFlash('Registro eliminado', 'flash_ok');
				}else{
					$this->Session->setFlash('Error al eliminar el registro', 'flash_error');
				}
				break;
		}
		if ($save){
			if (!empty($this->params["data"])){
				if ($this->Groupping->save($this->params["data"]["Groupping"])){
					$this->Session->setFlash('Datos guardados correctamente.', 'flash_ok');
					// CLEAN FIELDS!!!
					unset($this->data["Groupping"]["id"]);
					unset($this->data["Groupping"]["title"]);
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
			isset($this->data["Groupping"]["competition_id"]) ? 
				$this->data["Groupping"]["competition_id"] : array_shift(array_keys($competitions));
		$this->set('competitions', $competitions);
		$this->set('competition_id', $competition_id);

		//Fase selection
		$fases = $this->requestAction('/fases/listAll/'.$competition_id);	
		$fase_id = NULL;
		if ($competition_id!=NULL){
			$fase_id = 
				isset($this->data["Groupping"]["fase_id"]) && $onchange!='competition' ? 
					$this->data["Groupping"]["fase_id"] : array_shift(array_keys($fases));
		}
		$this->set('fases', $fases);
		$this->set('fase_id', $fase_id);

		$grouppings = NULL;
		if (!is_null($fase_id)){
			$grouppings = $this->Groupping->find('all', array('conditions'=>array('Groupping.fase_id =' =>$fase_id)));
		}
		if (is_null($grouppings) || count($grouppings)==0) $grouppings = NULL;
		$this->set('grouppings', $grouppings);
		
	}

}

