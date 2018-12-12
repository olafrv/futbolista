<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\ORM\TableRegistry;
use Cake\Collection\Collection;
				
class MatchesCell extends Cell
{

    public function kickoffs($id, $selected_value, $matches)
    {
 				$competition_id = $this->request->session()->read("Competition.id");
				$Matches = TableRegistry::get('Matches');
	 		  $query = $Matches->find('all', [
	 		  	'fields' => [ 'ymd_kickoff' => 'DATE(Matches.kickoff)' ]
	 		  	,'order' => [ 'Matches.kickoff' => 'ASC' ]
	 		  ])
	 		  ->distinct(['DATE(Matches.kickoff)'])
	 		  ->matching(
						'Grouppings.Fases.Competitions', function ($q) use ($competition_id){
							return $q
			 		     	->where([
			 		     		'Competitions.id' => $competition_id
			  		    ]);							
						}
				);
				$collection = new Collection($query->toArray());
				$kickoffs = $collection->map(function ($value, $key) {
			    return $value->ymd_kickoff;
				})->toArray();
        $this->set(compact('kickoffs'));
        $this->set(compact('id'));
        $this->set(compact('selected_value'));
        $this->set('matches', $matches);
    }

}
