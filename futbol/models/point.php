<?php
	class Point extends AppModel{
		var $name = 'Point';
  		var $belongsTo = array('Competition','User');
	}
