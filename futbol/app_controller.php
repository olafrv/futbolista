<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.app
 */
class AppController extends Controller {

/*   
	//Maybe a custom message implementation?, must dig for it!
	function invalidate($field, $value = true) {
		return parent::invalidate($field, __($value, true));
  }
	//This will look for $value in the validation_errors.po file.
  function invalidate($field, $value = true) {
  	return parent::invalidate($field, __d('validation_errors', $value, true));
  }
*/

	//var $components = array('Auth', 'Session'); //Defaults but we use more
	var $components = array('Acl', 'Auth', 'Session', 'Geoip');
	var $helpers = array('Html', 'Form', 'Session', 'Time', 'Js' => array('Jquery'));
	
	function beforeFilter() {

		// Used to show IP and flaq in footer
		if (isset($_SERVER["REMOTE_ADDR"])){
			Configure::write("Futbol.userIpCountry", $this->Geoip->country_code_by_addr($_SERVER["REMOTE_ADDR"]));
		}

		/*
		//MONACA Microsoft Proxy Problem (Crosslogon)
		if (isset($_SERVER["REMOTE_ADDR"])){
			if ($_SERVER["REMOTE_ADDR"]=="200.11.139.146"  
				 || $_SERVER["REMOTE_ADDR"]=="200.11.139.147"
			){
				$this->redirect('/monaca.html');
			}
		}
		// Destroy session for the visitors (Insecure solution to Crosslogon!) 
		//if ($this->action=="display") $this->Auth->logout(); //Leaved as an example of logout!
		//MONACA Microsoft Proxy Problem (Crosslogon)
		*/
	
		//Show maintenance page
		if (Configure::read('SystemPref.site_status') == 'Offline'
        && $this->here != Configure::read('SystemPref.site_offline_url')) {
        $this->redirect(Configure::read('SystemPref.site_offline_url'));
    }

		//AuthComponent configuration
		$this->Auth->authorize = 'actions';
		$this->Auth->actionPath = 'controllers/'; //After running: cake acl create aco root controllers
    $this->Auth->autoRedirect = 'false';
		$this->Auth->loginError = __('Error al iniciar sesión, intente nuevamente.',true);
    $this->Auth->authError = __('Acceso denegado, intente iniciar sesión.', true);
		$this->Auth->allowedActions = array('display'); // Allow to view webroot and display errors
		//$this->Auth->allowedActions = array("*"); //Allow to view everything, be careful!

/*
		//Disable because after redirect User controller flash messages are lost
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
	  $this->Auth->loginRedirect = array('controller' => 'bets', 'action' => 'mine');
		$this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'logout');
*/		

	}

}
