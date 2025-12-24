<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\I18n\FrozenDate;

/**
 * CashBalances Controller
 *
 * @property \App\Model\Table\CashBalancesTable $CashBalances
 */
class CashBalancesController extends AppController
{
    public function add()
    {
        $cashBalance = $this->CashBalances->newEmptyEntity();
        $calculatedExpected = 0;

        if ($this->request->is(['post', 'put'])) {

            $data = $this->request->getData();
            $balanceDate = new FrozenDate($data['balance_date']);
            $today = FrozenDate::today();

            // validación fecha
            if ($balanceDate > $today) {
                $this->Flash->error(
                    'La fecha de la cuadratura no puede ser mayor a hoy.'
                );
                return;
            }

            // buscar pedidos cerrados de ESA fecha
            $ordersTable = $this->fetchTable('Orders');
            $orders = $ordersTable
                ->find('closedByDate', ['date' => $balanceDate])
                ->all();

            // calcular expected automático
            foreach ($orders as $order) {
                foreach ($order->products as $product) {
                    $calculatedExpected +=
                        $product->price * $product->_joinData->quantity;
                }
            }

            // Patch entity (incluye expected_amount si viene del form)
            $cashBalance = $this->CashBalances->patchEntity($cashBalance, $data);

            // expected_amount: manual > automático
            if (
                isset($data['expected_amount']) &&
                $data['expected_amount'] !== '' &&
                $data['expected_amount'] !== null
            ) {
                $cashBalance->expected_amount = (float)$data['expected_amount'];
            } else {
                $cashBalance->expected_amount = $calculatedExpected;
            }

            // otras asignaciones
            $cashBalance->balance_date = $balanceDate;

            $cashBalance->difference =
                $cashBalance->actual_amount - $cashBalance->expected_amount;

            $cashBalance->status =
                ($cashBalance->difference == 0) ? 'OK' : 'MISMATCH';

            try {
                if ($this->CashBalances->save($cashBalance)) {
                    $this->Flash->success(
                        'La cuadratura fue guardada correctamente.'
                    );
                    return $this->redirect(['action' => 'index']);
                }
            } catch (\PDOException $e) {
                $this->Flash->error(
                    'Ya existe una cuadratura para esta fecha.'
                );
            }

            $this->Flash->error(
                'No se pudo guardar la cuadratura.'
            );
        }

        // Para mostrar el cálculo en la vista (GET)
        $this->set(compact('cashBalance', 'calculatedExpected'));
    }


    public function index()
    {
        $this->paginate = [
            'limit' => 10,
            'order' => [
                'balance_date' => 'DESC',
            ],
        ];

        $cashBalances = $this->paginate($this->CashBalances);
        
        // Últimos 7 días
        $startDate = FrozenDate::today()->subDays(6);


        $last7Days = $this->CashBalances
            ->find()
            ->select([
                'balance_date',
                'expected_amount',
                'actual_amount'
            ])
            ->where([
                'balance_date >=' => $startDate->format('Y-m-d')
            ])
            ->order(['balance_date' => 'ASC'])
            ->enableHydration(false)
            ->toArray();

        $labels = [];
        $expectedData = [];
        $actualData = [];

        foreach ($last7Days as $row) {
            $labels[] = $row['balance_date']->format('d-m');
            $expectedData[] = (float)$row['expected_amount'];
            $actualData[] = (float)$row['actual_amount'];
        }

        $this->set(compact(
            'cashBalances',
            'labels',
            'expectedData',
            'actualData'
        ));
        #$this->set(compact('cashBalances'));
    }

    public function today()
    {
        $today = FrozenDate::today();

        // Obtener la cuadratura de hoy
        $cashBalance = $this->CashBalances
            ->find()
            ->where(['balance_date' => $today])
            ->first();

        if (!$cashBalance) {
            $this->Flash->error('No existe cuadratura registrada para hoy.');
            return $this->redirect(['action' => 'index']);
        }

        // Obtener pedidos cerrados de hoy
        $ordersTable = $this->fetchTable('Orders');
        $orders = $ordersTable
            ->find('closedToday')
            ->contain(['Products'])
            ->all();

        //  Calcular totales por pedido
        $totalDia = 0;
        $totalesPorPedido = [];

        foreach ($orders as $order) {
            $totalPedido = 0;

            foreach ($order->products as $product) {
                $totalPedido +=
                    $product->price * $product->_joinData->quantity;
            }

            $totalesPorPedido[$order->id] = $totalPedido;
            $totalDia += $totalPedido;
        }

        //Enviar datos a la vista
        $this->set(compact(
            'cashBalance',
            'totalesPorPedido',
            'totalDia',
            'today'
        ));
    }

    public function view($id = null)
    {
        $cashBalance = $this->CashBalances->get($id);

        $this->set(compact('cashBalance'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $cashBalance = $this->CashBalances->get($id);
        if ($this->CashBalances->delete($cashBalance)) {
            $this->Flash->success(__('The cash balance has been deleted.'));
        } else {
            $this->Flash->error(__('The cash balance could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function edit($id = null)
    {
        $cashBalance = $this->CashBalances->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {

            // Permitimos editar expected_amount, actual_amount y description
            $cashBalance = $this->CashBalances->patchEntity(
                $cashBalance,
                $this->request->getData(),
                [
                    'fields' => [
                        'expected_amount',
                        'actual_amount',
                        'description'
                    ]
                ]
            );

            // recalcular diferencia
            $cashBalance->difference =
                $cashBalance->actual_amount - $cashBalance->expected_amount;

            // recalcular estado
            $cashBalance->status =
                ($cashBalance->difference == 0) ? 'OK' : 'MISMATCH';

            if ($this->CashBalances->save($cashBalance)) {
                $this->Flash->success(
                    'La cuadratura fue actualizada correctamente.'
                );
                return $this->redirect(['action' => 'view', $id]);
            }

            $this->Flash->error(
                'No se pudo actualizar la cuadratura.'
            );
        }

        $this->set(compact('cashBalance'));
    }

    public function expectedAmountByDate()
    {
        $this->request->allowMethod(['get']);
        $this->autoRender = false;

        $dateString = $this->request->getQuery('date');
        if (!$dateString) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['expectedAmount' => 0]));
        }

        $date = new FrozenDate($dateString);

        $ordersTable = $this->fetchTable('Orders');
        $orders = $ordersTable
            ->find('closedByDate', ['date' => $date])
            ->all();

        $expectedAmount = 0;

        foreach ($orders as $order) {
            foreach ($order->products as $product) {
                $expectedAmount +=
                    $product->price * $product->_joinData->quantity;
            }
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'expectedAmount' => $expectedAmount
            ]));
    }
    
}
