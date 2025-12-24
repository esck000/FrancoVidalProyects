<?php
/**
 * Vista: Listado de Pedidos
 * Estilo consistente con los demás módulos del panel.
 */

// Parámetros de ordenamiento actual
$params = $this->Paginator->params();
$sort = $params['sort'] ?? null;
$direction = $params['direction'] ?? null;

/**
 * Retorna el ícono de ordenamiento según la columna ordenada.
 * - Columna no ordenada: ícono neutral.
 * - Columna ordenada: flecha ascendente o descendente.
 */
function sortIcon($field, $currentSort, $direction) {
    if ($field !== $currentSort) {
        return ' <i class="bi bi-arrow-down-up sort-neutral"></i>';
    }

    return $direction === 'asc'
        ? ' <i class="bi bi-arrow-up"></i>'
        : ' <i class="bi bi-arrow-down"></i>';
}
?>

<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <h2 class="fw-semibold text-dark">Gestión de Pedidos</h2>

        <?= $this->Html->link(
            '<i class="bi bi-plus-circle me-1"></i> Nuevo Pedido',
            ['action' => 'add'],
            ['class' => 'btn btn-primary shadow-sm', 'escape' => false]
        ) ?>
    </div>

    <div class="mb-3">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'd-flex align-items-center flex-wrap']) ?>

            <?= $this->Form->control('search', [
                'label' => false,
                'value' => $search ?? '',
                'placeholder' => 'Buscar por ID...',
                'class' => 'form-control me-2 mb-2'
            ]) ?>

            <?= $this->Form->control('status', [
                'label' => false,
                'options' => [
                    'in_process' => 'En proceso',
                    'closed'     => 'Cerrado',
                    'cancelled'  => 'Cancelado'
                ],
                'empty'  => 'Todos los estados',
                'value'  => $statusFilter ?? '',
                'class'  => 'form-select me-2 mb-2'
            ]) ?>

            <?= $this->Form->button('Buscar', ['class' => 'btn btn-primary mb-2 me-2']) ?>

            <?= $this->Html->link('Limpiar', ['action' => 'index'], ['class' => 'btn btn-secondary mb-2']) ?>

        <?= $this->Form->end() ?>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead style="background: linear-gradient(90deg, #009FE3 0%, #4CC3FF 100%); color: #fff;">
                        <tr>
                            <th>
                                <?= $this->Paginator->sort('id', 'ID') ?>
                                <?= sortIcon('id', $sort, $direction) ?>
                            </th>

                            <th>
                                <?= $this->Paginator->sort('status', 'Estado') ?>
                                <?= sortIcon('status', $sort, $direction) ?>
                            </th>

                            <th>
                                <?= $this->Paginator->sort('created', 'Creado') ?>
                                <?= sortIcon('created', $sort, $direction) ?>
                            </th>

                            <th>
                                <?= $this->Paginator->sort('modified', 'Modificado') ?>
                                <?= sortIcon('modified', $sort, $direction) ?>
                            </th>

                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>

                                <td><?= h($order->id) ?></td>

                                <td>
                                    <?php
                                        $labels = [
                                            'in_process' => ['label' => 'En Proceso', 'class' => 'info'],
                                            'cancelled'  => ['label' => 'Cancelado',  'class' => 'danger'],
                                            'closed'     => ['label' => 'Cerrado',    'class' => 'success'],
                                        ];
                                        $status = $labels[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 'secondary'];
                                    ?>
                                    <span class="badge badge-soft-<?= $status['class'] ?>">
                                        <?= h($status['label']) ?>
                                    </span>
                                </td>

                                <td><?= $order->created ? $order->created->format('d/m/Y H:i') : '-' ?></td>
                                <td><?= $order->modified ? $order->modified->format('d/m/Y H:i') : '-' ?></td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">

                                        <?= $this->Html->link(
                                            '<i class="bi bi-eye"></i>',
                                            ['action' => 'view', $order->id],
                                            [
                                                'class' => 'btn btn-outline-primary btn-sm rounded shadow-sm',
                                                'escape' => false,
                                                'title' => 'Ver',
                                                'style' => 'border-width:1.5px;'
                                            ]
                                        ) ?>

                                        <?= $this->Html->link(
                                            '<i class="bi bi-pencil"></i>',
                                            ['action' => 'edit', $order->id],
                                            [
                                                'class' => 'btn btn-outline-warning btn-sm rounded shadow-sm',
                                                'escape' => false,
                                                'title' => 'Editar',
                                                'style' => 'border-width:1.5px;'
                                            ]
                                        ) ?>

                                        <?= $this->Form->postLink(
                                            '<i class="bi bi-trash"></i>',
                                            ['action' => 'delete', $order->id],
                                            [
                                                'confirm' => '¿Seguro que deseas eliminar este pedido?',
                                                'class' => 'btn btn-outline-danger btn-sm rounded shadow-sm',
                                                'escape' => false,
                                                'title' => 'Eliminar',
                                                'style' => 'border-width:1.5px;'
                                            ]
                                        ) ?>

                                    </div>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">

                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <?= $this->Paginator->prev() ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next() ?>
                    </ul>
                </nav>

                <p class="text-muted mb-0 small">
                    Página <?= $this->Paginator->counter('{{page}} de {{pages}}') ?>
                </p>
            </div>

        </div>
    </div>

</div>

<style>
    table {
        table-layout: fixed;
        width: 100%;
    }

    th {
        white-space: nowrap;
        cursor: pointer;
    }

    th i {
        font-size: 0.82rem;
        margin-left: 4px;
    }

    td, th {
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sort-neutral {
        opacity: 0.45;
        transition: opacity .15s ease-in-out;
    }

    th:hover .sort-neutral {
        opacity: 0.9;
    }

    .badge-soft-info {
        background-color: rgba(13, 202, 240, 0.15);
        color: #0dcaf0;
        font-weight: 500;
        padding: .45em .9em;
        border-radius: 8px;
    }

    .badge-soft-success {
        background-color: rgba(25, 135, 84, 0.15);
        color: #198754;
        font-weight: 500;
        padding: .45em .9em;
        border-radius: 8px;
    }

    .badge-soft-danger {
        background-color: rgba(220, 53, 69, 0.15);
        color: #dc3545;
        font-weight: 500;
        padding: .45em .9em;
        border-radius: 8px;
    }

    .badge-soft-secondary {
        background-color: rgba(108, 117, 125, 0.15);
        color: #6c757d;
        font-weight: 500;
        padding: .45em .9em;
        border-radius: 8px;
    }

</style>
