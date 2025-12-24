<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductImages Model
 *
 * @property \App\Model\Table\ProductsTable&\Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\ProductImage newEmptyEntity()
 * @method \App\Model\Entity\ProductImage newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\ProductImage> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductImage get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\ProductImage findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\ProductImage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\ProductImage> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductImage|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\ProductImage saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\ProductImage>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductImage>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProductImage>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductImage> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProductImage>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductImage>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProductImage>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductImage> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProductImagesTable extends Table
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

        $this->setTable('product_images');
        $this->setDisplayField('mime_type_small');
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
            ->requirePresence('image_small', 'create')
            ->notEmptyString('image_small');

        $validator
            ->requirePresence('image_medium', 'create')
            ->notEmptyString('image_medium');

        $validator
            ->requirePresence('image_large', 'create')
            ->notEmptyString('image_large');

        $validator
            ->scalar('mime_type_small')
            ->maxLength('mime_type_small', 100)
            ->requirePresence('mime_type_small', 'create')
            ->notEmptyString('mime_type_small');

        $validator
            ->scalar('mime_type_medium')
            ->maxLength('mime_type_medium', 100)
            ->requirePresence('mime_type_medium', 'create')
            ->notEmptyString('mime_type_medium');

        $validator
            ->scalar('mime_type_large')
            ->maxLength('mime_type_large', 100)
            ->requirePresence('mime_type_large', 'create')
            ->notEmptyString('mime_type_large');

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
