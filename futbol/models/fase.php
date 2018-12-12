<?php
	class Fase extends AppModel{
		var $name = 'Fase';
		//var $hasMany = array('Groupping', 'Match');
		var $hasMany = array('Groupping');
  		var $belongsTo = array('Competition');
      var $displayField = 'title';
      var $virtualFields = array('title_id' => 'CONCAT(Fase.title, \' (\', Fase.id, \')\')');
		var $order = array("Fase.begins" => "DESC", "Fase.ends" => "DESC");
	}
