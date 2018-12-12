<?php

class CompetitionsController extends AppController {
	
	var $layout = "cake";
	var $uses = array('Competition','Url');

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('listAll', 'listOpened', 'listOpenedOrRecentlyClosed','info','listCosts'));
	}

	function getSport($competition_id){
		$result = $this->Competition->findById($competition_id, array('sport'));
		return !empty($result) ? $result["Competition"]["sport"] : NULL;
	}

	function listOpened(){
		$data = $this->Competition->find('list',
			array('conditions'=>
				array(
					'DATE(Competition.begins) <= DATE("' . date('Y-m-d') . '")',
					'DATE(Competition.ends) >= DATE("' . date('Y-m-d') . '")'					
				)
			)
		);
/*		if (!empty($this->params['requested']) || $inner){ */
		return $data;
/*		}else{
			$this->layout="default";
			$this->set("data", $data);
			$this->render("debug");
		}
*/
	}

	function listWithTop10(){
		$this->loadModel('Match');	
		$competitions = $this->listOpenedOrClosed();
		$competitions_top10 = array();
		foreach($competitions as $competition_id => $competition_title){
			$competition_top10 = $this->Match->find('first',
				array(
					'link' => array(
							'Groupping'=>array(
								'Fase'=> array(
									'Competition'=>array(
									)
								)
							)
						)
						, 'conditions'=>array(
							'Competition.id = ' . $competition_id,
							'DATE(Match.kickoff) <= DATE(NOW())',
							'Match.host_goals IS NOT NULL',
							'Match.guest_goals IS NOT NULL'
						)
				)
			);
			if (!empty($competition_top10)) $competitions_top10[$competition_id] = $competition_title;
		}
		return $competitions_top10;
	}	

	function listOpenedOrClosed(){
		$data = $this->Competition->find('list',
			array('conditions'=>
				array(
					array('or' =>
						array(
							array(
								'DATE(Competition.begins) <= DATE("' . date('Y-m-d') . '")',
								'DATE(Competition.ends) >= DATE("' . date('Y-m-d') . '")'					
							),
							array(
								'DATE(Competition.ends) < DATE("' . date('Y-m-d') . '")'
							)
						)
					)
				)
			)
		);
/*		if (!empty($this->params['requested']) || $inner){ */
		return $data;
	}

	function listOpenedOrRecentlyClosed(){
		$data = $this->Competition->find('list',
			array('conditions'=>
				array(
					'DATE(Competition.begins) <= DATE("' . date('Y-m-d') . '")',
					'DATE(Competition.ends) >= DATE_SUB(DATE("' . date('Y-m-d') . '"), INTERVAL 1 DAY)'
				)
			)
		);
/*		if (!empty($this->params['requested'])){ */
		return $data;
/*		}else{
			$this->layout="default";
			$this->set("data", $data);
			$this->render("debug");
		}
*/
	}

	function listAll(){
		return $this->Competition->find('list');
	}

	function listCosts(){
		if (!empty($this->params['requested'])){
			return $this->Competition->find(
				'all',
				array(
					'fields'=> array('Competition.title', 'Competition.id', 'Competition.cost', 'Competition.begins', 'Competition.ends'),
					'conditions' => array("DATE(Competition.ends) >= DATE(NOW())")
				)
			);
		}
	}

	function duplicate($competition_id){
		$Competition = $this->Competition->findById($competition_id);
		if (!empty($Competition)){
			$this->Competition->create();
			$Competition = $Competition["Competition"];
			$Competition["id"] = NULL;
			$Competition["title"] .= " Copy";
      $datasource = $this->Competition->getDataSource();
 	    $datasource->begin($this->Competition);
			if ($this->Competition->save($Competition)){
				$new_id = $this->Competition->getLastInsertID();
        $Fases = $this->Competition->Fase->findAllByCompetitionId($competition_id);
        $duplicated = true;
        foreach($Fases as $Fase){
          $duplicated = $duplicated &&
            ($this->requestAction("/Fases/duplicate/".$Fase["Fase"]["id"]."/".$new_id)>0);
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
			$msg = "No existe el registro a duplicar";
			$error = -2;
		}
    if ($error < 0){
      $datasource->rollback($this->Competition);
    }else{
      $datasource->commit($this->Competition);
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
							$this->layout = 'default';
    }
	}

	function index(){
		$this->layout = 'default';

		// BEGIN - ACTION MANAGEMENT
		//Form button default action
		$action = array('controller'=> 'Competitions', 'action' => 'index', 'todo'=>'create'); 
		// Todo?
		$todo = isset($this->params["named"]["todo"]) ? $this->params["named"]["todo"] : NULL;
		$save = false;
		switch($todo){
			case "create":
				$this->Competition->create();
				$save = true;
				break;
			case "edit":
				$row = $this->Competition->findById($this->params["named"]["id"]);
				$this->data["Competition"] = $row["Competition"];
				$action = array('controller'=> 'Competitions', 'action' => 'index', 'todo'=>'save', 'id'=>$this->params["named"]["id"]);
				break;
			case "save":
				if (isset($this->params["named"]["id"])) $this->Competition->set('id',$this->params["named"]["id"]);
				$save = true;
				break;
			case "delete":
				$this->layout = 'default';
				if ($this->Competition->delete($this->params["named"]["id"])){
					$this->Session->setFlash('Registro eliminado', 'flash_ok');
				}else{
					$this->Session->setFlash('Error al eliminar el registro', 'flash_error');
				}
				break;
		}
		if ($save){
			if (!empty($this->params["data"])){
				if ($this->Competition->save($this->params["data"]["Competition"])){
					$this->Session->setFlash('Datos guardados correctamente.', 'flash_ok');
					// CLEAN FIELDS!!!
					unset($this->data["Competition"]["id"]);
					unset($this->data["Competition"]["title"]);
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
	  $competitions = $this->Competition->find('all');
		$this->set('competitions', $competitions);

	}

	function info(){ //Users
		$this->loadModel('Photo');
		$this->layout = 'default';
		$this->helpers[] = 'FutbolGui';

		$competition_list = $this->requestAction('/competitions/listAll', array('cache'=>'+1 hour'));

		$competition_id = $this->data['Competition']['competition_id'];

		if (empty($competition_id)) $competition_id = $this->Session->read('UserPref.Competition.id');
		if (empty($competition_id)){
			$result = $this->Competition->find('first');
			$competition_id = $result['Competition']['id'];
		}

		$urls = $this->Competition->Url->find('all',
			array(
				'conditions' => array('Url.competition_id = ' => $competition_id)
			)
		);

		$this->set('urls', $urls);
		$this->set('competition_list', $competition_list);			
		$this->set('competition_id', $competition_id);			
		$this->set("photos", $this->Photo->find('all', 
			array('conditions'=>array(
						'Photo.competition_id' => $competition_id,
						'Photo.public' => 1
					)
				)
			)
		);

	}

	function news(){ //Users
		$this->layout = 'default';
		App::import('Component', 'Simplepie');
		$this->Simplepie = new SimplepieComponent();
		$this->Simplepie->startup($this);
		$this->Competition->recursive = -1;
		$competition = $this->Competition->find('first',
			array(
			 'fields' => array('Competition.rss', 'Competition.title'),
			 'order'=>array('Competition.begins'=>'DESC')
			)
		);
		$title = "";
		$rss = array();
		if (isset($competition['Competition'])){
			$url = $competition['Competition']['rss'];
			if (!is_null($url) && $url!=""){
				$title = $competition['Competition']['title'];
				$this->Simplepie->simplepie->set_cache_location(TMP);
				$this->Simplepie->simplepie->set_cache_duration(900); //15 minutes
				$this->Simplepie->simplepie->set_autodiscovery_cache_duration(3600); // 1 hour
				$rss = $this->Simplepie->fetch($url, 5); //5 news
			}
		}
		$this->set('rss_url', $url);
		$this->set('competition_title', $title);
		$this->set('competition_rss', $rss);
	}

	function display(){
		$this->loadModel('Photo');
		$this->layout = 'default';
		$this->set("photos", $this->Photo->find('all', array(
			'conditions'=>array(
						'Photo.homepage' => 1						
			)
			, 'limit' => 6
			, 'order' => array('modified'=>'DESC')
		)));
	}

}
