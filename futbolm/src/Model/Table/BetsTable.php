<?php
namespace App\Model\Table;

use App\Model\Entity\Bet;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Bets Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Matches
 */
class BetsTable extends Table
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

        $this->table('futbol_bets');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Matches', [
            'foreignKey' => 'match_id',
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
            ->add('host_goals', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('host_goals');

        $validator
            ->add('guest_goals', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('guest_goals');

        $validator
            ->add('host_penalties', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('host_penalties');

        $validator
            ->add('guest_penalties', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('guest_penalties');

        $validator
            ->add('points', 'valid', ['rule' => 'decimal'])
            ->allowEmpty('points');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['match_id'], 'Matches'));
        return $rules;
    }
}
