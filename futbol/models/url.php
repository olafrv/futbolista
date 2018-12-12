<?php
	class Url extends AppModel{
		var $name = 'Url';
 		var $belongsTo = array('Competition');
	  var $order = array("Url.ordering" => "ASC");
	}
