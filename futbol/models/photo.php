<?php
	class Photo extends AppModel{
		var $name = 'Photo';
		var $belongsTo = array('Competition');
		var $order = Array("Photo.modified"=> "DESC", "Photo.name" => "ASC");
	}
