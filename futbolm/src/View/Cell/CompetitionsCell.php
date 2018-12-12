<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\ORM\TableRegistry;

class CompetitionsCell extends Cell
{

    public function display($id, $selected_value)
    {
		    $Competitions = TableRegistry::get('Competitions');
        $query = $Competitions->find('all',[
        	'order'=>[
        		'begins' => 'DESC'
        	]
        ]);
        $this->set('competitions', $query->toArray());
        $this->set(compact('id'));
        $this->set(compact('selected_value'));
    }

    public function dropdown($id, $selected_value, $action)
    {
		    $Competitions = TableRegistry::get('Competitions');
        $query = $Competitions->find('all',[
        	'order'=>[
        		'begins' => 'DESC'
        	]
        ]);
        $this->set('competitions', $query->toArray());
        $this->set(compact('id'));
        $this->set(compact('selected_value'));
        $this->set(compact('action'));
    }

}
