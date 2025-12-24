<?php
/**
 * Vista: Listado de Recetas
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Recipe[]|\Cake\Collection\CollectionInterface $recipes
 */

use Cake\Utility\Text;

// Parámetros del paginador
$params = $this->Paginator->params();
$sort = $params['sort'] ?? null;
$direction = $params['direction'] ?? null;

/**
 * Retorna el ícono de ordenamiento según el estado actual.
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
        <h2 class="fw-semibold text-dark">Gestión de Recetas</h2>
        <?= $this->Html->link(
            '<i class="bi bi-plus-circle me-1"></i> Nueva Receta',
            ['action' => 'add'],
            ['class' => 'btn btn-primary shadow-sm', 'escape' => false]
        ) ?>
    </div>

    <div class="mb-3">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'd-flex']) ?>
            <?= $this->Form->control('search', [
                'label' => false,
                'value' => $search ?? '',
                'placeholder' => 'Buscar receta...',
                'class' => 'form-control me-2'
            ]) ?>
            <?= $this->Form->button('Buscar', ['class' => 'btn btn-primary']) ?>
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

                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($recipes as $recipe): ?>
                            <tr>
                                <td><?= h($recipe->name) ?></td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">

                                        <?= $this->Html->link(
                                            '<i class="bi bi-eye"></i>',
                                            ['action' => 'view', $recipe->id],
                                            [
                                                'class' => 'btn btn-outline-primary btn-sm rounded shadow-sm',
                                                'escape' => false,
                                                'title' => 'Ver'
                                            ]
                                        ) ?>

                                        <?= $this->Html->link(
                                            '<i class="bi bi-pencil"></i>',
                                            ['action' => 'edit', $recipe->id],
                                            [
                                                'class' => 'btn btn-outline-warning btn-sm rounded shadow-sm',
                                                'escape' => false,
                                                'title' => 'Editar'
                                            ]
                                        ) ?>

                                        <?= $this->Form->postLink(
                                            '<i class="bi bi-trash"></i>',
                                            ['action' => 'delete', $recipe->id],
                                            [
                                                'confirm' => '¿Seguro que deseas eliminar esta receta?',
                                                'class' => 'btn btn-outline-danger btn-sm rounded shadow-sm',
                                                'escape' => false,
                                                'title' => 'Eliminar'
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
