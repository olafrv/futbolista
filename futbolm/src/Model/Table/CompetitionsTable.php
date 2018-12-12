<?php
namespace App\Model\Table;

use App\Model\Entity\Competition;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Competitions Model
 *
 * @property \Cake\ORM\Association\HasMany $Fases
 * @property \Cake\ORM\Association\HasMany $Payments
 * @property \Cake\ORM\Association\HasMany $Photos
 * @property \Cake\ORM\Association\HasMany $Points
 * @property \Cake\ORM\Association\HasMany $Urls
 */
class CompetitionsTable extends Table
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

        $this->table('futbol_competitions');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->hasMany('Fases', [
            'foreignKey' => 'competition_id'
        ]);
        $this->hasMany('Payments', [
            'foreignKey' => 'competition_id'
        ]);
        $this->hasMany('Photos', [
            'foreignKey' => 'competition_id'
        ]);
        $this->hasMany('Points', [
            'foreignKey' => 'competition_id'
        ]);
        $this->hasMany('Urls', [
            'foreignKey' => 'competition_id'
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
            ->allowEmpty('rss');

        $validator
            ->requirePresence('resume', 'create')
            ->notEmpty('resume');

        $validator
            ->add('cost', 'valid', ['rule' => 'decimal'])
            ->requirePresence('cost', 'create')
            ->notEmpty('cost');

        $validator
            ->requirePresence('sport', 'create')
            ->notEmpty('sport');

        return $validator;
    }
}
