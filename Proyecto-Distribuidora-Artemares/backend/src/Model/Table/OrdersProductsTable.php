<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\I18n\FrozenDate;
use DateTimeInterface;

/**
 * OrdersProducts Model
 *
 * @property \App\Model\Table\OrdersTable&\Cake\ORM\Association\BelongsTo $Orders
 * @property \App\Model\Table\ProductsTable&\Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\OrdersProduct newEmptyEntity()
 * @method \App\Model\Entity\OrdersProduct newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\OrdersProduct> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrdersProduct get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\OrdersProduct findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\OrdersProduct patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\OrdersProduct> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrdersProduct|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\OrdersProduct saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\OrdersProduct>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\OrdersProduct>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\OrdersProduct>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\OrdersProduct> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\OrdersProduct>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\OrdersProduct>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\OrdersProduct>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\OrdersProduct> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrdersProductsTable extends Table
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

        $this->setTable('orders_products');
        $this->setDisplayField(['order_id', 'product_id']);
        $this->setPrimaryKey(['order_id', 'product_id']);

        $this->addBehavior('Timestamp');
        
        $this->belongsTo('Orders', [
            'foreignKey' => 'order_id',
            'joinType' => 'INNER',
        ]);
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
            ->integer('quantity')
            ->requirePresence('quantity', 'create')
            ->notEmptyString('quantity');

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
        $rules->add($rules->existsIn(['order_id'], 'Orders'), ['errorField' => 'order_id']);
        $rules->add($rules->existsIn(['product_id'], 'Products'), ['errorField' => 'product_id']);

        return $rules;
    }

    //obtener ventas por producto
    public function getSalesByProduct(DateTimeInterface $from): array
    {
        return $this->find()
            ->select([
                'product_name' => 'Products.name',
                'total_quantity' => $this->find()->func()->sum('OrdersProducts.quantity'),
                'total_amount' => $this->find()->func()->sum(
                    'OrdersProducts.quantity * Products.price'
                )
            ])
            ->innerJoinWith('Orders', function ($q) use ($from) {
                return $q->where([
                    'Orders.status' => 'closed',
                    'Orders.modified >=' => $from
                ]);
            })
            ->innerJoinWith('Products')
            ->group(['Products.id', 'Products.name'])
            ->orderAsc('Products.name')
            ->enableHydration(false)
            ->toArray();
    }
}
