<?php

App::import('Vendor','mailgun' ,array('file'=>'mailgun-php'.DS.'vendor'.DS.'autoload.php'));

use Mailgun\Mailgun;

class MailgunComponent extends Object {

	private $controller = NULL;
	private $mailgun = NULL;
	private $initialized = false;
	private $settings = array(); // key, domain
	private $headers = array();

	function initialize(&$controller) {
		$this->controller =& $controller;
		$this->errorMsg = "";
		$this->initialized = true;
	}

	function webhookVerify($api_key, $token, $timestamp, $signature){
		return $signature == hash_hmac('sha256', $timestamp . $token, $api_key);
	}

	function startup(&$controller) {
		if (!$this->initialized){
			$this->controller->log('MailgunComponent was not initialized');
		}else{
			$this->started = true;
		}
	}

  function setSettings($settings){
    $this->settings = $settings;
	}

	function addHeader($name, $value){
		$this->headers['h:'.$name] = $value;
	}

  function clearCustomHeaders(){
    $this->headers = array();
  }

	function send($from, $reply=NULL, $to, $subject, $htmlBody = "", $plainBody = "", $attachments=array()){ 
		//Create a new Mailgun instance
		if (is_null($this->mailgun)) $this->mailgun = new Mailgun($this->settings["key"]); 
		$testmode = "yes";
		if (!empty($this->settings["testmode"]) 
					&& in_array(strtolower($this->settings["testmode"]), array("yes", "no"))){
			$testmode = $this->settings["testmode"];	
		}
		$result = $this->mailgun->sendMessage(
			$this->settings["domain"]
			, array_merge(
				array(
					'from' => $from, 
    	    'h:Reply-To' => (is_null($reply) ? $from : $reply),
					'to' => $to,
	       	'subject' => $subject, 
  	      'html' => $htmlBody,
					'text' => $plainBody,
					'o:testmode' => $testmode
				)
				, $this->headers
			)
		);
		$httpResponseCode = $result->http_response_code;
		$httpResponseBody = $result->http_response_body;
		if ($httpResponseCode == 200){
			$this->errorMsg = "";
			return true;
		}else{
			$this->errorMsg = "Error $httpResponseCode (HTTP) => $httpResponseBody";
			return false;
		}
	}

	function getError(){ return $this->errorMsg; }

	function shutdown(&$controller){ return true; }
}

