<?php
	class BetsController extends AppController {

		var $uses = array('Bet', 'Competition');
		var $layout = 'cake';	

		function beforeFilter() {
    	parent::beforeFilter();
			$this->Auth->allow(array('rules', 'contact', 'audit', 'crosstop10')); // Anonymous + Token Access

			// Token autentication
			if (isset($this->params['named']['token'])){
				if (Configure::read('Futbol.wsFutbolistaToken') == $this->params['named']['token']){
			    App::import('Controller', 'Users');
			    $Users = new UsersController();
			    $Users->constructClasses();

        // --- Eliminada dependencia de config/password.php
        //$password = Security::hash(Configure::read('Password.admin'), null, true);
        //Configure::write('debug', 1);
        $Users->loadModel('Users');
        $Users->User->recursive = false;
        $result = $Users->User->findByUsername("admin");
        $password = $result["User"]["password"];
        // --- Eliminada dependencia de config/password.php

			    $Users->data = array('User'=>array('username'=>'admin','password'=>$password));
			    $Users->loginFromShell = true;
			    $Users->login();
				}	
			}

		}

		/* Return true if user paid for the competition  */
		function isBetable($competition_id, $User){
			$this->loadModel('Payment');	
			$this->Payment->recursive = -1;
			return $this->Payment->find(
				'count',	array(
					'conditions'=>array(
						'user_id = ' => $User['id'],
						'competition_id' => $competition_id
					)
				)
			);
		}	

		/* Show user's own bets */
		function mine(){ 
			$this->loadModel('Match');	
			$this->layout = 'default';
		  $this->helpers[] = 'FutbolGui';	

			//Competition
			$competition_list = $this->requestAction('/competitions/listAll', array('cache'=>'+1 hour'));
			$competition_list_opened = $this->requestAction('/competitions/listOpened', array('cache'=>'+1 hour'));
			if (isset($this->params['named']['competition'])){
				$competition_id = $this->params['named']['competition'];
			}else if (isset($this->data['Bet']['competition_id'])){
				$competition_id =  $this->data['Bet']['competition_id'];
		  }else if ($this->Session->read('UserPref.Competition.id')>0){
				$competition_id =	$this->Session->read('UserPref.Competition.id');
		  }else if (count($competition_list_opened)>0){
		  	$competition_id = array_shift(array_reverse(array_keys($competition_list_opened)));
		  }else if (count($competition_list)>0){
		  	$competition_id = array_shift(array_keys($competition_list));
			}else{
				$competition_id = NULL;
			}
			$this->Session->write('UserPref.Competition.id', $competition_id);

			$competition_sport = $this->requestAction('/competitions/getSport/'.$competition_id, array('cache'=>'+1 hour'));

			//Fase
			$fase_list = $this->requestAction('/fases/listAll/'.$competition_id, array('cache'=>'+1 hour'));
			$lastest_fase = $this->requestAction("/fases/lastest/$competition_id", array('cache'=>'+1 hour'));
			$fase_id = isset($this->data['Bet']['fase_id']) ? $this->data['Bet']['fase_id'] : 
				(empty($lastest_fase) ? array_shift(array_keys($fase_list)) : array_shift(array_keys($lastest_fase)));
			//$this->Session->write('UserPref.Competition.fase_id', $fase_id);

			//Groupping
			$groupping_list = $this->requestAction('/grouppings/listAll/'.$fase_id, array('cache'=>'+1 hour'));
			$groupping_id = isset($this->data['Bet']['groupping_id']) ? $this->data['Bet']['groupping_id'] : NULL;
			$groupping_id = $groupping_id!="" ? $groupping_id : NULL; 
 				//: array_shift(array_keys($groupping_list));

			//Fixtures
			$fixture_list = $this->requestAction('/matches/listFixtures/fase:'.$fase_id.'/groupping:'.$groupping_id, array('cache'=>'+1 hour'));
			$fixture = isset($this->data['Bet']['fixture']) ? $this->data['Bet']['fixture'] : 
				($groupping_id!=NULL ? NULL : array_shift(array_keys($fixture_list)));

			if (isset($this->params['named']['user'])){
				//Other user
				$other_user = true;
				$this->loadModel('User');	
				$this->User->recursive = -1;
				$this->User->id = $this->params['named']['user'];
				$this->User->read();
				$User = $this->User->data['User'];				
			}else{
				//Logged User
				$other_user = false;
				$User = array_shift($this->Auth->user());
			}

			/* BEGIN - Bets update */
			$save_button = isset($this->params['named']['save_button']); //Save pressed?
			if (!$other_user && $this->data && $save_button){ 

				// Clean none bet vars... 
				unset($this->data['Bet']['competition_id']);
				unset($this->data['Bet']['fase_id']);
				unset($this->data['Bet']['groupping_id']);
				unset($this->data['Bet']['fixture']);

				foreach($this->data['Bet'] as $bet){
					$save = false;
					$bet_exists = $this->Bet->find('count', 
						array(
							'recursive'=>-1,
							'conditions'=>array('Bet.user_id ='=>$User['id'], 'Bet.match_id ='=> $bet['match_id'])
						)
					)>0;
					$match_pending = $this->Match->find('count',
						array(
							'recursive'=>-1,
							'conditions'=>array('Match.id ='=>$bet['match_id'], 'Match.is_pending ='=> true)
						)
					)>0;
					if ($bet_exists){
						if ($match_pending){
							$this->Bet->id = $bet['id'];
							if ($this->Bet->read()){
								$bet['modified'] = NULL;
								$save = true;
							}else{
								$this->Session->setFlash(
									__('No se puede leer (Bet.id='.$bet['id'].').', true), 'flash_ok');
							}
						}
					}else{
						if ($match_pending){
							if ($this->Bet->create()){
								$save = true;
							}else{
								$this->Session->setFlash(__('No se puede crear el objeto (Bet).', true), 'flash_ok');
							}
						}
					}
					if ($save){
						$bet['user_id'] = $User['id']; //Force user for security
						if ($this->Bet->save(
								array('Bet'=>$bet, true, array('match_id','user_id', 'host_goals','guest_goals')))
						){
							$this->Session->setFlash(__('Los datos han sido guardados con éxito.', true), 'flash_ok');
						}else{
							$this->Session->setFlash(__('Error al guardar los datos.', true), 'flash_error');
						}
					}
				}
				unset($save);
				unset($bet_exists);
				unset($match_pending);
			}
			/* END - Bets update */

			$contain = array(
				'PseudoTeamHost' => array(
					'fields' => array('id', 'abreviation')
					,'Team' => array(
						'fields' => array('id', 'title', 'has_shield', 'abreviation')
						,'Country'=> array(
							'fields'=>array('id','code')
						)
					)
					,'Groupping'=>array(
						'Fase'
					)
				)
				,'PseudoTeamGuest' => array(
					'fields' => array('id', 'abreviation')
					,'Team' => array(
						'fields' => array('id', 'title', 'has_shield', 'abreviation')
						,'Country'=> array(
							'fields'=>array('id','code')
						)
					)
					,'Groupping'=>array(
						'Fase'=>array(
							
						)
					)
				)
/*
				, 'Stadium'=>array(
					'fields' => array('id', 'title')
				)
*/
				, 'Bet' => array(
					'conditions' => array('Bet.user_id = ' => $User['id'])
				)
			);
			$conditions = is_null($groupping_id) ? array() : array('Groupping.id'=>$groupping_id);
			$conditions = array_merge($conditions, array('Fase.id = ' => $fase_id));
			if ($fixture!=NULL){
				$conditions = array_merge($conditions, array('DATE(Match.kickoff)' => $fixture));
			}
			if ($other_user) $conditions = array_merge($conditions, array('DATE(Match.kickoff) <= DATE("'.date('Y-m-d').'")'));
			$bet_table = $this->Match->find('all', 
				array(
					'contain'=>$contain
					,'link'=>array(
						'Groupping'=>array(
							'fields' => array('id', 'title')
							,'conditions' => 'Match.groupping_id = Groupping.id'		
							,'Fase' => array(
								'conditions' => 'Groupping.fase_id = Fase.id'		
							)
						)
					)
					,'conditions'=>$conditions
					,'order'=>array('Match.kickoff')
				)
			);

			//Bet points
			$this->loadModel('Point');	
			$this->Point->recursive = -1;
			$bet_total_points = $this->Point->find('all', 
				array(
					'fields'=> array('SUM(Point.points) AS total_points'),
					'conditions' => array(
						'Point.competition_id =' => $competition_id,
						'Point.user_id =' => $User['id'],
					)
				)
			);
			$bet_total_points = count($bet_total_points)==1 ? $bet_total_points[0][0]['total_points'] : 0;

			$this->set('pot_amount', $this->requestAction('/payments/pot/competition_id:'.$competition_id, array('cache'=>'+1 hour')));
			$this->set('other_user', $other_user);
			$this->set('bet_table', $bet_table);
			$this->set('bet_total_points', round($bet_total_points,2));
			$this->set('betable', $this->isBetable($competition_id, $User));
			$this->set('User', $User);
			$this->set('competition_list', $competition_list); 
			$this->set('competition_id', $competition_id); 
			$this->set('competition_sport', $competition_sport); 
			$this->set('fase_list', $fase_list); 
			$this->set('fase_id', $fase_id); 
			$this->set('groupping_list', $groupping_list); 
			$this->set('groupping_id', $groupping_id); 
			$this->set('fixture_list', $fixture_list); 
			$this->set('fixture', $fixture); 

		}

		/** 
		 * Use:
		 *  - After match save, the model calls this method to
		 *    updates the table of points that contains the total 
		 *    earned bet points per user (in a competition) and 
		 *    updates the home away table (for a groupping). 
		 *  - Also can be called from shell for debugging but
		 *    is commented out.
		 *
		 * Params:
		 *	 $competition_id: Competition of the updated match.
		 *  $groupping_id: Groupping of the updated match.
		 *  $redirect: Where to go after calculations?
		 */
		function calculate($competition_id=NULL, $groupping_id=NULL, $redirect=null){

			//Update points per user in the competition
			if ($competition_id!=null){
				$competition_filter = "AND f.competition_id = '$competition_id'";
			}else{
				$competition_filter = "";
			}

			$q1 = $this->Bet->query(
				" REPLACE INTO futbol_points".
				" SELECT 0, b.user_id, f.competition_id, SUM(b.points), DATE('".date('Y-m-d H:i:s')."')".
				" FROM futbol_bets as b, futbol_matches m, futbol_grouppings as g, futbol_fases as f".
				" WHERE b.match_id = m.id AND m.groupping_id=g.id".
				" AND g.fase_id = f.id $competition_filter".
				" GROUP BY b.user_id, f.competition_id" 
			);
			
		   //Update home away information for this competition	
			if ($groupping_id!=null){
				$groupping_filter = "AND pt.groupping_id = '$groupping_id'";
			}else{
				$groupping_filter = "";
			}
	
			$q2 = $this->Bet->query(

				"REPLACE INTO futbol_rankings

				SELECT NULL, t.pseudo_team_id as pseudo_team_id, 
				
				SUM(t.played+t.away_played) AS played,
				
				SUM(t.played) AS home_played, 
				SUM(t.win) AS home_win, 
				SUM(t.lost) AS home_lost, 
				SUM(t.drawn) AS home_drawn,	
				SUM(t.favor_goals) AS home_favor_goals,	
				SUM(t.against_goals) AS home_against_goals,	
				SUM(t.diff_goals) AS home_diff_goals,	
				
				SUM(t.away_played) AS away_played, 
				SUM(t.away_win) AS away_win,
				SUM(t.away_lost) AS away_lost, 
				SUM(t.away_drawn) AS away_draw,	
				SUM(t.away_favor_goals) AS away_favor_goals,	
				SUM(t.away_against_goals) AS away_against_goals,	
				SUM(t.away_diff_goals) AS away_diff_goals,	

				SUM(t.diff_goals + t.away_diff_goals) AS diff_goals,	

				SUM(t.win+t.away_win) * 3 + SUM(t.drawn+t.away_drawn) AS points				
	
				FROM

				(
				SELECT pt.id as pseudo_team_id, 
				count(*) as played, 
				SUM(CASE WHEN m.host_goals > m.guest_goals THEN 1 ELSE 0 END) AS win,
				SUM(CASE WHEN m.host_goals < m.guest_goals THEN 1 ELSE 0 END) AS lost,
				SUM(CASE WHEN m.host_goals = m.guest_goals THEN 1 ELSE 0 END) AS drawn,
				SUM(m.host_goals) AS favor_goals,
				SUM(m.guest_goals) AS against_goals,
				SUM(m.host_goals) - SUM(m.guest_goals) AS diff_goals,
				0 as away_played, 
				0 AS away_win,
				0 AS away_lost,
				0 AS away_drawn,
				0 AS away_favor_goals,
            0 AS away_against_goals,
				0 AS away_diff_goals
				FROM futbol_matches m, futbol_pseudo_teams pt
				WHERE 1=1
				AND m.host_id = pt.id $groupping_filter
				AND m.host_goals IS NOT NULL AND m.guest_goals IS NOT NULL
				GROUP BY pt.id

				UNION

				SELECT pt.id as pseudo_team_id, 
				0 as played, 
				0 AS win,
				0 AS lost,
				0 AS drawn,
				0 AS favor_goals,
            0 AS against_goals,
				0 AS diff_goals,
				count(*) as away_played, 
				SUM(CASE WHEN m.host_goals < m.guest_goals THEN 1 ELSE 0 END) AS away_win,
				SUM(CASE WHEN m.host_goals > m.guest_goals THEN 1 ELSE 0 END) AS away_lost,
				SUM(CASE WHEN m.host_goals = m.guest_goals THEN 1 ELSE 0 END) AS away_drawn,
				SUM(m.guest_goals) AS away_favor_goals,
				SUM(m.host_goals) AS away_against_goals,
				SUM(m.guest_goals) - SUM(m.host_goals) AS away_diff_goals
				FROM futbol_matches m, futbol_pseudo_teams pt
				WHERE 1=1
				AND m.guest_id = pt.id $groupping_filter
				AND m.host_goals IS NOT NULL AND m.guest_goals IS NOT NULL
				GROUP BY pt.id
				) t
				GROUP BY t.pseudo_team_id"
			);
			
			if ($q1 && $q2){
				$this->Session->setFlash(__('Cálculos realizados con éxito.', true), 'flash_ok');
			}else{
				$this->Session->setFlash(__('Hubo problemas para realizar los cálculos.', true), 'flash_error');
			}
			
			if (is_null($redirect)){
				return $q1 && $q2;
			}else{
				$this->redirect($redirect);
			}
   	}
   	
   	function crosstop10(){
			$points = NULL;
	   	if (isset($this->params['named']['token']) && Configure::read('Futbol.wsFutbolistaToken') == $this->params['named']['token'])
	   	{
	   		$competition = isset($this->params['named']['competition']) ? ("competition:" . $this->params['named']['competition'])  : "";
	   		$points = $this->requestAction('/bets/top10/'.$competition.'/showall:1', array('cache'=>'+5 minute'));
	   	}
   		$json = json_encode($points);
			$this->set(compact('json'));
	    $this->layout = 'json';
			$this->render('json');
	  }

		function top10(){ 
			$this->loadModel('Point');	
			$this->layout = 'default';
		  $this->helpers[] = 'FutbolGui';	
		
			//$competition_list = $this->requestAction('/competitions/listOpenedOrClosed', array('cache'=>'+1 hour'));
			$competition_list = $this->requestAction('/competitions/listWithTop10', array('cache'=>'+15 minute'));

      if (isset($this->params['named']['competition'])){
        $competition_id = $this->params['named']['competition'];
      }else if (isset($this->params['named']['competition_id'])){
        $competition_id = $this->params['named']['competition_id'];
      }else if (isset($this->data['Competition']['competition_id'])){
        $competition_id =  $this->data['Competition']['competition_id'];
      }else if ($this->Session->read('UserPref.Competition.id')>0 && 
				in_array($this->Session->read('UserPref.Competition.id'), array_keys($competition_list))){
				// Check previous selection is opened or closed!
				$competition_id = $this->Session->read('UserPref.Competition.id');
      }else if (count($competition_list)>0){
        $competition_id = array_shift(array_keys($competition_list));
      }else{
        $competition_id = NULL;
      }
			$this->Session->write('UserPref.Competition.id', $competition_id);
			
			if (!is_null($competition_id) && !array_key_exists($competition_id, $competition_list)){
				//Avoid competition without Top 10
				$competition_id = NULL; 
			}
		
/*
			$options = array(
				'contain'=>array('User'=>
					array(
						'fields'=>array('id','username')
					)
				),
				'conditions'=>array(
					'User.username != ' => 'admin'
					,'Point.competition_id = ' => $competition_id
				),
				'order'=>array('Point.points'=>'DESC', 'User.username'=>'ASC')
			);
*/

			$competitions_array = array($competition_id);
			foreach(Configure::read('Futbol.Pots') as $Pot){
				if (in_array($competition_id, $Pot)){
					$competitions_array = $Pot;
				}				
			}

			$options = array(
				'fields' => array('User.username', 'SUM(Point.points) AS spoints') 
				,'link'=>array(
					'User'=>array(
						'fields'=>array('id','username'),
						'Payment'=>array(
							'fields'=>array('amount')
						)
					)
				),
				'conditions'=>array(
					'User.username != ' => 'admin'
					,'Payment.amount IS NOT NULL'
					,'Payment.competition_id = Point.competition_id'
					,'Point.competition_id' => $competitions_array
				),
				'order'=>array('SUM(Point.points)'=>'DESC', 'User.username'=>'ASC'),
				'group'=>array('User.username')
			);

			if ($this->data['Competition']['showall']!=1 
					&& !isset($this->params['named']['showall'])) $options['limit'] = 10;	

			$points = $this->Point->find('all', $options);

			// debug($points);

			$free_users = $this->requestAction('/payments/listFreeUsers/competition:' . $competition_id, array('cache'=>'+1 hour'));
	
			if (!empty($this->params['requested'])){
				return $points; 
			}else{
				if (!count($points)) 
					$this->Session->setFlash(
						__('Aún no se han calculado puntos para esta competencia.', true), 'flash_ok');
				$this->set('competition_list', $competition_list); 
				$this->set('competition_id', $competition_id); 
				$this->set('points', $points);	
        $this->set('showall', $this->data['Competition']['showall']);
        $this->set('free_users', $free_users);						
			}
		}

		/* Sent Nightly Email with Top 10, Bets (+Audit) and News */
		function futbolista($shell = false){ 

			// Is called as Web Service with Token?
			$webservice = isset($this->params['named']['ws']); 

			// Audit files do not exist and were generated with this call?
			$error_code_forecast_audit = $this->forecast_audit($shell); 
			if ($error_code_forecast_audit != 0){
				if ($webservice){
					$this->layout = 'ajax';
					$this->set('respuesta', "Error: Ya existen archivos o hubo un errores ($error_code_forecast_audit)");
					$this->render('/elements/ajax');
					return $error_code_forecast_audit;
				}else if ($shell){
					return $error_code_forecast_audit;
				}
			}

			$htmlOutput = true; // Force HTML output for Web and massive mail

			App::import('Vendor','ArrayToTextTable' ,array('file'=>'ArrayToTextTable.php'));

			//Todays competitions list and free users
			$competition_list = $this->requestAction('/competitions/listOpenedOrRecentlyClosed');
			$top10_data = array();
			$free_users_data = array();
			foreach($competition_list as $competition_id => $competition_title){	
				// Competition Processing - BEGIN
				//$top10_table = $this->requestAction('/bets/top10/showall:1/competition_id:' . $competition_id);
				$top10_table = $this->requestAction('/bets/top10/competition_id:' . $competition_id);
				$data = array();
				foreach($top10_table as $index => $top10_row){
					// Multi-Competencia (Pote Unico)
					$data[] = array(
						'Posicion'=>$index+1,
						'Usuario'=>$top10_row['User']['username'],
						'Puntos'=>$top10_row[0]['spoints']
					);
					// 'Puntos'=>$top10_row['Point']['points'] -- ANTES ERA ASI!!!
				}
				if (!empty($data)){
					if ($htmlOutput){
						$view = new View($this, false);
						$view->set('arreglo', $data);
						$view->layout = 'html';
						$top10_table = $view->render('/elements/table');
					}else{
						$renderer = new ArrayToTextTable($data);
						$renderer->showHeaders(true);
						$top10_table = $renderer->render(true);
					}		
				}else{
					$top10_table = "";
				}
				$top10_data[] = array(
					'competition' => array('id'=>$competition_id, 'title'=>$competition_title)
					,'top10_table' => $top10_table
				);
				// Competition Processing - END

				// Free Users Processing - BEGIN
				$free_users = $this->requestAction('/payments/listFreeUsers/competition:' . $competition_id, array('cache'=>'+1 hour'));
				$data = array();
				foreach($free_users as $index => $free_user){
					$data[] = array('Usuario'=>$free_user);
				}
				if (!empty($data)){
					if ($htmlOutput){
						$view = new View($this, false);
						$view->set('arreglo', $data);
						$view->layout = 'html';
						$free_users_table = $view->render('/elements/table');
					}else{
						$renderer = new ArrayToTextTable($data);
						$renderer->showHeaders(false);
						$free_users_table = $renderer->render(true);
					}
				}else{
					$free_users_table = "";
				}
				$free_users_data[] = array(
					'competition' => array('id'=>$competition_id, 'title'=>$competition_title)
					, 'free_users_table' =>  $free_users_table
				);
				// Free Users Processing - END
			}

			//Today match list predictions
			$match_list = $this->requestAction('/matches/listToday');
			$forecast_data = array();
			foreach($match_list as $match_id => $match_title){	
				$forecast_request = $this->requestAction('/bets/forecast/match_id:' . $match_id);
				$forecast_table = $forecast_request['bet_table'];
				$forecast_count = $forecast_request['bet_count'];
				$data = array();
				foreach($forecast_table as $index => $forecast_row){
					$data[] = array(
						'Predicción'=>$forecast_row['Bet']['host_goals'] . "-" . $forecast_row['Bet']['guest_goals'],
						'Porcentaje'=>$forecast_row[0]['percentage'],
					);
				}
				if (!empty($data)){
					if ($htmlOutput){
						$view = new View($this, false);
						$view->set('arreglo', $data);
						$view->layout = 'html';
						$forecast_table = $view->render('/elements/table');
					}else{
						$renderer = new ArrayToTextTable($data);
						$renderer->showHeaders(true);
						$forecast_table = $renderer->render(true);
					}
				}else{
					$forecast_table = "";
				}
				$forecast_data[] = array(
					   'bet_count'=>$forecast_count
						,'bet_table'=>$forecast_table
						,'bet_match' => array('id'=>$match_id, 'title'=>$match_title)
						
				);
			}

			// Yesterday match list
			$yesterday_list = $this->requestAction('/matches/listYesterday');
			$data = array();
			foreach($yesterday_list as $match_id => $match_title){	
				$data[] = array('Partido' => $match_title);
			}
			if (!empty($data)){
				if ($htmlOutput){
        	$view = new View($this, false);
					$view->set('arreglo', $data);
					$view->layout = 'html';
					$match_table = $view->render('/elements/table');
				}else{
					$renderer = new ArrayToTextTable($data);
					$renderer->showHeaders(false);
					$match_table = $renderer->render(true);
				}
			}else{
				$match_table = "";
			}
			$yesterday_data = array("match_count"=> count($yesterday_list), "match_table"=> $match_table);
	
			// Read audit files content
			$kickoff = date("Y-m-d");
			$audit_dir = WWW_ROOT . 'audit';
			$audit_url = Configure::read('Futbol.serverSslUrl') . '/audit';
			$data_dir = $audit_dir . DS . 'data';
			$signature_dir = $audit_dir . DS . 'signature';
			$filename = $data_dir . DS . $kickoff . '.txt';
			$sign_filename = $signature_dir. DS . $kickoff . '-s.txt';

			$auditdataurl = $audit_url . '/data/' . $kickoff . '.txt';
			if (is_file($filename)){
				$auditdata = file_get_contents($filename);	
			}else{
				$auditdata = "";
			}

			if (is_file($sign_filename)){
				$signature = file_get_contents($sign_filename);	
			}else{
				$signature = "";
			}

			$ok = false; // Response everything OK to shell?
			$text = NULL; // Generated plain/text email
			$saved = false; // Saved after generation?
			$this->autoRender = false; // Disable auto rendering!
			
			// If there is a match today or yesterday there was a match or debugging is on
			if (count($match_list)>0 || count($yesterday_list)>0 || Configure::read('debug')>0){

				// Generate view and save a new mail (and show it?)
				$this->layout = 'html';
				$view = new View($this, false);
				$view->set('auditdata',$auditdata);
				$view->set('auditdataurl',$auditdataurl);
				$view->set('signature', $signature);
				$view->set('top10_data', $top10_data);
				$view->set('forecast_data', $forecast_data);
				$view->set('yesterday_data', $yesterday_data);
				$view->set('free_users_data', $free_users_data);
				$text = $view->render('/elements/email/html/futbolista');

				$this->loadModel('Mail');
				$this->Mail->create();
				$this->Mail->set(array(
					'subject' => date('Y-m-d') . __(': Posiciones, Resultados y Predicciones', true),
					'body' => $text,
					'html' => ($htmlOutput ? 1 : 0)
				));
				// If no errors with audit file generation we
				// Must save massive mail that will be processed
        // Later with a cronjob for "/Mails/send" action
				if ($error_code_forecast_audit == 0){
					$saved = $this->Mail->save();
				}else{
					$saved = true;
				}
				$ok = $saved; // If mail was saved then everything is OK
			}else{
				$ok = true;  // Nothing to do, so everything is OK
			}
			
			if ($shell){
				return ($ok ? 0 : 1);
			}else if (isset($this->params['named']['ws'])){
				$this->layout = 'ajax';
				$this->set('respuesta', ($ok ? 'OK' : 'NO'));
				$this->render('/elements/ajax');
			}else{
				$this->layout = 'default';
				$this->set('text', $text);
				$this->set('htmlOutput',$htmlOutput);
				$this->render();
			}

		}

		/**
     * Use:
     *  - Function is called by futbolista() as stated on /Bets/rules
     *    everyday it generates audits files with all user bets per
     *    competition and digital signatures, located in:
     *       WWW_ROOT/audit/{data,signature}/*.txt
     *  - Usually must run between 12:00 A.M - 12:30 A.M. from shell
     *    triggered by a cron job.
     */
		private function forecast_audit($shell=false){
			$this->layout = 'default';
			
			//$kickoff='2011-07-11'; //Only for debug
		
			$kickoff = date("Y-m-d");
			$audit_dir = WWW_ROOT . 'audit';
			$data_dir = $audit_dir . DS . 'data';
			$signature_dir = $audit_dir . DS . 'signature';
			$filename = $data_dir . DS . $kickoff . '.txt';
			$sign_filename = $signature_dir. DS . $kickoff . '-s.txt';
			$fileurl = '/audit/data/'. basename($filename);
			$sign_fileurl = '/audit/signature/'. basename($sign_filename);

			$sign = false; //Create signature file?
			$error_code = 0; //We start ok...
	
	
			if (is_file($filename)){
				$error_msg = 'El archivo del '.$kickoff.' ya existe.';
				$this->Session->setFlash(__($error_msg,true),'flash_error');
				if ($shell) echo $error_msg;
				$error_code = 1;
			}else if (!is_dir($audit_dir) || !is_dir($data_dir) || !is_dir($signature_dir)){
				$error_msg = 'Estructura de subdirectorios incorrecta en el directorio "'. $audit_dir . '"';
				$this->Session->setFlash(__($error_msg,true),'flash_error');
				if ($shell) echo $error_msg;
				$error_code = 2;
			}else if(!is_writable($audit_dir)){
				$error_msg = 'Sin permisos de escritura en el directorio '. $audit_dir;
				$this->Session->setFlash(__($error_msg,true),'flash_error');
				if ($shell) echo $error_msg;
				$error_code = 3;
			}else{
				App::import('Vendor','ArrayToTextTable' ,array('file'=>'ArrayToTextTable.php'));
				$competition_list = $this->requestAction('/competitions/listAll', array('cache'=>'+1 hour'));

				$headers = array(
					__('(Id) - (Kickoff) Host - Guest',true), 
					__('User (name (id))',true), 
					__('Result (H/G)',true),
					__('Bet (id)',true), 
					__('Bet (date)',true),
					__('Competition (name (id))',true)
				);

				$data = array();
				foreach($competition_list as $competition_id => $competition_title){
					$sql  = ' SELECT b.id, CONCAT(u.username, \'(\', u.id, \')\') as user_info,';
					$sql .= ' b.modified, CONCAT(c.title, \'(\', c.id, \')\') as competition_info, m.id,';
					$sql .= ' CONCAT(b.host_goals, \'-\', b.guest_goals) as result';
					$sql .= ' FROM futbol_bets b, futbol_users u, futbol_matches m';
					$sql .= ' , futbol_grouppings g, futbol_fases f, futbol_competitions c';
					$sql .= ' WHERE b.match_id = m.id AND b.user_id=u.id';
					$sql .= ' AND DATE(m.kickoff)=DATE(\''.$kickoff.'\')';
					$sql .= ' AND m.groupping_id = g.id AND g.fase_id = f.id';
					$sql .= ' AND f.competition_id = c.id AND c.id='.$competition_id;
					$sql .= ' ORDER BY c.id, m.id, u.username';
					$query = $this->Bet->query($sql);
					$matches = $this->requestAction('/matches/listToday');
					foreach($query as $row){
						$data[] = array(
							$headers[0]=> "(" . $row['m']['id'] . ") " . $matches[$row['m']['id']], 
							$headers[1]=>$row[0]['user_info'],
							$headers[2]=>$row[0]['result'],
							$headers[3]=>$row['b']['id'],
							$headers[4]=>$row['b']['modified'],
							$headers[5]=>$row[0]['competition_info']
						);
					}
				}
				$renderer = new ArrayToTextTable($data);
				$renderer->showHeaders(true);
				$fd = fopen($filename, 'w');
				fwrite($fd, "Generated: " .
									date("Y-m-d H:i:s")."\n\nBet Count: " . count($data) .
										 "\n\n" . $renderer->render(true) . "\n");
				fclose($fd);
				$sign = true;
			}

			if (is_file($filename)){	
				$raw_data = file_get_contents($filename);
			}else{
				$raw_data = "";
			}

			if ($sign){
				$signature = hash("sha256", $raw_data);
				$fd = fopen($sign_filename, 'w');
				fwrite($fd, $signature);
				fclose($fd);
			}else{
				if (is_file($filename)){	
					$signature = file_get_contents($sign_filename);
				}else{
					$signature = "";
				}
			}

			$this->set('signature', $signature);
			$this->set('fileurl', $fileurl);
			$this->set('sign_fileurl', $sign_fileurl);
			$this->set('text', $raw_data);
			
			return $error_code;
		}

		/* Show bet report for all users */
		function forecast($match_id=null, $showperuser=null){ 
			$this->loadModel('Match');	
			$this->layout = 'default';
		   $this->helpers[] = 'FutbolGui';	

			//Show per user?
			if (is_null($showperuser)){
				$showperuser = isset($this->data['Bet']['showperuser']) ?
					($this->data['Bet']['showperuser'] == 1 ? true : false ) : false;
			}

			//Match score
			$bet_score = isset($this->params['named']['bet_score']) ? explode('-',$this->params['named']['bet_score']) : NULL;

			//Competition
			$competition_list = $this->requestAction('/competitions/listAll', array('cache'=>'+1 hour'));
      if (isset($this->data['Bet']['competition_id'])){
        $competition_id =  $this->data['Bet']['competition_id'];
      }else if ($this->Session->read('UserPref.Competition.id')>0){
        $competition_id = $this->Session->read('UserPref.Competition.id');
      }else if (count($competition_list)>0){
        $competition_id = array_shift(array_keys($competition_list));
      }else{
				$competition_id = NULL;
			}
      $this->Session->write('UserPref.Competition.id', $competition_id);

			//Fase
			$fase_list = $this->requestAction('/fases/listAll/'.$competition_id, array('cache'=>'+1 hour'));
			$lastest_fase = $this->requestAction("/fases/lastest/$competition_id", array('cache'=>'+1 hour'));
			$fase_id = isset($this->data['Bet']['fase_id']) ? $this->data['Bet']['fase_id'] : 
				(empty($lastest_fase) ? array_shift(array_keys($fase_list)) : array_shift(array_keys($lastest_fase)));

			//Groupping
			$groupping_list = $this->requestAction('/grouppings/listAll/'.$fase_id, array('cache'=>'+1 hour'));
			$groupping_id = isset($this->data['Bet']['groupping_id']) ? $this->data['Bet']['groupping_id'] : 
				array_shift(array_keys($groupping_list));

			//Match
			$match_list = $this->requestAction('/matches/listByGroupping/'.$groupping_id);
         if (isset($this->params['named']['match_id'])){
            $match_id = $this->params['named']['match_id'];
         }else if (is_null($match_id)){
				$match_id = isset($this->data['Bet']['match_id']) ? $this->data['Bet']['match_id'] : 
					array_shift(array_keys($match_list));
			}

			//Bets
			$administrator_id = array_shift(array_keys($this->requestAction('/users/administrator')));
			$this->Bet->recursive = 0;
			$bet_count = $this->Bet->find(
				'count',
 				array(
					'conditions' => array(
						'Match.id ='=>$match_id
						,'Bet.host_goals IS NOT NULL'
						,'Bet.guest_goals IS NOT NULL'
						,'Bet.user_id !='=> $administrator_id
					)
				)
			);
			$fields = array(
				'Bet.host_goals', 'Bet.guest_goals', 'COUNT(*)*100/'.$bet_count.' AS percentage'
			);
 			$conditions = array(
				'Match.id ='=>$match_id
				,'Bet.host_goals IS NOT NULL'
				,'Bet.guest_goals IS NOT NULL'
				,'Bet.user_id !='=> $administrator_id
			);
			$group = array('Bet.host_goals', 'Bet.guest_goals');

			//Show per user?
			if ($showperuser){
				$fields[] = 'User.username';
				$group[] = 'Bet.user_id';
				$contain = array(
					'Match' => array(
						'fields'=> array('id')
					)
					,'User' => array(
						'fields'=> array('username','id')
					)
				);
				$conditions[] = 'NOT (' . $this->Match->getVirtualField('is_pending') . ')';
				if (!is_null($bet_score)){
					$conditions[] = array('Bet.host_goals ='=> $bet_score[0]);
					$conditions[] = array('Bet.guest_goals ='=> $bet_score[1]);
				}
				$this->Bet->recursive = 0;
				$order = array('User.username ASC');
			}else{
				$contain = array(
					'Match' => array(
						'fields'=> array('id')
					)
				);
				$this->Bet->recursive = -1;
				$order = array('Bet.host_goals DESC', 'Bet.guest_goals DESC');
			}

			$bet_table = $this->Bet->find(
				'all', 
				array(
					'fields' => $fields
					, 'conditions' => $conditions
					, 'group' => $group
					, 'contain' => $contain
					, 'order' => $order
				)
			);

			if (!empty($this->params['requested'])){
				//Bets
				return array('bet_count'=>$bet_count, 'bet_table'=>$bet_table);	
			}else{
				//Filters	for view
				$this->set('competition_list', $competition_list); 
				$this->set('competition_id', $competition_id); 
				$this->set('fase_list', $fase_list); 
				$this->set('fase_id', $fase_id); 
				$this->set('groupping_list', $groupping_list); 
				$this->set('groupping_id', $groupping_id); 
				$this->set('match_list', $match_list); 
				$this->set('match_id', $match_id);
				$this->set('bet_score', $bet_score);

				//Show per user?
				$this->set('showperuser', $showperuser);
				if ($showperuser && count($bet_table) == 0 ){
					$this->Session->setFlash(__('Para este partido podrá visualizar las predicciones '
						. 'de cada usuario a partir de las 12:00 A.M. del día del partido.', true), 'flash_ok');
				}	
	
				//Bets
				$this->set('bet_count', $bet_count); 
				$this->set('bet_table', $bet_table); 
			}

		}

		/* Show term and conditions */
		function rules(){
			$this->layout = 'default';
		}

		/* Show contact information */
		function contact(){
			$this->layout = 'default';
		}		
}
