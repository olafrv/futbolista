<?php

class PseudoTeam extends AppModel {
	var $name = 'PseudoTeam';
	var $displayField = 'abreviation';
	var $belongsTo = array('Team','Groupping');
	var $hasMany = array(
   	'MatchHost' => array(
    		'className' => 'Match',
    		'foreignKey' => 'host_id'
    	),
    	'MatchGuest' => array(
    		'className' => 'Match',
    		'foreignKey' => 'guest_id'
    	)
	);
   // Problemas para añadir nuevo Match
   // var $order = array("PseudoTeam.id" => "DESC");
  var $validate = array(
    'abreviation' => array(
      'r1' => array(
          'rule' => array('maxLength', 5),
          'required' => true,
          'allowEmpty' => false,
          'message' => 'Debe introducir una abreviatura con max. 5 caracteres'
      )
		)
	);
}

