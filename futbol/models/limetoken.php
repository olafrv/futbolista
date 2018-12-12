<?php

/*
+---------------+-------------+------+-----+---------+----------------+
| Field         | Type        | Null | Key | Default | Extra          |
+---------------+-------------+------+-----+---------+----------------+
| tid           | int(11)     | NO   | PRI | NULL    | auto_increment | 
| firstname     | varchar(40) | YES  |     | NULL    |                | 
| lastname      | varchar(40) | YES  |     | NULL    |                | 
| email         | text        | YES  | MUL | NULL    |                | 
| emailstatus   | text        | YES  |     | NULL    |                | 
| token         | varchar(36) | YES  | MUL | NULL    |                | 
| language      | varchar(25) | YES  |     | NULL    |                | 
| sent          | varchar(17) | YES  |     | N       |                | 
| remindersent  | varchar(17) | YES  |     | N       |                | 
| remindercount | int(11)     | YES  |     | 0       |                | 
| completed     | varchar(17) | YES  |     | N       |                | 
| usesleft      | int(11)     | YES  |     | 1       |                | 
| validfrom     | datetime    | YES  |     | NULL    |                | 
| validuntil    | datetime    | YES  |     | NULL    |                | 
| mpid          | int(11)     | YES  |     | NULL    |                | 
+---------------+-------------+------+-----+---------+----------------+
*/

class Limetoken extends AppModel {
	var $name = 'Limetoken';
	var $displayField = 'token';   
	var $primaryKey = 'tid';
	var $useDbConfig = 'limesurvey';


	function __construct($id = false, $table = null, $ds = null) {	
		$this->useTable = 'tokens_' . Configure::read('Futbol.lastSurvey');
		parent::__construct($id, $table, $ds);
	}

/*
   function getToken($email){ //Users
      $token_array = $this->find('all',
         array(
            'conditions'=>array(
               'Limetoken.email'=>$email,
               'UCASE(Limetoken.emailstatus)'=>'OK',
               'Limetoken.completed'=>'N',
               'Limetoken.token IS NOT NULL'
            )
         )
      );

      return count($token_array)==1 ? $token_array[0]['Limetoken']['token'] : NULL;
   }
*/
}
