<?php

class Match extends AppModel {
	var $name = 'Match';
	var $belongsTo = array(
   	'PseudoTeamHost' => array(
    		'className' => 'PseudoTeam',
    		'foreignKey' => 'host_id'
    	),
    	'PseudoTeamGuest' => array(
    		'className' => 'PseudoTeam',
    		'foreignKey' => 'guest_id'
    	),
		'Groupping' => array('Groupping'),
		'Stadium' => array('Stadium')
	);
	var $hasMany = 'Bet';

	// Validations
	var $validate = array(
		'host_id' => array(
			'rule' => 'notEmpty',
			'required' => true,
			'message' => 'You must select a host for the match'
		),
		'guest_id' => array(
			'rule' => 'notEmpty',
			'required' => true,
			'message' => 'You must select a guest for the match'
		)
	);

   var $order = array("Match.kickoff" => "DESC");

   function __construct($id = false, $table = null, $ds = null) {
 		parent::__construct($id, $table, $ds);
	   $this->virtualFields['is_pending'] = sprintf(
			'DATE(%s.kickoff) > DATE("'.date('Y-m-d').'") AND %s.host_goals IS NULL AND %s.guest_goals IS NULL', 
				$this->alias, $this->alias, $this->alias);
   }

   function afterSave($created){

		//Retrieve "match"
		$this->recursive = -1;	
		$data = $this->read();
		$Match = $data['Match'];

		//Update "bet" earned points for this match (for all users)
		if ($Match['guest_goals']!="" && $Match['host_goals']!=""){
			if (strtotime($Match['kickoff'])<strtotime("01 Aug 2011")){ // Rules from 01 de Aug de 2011
				$this->query(
					" UPDATE futbol_bets".
					" SET points = CASE".
					" WHEN host_goals =" . $Match['host_goals'] .' AND guest_goals=' . $Match['guest_goals'] ." THEN 3".
					' WHEN host_goals = guest_goals AND ' . $Match['host_goals'] .' = ' . $Match['guest_goals'] ." THEN 1".
					" WHEN host_goals > guest_goals AND " . $Match['host_goals'] . ">" . $Match['guest_goals'] . " THEN 1".
					" WHEN host_goals < guest_goals AND " . $Match['host_goals'] . "<" . $Match['guest_goals'] . " THEN 1".
					" ELSE 0 END" .
					" WHERE match_id=" . $Match['id']
				);
			}else{ // Rules from 01 de Agosto de 2011
				// Basic earned points (120 min. before penalties)			
	         $this->query(
		         " UPDATE futbol_bets".
			      " SET points = CASE".
					// Result (Both scores)
					" WHEN host_goals =" . $Match['host_goals'] .' AND guest_goals=' . $Match['guest_goals'] ." THEN 3".
	            // Result (One score only)
					" WHEN (". 
					" (host_goals =" . $Match['host_goals'] .' OR guest_goals=' . $Match['guest_goals'] .")".
					" AND ((host_goals > guest_goals AND " . $Match['host_goals'] . ">" . $Match['guest_goals'] . ")". 
					" OR (host_goals < guest_goals AND " . $Match['host_goals'] . "<" . $Match['guest_goals'] . "))".
					" ) THEN 1.5".
					// Winner 
					" WHEN ((host_goals > guest_goals AND " . $Match['host_goals'] . ">" . $Match['guest_goals'] . ")". 
				   " OR (host_goals < guest_goals AND " . $Match['host_goals'] . "<" . $Match['guest_goals'] . ")) THEN 1".
					// Drawn
	            ' WHEN host_goals = guest_goals AND ' . $Match['host_goals'] .' = ' . $Match['guest_goals'] ." THEN 1".
					// Nothing
			      " ELSE 0 END" .
				   " WHERE match_id=" . $Match['id']
	         );	
				//Extra earned points (Penalties)
				/*
				if ($Match['has_penalties']==1 && $Match['host_goals']==$Match['guest_goals']){
					$this->query(
						" UPDATE futbol_bets".
						" SET points = points + ".
						" (CASE WHEN host_penalties =" . $Match['host_penalties'] .' AND guest_penalties=' . $Match['guest_penalties'] ." THEN 5".
						" WHEN ((host_penalties > guest_penalties AND " . $Match['host_penalties'] . ">" . $Match['guest_penalties'] . ")".
						" OR (host_penalties < guest_penalties AND " . $Match['host_penalties'] . "<" . $Match['guest_penalties'] . ")) THEN 3".
						" ELSE 0 END)".
						" WHERE match_id=" . $Match['id'] . " AND host_goals = guest_goals".
						" AND host_penalties IS NOT NULL AND guest_penalties IS NOT NULL"
					);
				}
				*/
				//Calculate total with ponderation factor
				$this->query(
					" UPDATE futbol_bets".
					" SET points = points * ". $Match['ponderation'] . 
					" WHERE match_id=" . $Match['id']
				);
			}
		}else{
			// Clean earned points for this match
			$this->query("UPDATE futbol_bets SET points = 0 WHERE match_id=" . $Match['id']);
		}

		/**
		 * NOT NEEDED BECAUSE OF INSERT OR REPLACE USED WHEN POPULATING THIS TABLE...
		 * Delete home away information for the match groupping
		 * $this->query("DELETE FROM futbol_rankings WHERE groupping_id = " . $Match["groupping_id"]);
		 */
	
		//Update total of "points" earned for this competition (for all users)	
		App::import('Controller', 'Bets');
		$BetsCtl = new BetsController(null,null,true);
		$BetsCtl->constructClasses();
		return $BetsCtl->calculate(null, $Match['groupping_id']);

	}
  
}
