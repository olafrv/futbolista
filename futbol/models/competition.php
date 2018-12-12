<?php
	class Competition extends AppModel{
		var $name = "Competition";
		var $hasMany = array('Fase', 'Payment', 'Url');
		var $displayField = 'title';
		var $virtualFields = array('title_id' => 'CONCAT(Competition.title, \' (\', Competition.id, \')\')');
		var $order = array("Competition.begins" => "DESC", "Competition.ends" => "DESC");
	}
