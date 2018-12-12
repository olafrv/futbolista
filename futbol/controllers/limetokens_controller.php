<?php

class LimetokensController extends AppController {

	var $uses = array('Limetoken');

   function getToken(){ //Users
		/*
      if (!empty($this->params['requested'])){
			$email = $this->params['name']['named']
		}
		*/
      //if (is_null($email)){
         $User = $this->Session->read('Auth.User');
         $email = $User['mail'];
      //}
      $token_array = $this->Limetoken->find('all',
         array(
            'conditions'=>array(
               'Limetoken.email'=>$email,
               'UCASE(Limetoken.emailstatus)'=>'OK',
               'Limetoken.completed'=>'N',
               'Limetoken.token IS NOT NULL',
					'Limetoken.token <> ""'
            )
         )
      );
      $token = count($token_array)==1 ? $token_array[0]['Limetoken']['token'] : NULL;
      if (!empty($this->params['requested'])){
			return $token;
		}else{
			$this->set('token',$token);
		}
   }
}
