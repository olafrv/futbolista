<?php

App::import('Core', 'Controller');  //Needed in all shells that use controllers
App::import('Core', 'Component'); //Needed in all shells that use controllers

class FutbolShell extends Shell {

   var $uses = array('User');

	function _welcome(){
		// Skip Shell Welcome Message
	}

	function getServerSslUrl(){
		echo Configure::read("Futbol.serverSslUrl");
	}

	/* Default method */
	function main(){
		echo "Methods:\n";
		echo " - genSurveyCSV\n";
		echo " - mailFutbolista\n";
		echo " - mailings\n";
		echo " - listEmails\n";
//		echo " - cleanAcls\n"; // Now we use /Admins/acls to allow/deny access
//		echo " - membersFutbolista\n"; // Now we don't use mailmain nor googlegroups.com
// 		echo " - totalizePoints\n"; // /Bets/calculate is called after Match save
//		echo " - forecast_audit\n"; //Called from $this->mailFutbolista()
	}

	/* Update points total per competition (for all users) */
	function totalizePoints(){
	
		return 0; //Comment to use this method
		
		$error_code = 99;
		if ($this->_login()){
			App::import('Controller', 'Bets');
			$BetsCtl = new BetsController(null,null,true);
			$BetsCtl->constructClasses();
			$error_code = $BetsCtl->calculate() ? 0 : 1;
		}
		echo "EXIT CODE: " . ($error_code == 0 ? '[OK]' : "[Error:$error_code]") . "\n";
	}

	/**
	 * USE: Delete ARO (Auth Resources Object) <-> ACO (Auth Control Objetc) 
	 *      relations. Is executed by ./acl.sh located in the APP_DIR
	 */
	function cleanAcls() {
	
		return 0; //Comment to use this method
		
		$error_code = 0;
		$db =& ConnectionManager::getInstance(); 
		$db_prefix = $db->config->default['prefix']; 
		//$error_code = $this->User->query("DELETE FROM ".$db_prefix."aros_acos") && $this->User->query("DELETE FROM ".$db_prefix."acos");
		$error_code = 77; echo "For security reasons, uncomment previous line!!!\n";
	  echo "cleanAcls:EXIT CODE: " . ($error_code == 1 ? '[OK]' : "[Error:$error_code]") . "\n";
	}

	/**
	 * USE: For more information see forecast_audit() function in Bet controller
	 */
	/*
	private function forecast_audit($shell=false){
		$error_code = 99;
		if ($this->_login()){
			App::import('Controller', 'Bets'); 
			$bets = new BetsController();
			$bets->constructClasses(); 
			$error_code = $bets->forecast_audit($shell);
		}
		if ($error_code == 0){
			return 0;
		}else{
			echo "[forecast_audit:error:$error_code]" . "\n";
		}		
	}
	*/

	/**
	 * USE: Logon on the system to allow calling controller actions in this shell
	 */
	private function _login(){
		App::import('Controller', 'Users'); 
		$Users = new UsersController();
		$Users->constructClasses(); 
		$password = Security::hash(Configure::read('Password.admin'), null, true);

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
		return $Users->login();
	}

	/**
	 * USE: List users' emails
	 */
	function listEmails(){
		if ($this->_login()){
			App::import('Controller','Users'); 
			$users = new UsersController();
			$users->constructClasses();
			$Users = $users->User->find('all',array('fields'=>'User.mail','order'=>'User.mail'));
			foreach($Users as $User){
				echo $User['User']['mail']."\n";
			}
		}
	}

	/**
   * USE: Generate a CSV file to import in limesurvey as tokens
	 */
	function genSurveyCSV(){
		if ($this->_login()){
			App::import('Controller','Users'); 
			$users = new UsersController();
			$users->constructClasses();
			$Users = $users->User->find('all',array('fields'=>'mail'));
			echo "email,firstname,lastname,emailstatus,language,invited,reminded,remindercount,completed,usesleft\n";
			foreach($Users as $User){
				echo '"' . $User['User']['mail'] . '"';
				echo ',"","","OK","es","Y","N","0","N","1"';
				echo "\n";
			}
		}
	}

	/**
	 * USE: Generate automatic email with top10, forecast and audit 
	 *      signature, must be executed between 12:00 A.M. - 12:30 A.M.
	 */
	function mailFutbolista(){
		$error_code = 99;
		if ($this->_login()){
			App::import('Controller', 'Bets'); 
			//App::import('Component', 'Email'); 
			$bets = new BetsController();
			$bets->constructClasses();
			//$bets->Email = new EmailComponent();
			//$bets->Email->initialize($bets);			
			//$error_code = $bets->forecast_audit(true); // See return codes in controller action
			//if ($error_code != 0){
			//	echo "[forecast_audit:error:$error_code]" . "\n";
			//}else{
				$error_code = $bets->futbolista(true); // See returns codes in controller action
				if ($error_code != 0){
					echo "[mailFutbolista:error:$error_code]" . "\n";
				}		
			//}
		}
		return $error_code;
	}

	/**
	 * USE: Send pending emails (/Mails/send) 
   *      must run the 5th minute every hour.
	 */
	function mailings(){
		$error_code = 99;
		if ($this->_login()){
			App::import('Controller', 'Mails'); 
			App::import('Component', 'Phpmailer');
			$mails = new MailsController();
		  $mails->constructClasses();
			$mails->Phpmailer = new PhpmailerComponent();
			$mails->Phpmailer->initialize($mails);
			$error_code = $mails->send(true); // error_code => number of failed mails
		}
		if ($error_code == 0){
			return 0;
		}else{
			echo "[mailings:error:$error_code]" . "\n";
		}		
	}

	/**
	 * USE: Download Futbolista (mailman) list of suscribers and update Users tables
	 *      field mailing_list to 1 (true) if an user's email match.
	 */
	function membersFutbolista(){

		return 0; //Comment to use this method

		$postvars='roster-email=olafrv@gmailcom&roster-pw=igyajp1498&language=es';  // POST VARIABLES TO BE SENT
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://olafrv.com/mailman/roster/futbolista_olafrv.com");

		/* Auth Options #1
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, base64_encode($userpwd); //$userpwd = 'USER:PASSWORD'
		*/

		/* Auth Options #2
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_USERPWD, $userpwd); //$userpwd = 'USER:PASSWORD'
		*/

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); 
		curl_setopt($ch, CURLOPT_HEADER,0);  // DO NOT RETURN HTTP HEADERS 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL

		$data = curl_exec($ch);

		curl_close($ch);

		$regex_pattern = "/(\w|\.|-)*\sat\s(\w|\.|-)*/";

		preg_match_all($regex_pattern,$data,$matches);

		echo "Detected " . count($matches) . "\n";

		foreach(array_shift($matches) as $match){
			$email = str_replace(' at ', '@', $match);
			if (filter_var($email, FILTER_VALIDATE_EMAIL)){
				$this->User->recursive = -1;
				$Users = $this->User->find('all', 
					array(
						'fields' => array('User.id', 'User.username','User.mailing_list')
						,'conditions' => array('User.mail =' => $email)
					)
				);
				if (count($Users)){
					foreach($Users as $Data){
						$User = $Data['User'];
						if ($User['mailing_list']){
							echo $User['username'] . '(' . $User['id']. ") suscrito.\n";	
						}else{
							echo $User['username'] . '(' . $User['id']. ') suscribiendo ';
							$this->User->read(null,$User['id']);
							$this->User->set(array('mailing_list'=>true));
							if ($this->User->save()){
								echo "[OK]\n";
							}else{
								echo "[ERROR]\n";
							}	
						}				
					}
				}else{
					echo "$email no registrado en sistema.\n";
				}
			}
		}
	}

}

