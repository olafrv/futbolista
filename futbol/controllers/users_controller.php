<?php
   class UsersController extends AppController {
		var $scaffold;
		var $name = 'Users';
		var $uses = array('User', 'Group');
 		var $components = array('Email', 'Phpmailer', 'Mailgun');
		var $layout = 'cake';
		var $loginFromShell = false;
		var $loginFromCross = false;

		function administrator(){
			if (!empty($this->params['requested'])){	
				return $this->User->find('list', array('conditions'=>array('username'=>'admin')));
			}
		}

		function beforeFilter() {
   		parent::beforeFilter();
			$this->Auth->autoRedirect = false;
    	$this->Auth->allow(
				array('register', 'login', 'securimage', 'logout', 'forgot', 'resetPassword', 'changePassword', 'privacy', 'unsubscribe', 'crosslogin')
			);
    }
    
    function crosslogin()
    {
			$json = json_encode(NULL);
			if (isset($this->params['named']['token']))
			{
				if (Configure::read('Futbol.wsFutbolistaToken') == $this->params['named']['token'])
				{
					if (isset($this->data['User']['username']) && isset($this->data['User']['password']))
					{
						$this->loginFromCross = true;
						if ($this->login()){
							$this->User->recursive = -1;
							$User = $this->User->findByUsername($this->data['User']['username']);
							unset($User['User']['password']);
 							unset($User['User']['forgot_uuid']);
							$json = json_encode(['User' => $User['User']]);
						}
						else
						{
							$json = json_encode(['Error' => 'Usuario o clave invalida.']);
						}
					}
				}					
			}
			$this->set(compact('json'));
	    $this->layout = 'json';
			$this->render('json');
		}
    
		function randomString($len){
			$str = "";
			for ($i=0; $i<$len; $i++) {
    			$d=rand(1,30)%2;
    			$str .= $d ? chr(rand(65,90)) : chr(rand(48,57));
			}
			return $str;
		}

		protected function isCaptcha($captcha){
			if (strpos(strtoupper($_SERVER["HTTP_USER_AGENT"]),"BLACKBERRY")!==false) return true;
			App::import('Vendor', 'Securimage', array('file' => 'securimage'.DS.'securimage.php'));
			$securimage = new Securimage();
			return $securimage->check($captcha);
		}

		protected function isBlockedUser($username){
			$user = $this->User->findByUsername($username);
			return isset($user['User']) && ($user['User']['enabled'] == false);
		}

		protected function blockUser($user_id){
			$this->User->id = $user_id;
			$this->User->read();
			$this->User->set('enabled', false);
			$this->Auth->logout();
			$this->User->save();
			return '/users/login';
		}

		function securimage($random_number){
			$this->autoLayout = false;
			$this->_loadSecurimage();
			$this->Securimage->captcha->code_length = 3;
			$this->Securimage->captcha->charset = 'ABCDEFGHJKLMNPRSTUVWXYZ123456789';
			$this->Securimage->captcha->image_bg_color =  $this->Securimage->color("#fefefe");
			$this->Securimage->show();				  
		} 

		protected function _loadSecurimage(){
			App::import('Component', 'Securimage');
			$this->Securimage = new SecurimageComponent();
			$this->Securimage->startup($this);
		}

		function acls() { 
			$this->layout = 'default';
			$lines = NULL;
			$groups = $this->requestAction('/Groups/listAll');
			$this->set('groups', $groups);
			/*
			$this->data = array('User' => array(
					'controller'=>'Groups', 'actions'=>'listAll', 'groups' => array(1,3)
			));
		 	*/	
			if (!empty($this->data)){	
				// Edite cname, anames y gtitles, luego, visite /users/acls	
   			$cname = trim($this->data["User"]["controller"]); //Aros => Controller (Plural, Case Sensitive)
		    $anames = array_map("trim", explode(',',$this->data["User"]["actions"])); //Aros => Action (Case Sensitive)
				$groups = $this->data["User"]["groups"]; //Acos => futbols_groups.title (Case Sensitive)
		
		    // cake acl create aco <parent-aco-alias> <child-aco-alias>
  		  $aco =& $this->Acl->Aco;        

		    $root = $aco->node('controllers');        
  		  if (!$root) {
    		  $aco->create(array('parent_id' => null, 'alias' => 'controllers'));            
	    	  $root = $aco->save();
  	    	$root['Aco']['id'] = $aco->id; 
	    	  $lines[] = "Created  ACO Node root -> controllers";
			   }else{
					$root = $root[0];
    		  $lines[] = "ACO Node root -> controllers exists.";    	
	  	  }

  		  $controllerAco = $aco->node('controllers/'.$cname);        
	   		if (!$controllerAco){
		      $aco->create(array('parent_id' => $root["Aco"]["id"], 'alias' => $cname));
	 	  	  $controllerAco = $aco->save();
    		  $controllerAco['Aco']['id'] = $aco->id; 
		      $lines[] = "Created ACO Node controllers -> $cname";
	  	  }else{
    		  $controllerAco = $controllerAco[0];
  		   	$lines[] = "ACO Node controllers -> $cname exists.";
		    }

  		  foreach($anames as $aname){
	    	  $actionAco = $aco->node('controllers/'.$cname.'/'.$aname);        
	  	    if (!$actionAco){
  		      $aco->create(array('parent_id' => $controllerAco["Aco"]["id"], 'alias' => $aname));
	    	    $actionAco = $aco->save();
    	  	  $actionAco['Aco']['id'] = $aco->id; 
  	      	$lines[] = "Created ACO Node controllers -> $cname -> $aname";
		   	  }else{
  		      $lines[] = "ACO Node controllers -> $cname -> $aname exists.";      
  	  	  }
		    }

  		  // cake grant <aro-alias> <aco-node> <actions = *, create, read, update, delete>
	    	foreach($groups as $group){
					$this->Group->recursive = -1;
		      $Group = $this->Group->findById($group); //Just rechecking!
					$title = $Group["Group"]["title"];
	  		  //$this->Acl->allow($group, 'controllers/'.$cname);
	  		  //$lines[] = "Granting to ARO $gtitle all to ACO Node controllers -> $cname";
  	  	  foreach($anames as $aname){
						if ($this->Acl->check(array('model'=>'Group', 'foreign_key'=>$group), $cname .'/'. $aname)){ 
  	  	    	$lines[] = "ACO Node controllers -> $cname -> $aname is accessible by ARO $title ($group) already";      
						}else{
							$this->Acl->allow(array('model'=>'Group', 'foreign_key'=>$group), $cname .'/'. $aname);
	  		      $lines[] = "ACO Node controllers -> $cname -> $aname is NOW accesible by ARO $title ($group)";
						}
    		  }
		    }
			}
			$this->set("lines", $lines);
		}

		function login() { //Users

			$this->layout = 'default';
			$this->set("hide_menu", true);
			$this->_loadSecurimage(); 

			if(isset($this->data['User']))
			{
				if ($this->isBlockedUser($this->data['User']['username']))
				{
					$this->Session->setFlash(__('Usuario bloqueado', true) , 'flash_error');
				}
				else
				{

					$captchaOk = true;
					if (!$this->loginFromShell && !$this->loginFromCross){
						if (!$this->isCaptcha($this->data['User']['captcha'])){
							$this->Session->setFlash(__('Texto de imagen de seguridad no coincide', true) , 'flash_error');
							$captchaOk = false;
						}
					}
					if ($captchaOk && $this->Auth->login($this->data)){
						$this->Session->setFlash(__('Bienvenido(a), "' . $this->data["User"]["username"] . '"', true), 'flash_ok');
						if ($this->loginFromShell || $this->loginFromCross){
							return true;
						}else{
							$this->redirect('/bets/mine');
						}
					}
				}
			}
			
			if ($this->loginFromShell || $this->loginFromCross){
				return false;
			}else{
				unset($this->data['User']['password']);
				unset($this->data['User']['captcha']);
				$this->Auth->logout();
			}						

		}

		function resetPassword(){
			$this->layout = 'default';
			$flash = null;
			$error = true;
			$mensaje = null;
			//$serverUrl = Configure::write('Futbol.serverSslUrl');
			if (isset($this->params['named']["forgot_uuid"]) && isset($this->params['named']["id"])){
				$this->User->id = $this->params['named']["id"]; 
				if ($this->User->read()){
					$username = $this->User->data["User"]["username"];
					if ($this->User->data["User"]["forgot_uuid"] != $this->params['named']["forgot_uuid"]){
						$flash = __('Esta solicitud expiró, debe solicitar el reinicio de su clave nuevamente', true);
					}else{
						$random_string = strtolower($this->randomString(6));
						$this->data['User']['password'] = Security::hash($random_string, null, true);
						$this->data['User']['forgot_uuid'] = null;
						if ($this->User->save($this->data)){
							$mensaje = __('Al usuario',true) . '&nbsp;<b>' . $username . '</b>&nbsp;' . 
								__('se le ha asignado la clave de acceso', true) . '&nbsp;<b>' . $random_string . '</b>';
							$error = false;
						}else{
							$flash = __('No se ha podido cambiar la clave.', true);
						}
					}
				}else{
					$flash = __('Usuario inválido', true);
				}
			}else{
				$flash = __('Parámetros inválidos', true);
			}
			if (!is_null($flash)) $this->Session->setFlash($flash, $error ? 'flash_error' : 'flash_ok');
			$this->set('error', $error);
			$this->set('mensaje', $mensaje);
		}


		function unsubscribe(){
			$this->layout = 'default';
			$this->_loadSecurimage();
			$this->set('manual', true); //Manual unsubscription form (user, password, captcha)
			$flash = null;
			$error = false;
			$mensaje = null;
			//$serverUrl = Configure::write('Futbol.serverSslUrl');
			if (isset($this->params['named']["unsubscribe_uuid"]) && isset($this->params['named']["id"])){
				$this->User->recursive = -1;
				$this->data = $this->User->findById($this->params['named']["id"]); 
				if ($this->data['User']["unsubscribe_uuid"] != $this->params['named']['unsubscribe_uuid']){
					$flash = __('Parámetros inválidos', true);
					$error = true;
				}else{
					$this->set('manual', false);
				}
			}else if (isset($this->data['User']['username']) && isset($this->data['User']['password'])){
				if (!$this->isCaptcha($this->data['User']['captcha'])){
					$flash = "Código de imagen de seguridad incorrecto";
					$error = true;
				}else{
					$this->User->recursive = -1;
					$password = $this->data['User']['password'];
					$this->data = $this->User->find('first',
						array('conditions'=>
							array(
								'username'=>$username = $this->data['User']['username']
							)
						)
					);
					$this->data['User']['password'] = $password;
				}	
			}
			if (!$error){
				if ($this->Auth->login($this->data)){
					$username = $this->data['User']['username'];
					$this->data['User']['mailing_list'] = 0;
					if ($this->User->save($this->data)){
						$flash = "El usuario $username ha sido eliminado de la lista de correo electrónico";
					}else{
						$flash = __('No se ha podido ser eliminado de la lista de correo electrónico.', true);						
						$error = true;
					}

				}else{
					$flash = __('Autenticación inválida o parámetros incompletos', true);
					$error = true;
				}
			}
      unset($this->data['User']['password']);
      unset($this->data['User']['captcha']);
			$this->Auth->logout();
			if (!is_null($flash)) $this->Session->setFlash($flash, $error ? 'flash_error' : 'flash_ok');
			$this->set('error', $error);
			$this->set('mensaje', $mensaje);
		}

		function forgot() //Users
		{ 
			$this->_loadSecurimage();
			$_token = Configure::read('Futbol.wsFutbolistaToken');
			$flash = "";  $flash_class = "error";
			if (!empty($this->data))
			{		
				if ((isset($this->params['named']['token']) && $_token != $this->params['named']['token'])
						|| 
						(!isset($this->params['named']['token']) && !$this->isCaptcha($this->data['User']['captcha'])))
				{
					$flash = 'Código de imagen (o token) inválido.';
				}
				else
				{
					if (isset($this->data['User']['usermail'])){
						$usermail = $this->data['User']['usermail'];
						$this->User->recursive = -1;
						if (strpos($usermail,'@')===false){	
							$results = $this->User->findAllByUsername($usermail);
						}else{
							$results = $this->User->findAllByMail($usermail);
						}
						if (count($results)>0){

							$Users = array();
							$Links = array();
							foreach($results as $result){
								$this->User->id = $result["User"]["id"];
								$this->User->read();
								$forgot_uuid = String::uuid();
								$this->User->save(
									array('User'=>
										array('forgot_uuid'=>$forgot_uuid)
									)
								);
								$Users[] = $result['User'];
								$Links[] = Configure::read('Futbol.serverSslUrl') . '/users/resetPassword/id:' 
									. $result['User']['id'] . "/forgot_uuid:" . $forgot_uuid;
							}

							/* Email - BEGIN */
	
//---
//	Configure::write("debug", 2);
	$smtp_comp = Configure::read('Futbol.smtpComponent');
 	$smtp_obj = NULL;
  if ($smtp_comp == "Mailgun"){
		$smtp_obj = $this->Mailgun;
	}else if ($smtp_comp == "Phpmailer"){
		$smtp_obj = $this->Phpmailer;
	}
	$smtp_obj->setSettings(Configure::read('Futbol.smtpOptions' . $smtp_comp));
	$smtp_obj->clearCustomHeaders();
	$subject = __('Confirmación de reinicio de clave', true);
	$htmlBody = "
Estimado Usuario(a),<br>
<br/>
AGRADECEMOS LEA CUIDADOSAMENTE ESTE CORREO.<br/>
<br/>
Ha recibido este correo electrónico porque se ha SOLICITADO el REINICIO DE SU CLAVE
de acceso al sistema " . Configure::read('Futbol.serverSslUrl') . ".<br>
<br>
Si UD. NO SOLICITO el reinicio de su clave HAGA CASO OMISO de este correo.<br/>
<br/>
Si solo olvidó su nombre de usuario y NO DESEA REINICIAR SU CLAVE
simplemente NO HAGA CLIC en los vínculos que se muestran debajo.<br/>
<br/>";

	foreach($Users as $index => $User)
	{
		$htmlBody .= "Para REINICIAR la CLAVE del usuario \"" . $User["username"] . "\"";
		$htmlBody .= " haga CLIC en el siguiente VINCULO:<br/><br/>";
		$htmlBody .= $Links[$index] . "<br/><br/>";
	}
	$htmlBody .= "Si tiene dudas, comentarios u observaciones visite ";
	$htmlBody .= Configure::read('Futbol.serverSslUrl') . '/bets/contact' . "<br/>";
	// echo $this->element('firma');
	$plainBody = NULL;
	if (Configure::read('debug')==0){
		//$realTo = $result['User']['username'] . ' <' . $result['User']['mail'] . '>';
		$realTo = $result['User']['mail'];
	}else{
	//if ($to != "olafrv@gmail.com"){
	  $realTo = Configure::read('Futbol.smtpTo');
	//}else{
	  //$realTo = "olafrv@gmail.com";
	  //$realTo = $to;
	//}
	}
	$from = Configure::read('Futbol.smtpFrom');
	$reply = Configure::read('Futbol.smtpReply');
	$fromWithName = array($from, Configure::read('Futbol.smtpFromName'));
	$replyWithName = array($reply, Configure::read('Futbol.smtpReplyName'));

	$sent = $smtp_obj->send(
		$fromWithName, $replyWithName, $realTo,
		$subject, $htmlBody, $plainBody, $attachments=array()
	);
	if (!$sent){
		$errors++;
		$flash = $smtp_obj->getError();
		$this->log($smtp_obj->getError());
	}else{
		$flash =
			'Se le ha enviado un correo electrónico con las instrucciones'
			. ' para recuperar su usuario o reiniciar su clave de acceso al sistema.';
			$flash_class = "ok";
	}
	

//---

							/* Email - END */
						}else{
							$flash = 'Usuario inválido';
						}
					}
				}
			}	
			
			if (isset($this->params['named']['token']))
			{
				$json = json_encode(NULL);
				if ($flash_class=="ok")
				{
					 $json = json_encode([
					 	'Message' => $flash
					 ]);
				}
				else
				{
					$json = json_encode([
						'Error' => $flash
					]);
				}
				$this->set(compact('json'));
			  $this->layout = 'json';
				$this->render('json');
			}
			else
			{
				$this->layout = 'default';
				unset($this->data['User']);
				if ($flash_class=="ok")
				{
					$this->Session->setFlash(__($flash, true), 'flash_ok', array());
				}
				else
				{
					if (!empty($flash)) $this->Session->setFlash(__($flash, true), 'flash_error', array());
				}
			}

		}

		function logout() { //Users
		   $this->autoLayout = false;
			$this->redirect($this->Auth->logout());
		}

		function privacy() { //Users
		   $this->layout = 'default';
		}

		function resetAdmin(){
			$User = $this->Auth->user();
			if ($User['username']=='admin'){
				$this->layout = 'default';
				$this->set("hide_menu", true);
				$admin = $this->User->findByUsername('admin');
				$datasource = $this->User->getDataSource();
				$datasource->begin($this->User);			
				if (count($admin)>0) debug($this->User->delete($admin['User']['id']));
				$newdata = array(
					'User' => array(
						'username' => 'admin',
						'password' => Security::hash('admin', null, true),
						'mail' => Configure::read('Futbol.contactMail'),
						'group_id' => 1,
						'enabled' => 1
					)
				);
				$this->User->create();
				if ($this->User->save($newdata)){
					$datasource->commit($this->User);
					$this->Session->setFlash(__('Se ha reiniciado la cuenta de administración.', true) , 'flash_ok');
				}else{
					$datasource->rollback($this->User);
					$this->Session->setFlash(__('No se puede reiniciar la cuenta de administración.', true), 'flash_error');
				}
				$this->redirect('/admins');
			}else{
				$this->flash(__('Su usuario ha sido bloqueado.', true), $this->blockUser($User['id']), 3);
			}
		}

		function changePassword() //Users
		{ 
			$flash = ''; $flash_class = 'error';
			if (!empty($this->data))
			{
				if (!isset($this->params['named']['token']) && 
							($this->data['User']['password'] != $this->data['User']['password_confirm'])
						||
						isset($this->params['named']['token']) &&
							($this->data['User']['password'] != Security::hash($this->data['User']['password_confirm'], null, true))
						)
				{
					$flash = 'Las claves no coinciden.';
				}
				else
				{
					$User = $this->Auth->user();		
					if (isset($this->params['named']['token'])) $User = $this->data;						
					$User = $this->User->findByUsername($User['User']['username']);
					if (!empty($User))
					{
						$this->User->id = $User['User']['id'];
						$this->User->read();
						if (!isset($this->params['named']['token'])) $this->data['User']['password'] = Security::hash($this->data['User']['password'], null, true);
						if ($this->User->save($this->data))
						{
							$flash = 'Clave cambiada.';
							$flash_class = 'ok';
						}
						else
						{
							$flash = 'No se ha podido cambiar la clave.';
						}
					}
					else
					{
							$flash = 'El usuario no existe';
					}				
				}
			}	
			
			if (isset($this->params['named']['token']))
			{
				$json = json_encode(NULL);
				if ($flash_class=="ok")
				{
					 $json = json_encode([
					 	'User' => []
					 ]);
				}
				else
				{
					$json = json_encode([
						'Error' => $flash
					]);
				}
				$this->set(compact('json'));
			  $this->layout = 'json';
				$this->render('json');
			}
			else
			{
				$this->layout = 'default';
				unset($this->data['User']['password']);
				unset($this->data['User']['password_confirm']);
				unset($this->data['User']['captcha']);
				if ($flash_class=="ok")
				{
					unset($this->data['User']['username']);
					$this->Session->setFlash(__($flash, true), 'flash_ok', array());
				}
				else
				{
					if (!empty($flash)) $this->Session->setFlash(__($flash, true), 'flash_error', array());
				}
			}

		}

   	function register() //Users
   	{ 
			$this->_loadSecurimage();
			$this->set("hide_menu", true);
			$_token = Configure::read('Futbol.wsFutbolistaToken');
			$flash = "";  $flash_class = "error";
			if (!empty($this->data))
			{		
				if ((isset($this->params['named']['token']) && $_token != $this->params['named']['token'])
						|| 
						(!isset($this->params['named']['token']) && !$this->isCaptcha($this->data['User']['captcha'])))
				{
					$flash = 'Código de imagen (o token) inválido.';
				}
				else if ($this->data['User']['password'] !== Security::hash($this->data['User']['password_confirm'], null, true)) 
				{
					$flash = 'Las claves no coinciden.';
				}
				else if($this->data['User']['mail'] != $this->data['User']['mail_confirm'])
				{
					$flash = 'Las direcciones de email no coinciden.';
				}
				else
				{
					$User = NULL;
					if (isset($this->data['User']['username']))
					{
						$this->User->recursive = -1;
						$User = $this->User->findByUsername($this->data['User']['username']);
					}
					if (empty($User)){
						$this->User->create();
						$this->data['User']['group_id'] = 3; // "Users"
						$this->data['User']['enabled'] = 1;
						$this->data['User']['mailing_list'] = 1;
						$this->data['User']['unsubscribe_uuid'] = md5(String::uuid());
						$this->data['User']['username'] = trim($this->data['User']['username']);
						
						if ($this->User->save($this->data))
						{
							$flash = 'El usuario fue creado.  Ahora puede ingresar al sistema.';
							$flash_class = "ok";
		 				}
		 				else
		 				{
							$flash = 'Error desconocido.';
						}
					}
					else
					{
						$flash = 'El usuario ya está registrado.';
					}
				}
			}
			if (isset($this->params['named']['token']))
			{
				$json = json_encode(NULL);
				if ($flash_class=="ok")
				{
					 $User = $this->User->findByUsername($this->data['User']['username']);
					 unset($User['User']['password']);
					 unset($User['User']['forgot_uuid']);
					 $json = json_encode([
					 	'User' => $User['User']
					 ]);
				}
				else
				{
					$json = json_encode([
						'Error' => $flash
					]);
				}
				$this->set(compact('json'));
		    $this->layout = 'json';
				$this->render('json');
			}
			else
			{
				$this->layout = 'default';
				unset($this->data['User']['password']);
				unset($this->data['User']['password_confirm']);
				unset($this->data['User']['captcha']);
				if ($flash_class=="ok")
				{
					unset($this->data['User']['username']);
					$this->Session->setFlash(__($flash, true), 'flash_ok', array());
					$this->render('login');
				}
				else
				{
					if (!empty($flash)) $this->Session->setFlash(__($flash, true), 'flash_error', array());
				}
			}
		}
	}
