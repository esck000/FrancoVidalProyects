<?php
/**
 * Vista: Agregar Pedido
 * Estilo Artemares — coherente con el resto del panel.
 *
 * Variables disponibles:
 * @var \App\Model\Entity\Order $order
 * @var \Cake\Collection\CollectionInterface $products
 * @var array $statuses
 */
?>

<div class="container-fluid px-4">
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="fw-semibold mb-1" style="color: #009FE3;">
                <i class="bi bi-cart-plus me-2"></i> Nuevo Pedido
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

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?= $this->Form->create($order, ['class' => 'row g-3']) ?>

            <!-- Estado del pedido -->
            <div class="col-md-6">
                <?= $this->Form->control('status', [
                    'label' => 'Estado del pedido',
                    'options' => $statuses,
                    'class' => 'form-select'
                ]) ?>
            </div>

            <div class="col-12 mt-4">
                <h5 class="fw-semibold text-dark mb-3">
                    <i class="bi bi-box-seam me-2 text-primary"></i> Selecciona los productos para este pedido
                </h5>

                <div class="table-responsive">
                    <table class="table table-hover align-middle table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Seleccionar</th>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th class="text-center" style="width: 120px;">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $i => $product): ?>
                                <tr>
                                    <td class="text-center">
                                        <?= $this->Form->checkbox("products.$i.id", [
                                            'value' => $product->id,
                                            'hiddenField' => false,
                                            'class' => 'form-check-input'
                                        ]) ?>
                                    </td>
                                    <td><?= h($product->name) ?></td>
                                    <td>$<?= number_format($product->price, 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <?= $this->Form->number("products.$i._joinData.quantity", [
                                            'value' => 1,
                                            'min' => 1,
                                            'label' => false,
                                            'class' => 'form-control text-center',
                                            'style' => 'max-width: 90px; margin: auto;'
                                        ]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Botones -->
            <div class="col-12 d-flex justify-content-end mt-3">
                <?= $this->Form->button(
                    '<i class="bi bi-save"></i> Guardar Pedido',
                    ['escapeTitle' => false, 'class' => 'btn btn-primary px-4']
                ) ?>
            </div>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
