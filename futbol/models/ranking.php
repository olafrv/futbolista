<?php
	class Ranking extends AppModel{
		var $name = 'Ranking';
  		var $belongsTo = array('Groupping','PseudoTeam');
	}
