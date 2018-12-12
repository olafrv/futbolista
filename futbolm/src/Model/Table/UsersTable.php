<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table
{

		public function initialize(array $config)
		{
        $this->table('futbol_mobile_users');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
		{
        return $validator
            ->requirePresence('username')
            ->notEmpty('username', 'A username is required')
            ->requirePresence('password')
            ->notEmpty('password', 'A password is required')
            ->requirePresence('role')
            ->notEmpty('role', 'A role is required')
            ->add('role', 'inList', [
                'rule' => ['inList', ['admin', 'basic']],
                'message' => 'Please enter a valid role'
            ])
            ->requirePresence('mail')
            ->notEmpty('mail', 'An email is required')
    				->add('mail', 'validFormat', [
        			'rule' => 'email',
        			'message' => 'E-mail must be valid'
    				]);
    }

}

