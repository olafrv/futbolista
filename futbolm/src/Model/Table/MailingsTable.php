<?php
namespace App\Model\Table;

use App\Model\Entity\Mailing;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Mailings Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Mails
 * @property \Cake\ORM\Association\BelongsTo $Users
 */
class MailingsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('futbol_mailings');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Mails', [
            'foreignKey' => 'mail_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->add('sent', 'valid', ['rule' => 'numeric'])
            ->requirePresence('sent', 'create')
            ->notEmpty('sent');

        $validator
            ->add('read', 'valid', ['rule' => 'numeric'])
            ->requirePresence('read', 'create')
            ->notEmpty('read');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['mail_id'], 'Mails'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }
}
