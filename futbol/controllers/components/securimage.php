<?php

// Tested on CakePHP 1.3.20

App::import('Vendor','Securimage' ,array('file'=>'securimage'.DS.'securimage.php')); //use this with the 1.2 core

// Remember to uncompress Secureimage.zip in vendors/securimage/
// and public_html/securimage/, the fast solution!

class SecurimageComponent extends Object {

	var $captcha;
	var $controller;

	function startup( &$controller ) {
		$this->controller =& $controller;
		$this->captcha = new Securimage();
		//url for the captcha image
		$this->controller->set('securimage_url', 
				Router::url('/'.$controller->plugin.'/'.$controller->name.'/securimage/0',true)
		); 
   }

	function color($htmlColor){
		return new Securimage_Color($htmlColor);
	}
	
	function show(){
		$this->captcha->Show();
	}

}

