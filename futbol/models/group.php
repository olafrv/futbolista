<?php

class Group extends AppModel{
 	// Association between users and group for ACL
   var $hasMany = array('Users');

   // The AclBehavior allows automagic connection of models and ACL tables. 
   var $actsAs = array('Acl' => array('type' => 'requester'));
   
   // This is the ACL parent node
	function parentNode() {
		return null;
   }

}


