<?php
namespace App\Model\Table;

use App\Model\Entity\Match;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Matches Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Grouppings
 * @property \Cake\ORM\Association\BelongsTo $Hosts
 * @property \Cake\ORM\Association\BelongsTo $Guests
 * @property \Cake\ORM\Association\BelongsTo $Stadia
 * @property \Cake\ORM\Association\HasMany $Bets
 */
class MatchesTable extends Table
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

        $this->table('futbol_matches');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Grouppings', [
            'foreignKey' => 'groupping_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Hosts', [
				    'className' => 'PseudoTeams',
            'foreignKey' => 'host_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Guests', [
				    'className' => 'PseudoTeams',
            'foreignKey' => 'guest_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Stadia', [
            'foreignKey' => 'stadium_id'
        ]);
        $this->hasMany('Bets', [
            'foreignKey' => 'match_id'
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
            ->allowEmpty('kickoff');

        $validator
            ->add('has_penalties', 'valid', ['rule' => 'boolean'])
            ->requirePresence('has_penalties', 'create')
            ->notEmpty('has_penalties');

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
            ->add('ponderation', 'valid', ['rule' => 'numeric'])
            ->requirePresence('ponderation', 'create')
            ->notEmpty('ponderation');

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
        $rules->add($rules->existsIn(['groupping_id'], 'Grouppings'));
        $rules->add($rules->existsIn(['host_id'], 'Hosts'));
        $rules->add($rules->existsIn(['guest_id'], 'Guests'));
        $rules->add($rules->existsIn(['stadium_id'], 'Stadia'));
        return $rules;
    }
}
