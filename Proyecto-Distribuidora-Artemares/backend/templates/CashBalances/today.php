<h2>
    Ventas del día
</h2>

<table class="table table-hover mt-4">
    <thead class="table-light">
        <tr>
            <th>Pedido</th>
            <th class="text-end">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($totalesPorPedido as $orderId => $total): ?>
            <tr>
                <td>Pedido <?= h($orderId) ?></td>
                <td class="text-end">
                    <?= $this->Number->currency($total, 'CLP') ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr class="fw-bold">
            <td>Total del día</td>
            <td class="text-end">
                <?= $this->Number->currency($totalDia, 'CLP') ?>
            </td>
        </tr>
    </tfoot>
</table>
