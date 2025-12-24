<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CashBalances Model
 *
 * @method \App\Model\Entity\CashBalance newEmptyEntity()
 * @method \App\Model\Entity\CashBalance newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\CashBalance> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CashBalance get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\CashBalance findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\CashBalance patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\CashBalance> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\CashBalance|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\CashBalance saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\CashBalance>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\CashBalance>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\CashBalance>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\CashBalance> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\CashBalance>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\CashBalance>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\CashBalance>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\CashBalance> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CashBalancesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('cash_balances');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->decimal('expected_amount')
            ->allowEmptyString('expected_amount');

        $validator
            ->decimal('actual_amount')
            ->requirePresence('actual_amount', 'create')
            ->notEmptyString('actual_amount');

        $validator
            ->decimal('difference')
            ->requirePresence('difference', 'create')
            ->notEmptyString('difference');
        
        $validator
            ->scalar('status')
            ->requirePresence('status', 'create')
            ->notEmptyString('status')
            ->inList('status', ['OK', 'MISMATCH']);

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->date('balance_date')
            ->requirePresence('balance_date', 'create')
            ->notEmptyDate('balance_date');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['balance_date']), ['errorField' => 'balance_date', 
            'message' => 'Ya existe una cuadratura de caja para esta fecha.']);

        return $rules;
    }
}
