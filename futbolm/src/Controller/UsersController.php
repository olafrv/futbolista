<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Routing\Router; 
use Cake\Network\Http\Client;
use Cake\Log\Log; 
use Cake\Core\Configure;

class UsersController extends AppController
{

    // Make all fields mass assignable except for primary key field "id".
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];

    public function beforeFilter(Event $event)
    {
				// You should not add the "login" action to allow list 
				// Doing so would cause problems with AuthComponent functioning
        $this->Auth->allow(['register', 'logout', 'ismobile', 'forgot']);
        parent::beforeFilter($event);
    }
    
    public function ismobile()
    {
    	// WS for old non-responsive site
			$ismobile = $this->request->is('mobile');
			$this->set(compact('ismobile')); 
    }

		// --- Google reCAPTCHA --- //   
    public function _captcha($Request)
    {
				return true; // Disable Google reCAPTCHA forcing true!!!

				$http = new Client();
				$url = 'https://www.google.com/recaptcha/api/siteverify';
				$Response = $http->post($url,
					[
				 			'secret' => '6LfSFBgTAAAAAN5YX_XATi_782U5x4_MsIeyqlAD',
				 			'response' => $Request->data('g-recaptcha-response'),
				 			'remoteip' => $Request->clientIp()	
				 	]
				);
				$Object = json_decode($Response->body);
				//var_dump($Response->body);
				return $Object->success;
    }
    
    public function forgot()
    {
			if ($this->request->is('post')) 
			{
				if (Configure::read('debug') || $this->_captcha($this->request))
				{
					// --- CROSS CHANGE --- //
					$http = new Client();
					$url = 'https://' . $this->request->host() . '/futbol/users/forgot/token:a1c2e478Ghjtz89';
					$response = $http->post($url
						, [	'data' => [ 'User' => [
					 			'usermail' => $this->request->data('usermail')				 			
					 	]]]
					 	, [ 'type'=>'json', 'ssl_verify_peer' => false ]
					);
					$data = json_decode($response->body, true);
					// --- CROSS CHANGE --- //
			

					if (isset($data['Message']))
					{
						$this->Flash->success($data['Message']);
					}
					else
					{
						if (isset($data['Error']))
						{
							$this->Flash->error($data['Error']);
						}
						else
						{
							$this->Flash->error(__('Cross password reset error'));
						}
					}
				}
				else
				{
					
				}
			}
    }
    
    public function chpass()
    {
			if ($this->request->is('post')) 
			{
				// --- CROSS CHANGE --- //
				$User = $this->request->session()->read("Auth.User");
				$http = new Client();
				$url = 'https://' . $this->request->host() . '/futbol/users/changePassword/token:a1c2e478Ghjtz89';
				$response = $http->post($url
					, [	'data' => [ 'User' => [
				 			'username' => $User['username'],
				 			'password' => $this->request->data('password'),
				 			'password_confirm' => $this->request->data('password_confirm')				 			
				 	]]]
				 	, [ 'type'=>'json', 'ssl_verify_peer' => false ]
				);
				$data = json_decode($response->body, true);
				// --- CROSS CHANGE --- //
			

				if (!isset($data['User']))
				{
					if (isset($data['Error']))
					{
						$this->Flash->error($data['Error']);
					}
					else
					{
						$this->Flash->error(__('Cross password change error'));
					}
				}
				else
				{
	
					// --- NORMAL CHANGE --- //
					$User = $this->request->session()->read("Auth.User");		
					$User = $this->Users->findByUsername($User['username'])->first();
					if (!empty($User))
					{
						$User->password = $this->request->data('password');
						if ($this->Users->save($User))
						{
								$this->Flash->success(__('Password updated'));
						}
						else
						{
							$this->Flash->error(__('Unable to save the password'));	
						}
					}
					// --- NORMAL CHANGE --- //
				
				}
			}
    }

    public function register()
		{
			if ($this->request->is('post')) 
			{
				if (Configure::read('debug') || $this->_captcha($this->request))
				{
					// --- CROSS REGISTER --- //
					$http = new Client();
					$url = 'https://' . $this->request->host() . '/futbol/users/register/token:a1c2e478Ghjtz89';
					$response = $http->post($url
						, [	'data' => [ 'User' => [
					 			'username' => $this->request->data('username'),
					 			'password' => $this->request->data('password'),
					 			'password_confirm' => $this->request->data('password_confirm'),
					 			'mail' => $this->request->data('mail'),
					 			'mail_confirm' => $this->request->data('mail_confirm')					 			
					 	]]]
					 	, [ 'type'=>'json', 'ssl_verify_peer' => false ]
					);
					$data = json_decode($response->body, true);
					// --- CROSS REGISTER --- //
			
					
	
					if (!isset($data['User']))
					{
						if (isset($data['Error']))
						{
							$this->Flash->error($data['Error']);
						}
						else
						{
							$this->Flash->error(__('Cross register unkwown error'));
						}
					}
					else
					{
						// --- NORMAL REGISTER --- //
						$User = $this->Users->findById($data['User']['id'])->first();
						if (empty($User))
						{
							$User = $this->Users->newEntity();
							$User = $this->Users->patchEntity($User, $data['User']);
							if ($data['User']['username'] == 'admin' || $data['User']['group_id']==1)
							{
								$User->role = 'admin';
							}else{
								$User->role = 'basic';
							}
							$User->password = $this->request->data('password');
							if ($this->Users->save($User))
							{
							    $this->Flash->success(__('The user has been saved/created, now you can login'));
							    return $this->redirect(['action' => 'login']);
							}
							else
							{
								$this->Flash->error(__('Unable to save the user'));	
							}
						}
						else
						{
							$this->Flash->error(__('User already exists'));	
						}
						// --- NORMAL REGISTER --- //
					}
    		}
    	}
		}
		
		public function login()
		{
			if ($this->request->is('post')) {
											
				if (Configure::read('debug') || $this->_captcha($this->request)){
			
					// --- CROSS LOGIN --- //
					$http = new Client();
					$url = 'https://' . $this->request->host() . '/futbol/users/crosslogin/token:a1c2e478Ghjtz89';
					$response = $http->post($url
						, [	'data' => [ 'User' => [
					 			'username' => $this->request->data('username'),
					 			'password' => $this->request->data('password'),
					 			'captcha' => $this->request->data('captcha')
					 	]]]
					 	, [ 'type'=>'json', 'ssl_verify_peer' => false ]
					);
					$data = json_decode($response->body, true);
					// --- CROSS LOGIN --- //

					if (!isset($data['User']))
					{
						if (isset($data['Error']))
						{
							$this->Flash->error($data['Error']);
						}
						else
						{
							$this->Flash->error(__('Cross login unkwown error'));
						}
					}
					else
					{
						// --- CROSS MIGRATION ---//
						$User = $this->Users->findById($data['User']['id'])->first();
						if (empty($User))
						{
							$User = $this->Users->newEntity();
							$User = $this->Users->patchEntity($User, $data['User']);
							if ($data['User']['username'] == 'admin' || $data['User']['group_id']==1)
							{
								$User->role = 'admin';
							}else{
								$User->role = 'basic';
							}
						}
						$User->password = $this->request->data('password');
						if ($this->Users->save($User))
						{
							$this->log(__("Cross login user '{0}' created/updated", $data['User']['username']));
						}
						// --- CROSS MIGRATION ---//
					
						// --- NORMAL LOGIN --- //
						$user = $this->Auth->identify();
					  if ($user)
					  {
					      $this->Auth->setUser($user);
								$this->request->session()->write("Auth.User", $user);
					      return $this->redirect($this->Auth->redirectUrl());
					  }							
		        $this->Flash->error(__('Username or password invalid'));	
		        // --- NORMAL LOGIN --- //
					}
				}
				else
				{
					$this->Flash->error(__('Invalid captcha'));	
				}				
			}
		}

		public function logout()
		{
 	  	return $this->redirect($this->Auth->logout());
		}
		
}
