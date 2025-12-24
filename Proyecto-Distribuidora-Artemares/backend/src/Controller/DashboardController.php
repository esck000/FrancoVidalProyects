<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\I18n\FrozenTime;
use Cake\I18n\FrozenDate;

/**
 * Dashboard Controller
 *
 */

class DashboardController extends AppController
{
    public function index()
    {
        $productsTable = $this->getTableLocator()->get('Products');
        $categoriesTable = $this->getTableLocator()->get('Categories');
        $ordersTable = $this->getTableLocator()->get('Orders');
        $ordersProductsTable = $this->getTableLocator()->get('OrdersProducts');

        // 1. total de productos
        $totalProducts = $productsTable->find()->count();

        // 2. total de categorías
        $totalCategories = $categoriesTable->find()->count();


        // 3. top 5 productos mas vendidos
        $productsMostSold = $ordersProductsTable->find()
            ->select([
                'product_id',
                'total_quantity' => 'SUM(OrdersProducts.quantity)',
                'Products__name' => 'Products.name' 
            ])
            ->matching('Orders', function ($q) {
                return $q->where(['Orders.status' => 'closed']);
            })
            ->matching('Products')
            ->group(['OrdersProducts.product_id', 'Products.name'])
            ->order(['total_quantity' => 'DESC'])
            ->limit(4)
            ->all();

        //$startDate = FrozenDate::today()->subDays(90);
        //$productsMostSold = $ordersProductsTable->find()
          //  ->select([
            //    'product_id',
              //  'total_quantity' => 'SUM(OrdersProducts.quantity)',
                //'Products__name' => 'Products.name'
            //])
            //->matching('Orders', function ($q) use ($startDate) {
              //  return $q->where([
                //    'Orders.status' => 'closed',
                  //  'Orders.created >=' => $startDate
                //]);
            //})
            //->matching('Products')
            //->group(['OrdersProducts.product_id', 'Products.name'])
            //->order(['total_quantity' => 'DESC'])
            //->limit(4)
            //->all();
        //pedidos marcados como closed del día actual
        $todayStart = FrozenTime::today()->startOfDay();
        $todayEnd = FrozenTime::today()->endOfDay();
        $closedOrdersToday = $ordersTable->find()
            ->where([
                'Orders.status' => 'closed',
                'Orders.modified >=' => $todayStart,
                'Orders.modified <=' => $todayEnd,
            ])
            ->count();
        // 4. productos por categoria
        $productsByCategory = $categoriesTable->find()
            ->select([
                'id',
                'name',
                'total_products' => $categoriesTable->Products->find()->func()->count('Products.id')
            ])
            ->leftJoinWith('Products')
            ->group(['Categories.id'])
            ->all();

        // 5. pedidos en proceso
        $pendingOrders = $ordersTable->find()
            ->where(['status' => 'in_process'])
            ->order(['created' => 'DESC'])
            ->all();

        // 6. 5 pedidos mas recientes
        $recentOrders = $ordersTable->find()
            ->order(['created' => 'DESC'])
            ->limit(5);

        // 8. accesos rápidos
        $quickAccess = [
            ['label' => 'Añadir Producto', 'url' => ['controller' => 'Products', 'action' => 'add']],
            ['label' => 'Añadir Pedido', 'url' => ['controller' => 'Orders', 'action' => 'add']],
            ['label' => 'Añadir cuadratura', 'url' => ['controller' => 'CashBalances', 'action' => 'add']],
            ['label' => 'Añadir receta', 'url' => ['controller' => 'Recipes', 'action' => 'add']],
            ['label' => 'Añadir categoría', 'url' => ['controller' => 'Categories', 'action' => 'add']]
            //['label' => 'Ver Categorías', 'url' => ['controller' => 'Categories', 'action' => 'index']],
        ];

        $sevenDays = (new FrozenTime())->subDays(7);
        // 9. Evolución de ventas por fecha
        $salesEvolution = $ordersProductsTable->find()
            ->select([
                'date' => 'DATE(OrdersProducts.created)',
                'total_quantity' => 'SUM(OrdersProducts.quantity)'
            ])
            ->matching('Orders', function ($q) use ($sevenDays) {
                return $q->where(['Orders.status' => 'closed', 'Orders.created >=' => $sevenDays]);
            })
            ->group('date')
            ->order(['date' => 'ASC'])
            ->all();

        // 10. ventas totales por producto últimos 7 días
        $sevenDaysAgo = (new FrozenTime())->subDays(7);

        $salesLast7DaysByProduct = $ordersProductsTable->find()
            ->select([
                'product_id',
                'total_quantity' => 'SUM(OrdersProducts.quantity)',
                'Products__name' => 'Products.name'
            ])
            ->matching('Orders', function ($q) use ($sevenDaysAgo) {
                return $q->where([
                    'Orders.status' => 'closed',
                    'Orders.created >=' => $sevenDaysAgo
                ]);
            })
            ->matching('Products')
            ->group(['OrdersProducts.product_id', 'Products.name'])
            ->order(['total_quantity' => 'DESC'])
            ->limit(5)
            ->all();
        
        //11. Ventas totales por producto últimos 30 días (solo pedidos cerrados, top 5)
        $thirtyDaysAgo = (new FrozenTime())->subDays(30);

        $salesLast30DaysTop5 = $ordersProductsTable->find()
            ->select([
                'product_id',
                'total_quantity' => 'SUM(OrdersProducts.quantity)',
                'Products__name' => 'Products.name'
            ])
            ->matching('Orders', function ($q) use ($thirtyDaysAgo) {
                return $q->where([
                    'Orders.status' => 'closed',
                    'Orders.created >=' => $thirtyDaysAgo
                ]);
            })
            ->matching('Products')
            ->group(['OrdersProducts.product_id', 'Products.name'])
            ->order(['total_quantity' => 'DESC'])
            ->limit(5)
            ->all();

        // 12. ventas totales por categoría últimos 7 días
        $salesLast7DaysByCategory = $ordersProductsTable->find()
            ->select([
                'category_id' => 'Products.category_id',
                'total_quantity' => 'SUM(OrdersProducts.quantity)',
                'Categories__name' => 'Categories.name'
            ])
            ->matching('Orders', function ($q) use ($sevenDaysAgo) {
                return $q->where([
                    'Orders.status' => 'closed',
                    'Orders.created >=' => $sevenDaysAgo
                ]);
            })
            ->matching('Products.Categories') 
            ->group(['Products.category_id', 'Categories.name'])
            ->order(['total_quantity' => 'DESC'])
            ->limit(5)
            ->all();
        #12. ventas totales por categoría últimos 30 días
        $salesLast30DaysByCategory = $ordersProductsTable->find()
        ->select([
            'category_id' => 'Products.category_id',
            'total_quantity' => 'SUM(OrdersProducts.quantity)',
            'Categories__name' => 'Categories.name'
        ])
        ->matching('Orders', function ($q) use ($thirtyDaysAgo) {
            return $q->where([
                'Orders.status' => 'closed',
                'Orders.created >=' => $thirtyDaysAgo
            ]);
        })
        ->matching('Products.Categories')
        ->group(['Products.category_id', 'Categories.name'])
        ->order(['total_quantity' => 'DESC'])
        ->limit(5)
        ->all();

        $this->set(compact(
            'totalProducts',
            'totalCategories',
            'productsMostSold',
            'productsByCategory',
            'pendingOrders',
            'recentOrders',
            'quickAccess',
            'salesEvolution',
            'salesLast7DaysByProduct',
            'salesLast30DaysTop5',
            'salesLast7DaysByCategory',
            'salesLast30DaysByCategory',
            'closedOrdersToday'
        ));
    }


}