<?php
	class Mailing extends AppModel{
		var $name = 'Mailing';
		var $belongsTo = array('User','Mail');
	}
