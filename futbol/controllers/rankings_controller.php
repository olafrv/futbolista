<?php
   class RankingsController extends AppController {
	   var $scaffold;	
		var $uses = array('Ranking');
		var $layout = 'cake';

		function show(){ //Users
			$this->layout = 'default';
		  $this->helpers[] = 'FutbolGui';	
		
			//Competition
			$competition_list = $this->requestAction('/competitions/listAll', array('cache'=>'+1 hour'));
      if (isset($this->data['Ranking']['competition_id'])){
        $competition_id =  $this->data['Ranking']['competition_id'];
      }else if ($this->Session->read('UserPref.Competition.id')>0){
        $competition_id = $this->Session->read('UserPref.Competition.id');
      }else if (count($competition_list)>0){
        $competition_id = array_shift(array_keys($competition_list));
      }else{
				$competition_id = NULL;
			}
      $this->Session->write('UserPref.Competition.id', $competition_id);

			$competition_sport = $this->requestAction('/competitions/getSport/'.$competition_id, array('cache'=>'+1 hour'));

			//Fase
			$fase_list = $this->requestAction('/fases/listEliminations/'.$competition_id, array('cache'=>'+1 hour'));
			$fase_id = isset($this->data['Ranking']['fase_id']) ? $this->data['Ranking']['fase_id'] : 
				array_shift(array_keys($fase_list)); 

			//Groupping
			$groupping_list = array();
			$groupping_id = null;
			if (!empty($fase_id)){
				$groupping_list = $this->requestAction('/grouppings/listAll/'.$fase_id, array('cache'=>'+1 hour'));
				$groupping_id = isset($this->data['Ranking']['groupping_id']) ? $this->data['Ranking']['groupping_id'] : 
					array_shift(array_keys($groupping_list));
			}

			$contain = array(
				'PseudoTeam' => array(
					'fields' => array('id', 'abreviation')
					,'Team' => array(
						'fields' => array('id', 'title', 'has_shield', 'abreviation')
						,'Country'=> array(
							'fields'=>array('id','code')
						)
					)
					,'Groupping'=>array(
						'fields' => array('id'),
					)
				)
			);
			
			$ranking_table = $this->Ranking->find('all', 
				array(
					'contain'=>$contain,
					'conditions' => array(
						'PseudoTeam.groupping_id =' => $groupping_id,
					),
					'order' => array (
						'Ranking.points DESC',
						'Ranking.diff_goals DESC'
					)
				)
			);

			if (count($ranking_table)==0){
	            $this->Session->setFlash(__('Todavía no hay resultados de partidos para generar una tabla, o bien, esta estadística por acumulación de puntos no aplica para la fase y competencia selecionadas.', true), 'flash_ok');
			}

			$this->set('homeaway', $this->data['Ranking']['homeaway']);
			$this->set('ranking_table', $ranking_table);
			$this->set('competition_list', $competition_list); 
			$this->set('competition_id', $competition_id); 
			$this->set('competition_sport', $competition_sport); 
			$this->set('fase_list', $fase_list); 
			$this->set('fase_id', $fase_id); 
			$this->set('groupping_list', $groupping_list); 
			$this->set('groupping_id', $groupping_id); 
		}

}
