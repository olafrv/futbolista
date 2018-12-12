<?php

class User extends AppModel {
	var $name = 'User';
	var $displayField = 'username';   

	// Association between users and group for ACL
	var $belongsTo = array('Group');

	var $hasMany = array('Bet', 'Payment', 'Point', 'Mailing');
	
   // The AclBehavior allows automagic connection of models and ACL tables. 
   var $actsAs = array('Acl' => array('type' => 'requester'));
	
	// Validations
	var $validate = array(
		'username' => array(
			'r1' => array(
					'rule' => array('maxLength', 16),
					'required' => true,
					'allowEmpty' => false,
					'message' => 'Debe introducir nombre de usuario con max. 10 caracteres'
			),
			'r2' => array(
				'rule' => 'isUnique',
				'message' => 'El nombre de usuario ya existe'
			)
		),
      'password' => array(
         'min' => array(
           'rule' => array('minLength', 6),
           'message' => 'El nombre de usuario debe tener al menos 6 caracteres'
         ),
         'required' => array(
           'rule' => 'notEmpty',
           'message'=> 'Por favor, introduzca una clave'
         ),
      ),
		'mail' => array(
				'rule' => 'email',
				'message' => 'Debe introducir una direccion de Email valida'
		)
	);

	/** 
    * This method will tell ACL to skip check only Group Aro's.
	 */
   function bindNode($user) {
   	return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
   }

	// For ACL to work
   function parentNode() {
	   if (!$this->id && empty($this->data)) {
   		return null;
   	}
  		if (isset($this->data['User']['group_id'])) {
   		$groupId = $this->data['User']['group_id'];
   	}else{
   		$groupId = $this->field('group_id');
   	}
   	if (!$groupId) {
   		return null;
   	}else{
   		return array('Group' => array('id' => $groupId));
   	}
   }
}

