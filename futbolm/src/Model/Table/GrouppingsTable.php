<?php
namespace App\Model\Table;

use App\Model\Entity\Groupping;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Grouppings Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Fases
 * @property \Cake\ORM\Association\HasMany $Matches
 * @property \Cake\ORM\Association\HasMany $PseudoTeams
 */
class GrouppingsTable extends Table
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

        $this->table('futbol_grouppings');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->belongsTo('Fases', [
            'foreignKey' => 'fase_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Matches', [
            'foreignKey' => 'groupping_id'
        ]);
        $this->hasMany('PseudoTeams', [
            'foreignKey' => 'groupping_id'
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
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->add('is_elimination', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_elimination', 'create')
            ->notEmpty('is_elimination');

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
        $rules->add($rules->existsIn(['fase_id'], 'Fases'));
        return $rules;
    }
}
