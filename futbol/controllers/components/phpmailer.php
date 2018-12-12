<?php

App::import('Vendor','phpmailer' ,array('file'=>'phpmailer'.DS.'PHPMailerAutoload.php'));

//define('PHPMAILER_CAKEPHP_VENDOR_DIR', APP . DS . 'vendors' . DS . 'phpmailer');

class PhpmailerComponent extends Object {

	private $controller = NULL;
	private $phpmailer = NULL;
	private $initialized = false;
	private $settings = array();

	function initialize(&$controller) {
		$this->controller =& $controller;
		$this->phpmailer = new PHPMailer(); //Create a new PHPMailer instance
		$this->initialized = true;
	}
	
	function startup(&$controller) {
		if (!$this->initialized){
			$this->controller->log('PhpmailerComponent was not initialized');
		}else{
			$this->phpmailer->isSMTP(); //Tell PHPMailer to use SMTP
			$this->phpmailer->SMTPDebug = 0; // 0 = off (for production use)
			$this->phpmailer->Debugoutput = 'html'; //Ask for HTML-friendly debug output
			$this->setCharset(); //Default to UTF-8
			$this->started = true;
		}
	}

	function debug($activate = true){
		$this->phpmailer->SMTPDebug = $activate ? 2 : 0;	//Enable SMTP debugging?
	}
	
	function setSettings($settings){
		$this->settings = $settings;
		$this->phpmailer->Host = $settings["host"];
		$this->phpmailer->Port = $settings["port"];
		$this->phpmailer->SMTPAuth = isset($settings["username"]);
		$this->phpmailer->Username = isset($settings["username"]) ? $settings["username"] : NULL;
		$this->phpmailer->Password = isset($settings["password"]) ? $settings["password"] : NULL;
	}
	
	function setCharset($encoding='UTF-8'){
		$this->phpmailer->CharSet = $encoding;
	}

	function clearCustomHeaders(){
		$this->phpmailer->clearCustomHeaders();
	}
	function addHeader($header, $value){
      if (is_null($this->settings)){
         $this->controller->log('PhpmailerComponent settings are empty');
         return false;
      }else{
				$this->phpmailer->addCustomHeader($header, $value);
				return true;
			}
	}
	
	function send($from, $reply=NULL, $to, $subject, $htmlBody = NULL, $plainBody = NULL, $attachments=array()){ 
		//return true;
		if (is_null($this->settings)){
			$this->controller->log('PhpmailerComponent settings are empty');
			return false;
		}

		$this->clear(); // Clear all addresses and attachments for next loop

		if (is_array($from)){
			$this->phpmailer->setFrom($from[0], $from[1]);
		}else{
			$this->phpmailer->setFrom($from);
		}
		if (is_array($to)){
			$this->phpmailer->addAddress($to[0], $to[1]);
		}else{
			$this->phpmailer->addAddress($to);
		}
		if (!is_null($reply)){
			if (is_array($reply)){
				$this->phpmailer->addReplyTo($reply[0], $reply[1]);
			}else{
				$this->phpmailer->addReplyTo($reply);
			}
		}
		$this->phpmailer->Subject = $subject;
		if (!is_null($htmlBody)){
			// Convert referenced images to embedded and ¿HTML into a basic plain-text alternative body?
			$this->phpmailer->msgHTML($htmlBody, dirname(__FILE__));
			if (!is_null($plainBody)){
				$this->phpmailer->AltBody = $plainBody; //Replace the plain text body with one created manually
		  }else{
		    $this->phpmailer->AltBody = __('Para ver el mensaje, utilice un cliente de correo compatible con HTML!', true);
			}
		}else if (!is_null($plainBody)){
			$this->phpmailer->Body = $plainBody;
		}else{
			$this->phpmailer->Body = "";
		}
		foreach($attachments as $path){
			$this->phpmailer->addAttachment($path); //Relative PATH to previous msgHTML() 2nd param!
		}
		return $this->phpmailer->send(); 
	}

  // Clear all addresses and attachments for next loop	
	function clear(){
    $this->phpmailer->clearAddresses();
    $this->phpmailer->clearAttachments();
  }
	
	function getError(){ return $this->phpmailer->ErrorInfo; }
	
	function shutdown(&$controller){ return true; }
}

