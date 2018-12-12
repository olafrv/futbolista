<?php
	class PaymentsController extends AppController {

		var $name = 'Payments';
		var $scaffold;
		var $layout = 'cake';

		function beforeFilter() {
    	parent::beforeFilter();
			$this->Auth->allow(array('pot','show')); // Anonymous + Token Access

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

		function reportPayments(){
			$this->layout = 'default';
		  $this->helpers[] = 'FutbolGui';	

			$competition_list = $this->requestAction('/competitions/listAll', array('cache'=>'+15 minute'));

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
			
			$options = array(
				'fields'=>array(
					'Competition.title',
					'Payment.receiver',
					'DATE(Payment.modified) as pay_date',
					'SUM(Payment.amount) as total_amount',
					'COUNT(Payment.id) as total_count'
				)
				, 'contain'=>array(
					'Competition',
					'User'
				)
				, 'order' => array(
					'Competition.begins DESC',
					'Payment.receiver DESC',
					'Payment.modified DESC'
				)
				, 'conditions' => array(
					'Competition.id' => $competition_id
				)
				, 'group' => array(
					'Competition.id',
					'Payment.receiver',
					'DATE(Payment.modified)'
				)
			);
			$pagos = $this->Payment->find('all', $options);
			if (!empty($this->params['requested'])){
				return $pagos;
			}else{
				foreach($pagos as $pago){
					$array[] = array(	
						'Competencia'=>array($pago["Competition"]["title"],array('class'=>'nw center')),
						'Receptor'=>array($pago["Payment"]["receiver"],array('class'=>'nw center')),
						'Fecha'=>array($pago[0]["pay_date"], array('class'=>'nw center')),
						'Transacciones'=>array($pago[0]["total_count"],array('class'=>'nw right')),
						'Valor'=>array($pago[0]["total_amount"],array('class'=>'nw right'))
					);
				}
				/*
				App::import('Vendor','ArrayToTextTable' ,array('file'=>'ArrayToTextTable.php'));
      	$renderer = new ArrayToTextTable($array);
        $renderer->showHeaders(true);
				$textout = $renderer->render(true);
        $this->set('textout', $textout);
				*/
				$this->set('pagos', $array);
				$this->set('competition_list', $competition_list);
				$this->set('competition_id', $competition_id);
			}
		}
    
		function listPaidUsers(){ //Users
			$this->loadModel("User");
			$options = array(
			  'fields' => array('id', 'username')
				,'joins' => array(    
					array(
						'table' => 'payments',
						'alias' => 'Payment',
						'type' => 'LEFT',
						'conditions' => array(
							'User.id = Payment.user_id'
		        )    
					)
				)
				,'conditions'=>array(
				  'NOT' => array('User.username' => 'admin'),
					'Payment.competition_id' => $this->params["named"]['competition']
				)
				,'order'=>array('User.username ASC')
			);
			$data = $this->User->find('list', $options);
			if (!empty($this->params['requested'])){
				return $data;
			}else{
				debug($data);
			}
		}
		
		function listFreeUsers(){ //Users
			$this->loadModel("User");
			$options = array(
			  'fields' => array('id', 'username')
				,'joins' => array(    
					array(
						'table' => 'bets',
						'alias' => 'Bet',
						'type' => 'LEFT',
						'conditions' => array(
							'User.id = Bet.user_id'
		        )    
					)
					,array(
						'table' => 'matches',
						'alias' => 'Match',
						'type' => 'LEFT',
						'conditions' => array(
							'Match.id = Bet.match_id'
		        )    
					)
					,array(
						'table' => 'grouppings',
						'alias' => 'Groupping',
						'type' => 'LEFT',
						'conditions' => array(
							'Groupping.id = Match.groupping_id'
		        )    
					)
					,array(
						'table' => 'fases',
						'alias' => 'Fase',
						'type' => 'LEFT',
						'conditions' => array(
							'Fase.id = Groupping.fase_id',

		        )    
					)
					,array(
						'table' => 'payments',
						'alias' => 'Payment',
						'type' => 'LEFT',
						'conditions' => array(
							'Payment.user_id = User.id',
							'Payment.competition_id = Fase.competition_id'
		        )    
					)
				)
				,'conditions'=>array(
				  'NOT' => array('User.username' => 'admin'),
					'Fase.competition_id' => $this->params["named"]['competition'],
					'Payment.id IS NULL'
				)
				,'order'=>array('User.username ASC')
			);
			$data = $this->User->find('list', $options);
			if (!empty($this->params['requested'])){
				return $data;
			}else{
				debug($data);
			}
		}

		function pot(){ //Users
      	$this->Payment->recursive = -1;
      	$competition_id = $this->params['named']['competition_id'];
				$competitions_array = array($competition_id);
				foreach(Configure::read('Futbol.Pots') as $Pot){
					if (in_array($competition_id, $Pot)){
						$competitions_array = $Pot;
					}				
				}
				$amount = $this->Payment->find('all',
          array(
           	'fields'=> array('SUM(Payment.amount) AS pot_amount'),
           	'conditions' => array(
           		'Payment.competition_id' => $competitions_array
           	)
         	)
        );
				$pot_amount = count($amount)==1 ? $amount[0][0]['pot_amount'] : 0;
				$pot[0] = round($pot_amount,2)*0.9*0.6;
				$pot[1] = round($pot_amount,2)*0.9*0.3;
				$pot[2] = round($pot_amount,2)*0.9*0.1;
				$pot[3] = round($pot_amount,2)*0.1;
				if (!empty($this->params['requested'])){
					return $pot;
				}
				else if (isset($this->params['named']['token']) 
						&& Configure::read('Futbol.wsFutbolistaToken') == $this->params['named']['token'])
	   		{
   				$json = json_encode($pot);
					$this->set(compact('json'));
		  	  $this->layout = 'json';
					$this->render('json');
				}
		}

		function show(){ //Users
			$this->layout = 'default';
			$User = $this->Auth->user();
			$payments_table = null;
			if (!is_null($User)){
				$User = array_shift($User);
				$this->Payment->recursive = 0;
				$payments_table = $this->Payment->find('all',
					array(
						'contain'=>array(
							'Competition',
							'User'
						)
						,'conditions'=>array(
							'User.id' => $User['id']
						)
					)
				);
			}
			$costs =  $this->requestAction("/Competitions/listCosts");
			$this->set('payments_table', $payments_table);
			$this->set('costs', $costs);
		}

	}
