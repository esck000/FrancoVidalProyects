<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product[]|\Cake\Collection\CollectionInterface $products
 */

// Parámetros actuales del paginador
$params = $this->Paginator->params();
$sort = $params['sort'] ?? null;
$direction = $params['direction'] ?? null;

/**
 * Devuelve el ícono correcto según el estado del ordenamiento.
 * - Si no es la columna ordenada: ícono neutral.
 * - Si es la columna ordenada: flecha ascendente o descendente.
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
        <h2 class="fw-semibold text-dark">Gestión de Productos</h2>
        <?= $this->Html->link(
            '<i class="bi bi-plus-circle me-1"></i> Nuevo producto',
            ['action' => 'add'],
            ['class' => 'btn btn-primary shadow-sm', 'escape' => false]
        ) ?>
    </div>

    <div class="mb-3">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'd-flex align-items-center flex-wrap']) ?>

            <?= $this->Form->control('search', [
                'label' => false,
                'value' => $search ?? '',
                'placeholder' => 'Buscar producto...',
                'class' => 'form-control me-2 mb-2'
            ]) ?>

            <?= $this->Form->control('category', [
                'label' => false,
                'options' => $categoriesList ?? [],
                'empty' => 'Todas las categorías',
                'value' => $categoryFilter ?? '',
                'class' => 'form-select me-2 mb-2'
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
                                <?= $this->Paginator->sort('name', 'Nombre') ?>
                                <?= sortIcon('name', $sort, $direction) ?>
                            </th>

                            <th>
                                <?= $this->Paginator->sort('price', 'Precio') ?>
                                <?= sortIcon('price', $sort, $direction) ?>
                            </th>

                            <th>
                                <?= $this->Paginator->sort('Categories.name', 'Categoría') ?>
                                <?= sortIcon('Categories.name', $sort, $direction) ?>
                            </th>

                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= h($product->name) ?></td>
                                <td>$<?= number_format($product->price, 0, ',', '.') ?></td>
                                <td><?= h($product->category->name ?? 'Sin categoría') ?></td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <?= $this->Html->link(
                                            '<i class="bi bi-eye"></i>',
                                            ['action' => 'view', $product->id],
                                            [
                                                'class' => 'btn btn-outline-primary btn-sm rounded shadow-sm',
                                                'escape' => false,
                                                'title' => 'Ver',
                                                'style' => 'border-width:1.5px;'
                                            ]
                                        ) ?>

                                        <?= $this->Html->link(
                                            '<i class="bi bi-pencil"></i>',
                                            ['action' => 'edit', $product->id],
                                            [
                                                'class' => 'btn btn-outline-warning btn-sm rounded shadow-sm',
                                                'escape' => false,
                                                'title' => 'Editar',
                                                'style' => 'border-width:1.5px;'
                                            ]
                                        ) ?>

                                        <?= $this->Form->postLink(
                                            '<i class="bi bi-trash"></i>',
                                            ['action' => 'delete', $product->id],
                                            [
                                                'confirm' => '¿Seguro que deseas eliminar este producto?',
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
</style>
