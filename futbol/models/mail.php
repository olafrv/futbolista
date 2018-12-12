<?php
	class Mail extends AppModel{
		var $name = 'Mail';
  	var $hasMany = array('Mailing');
		var $validate = array(
			'subject' => array(
				'r1' => array(
					'rule' => array('maxLength', 255),
					'required' => true,
					'allowEmpty' => false,
					'message' => 'Debe introducir un asunto con max. 255 caracteres'
				)
			)
		);
	}
