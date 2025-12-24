<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\NutritionalInformation[]|\Cake\Collection\CollectionInterface $nutritionalInformations
 */
?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <h2 class="fw-semibold text-dark">Gestión de Información Nutricional</h2>
        <?= $this->Html->link(
            '<i class="bi bi-plus-circle me-1"></i> Nueva Información',
            ['action' => 'add'],
            ['class' => 'btn btn-primary shadow-sm', 'escape' => false]
        ) ?>
    </div>

    <div class="mb-3">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'd-flex align-items-center flex-wrap']) ?>

            <!-- Buscador por nombre de producto -->
            <?= $this->Form->control('search', [
                'label' => false,
                'value' => $search ?? '',
                'placeholder' => 'Buscar por producto...',
                'class' => 'form-control me-2 mb-2'
            ]) ?>

            <!-- Botón Buscar -->
            <?= $this->Form->button('Buscar', ['class' => 'btn btn-primary mb-2 me-2']) ?>

            <!-- Botón Limpiar -->
            <?= $this->Html->link('Limpiar', ['action' => 'index'], ['class' => 'btn btn-secondary mb-2']) ?>

        <?= $this->Form->end() ?>
    </div>


    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead style="background: linear-gradient(90deg, #009FE3 0%, #4CC3FF 100%); color: #fff;">
                        <tr>
                            <th><?= $this->Paginator->sort('id', 'ID') ?></th>
                            <th><?= $this->Paginator->sort('product_id', 'Producto') ?></th>
                            <th><?= $this->Paginator->sort('measurement', 'Medición') ?></th>
                            <th><?= $this->Paginator->sort('calories', 'Calorías') ?></th>
                            <th><?= $this->Paginator->sort('protein', 'Proteína') ?></th>
                            <th><?= $this->Paginator->sort('total_fat', 'Grasas Totales') ?></th>
                            <th><?= $this->Paginator->sort('carbohydrates', 'Carbohidratos') ?></th>
                            <th><?= $this->Paginator->sort('sodium', 'Sodio') ?></th>
                            <th><?= $this->Paginator->sort('cholesterol', 'Colesterol') ?></th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($nutritionalInformations as $info): ?>
                            <tr>
                                <td><?= h($info->id) ?></td>
                                <td><?= h($info->product->name ?? 'Sin producto') ?></td>
                                <td><?= h($info->measurement) ?></td>
                                <td><?= h($info->calories) ?> kcal</td>
                                <td><?= h($info->protein) ?> g</td>
                                <td><?= h($info->total_fat) ?> g</td>
                                <td><?= h($info->carbohydrates) ?> g</td>
                                <td><?= h($info->sodium) ?> mg</td>
                                <td><?= h($info->cholesterol) ?> mg</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <?= $this->Html->link(
                                            '<i class="bi bi-eye"></i>',
                                            ['action' => 'view', $info->id],
                                            [
                                                'class' => 'btn btn-outline-primary btn-sm rounded shadow-sm',
                                                'escape' => false,
                                                'title' => 'Ver',
                                                'style' => 'border-width:1.5px;'
                                            ]
                                        ) ?>
                                        <?= $this->Html->link(
                                            '<i class="bi bi-pencil"></i>',
                                            ['action' => 'edit', $info->id],
                                            [
                                                'class' => 'btn btn-outline-warning btn-sm rounded shadow-sm',
                                                'escape' => false,
                                                'title' => 'Editar',
                                                'style' => 'border-width:1.5px;'
                                            ]
                                        ) ?>
                                        <?= $this->Form->postLink(
                                            '<i class="bi bi-trash"></i>',
                                            ['action' => 'delete', $info->id],
                                            [
                                                'confirm' => '¿Seguro que deseas eliminar este registro nutricional?',
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

            <!-- Paginación -->
            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                <div>
                    <?= $this->Paginator->prev('< Anterior', ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                    <?= $this->Paginator->numbers(['class' => 'pagination pagination-sm d-inline-flex']) ?>
                    <?= $this->Paginator->next('Siguiente >', ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                </div>
                <p class="text-muted mb-0 small">
                    Página <?= $this->Paginator->counter('{{page}} de {{pages}}') ?>
                </p>
            </div>
        </div>
    </div>
</div>
