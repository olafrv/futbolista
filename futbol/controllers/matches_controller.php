<?php

	class MatchesController extends AppController {
	
		var $uses = array('Match', 'Competition', 'Stadium', 'Fase');
    var $helpers = array('Js' => array('Jquery'));
		var $layout = 'cake';

		function findAll($conditions=array()){
			$Matches = $this->Match->find('all', array(
				'contain'=>array(
					'PseudoTeamHost' => array(
						'fields' => array('id', 'abreviation')
						,'Team' => array(
							'fields' => array('id', 'title')
							,'Country'=> array(
								'fields'=>array('id','code')
							)
						)
						,'Groupping'=>array(
							'Fase'=>array(
								'Competition'=>array(
									'fields'=>array('id', 'sport')
								)
							)
						)
					)
					,'PseudoTeamGuest' => array(
						'fields' => array('id', 'abreviation')
						,'Team' => array(
							'fields' => array('id', 'title')
							,'Country'=> array(
								'fields'=>array('id','code')
							)
					 	)
						,'Groupping'=>array(
							'Fase'=>array(
								'Competition'=>array(
									'fields'=>array('id', 'sport')
								)
							)
						)
					)
					,'Stadium'
	 			)
				,'conditions'=>$conditions
				,'order'=>array("Match.kickoff")
			));
			return $Matches;
		}	
			
		function listAll($conditions=array(), $options=array()){ 
			$Matches = $this->findAll($conditions);
			$match_list = array();
			foreach($Matches as $Match){
				$host = isset($Match['PseudoTeamHost']['Team']['title']) ?
					 $Match['PseudoTeamHost']['Team']['title'] : $Match['PseudoTeamHost']['abreviation'];
 				$guest = isset($Match['PseudoTeamGuest']['Team']['title']) ?
					 $Match['PseudoTeamGuest']['Team']['title'] : $Match['PseudoTeamGuest']['abreviation'];
				$sport = $Match['PseudoTeamGuest']['Groupping']['Fase']['Competition']['sport'];		
				if (isset($Match['Match']['host_goals']) && isset($Match['Match']['guest_goals'])){
					if ($sport == 'Futbol'){
						$goals = $Match['Match']['host_goals'] . " - " . $Match['Match']['guest_goals'];
					}else{
						$goals = $Match['Match']['guest_goals'] . " - " . $Match['Match']['host_goals'];
					}
				}else{
					$goals = "?-?";
				}
				if ($sport == 'Futbol'){
					$match_list[$Match['Match']['id']] = "$host $goals $guest";
				}else{
					$match_list[$Match['Match']['id']] = "$guest $goals $host";
				}
				if (isset($options["show_stadium"]) && $options["show_stadium"]){
	 				 $match_list[$Match['Match']['id']] = $Match['Match']['kickoff']. " / " . $match_list[$Match['Match']['id']] . " / "
						 . $Match['Stadium']['title'] . ' (' . $Match['Stadium']['city'] . ')';
				}else{
	 				 $match_list[$Match['Match']['id']] = "(" . $Match['Match']['kickoff'].") " . $match_list[$Match['Match']['id']];
				}

			}
			return $match_list;
		}
	
		function listToday(){ 
			$conditions = array('DATE(Match.kickoff) = DATE("'.date('Y-m-d').'")');
			return $this->listAll($conditions);
		}
		
		function listYesterday(){ 
			$conditions = array('DATE(Match.kickoff) = DATE_SUB(DATE("'.date('Y-m-d').'"), INTERVAL 1 DAY)');
			return $this->listAll($conditions);
		}

		function listByGroupping($groupping_id){ 
			$conditions = array('Match.groupping_id =' =>$groupping_id);
 			return $this->listAll($conditions);
		}

		function listWithStadium($conditions=array()){
			return $this->listAll($conditions, array('show_stadium'=>true));
		}	

		function listFixtures(){ 
    	if (!empty($this->params['requested'])){
				$fase = $this->params['named']['fase'];
				$groupping = $this->params['named']['groupping'];
				$conditions = array('Fase.id'=>$fase);
				if ($groupping!="") $conditions['Match.groupping_id'] = $groupping;
				$this->Match->recursive = 1;
				$fixture_list = $this->Match->find('all',
					array(
						'fields'=>array('DISTINCT DATE(Match.kickoff) AS kickoff_YMD'),
						'conditions'=>$conditions,
						'order'=>array('DATE(Match.kickoff) ASC'),
						'link'=>array(
							'Groupping'=>array(
								'Fase'=>array()
							)
						)
					)
				);
				$fixture_array = array();
				foreach($fixture_list as $fixture){
					$fixture_array[] = $fixture[0]['kickoff_YMD'];
				}
				return $fixture_array;
			}
		}
		
		function index(){
		
			$this->layout = 'default';

			// BEGIN - ACTION MANAGEMENT
			//Select onchange? /Matches/onchange:[competition|fase|groupping]
			$onchange = isset($this->params['named']['onchange']) ? $this->params['named']['onchange'] : NULL;
			//Form button default action
			$action = array('controller'=> 'Matches', 'action' => 'index', 'todo'=>'create');
			//Todo?
			$todo = isset($this->params["named"]["todo"]) ? $this->params["named"]["todo"] : NULL;
			$save = false;
			switch($todo){
				case "create":
					$this->Match->create();
					$save = true;
					break;
				case "edit":
					$row = $this->Match->findById($this->params["named"]["id"]);
					$this->data["Match"] = $row["Match"];
					$this->data["Match"]["fase_id"] = $row["Groupping"]["fase_id"];
					$row = $this->Fase->findById($this->data["Match"]["fase_id"]);
					$this->data["Match"]["competition_id"] = $row["Fase"]["competition_id"];
					$action = array('controller'=> 'Matches', 'action' => 'index', 'todo'=>'save', 'id'=>$this->params["named"]["id"]);
					break;
				case "save":
					if (isset($this->params["named"]["id"])) $this->Match->set('id',$this->params["named"]["id"]);
					$save = true;
					break;
				case "delete":
					$this->layout = 'default';
					if ($this->Match->delete($this->params["named"]["id"])){
						$this->Session->setFlash('Registro eliminado', 'flash_ok');
					}else{
						$this->Session->setFlash('Error al eliminar el registro', 'flash_error');
					}
					break;
			}
			if ($save){
				if (!empty($this->params["data"])){
					if ($this->Match->save($this->params["data"]["Match"])){
						$this->Session->setFlash('Datos guardados correctamente.', 'flash_ok');
						// CLEAN FIELDS!!!
						unset($this->data["Match"]["id"]);
						unset($this->data['Match']['host_kickoff']);
						unset($this->data['Match']['host_id']);
						unset($this->data['Match']['host_goals']);
						unset($this->data['Match']['guest_id']);
						unset($this->data['Match']['guest_goals']);
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
				isset($this->data["Match"]["competition_id"]) ? $this->data["Match"]["competition_id"] : array_shift(array_keys($competitions));
			$this->set('competitions', $competitions);
			$this->set('competition_id', $competition_id);

			//Fase selection
			$fases = $this->requestAction('/fases/listAll/'.$competition_id);	
			$fase_id = NULL;
			if ($competition_id!=NULL){
				$fase_id = 
					isset($this->data["Match"]["fase_id"]) && $onchange!='competition' ? 
						$this->data["Match"]["fase_id"] : array_shift(array_keys($fases));
			}
			$this->set('fases', $fases);
			$this->set('fase_id', $fase_id);
			
			//Groupping selection
			$grouppings = $this->requestAction('/Grouppings/listAll/'.$fase_id);	
			$groupping_id = NULL;
			if ($fase_id!=NULL){
				$groupping_id = 
					isset($this->data["Match"]["groupping_id"]) && $onchange!='fase' && $onchange!='competition' ? 
						$this->data["Match"]["groupping_id"] : array_shift(array_keys($grouppings));
			}
			$this->set('grouppings', $grouppings);
			$this->set('groupping_id', $groupping_id);

			//Stadium selection
			$stadiums = $this->requestAction('/Stadia/listAll');
			$this->set('stadiums', $stadiums);

			// PseudoTeam (Team) -> Host/Guest selection
			if ($groupping_id!=NULL){
				$results = $this->Competition->Fase->Groupping->PseudoTeam->find(
					'all', array(
						'fields' => array('PseudoTeam.id', 'PseudoTeam.abreviation', 'Team.title', 'Team.abreviation'),
						'conditions' => array('PseudoTeam.groupping_id = ' . $groupping_id),
						'order' => 'PseudoTeam.abreviation, Team.title',
						'recursive' => 0
					)
				);
				$teams = array();
				foreach ($results as $result){
					$title = isset($result["Team"]['title']) ? 
						$result["Team"]['title'] . ' (' . $result["Team"]['abreviation'] . ')'  
							: $result["PseudoTeam"]['abreviation'] . ' (' . __('Indefinido', true) . ')';
					$teams[$result["PseudoTeam"]['id']] = $title;
				}
				$this->set('teams', $teams);				
			}

			//Matches selection
			$matches = $this->listWithStadium(array('Match.groupping_id'=>$groupping_id));
			$this->set('matches',$matches);	
		}
	}
	
