<?php
class PhotosController extends AppController {
//	var $scaffold;
	var $name = 'Photos';
	var $uses = array('Photo', 'Competition');
	var $layout = 'default';
	function show(){
		$competitions = $this->requestAction('/competitions/listAll', array('cache'=>'+1 hour'));
    $competition_id = isset($this->data["Photo"]["competition_id"]) ? $this->data["Photo"]["competition_id"] : 
			$this->Session->read('UserPref.Competition.id');
		if (is_null($competition_id))	$competition_id = count($competitions)>0 ? array_shift(array_keys($competitions)): NULL;
    $this->set('competitions', $competitions);
    $this->set('competition_id', $competition_id);
		$conditions = array();
		if (!is_null($competition_id)) $conditions = array('Photo.competition_id' => $competition_id, 'Photo.public'=>0);
		$conditions["Photo.public"]=0;
		$conditions["Photo.homepage"]=0;
		$photos = $this->Photo->find('all', array('conditions'=>$conditions));
		if (count($photos)==0) $this->Session->setFlash('Aún no hay fotos cargadas para esta competencia.', 'flash_ok');
		$this->set("photos", $photos); 
	}
	
	
	function add(){
		$this->layout = 'default';

		// BEGIN - ACTION MANAGEMENT
		//Form button default action
		$action = array('controller'=> 'Photos', 'action' => 'add', 'todo'=>'create'); 
		// Todo?
		$todo = isset($this->params["named"]["todo"]) ? $this->params["named"]["todo"] : NULL;
		$save = false;
		switch($todo){
			case "create":
				$this->Photo->create();
				$save = true;
				break;
			case "edit":
				$row = $this->Photo->findById($this->params["named"]["id"]);
				$this->data["Photo"] = $row["Photo"];
				$action = array('controller'=> 'Photos', 'action' => 'add', 'todo'=>'save', 'id' => $this->params["named"]["id"]);
				break;
			case "save":
				if (isset($this->params["named"]["id"])) $this->Photo->set('id',$this->params["named"]["id"]);
				$save = true;
				break;
			case "delete":
				$this->layout = 'default';
				$row = $this->Photo->findById($this->params["named"]["id"]);
				if ($this->Photo->delete($this->params["named"]["id"])){
					if (unlink(WWW_ROOT . 'img' . DS . 'photos' . DS . $row["Photo"]["name"])){
						$this->Session->setFlash('Registro y archivo eliminados', 'flash_ok');
					}else{
						$this->Session->setFlash('Registro eliminado, pero el archivo no fue eliminado', 'flash_error');
					}
				}else{
					$this->Session->setFlash('Error al eliminar el registro', 'flash_error');
				}
				break;
		}

		if ($save){
			if (!empty($this->params["data"])){
			  $content = $this->params["data"]["Photo"]["content"];
				if (!empty($content["tmp_name"]) && isset($content['error']) && $content["error"] != 0){
					$this->Session->setFlash('Error ' . $content["error"] . ' al cargar el archivo','flash_error');
				}else{											
					if (empty($content["name"]) || move_uploaded_file($content["tmp_name"], WWW_ROOT . 'img' . DS . 'photos' . DS . $content["name"])){
						if (!empty($content["name"])){
							$this->params["data"]["Photo"]["name"] = $content["name"];
							$this->params["data"]["Photo"]["type"] = $content["type"];
							$this->params["data"]["Photo"]["size"] = $content["size"];
						}											
						if ($this->Photo->save($this->params["data"]["Photo"])){
							$this->Session->setFlash('Datos guardados correctamente.', 'flash_ok');
							// CLEAN FIELDS!!!
							unset($this->data["Photo"]);										
						}else{
							$this->Session->setFlash(__('Error al guardar los datos', true) , 'flash_error');	
						}
					}else{
						$this->Session->setFlash("Error al mover el archivo a '$dest'","flash_error");
					}
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
      isset($this->data["Photo"]["competition_id"]) ?
        $this->data["Photo"]["competition_id"] : array_shift(array_keys($competitions));
    $this->set('competitions', $competitions);
    $this->set('competition_id', $competition_id);

    //Photo selection
    $photos = $this->Photo->findAllByCompetitionId($competition_id);
    $this->set('photos', $photos);
  
  }
	
}
