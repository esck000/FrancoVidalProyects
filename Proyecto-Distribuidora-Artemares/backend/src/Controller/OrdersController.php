<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Orders Controller
 *
 * @property \App\Model\Table\OrdersTable $Orders
 */
class OrdersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Query base sin order forzado para permitir sort dinámico
        $query = $this->Orders->find();

        // --- Búsqueda por ID de pedido ---
        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            // filtrar por ID exacto
            $query->where(['Orders.id' => $search]);
        }

        // --- Filtro por estado ---
        $statusFilter = $this->request->getQuery('status');
        if (!empty($statusFilter)) {
            $query->where(['Orders.status' => $statusFilter]);
        }

        // --- Configurar paginación con orden dinámico ---
        $this->paginate = [
            'limit' => 20,
            'order' => ['id' => 'desc'],   // Orden por defecto
            /*'sortableFields' => ['id', 'status', 'created', 'modified']*/
        ];

        // Ejecutar paginación
        $orders = $this->paginate($query);

        // Opciones disponibles de estado
        $statuses = [
            'in_process' => 'En proceso',
            'closed' => 'Cerrado',
            'cancelled' => 'Cancelado'
        ];

        $this->set(compact('orders', 'search', 'statusFilter', 'statuses'));
    }

    /**
     * View method
     *
     * @param string|null $id Order id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $order = $this->Orders->get($id, contain: ['Products']);
        $this->set(compact('order'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    

    public function add()
    {
        $order = $this->Orders->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // --- Filtrar solo productos seleccionados ---
            $selectedProducts = [];
            if (!empty($data['products'])) {
                foreach ($data['products'] as $prod) {
                    if (!empty($prod['id'])) { // checkbox marcado
                        $selectedProducts[] = [
                            'id' => $prod['id'],
                            '_joinData' => [
                                'quantity' => $prod['_joinData']['quantity'] ?? 1
                            ]
                        ];
                    }
                }
            }

            // Reemplazamos los productos en el request con solo los seleccionados
            $data['products'] = $selectedProducts;

            // --- Creamos el pedido ---
            $order = $this->Orders->patchEntity($order, $data, [
                'associated' => ['Products._joinData']
            ]);

            if ($this->Orders->save($order)) {
                $this->Flash->success(__('Pedido creado correctamente.'));
                return $this->redirect(['action' => 'index']);
            }

            debug($order->getErrors()); // para ver si hay validación fallando
            $this->Flash->error(__('No se pudo crear el pedido. Por favor, intenta nuevamente.'));
        }

        // --- Datos para la vista ---
        #$products = $this->Orders->Products->find('all')->toArray();
        $products = $this->Orders->Products->find()
            ->order(['Products.name' => 'ASC'])
            ->all()
            ->toArray();
        $statuses = [
            'in_process' => 'En proceso',
            'closed' => 'Cerrado',
            'cancelled' => 'Cancelado'
        ];

        $this->set(compact('order', 'products', 'statuses'));
    }




    /**
     * Edit method
     *
     * @param string|null $id Order id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    
    public function edit($id = null)
    {
        $order = $this->Orders->get($id, [
            'contain' => ['Products']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            // Filtrar solo productos seleccionados y preparar _joinData
            $selectedProducts = [];
            if (!empty($data['products'])) {
                foreach ($data['products'] as $prod) {
                    if (!empty($prod['id'])) { // checkbox marcado
                        $selectedProducts[] = [
                            'id' => $prod['id'],
                            '_joinData' => [
                                'quantity' => $prod['_joinData']['quantity'] ?? 1
                            ]
                        ];
                    }
                }
            }

            $data['products'] = $selectedProducts;

            $order = $this->Orders->patchEntity($order, $data, [
                'associated' => ['Products._joinData']
            ]);

            if ($this->Orders->save($order)) {
                $this->Flash->success(__('Pedido actualizado correctamente.'));
                return $this->redirect(['action' => 'index']);
            }

            debug($order->getErrors());
            $this->Flash->error(__('No se pudo actualizar el pedido. Por favor, intenta nuevamente.'));
        }

        #$products = $this->Orders->Products->find('all')->toArray();
        $products = $this->Orders->Products->find()
            ->order(['Products.name' => 'ASC'])
            ->all()
            ->toArray();
        $statuses = [
            'in_process' => 'En proceso',
            'closed' => 'Cerrado',
            'cancelled' => 'Cancelado'
        ];

        $this->set(compact('order', 'products', 'statuses'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Order id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $order = $this->Orders->get($id);
        if ($this->Orders->delete($order)) {
            $this->Flash->success(__('The order has been deleted.'));
        } else {
            $this->Flash->error(__('The order could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}