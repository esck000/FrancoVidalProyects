<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NutritionalInformations Model
 *
 * @property \App\Model\Table\ProductsTable&\Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\NutritionalInformation newEmptyEntity()
 * @method \App\Model\Entity\NutritionalInformation newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\NutritionalInformation> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NutritionalInformation get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\NutritionalInformation findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\NutritionalInformation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\NutritionalInformation> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\NutritionalInformation|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\NutritionalInformation saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\NutritionalInformation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NutritionalInformation>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NutritionalInformation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NutritionalInformation> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NutritionalInformation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NutritionalInformation>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NutritionalInformation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NutritionalInformation> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class NutritionalInformationsTable extends Table
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

        $this->setTable('nutritional_informations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER',
        ]);
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
            ->integer('product_id')
            ->notEmptyString('product_id')
            ->add('product_id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('measurement')
            ->maxLength('measurement', 100)
            ->allowEmptyString('measurement');

        $validator
            ->decimal('calories')
            ->allowEmptyString('calories');

        $validator
            ->decimal('protein')
            ->allowEmptyString('protein');

        $validator
            ->decimal('total_fat')
            ->allowEmptyString('total_fat');

        $validator
            ->decimal('carbohydrates')
            ->allowEmptyString('carbohydrates');

        $validator
            ->decimal('sodium')
            ->allowEmptyString('sodium');

        $validator
            ->decimal('cholesterol')
            ->allowEmptyString('cholesterol');

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
        $rules->add($rules->isUnique(['product_id']), ['errorField' => 'product_id']);
        $rules->add($rules->existsIn(['product_id'], 'Products'), ['errorField' => 'product_id']);

        return $rules;
    }

}
