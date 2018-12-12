<?php 
	class Bet extends AppModel {
		var $name = 'Bet';
		var $belongsTo = array('Match', 'User');
}
