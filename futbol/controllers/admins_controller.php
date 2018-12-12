<?php

class AdminsController extends AppController{

  var $name = "Admins";
  var $uses = array("User","Group");
  
  function index(){}

	function phpinfo(){
		$this->layout = "blank";
	}
  
	function email(){
	  $this->helpers[] = 'Html';
	  $this->set('action',	array('controller' => 'Mails', 'action' => 'index'));
	}
	
}
