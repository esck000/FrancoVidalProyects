<?php
/**
 * Vista: Detalle del Pedido
 * Estilo Artemares — coherente con el resto del panel
 *
 * Variables disponibles:
 * @var \App\Model\Entity\Order $order
 */
?>

<!-- Encabezado -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="fw-semibold mb-1" style="color: #009FE3;">
            <i class="bi bi-eye me-2"></i> Detalle del Pedido
        </h3>
        <?= $this->Html->link(
            '<i class="bi bi-arrow-left"></i> Volver',
            ['action' => 'index'],
            ['escape' => false, 'class' => 'btn btn-outline-secondary btn-sm shadow-sm']
        ) ?>
    </div>

    <div style="
        height: 3px;
        margin-top: 6px;
        border-radius: 2px;
        background: linear-gradient(90deg, #009FE3 0%, #4CC3FF 100%);
        width: 100%;
    "></div>
</div>

<!-- Card principal -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-4 text-muted">ID del Pedido</dt>
            <dd class="col-sm-8"><?= h($order->id) ?></dd>

            <dt class="col-sm-4 text-muted">Estado</dt>
            <dd class="col-sm-8">
                <?php
                    $statusColors = [
                        'pending'    => 'secondary',
                        'in_process' => 'info',
                        'completed'  => 'success',
                        'cancelled'  => 'danger',
                        'closed'     => 'success',
                    ];

                    $statusLabels = [
                        'pending'    => 'Pendiente',
                        'in_process' => 'En proceso',
                        'completed'  => 'Completado',
                        'cancelled'  => 'Cancelado',
                        'closed'     => 'Cerrado',
                    ];

                    $status = $order->status ?? 'desconocido';
                    $badgeClass = $statusColors[$status] ?? 'secondary';
                    $label = $statusLabels[$status] ?? ucfirst($status);
                ?>
                <span class="badge bg-<?= $badgeClass ?>"><?= h($label) ?></span>
            </dd>

            <dt class="col-sm-4 text-muted">Creado</dt>
            <dd class="col-sm-8"><?= $order->created ? $order->created->format('d/m/Y H:i') : '-' ?></dd>

            <dt class="col-sm-4 text-muted">Última Modificación</dt>
            <dd class="col-sm-8"><?= $order->modified ? $order->modified->format('d/m/Y H:i') : '-' ?></dd>
        </dl>
    </div>
</div>

<!-- Productos asociados -->
<?php if (!empty($order->products)): ?>
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white border-0 pb-2">
            <h5 class="fw-semibold text-dark mb-0">
                <i class="bi bi-box-seam me-2 text-primary"></i> Productos en este Pedido
            </h5>
            <div style="
                height: 2px;
                margin-top: 6px;
                width: 100%;
                background: linear-gradient(90deg, #009FE3 0%, #4CC3FF 100%);
                border-radius: 1px;
            "></div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Producto</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Precio Unitario</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $total = 0;
                            foreach ($order->products as $product):
                                $qty = $product->_joinData->quantity ?? 1;
                                $subtotal = $product->price * $qty;
                                $total += $subtotal;
                        ?>
                            <tr>
                                <td><?= h($product->id) ?></td>
                                <td><?= h($product->name) ?></td>
                                <td class="text-center"><?= h($qty) ?></td>
                                <td class="text-end">$<?= number_format($product->price, 0, ',', '.') ?></td>
                                <td class="text-end">$<?= number_format($subtotal, 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="4" class="text-end">Total:</th>
                            <th class="text-end">$<?= number_format($total, 0, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-secondary mt-4 mb-0 text-center">
        <i class="bi bi-info-circle me-2"></i> Este pedido no tiene productos asociados.
    </div>
<?php endif; ?>
