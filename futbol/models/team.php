<?php

class Team extends AppModel {
	var $name = 'Team';
	var $belongsTo = array('Country','Stadium');
	var $hasMany = array('PseudoTeam');
  var $order = array("Team.title");
}

