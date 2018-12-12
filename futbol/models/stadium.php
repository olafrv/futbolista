<?php
	class Stadium extends AppModel{
		var $name = 'Stadium';
 		var $hasMany = array('Match');
		var $belongsTo = array('Country');
		var $displayField = 'title_city';
    var $virtualFields = array('title_city' => 'CONCAT(Stadium.title, \' (\', Stadium.city, \')\')');
		var $order = array("Stadium.title");
	}
