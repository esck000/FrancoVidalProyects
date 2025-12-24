<?php
declare(strict_types=1);

namespace App\Controller;

class CashClosureController extends AppController
{
    public function index()
    {
        $ordersTable = $this->fetchTable('Orders');

        // obtener pedidos cerrados del día actual con productos y cantidades
        $orders = $ordersTable->find('closedToday')->all();

        // calcular total del día
        $totalDia = 0;
        $detalles = [];

        foreach ($orders as $order) {
            $totalPedido = 0;

            foreach ($order->products as $product) {
                $subtotal = $product->price * $product->_joinData->quantity;
                $totalPedido += $subtotal;

                $detalles[] = [
                    'order_id' => $order->id,
                    'producto' => $product->name,
                    'precio'   => $product->price,
                    'cantidad' => $product->_joinData->quantity,
                    'total_linea' => $subtotal,
                ];
            }

            $order->total = $totalPedido;
            $totalDia += $totalPedido;
        }

        $this->set(compact('orders', 'totalDia', 'detalles'));
    }
}
