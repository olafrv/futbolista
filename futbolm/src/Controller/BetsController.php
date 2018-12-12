<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Bet;

use Cake\Network\Http\Client;

class BetsController extends AppController
{

	public function beforeFilter(Event $event)
	{
	  if (empty($this->request->session()->read("Competition.id")))
	 	{
			$Competitions = TableRegistry::get('Competitions');
	 	  $Competition = $query = $Competitions->find('all', [
	 	  	'order' => [
	 	  		'ends'=> 'DESC',
	 	  		'begins' => 'DESC'
	 	  	]
	 	  ])->first();
	 		$this->request->session()->write("Competition.id", $Competition->id);
	 	}
   	parent::beforeFilter($event);
	}

	public function pot($competition = NULL)
	{
 		$competition = is_null($competition) ? $this->request->session()->read("Competition.id") : $competition;
  
		// --- CROSS POT --- //
		$User = $this->request->session()->read("Auth.User");
		$http = new Client();
		$url = 'https://' . $this->request->host() 
				. '/futbol/payments/pot/competition_id:' . $competition . '/token:a1c2e478Ghjtz89';
		$response = $http->get($url
			, NULL
		 	, [ 'type'=>'json', 'ssl_verify_peer' => false ]
		);
		$data = json_decode($response->body, true);
		// --- CROSS POT -- //
		$this->set(compact('competition'));
		$this->set('pot', $data);
	}

  public function top10($competition = NULL)
  {
 		$competition = is_null($competition) ? $this->request->session()->read("Competition.id") : $competition;
  
		// --- CROSS TOP10 --- //
		$User = $this->request->session()->read("Auth.User");
		$http = new Client();
		$url = 'https://' . $this->request->host() 
			. '/futbol/bets/crosstop10/competition:' . $competition . '/token:a1c2e478Ghjtz89';
		$response = $http->get($url
			, NULL
		 	, [ 'type'=>'json', 'ssl_verify_peer' => false ]
		);
		$data = json_decode($response->body, true);
		// --- CROSS TOP10 --- //

		$this->set(compact('competition'));
		$this->set('points', $data);
	}

	public function save(){
		if ($this->request->is('post')  && $this->request->accepts('application/json'))
		{
	    $data = $this->request->data();
			$User = $this->request->session()->read("Auth.User");
			if (!empty($data['match_id']))
			{
				$Matches = TableRegistry::get('Matches');
				$Match = $Matches->find('all', [
					'conditions' => [
						'Matches.id' => $data['match_id'],
						'DATE(Matches.kickoff) > DATE("' . date('Y-m-d') . '")'],
						'Matches.host_goals IS NULL', 
						'Matches.guest_goals IS NULL'
				])->first();
				if (!empty($Match))
				{			
					$Bet = $this->Bets->find('all', [
						'conditions' => [
							'match_id' => $data['match_id'],
							'user_id' => $User['id']
						]
					])->first();
					if (!empty($Bet))
					{
						$this->Bets->patchEntity($Bet, $data);
						if ($this->Bets->save($Bet))
						{	
							$data["action"] = 'updated';			
						}
						else
						{
							$data = ['error' => __('Unable to update and save bet data'), ];
						}
					}
					else
					{
						$Bet = $this->Bets->newEntity($data);
						$Bet->user_id = $User['id'];
						if ($this->Bets->save($Bet))
						{
							$data["action"] = 'created';
							$data['id'] = $Bet->id;
					  }
					  else
					  {
					  	$data = ['error' => __('Unable to save created bet data'), ];
					  }
					}	
				}
				else
				{
					$data = ['error' => __('Match is closed for bets') ];
				}
			}
			else
			{
				$data = ['error' => __('Match not found') ];
			}
			$this->set(compact('data'));
		}
	}

	public function index($competition = NULL, $match_kickoff = NULL)
  {
		$competition = is_null($competition) ? 
			$this->request->session()->read("Competition.id") : $competition;
		$this->request->session()->write("Competition.id", $competition);
		if (empty($match_kickoff)){
			$Matches = TableRegistry::get('Matches');
 		  $Match = $Matches->find('all', [
 		  	'fields' => [ 'Matches.kickoff' ]
 		  	,'order' => [ 'Matches.kickoff' => 'ASC' ]
 		  ])
 		  ->matching(
					'Grouppings.Fases.Competitions', function ($q) use ($competition){
						return $q
  	 		     	->where([
  	 		     		'Competitions.id' => $competition
  	  		    ]);							
					}
			)->first();
			$match_kickoff = $Match->kickoff->i18nFormat('yyyy-MM-dd');  
		}
		$this->request->session()->write("Match.kickoff", $match_kickoff);

		$User = $this->request->session()->read("Auth.User");		
		
		$Matches = TableRegistry::get('Matches');
		$query = $Matches->find()
     	->where([
     		'DATE(Matches.kickoff) = DATE("' . $match_kickoff . '")' 
     		, 'Competitions.id' => $competition
     	])		
      ->order(['Matches.kickoff' => 'ASC'])
      ->contain([
          'Bets' => function ($q) use ($User){
			       return $q
      	      ->where(['Bets.user_id' => $User['id']]);
      	   }
      	   , 'Hosts.Teams.Countries'
      	   , 'Guests.Teams.Countries'
      ])
      ->matching(
      	'Grouppings.Fases.Competitions', function ($q) use ($competition){
		       return $q
    	      ->where(['Competitions.id' => $competition]);
      	}
      );
    $matches = $query->toArray();

		$this->set(compact('competition'));
		$this->set(compact('matches'));
		
	}

}
