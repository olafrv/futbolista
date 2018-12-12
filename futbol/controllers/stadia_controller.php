<?php
class StadiaController extends AppController {
	var $scaffold;
	var $name = 'Stadia';
	var $layout = 'cake';
	function listAll(){
		if (!empty($this->params['requested'])){
			$Results = $this->Stadium->find('all', array(
				'contain'=>array('Country')
				, 'order'=>array('Country.title','Stadium.city', 'Stadium.title')
				, 'recursive' => 0
			));
			$stadia = array();
			foreach($Results as $Result){
				$Stadium = $Result["Stadium"];
				$Country = $Result["Country"];
				$stadia[$Stadium['id']] = $Country["title"] . ", " . $Stadium["city"] . " - " . $Stadium["title"];
			}
			return $stadia;
		}
	}
}

