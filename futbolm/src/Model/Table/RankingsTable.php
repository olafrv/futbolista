<?php
namespace App\Model\Table;

use App\Model\Entity\Ranking;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Rankings Model
 *
 * @property \Cake\ORM\Association\BelongsTo $PseudoTeams
 */
class RankingsTable extends Table
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

        $this->table('futbol_rankings');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('PseudoTeams', [
            'foreignKey' => 'pseudo_team_id',
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
            ->add('played', 'valid', ['rule' => 'numeric'])
            ->requirePresence('played', 'create')
            ->notEmpty('played');

        $validator
            ->add('home_played', 'valid', ['rule' => 'numeric'])
            ->requirePresence('home_played', 'create')
            ->notEmpty('home_played');

        $validator
            ->add('home_win', 'valid', ['rule' => 'numeric'])
            ->requirePresence('home_win', 'create')
            ->notEmpty('home_win');

        $validator
            ->add('home_lost', 'valid', ['rule' => 'numeric'])
            ->requirePresence('home_lost', 'create')
            ->notEmpty('home_lost');

        $validator
            ->add('home_drawn', 'valid', ['rule' => 'numeric'])
            ->requirePresence('home_drawn', 'create')
            ->notEmpty('home_drawn');

        $validator
            ->add('home_favor_goals', 'valid', ['rule' => 'numeric'])
            ->requirePresence('home_favor_goals', 'create')
            ->notEmpty('home_favor_goals');

        $validator
            ->add('home_against_goals', 'valid', ['rule' => 'numeric'])
            ->requirePresence('home_against_goals', 'create')
            ->notEmpty('home_against_goals');

        $validator
            ->add('home_diff_goals', 'valid', ['rule' => 'numeric'])
            ->requirePresence('home_diff_goals', 'create')
            ->notEmpty('home_diff_goals');

        $validator
            ->add('away_played', 'valid', ['rule' => 'numeric'])
            ->requirePresence('away_played', 'create')
            ->notEmpty('away_played');

        $validator
            ->add('away_win', 'valid', ['rule' => 'numeric'])
            ->requirePresence('away_win', 'create')
            ->notEmpty('away_win');

        $validator
            ->add('away_lost', 'valid', ['rule' => 'numeric'])
            ->requirePresence('away_lost', 'create')
            ->notEmpty('away_lost');

        $validator
            ->add('away_drawn', 'valid', ['rule' => 'numeric'])
            ->requirePresence('away_drawn', 'create')
            ->notEmpty('away_drawn');

        $validator
            ->add('away_favor_goals', 'valid', ['rule' => 'numeric'])
            ->requirePresence('away_favor_goals', 'create')
            ->notEmpty('away_favor_goals');

        $validator
            ->add('away_against_goals', 'valid', ['rule' => 'numeric'])
            ->requirePresence('away_against_goals', 'create')
            ->notEmpty('away_against_goals');

        $validator
            ->add('away_diff_goals', 'valid', ['rule' => 'numeric'])
            ->requirePresence('away_diff_goals', 'create')
            ->notEmpty('away_diff_goals');

        $validator
            ->add('diff_goals', 'valid', ['rule' => 'numeric'])
            ->requirePresence('diff_goals', 'create')
            ->notEmpty('diff_goals');

        $validator
            ->add('points', 'valid', ['rule' => 'numeric'])
            ->requirePresence('points', 'create')
            ->notEmpty('points');

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
        $rules->add($rules->existsIn(['pseudo_team_id'], 'PseudoTeams'));
        return $rules;
    }
}
