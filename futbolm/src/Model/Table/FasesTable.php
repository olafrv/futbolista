<?php
namespace App\Model\Table;

use App\Model\Entity\Fase;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Fases Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Competitions
 * @property \Cake\ORM\Association\HasMany $Grouppings
 */
class FasesTable extends Table
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

        $this->table('futbol_fases');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->belongsTo('Competitions', [
            'foreignKey' => 'competition_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Grouppings', [
            'foreignKey' => 'fase_id'
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
            ->add('begins', 'valid', ['rule' => 'date'])
            ->requirePresence('begins', 'create')
            ->notEmpty('begins');

        $validator
            ->add('ends', 'valid', ['rule' => 'date'])
            ->requirePresence('ends', 'create')
            ->notEmpty('ends');

        $validator
            ->add('is_elimination', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('is_elimination');

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
        $rules->add($rules->existsIn(['competition_id'], 'Competitions'));
        return $rules;
    }
}
