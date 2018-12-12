<?php

class FasesController extends AppController {
	var $scaffold;
	var $layout = 'cake';

	function current($competition_id, $now=NULL){ //Users
		if ($now==NULL) $now = date('Y-m-d');
		if (!empty($this->params['requested'])){
			$now = "\"" . $now . "\"";
			$fase = $this->Fase->find(
				'list',
        	array(
          	'conditions'=>array(
        	  'Fase.competition_id =' => $competition_id,
						'Fase.begins <= DATE(' . $now . ')',
						'Fase.ends >= DATE(' . $now . ')'
					)
    		)
			);
			return $fase;
  	}
	}

	function lastest($competition_id, $now=NULL){ //Users
         if ($now==NULL) $now = date('Y-m-d');
         if (!empty($this->params['requested'])){
            $fase = $this->Fase->find(
               'list',
               array(
                  'conditions'=>array(
                     'Fase.competition_id =' => $competition_id,
                     'OR' => array(
                        array(
                           'Fase.begins <= DATE("' . $now . '")',
                           'Fase.ends >= DATE("' . $now . '")'
                        ),
                        'Fase.begins > DATE("' . $now . '")'
                     )
                  )
                  , 'limit' => 1
                  , 'order' => 'Fase.begins'
               )
            );
            return $fase;
         }
      }

		function listAll($competition_id){ //Users
			return $this->Fase->find(
				'list',
				array(
					'conditions'=>array('Fase.competition_id'=>$competition_id),
					'order' => array('Fase.begins' => 'ASC')
				)
			);
		}

		function listEliminations($competition_id){ //Users
			if (!empty($this->params['requested'])){
				 return $this->Fase->find(
              'list',
              array(
                 'conditions'=>array(
							'Fase.competition_id'=>$competition_id,
							'Fase.is_elimination'=>true
						),
                 'order' => array('Fase.begins' => 'ASC')
              )
           );
			}
		}

  function duplicate($fase_id, $competition_id){
    $Fase = $this->Fase->findById($fase_id);
    $Competition = $this->Fase->Competition->findById($competition_id);
    if (!empty($Fase) && !empty($Competition)){
	    $this->Fase->create();
      $Fase = $Fase["Fase"];
      $Fase["id"] = NULL;
      $Fase["competition_id"] = $competition_id;
			$Fase["title"] .= " Copy";
      $datasource = $this->Fase->getDataSource();
      $datasource->begin($this->Fase);
      if ($this->Fase->save($Fase)){
        $new_id = $this->Fase->getLastInsertID();
        $Grouppings = $this->Fase->Groupping->findAllByFaseId($fase_id);
        $duplicated = true;
        foreach($Grouppings as $Groupping){
          $duplicated = $duplicated &&
            ($this->requestAction("/Grouppings/duplicate/"
							.$Groupping["Groupping"]["id"]."/".$new_id)>0);
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
      $datasource->rollback($this->Fase);
    }else{
      $datasource->commit($this->Fase);
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
		//Form button default action
		$action = array('controller'=> 'Fases', 'action' => 'index', 'todo'=>'create'); 
		// Todo?
		$todo = isset($this->params["named"]["todo"]) ? $this->params["named"]["todo"] : NULL;
		$save = false;
		switch($todo){
			case "create":
				$this->Fase->create();
				$save = true;
				break;
			case "edit":
				$row = $this->Fase->findById($this->params["named"]["id"]);
				$this->data["Fase"] = $row["Fase"];
				$action = array('controller'=> 'Fases', 'action' => 'index', 'todo'=>'save', 'id'=>$this->params["named"]["id"]);
				break;
			case "save":
				if (isset($this->params["named"]["id"])) $this->Fase->set('id',$this->params["named"]["id"]);
				$save = true;
				break;
			case "delete":
				$this->layout = 'default';
				if ($this->Fase->delete($this->params["named"]["id"])){
					$this->Session->setFlash('Registro eliminado', 'flash_ok');
				}else{
					$this->Session->setFlash('Error al eliminar el registro', 'flash_error');
				}
				break;
		}
		if ($save){
			if (!empty($this->params["data"])){
				if ($this->Fase->save($this->params["data"]["Fase"])){
					$this->Session->setFlash('Datos guardados correctamente.', 'flash_ok');
					// CLEAN FIELDS!!!
					unset($this->data["Fase"]["id"]);
					unset($this->data["Fase"]["title"]);
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
      isset($this->data["Fase"]["competition_id"]) ?
        $this->data["Fase"]["competition_id"] : array_shift(array_keys($competitions));
    $this->set('competitions', $competitions);
    $this->set('competition_id', $competition_id);

    //Fase selection
    $fases = $this->Fase->findAllByCompetitionId($competition_id);
    $fase_id = NULL;
    if ($competition_id!=NULL){
      $fase_id =
        isset($this->data["Fase"]["fase_id"]) && $onchange!='competition' ?
          $this->data["Fase"]["fase_id"] : array_shift(array_keys($fases));
    }
    $this->set('fases', $fases);
    $this->set('fase_id', $fase_id);

	}

}

