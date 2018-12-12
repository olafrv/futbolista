<?php

class MailsController extends AppController{

	var $uses = array('Mail','Mailing','User');
 	var $components = array('Phpmailer', 'Mailgun', 'RequestHandler');

  function beforeFilter() {
  	parent::beforeFilter();
		$this->Auth->allow(array('send', 'feedback')); // Anonymous + Token Access
		// Token autentication
		if (isset($this->params['named']['token'])){
			if (Configure::read('Futbol.wsFutbolistaToken') == $this->params['named']['token']){
				App::import('Controller', 'Users');
				$Users = new UsersController();
				$Users->constructClasses();

        // --- Eliminada dependencia de config/password.php
        //$password = Security::hash(Configure::read('Password.admin'), null, true);
        //Configure::write('debug', 1);
        $Users->loadModel('Users');
        $Users->User->recursive = false;
        $result = $Users->User->findByUsername("admin");
        $password = $result["User"]["password"];
        // --- Eliminada dependencia de config/password.php

				$Users->data = array('User'=>array('username'=>'admin','password'=>$password));
				$Users->loginFromShell = true;
				$Users->login();
			}
		}
	}

	function feedback(){
		$respuesta = "";
		$codigo = "";
		$evento = "";
		if ($this->RequestHandler->isPost()){
			$this->data = $this->params['form'];
			$settings = Configure::read('Futbol.smtpOptionsMailgun');
			$this->Mailgun->setSettings($settings);
			
			if (isset($this->data['token']) 
					&& isset($this->data['timestamp']) && isset($this->data['signature'])
						&& $this->Mailgun->webhookVerify($settings['key'], $this->data['token'], 
									$this->data['timestamp'], $this->data['signature'])){
				//$this->data['recipient'] = 'olafrv@gmail.com';
				$evento = $this->data['event'];
				switch($evento){
					case 'bounced':
					case 'complained':
					case 'dropped':
						$Users = $this->User->find('all', array('conditions'=>array(
							'mail' => $this->data['recipient']
						)));
						foreach($Users as $User){
							$this->User->read(null, $User["User"]["id"]);
							$this->User->set('mailing_list', 0);
							$this->User->save();
						}
						if (count($Users)>0){
							$respuesta = "Usuario '".$this->data['recipient']."' desactivado.";
							$codigo = '200 OK';
						}else{
							$respuesta = "Usuario '".$this->data['recipient']."' no existe.";
							$codigo = '406 Not Acceptable';
						}
						break;
					default:
						$respuesta = "Evento no procesable";
						$codigo = '406 Not Acceptable';
						break;
				}
			}else{
				$respuesta = 'Firma invalida';
				$codigo = '401 Not Authorized';
			}
		}else{
			$respuesta = 'Solicitud invalida';
			$codigo = '403 Forbidden';
		}
		$this->header('HTTP/1.1 ' . $codigo);
   	$this->layout = 'ajax';
		$this->set('respuesta', (!empty($evento)?"Feedback '$evento':":"") . " $respuesta");
		$this->render('/elements/ajax');
	}

	function addHtmlTags($html){
		$htmlBody = "";
		$htmlBody = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"";
						$htmlBody .= " \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
    $htmlBody .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"es\" lang=\"es\">";
		$htmlBody .= "<head>";
		$htmlBody .= "<meta http-equiv='content-language' content='es'>";
		$htmlBody .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>";
		$htmlBody .= "</head><body>" . $html . "</body></html>";
		return $htmlBody;
	}

	function index(){
    // BEGIN - ACTION MANAGEMENT
    //Form button default action
    $action = array('controller'=> 'Mails', 'action' => 'index', 'todo'=>'create');
    // Todo?
    $todo = isset($this->params["named"]["todo"]) ? $this->params["named"]["todo"] : NULL;
    $save = false;
    switch($todo){
      case "create":
        $this->Mail->create();
				if ($this->data["Mail"]["html"] == 1) $this->data["Mail"]["body"] = $this->addHtmlTags($this->data["Mail"]["body"]);
        $save = true;
        break;
      case "edit":
        $row = $this->Mail->findById($this->params["named"]["id"]);
        $this->data["Mail"] = $row["Mail"];
        $action = array('controller'=> 'Mails', 'action' => 'index', 'todo'=>'save', 'id'=>$this->params["named"]["id"]);
        break;
      case "save":
        if (isset($this->params["named"]["id"])) $this->Mail->set('id',$this->params["named"]["id"]);
				if ($this->data["Mail"]["html"] == 1) $this->data["Mail"]["body"] = $this->addHtmlTags($this->data["Mail"]["body"]);
			  $save = true;
        break;
      case "delete":
        $this->layout = 'default';
        if ($this->Mail->delete($this->params["named"]["id"])){
          $this->Session->setFlash('Registro eliminado', 'flash_ok');
        }else{
          $this->Session->setFlash('Error al eliminar el registro', 'flash_error');
        }
        break;
    }
    if ($save){
	      if (!empty($this->params["data"])){
        if ($this->Mail->save($this->params["data"]["Mail"])){
          $this->Session->setFlash('Datos guardados correctamente.', 'flash_ok');
          // CLEAN FIELDS!!!
          unset($this->data["Mail"]["id"]);
          unset($this->data["Mail"]["subject"]);
          unset($this->data["Mail"]["body"]);
        }else{
          $this->Session->setFlash(__('Error al guardar los datos', true) , 'flash_error');
        }
      }else{
        $this->Session->setFlash(__('Error datos inválidos', true) , 'flash_error');
      }
    }
    $this->set('action', $action);
    // END - ACTION MANAGEMENT
		$this->set('mails', $this->Mail->find('all', array('order'=>'Mail.id DESC', 'limit'=>5)));
	}

	function send($shell = false){
		$webservice = isset($this->params['named']['ws']);
		$suspended = Configure::read('Futbol.smtpMasiveSuspended');
		$sent_list = array();
 		// Phpmailer, Mailgun
		$smtp_comp = Configure::read('Futbol.smtpComponent'); 
		$smtp_obj = NULL;
		if ($smtp_comp == "Mailgun"){
			$smtp_obj = $this->Mailgun;
		}else if ($smtp_comp == "Phpmailer"){
			$smtp_obj = $this->Phpmailer;
		}
		$smtp_obj->setSettings(Configure::read('Futbol.smtpOptions' . $smtp_comp));
		$this->User->recursive = -1;
		$Users = $this->User->find('all', array(
			'fields' => array('id','mail','created','unsubscribe_uuid')
			, 'conditions' => array(
					'mailing_list' => 1
		//			, 'id'=>24
		//			, 'id'=>9999999
				)
		));
		$this->Mail->recursive = -1;
		$Mails = $this->Mail->find('all',
			//array('conditions'=>array('DATE(Mail.modified) >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)'))
			array('conditions'=>array('DATE(Mail.modified) >= DATE_ADD(CURDATE(),INTERVAL -1 DAY)'))
		);
		$from = Configure::read('Futbol.smtpFrom');
		$reply = Configure::read('Futbol.smtpReply');
		$i = 0;
		$i_max = Configure::read('Futbol.smtpMaxMails')-1;
		$errors = 0;
		foreach($Users as $User){
			if ($i == $i_max) break;
			$Mailings = $this->Mailing->find('list', array(
					'fields'=>array('mail_id','sent'),
					'conditions'=>array('user_id'=>$User['User']['id'])
			));
			foreach($Mails as $Mail){
				if (strtotime($Mail["Mail"]["modified"]) < strtotime($User['User']['created'])){
					// Skip old mails for new users
					continue;
				}
				if ($i == $i_max) break;
				if (!isset($Mailings[$Mail["Mail"]["id"]]) ||
						  (isset($Mailings[$Mail["Mail"]["id"]]) && 
						  	isset($Mailings[$Mail["Mail"]["id"]]["sent"]) && 
						  		$Mailings[$Mail["Mail"]["id"]]["sent"]==0)){
					++$i;
					$to = $User["User"]["mail"];
					$subject = $Mail["Mail"]["subject"];
					if ($Mail["Mail"]["html"]==1){
						$htmlBody = $Mail["Mail"]["body"];
						$plainBody = NULL;
					}else{
						$htmlBody = NULL;
						$plainBody = $Mail["Mail"]["body"];
					}					
					if (Configure::read('debug')==0){
						$realTo = $to;
					}else{
						//if ($to != "olafrv@gmail.com"){
							$realTo = Configure::read('Futbol.smtpTo');
						//}else{
							//$realTo = "olafrv@gmail.com";
							//$realTo = $to;
						//}
					}
					// For GMAIL SPAM Prevention
					$smtp_obj->clearCustomHeaders();
					$smtp_obj->addHeader('X-Futbolista-Mail-Id', $Mail["Mail"]["id"]);
					$smtp_obj->addHeader('X-Futbolista-User-Id', $User["User"]["id"]);
					$smtp_obj->addHeader('Precedence', 'bulk');
					$smtp_obj->addHeader('List-Unsubscribe', 
						"<" . Configure::read('Futbol.serverSslUrl') . '/Users/unsubscribe/id:' . 
							$User["User"]["id"] . '/unsubscribe_uuid:' . $User["User"]["unsubscribe_uuid"] . ">, " .
						"<mailto:" . Configure::read('Futbol.mailingListUnsubscribe') . ">" 
					);
					$fromWithName = array($from, Configure::read('Futbol.smtpFromName'));
					$replyWithName = array($reply, Configure::read('Futbol.smtpReplyName')); 
					// For GMAIL SPAM Prevention
					if (!$suspended){
						$sent = $smtp_obj->send(
							$fromWithName, $replyWithName, $realTo, 
							$subject, $htmlBody, $plainBody, $attachments=array()
						);
					}else{
						$sent = true; // Massive Mailing Suspended
					}
					if (!$shell) $sent_list[] = array($to, $Mail["Mail"]["id"]);
					$Existing = $this->Mailing->find('first', array('conditions'=>array(
						'mail_id' => $Mail["Mail"]["id"],
						'user_id' => $User["User"]["id"]
					)));
					if (!empty($Existing)){
						$this->Mailing->read(null, $Existing["Mailing"]["id"]);
						$this->Mailing->set('sent', ($sent ? 1 : 0));
						$this->Mailing->save();
					}else{
						$this->Mailing->create();
						$this->Mailing->save(array('Mailing'=>array(
							'mail_id' => $Mail["Mail"]["id"],
							'user_id' => $User["User"]["id"],
							'sent' => ($sent ? 1 : 0)
						)));
					}
					if (!$sent){
						$errors++;
						$this->log($smtp_obj->getError());
					}
				}
			}
		}
		if ($shell){
			return $errors;
		}else if ($webservice){
    	$this->layout = 'ajax';
			$this->set('respuesta', ($errors==0 ? 'OK' : 'Error: ' . $errors . ' emails with problems while sending'));
			$this->render('/elements/ajax');
		}else{
			$this->set('sent_list', $sent_list);
		}
	}

	// Show message
	function message(){
		$User = $this->Auth->user();
		$this->Mailing->recursive = -1; // Find Mailing and Mark as Read
		$Mailing = $this->Mailing->find('first', array('conditions'=> array(
				'user_id' => $User["User"]["id"],
				'mail_id' => $this->params["named"]["id"],
				'read' => 0
		)));
		if (!empty($Mailing)){
			$Mailing["Mailing"]["read"] = 1;
			$this->Mailing->save($Mailing["Mailing"]);
		}
		$this->Mailing->recursive = 0; // Find Mailing + Mail and Show
		$message = $this->Mailing->find('first',array(
			'contain'=> array('Mail'=>array(
					'fields'=>array('Mail.id','Mail.subject', 'Mail.body', 'Mail.html')
			))
			, 'conditions'=> array(
				'Mailing.user_id' => $User["User"]["id"],
				'Mailing.mail_id' => $this->params["named"]["id"]
			)
			, 'fields' => array('Mailing.modified')
		));
		if ($message["Mail"]["html"]==1){
    	$this->layout = 'html'; // JQuery Dialog
		}else{
    	$this->layout = 'text'; // JQuery Dialog
		}
		$this->set('message', $message);
	}

	// Show message Inbox
	function messages(){
		$User = $this->Auth->user();
		$this->paginate =  array(
			'contain'=> array(
			 'Mail'=>array(
					'fields'=>array('Mail.id','Mail.subject', 'Mail.html')
				)
			) 
			, 'conditions'=> array('Mailing.user_id' => $User["User"]["id"])
			, 'fields' => array('Mailing.read', 'Mailing.modified')
			, 'order' => array('Mailing.modified'=>'DESC')
			, 'limit' => 10
		);
		$this->set('messages', $this->paginate('Mailing'));
	}

}

