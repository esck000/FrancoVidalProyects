<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Administrator[]|\Cake\Collection\CollectionInterface $administrators
 */
?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <h2 class="fw-semibold text-dark">Gestión de Administradores</h2>
        <?= $this->Html->link(
            '<i class="bi bi-plus-circle me-1"></i> Nuevo Administrador',
            ['action' => 'add'],
            ['class' => 'btn btn-primary shadow-sm', 'escape' => false]
        ) ?>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead style="background: linear-gradient(90deg, #009FE3 0%, #4CC3FF 100%); color: #fff;">
                        <tr>
                            <th><?= $this->Paginator->sort('id', 'ID') ?></th>
                            <th><?= $this->Paginator->sort('username', 'Usuario') ?></th>
                            <th><?= $this->Paginator->sort('email', 'Correo') ?></th>
                            <th><?= $this->Paginator->sort('full_name', 'Nombre') ?></th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($administrators as $administrator): ?>
                            <tr>
                                <td><?= h($administrator->id) ?></td>
                                <td><?= h($administrator->username) ?></td>
                                <td><?= h($administrator->email) ?></td>
                                <td><?= h($administrator->full_name ?? '—') ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <?= $this->Html->link(
                                            '<i class="bi bi-eye"></i>',
                                            ['action' => 'view', $administrator->id],
                                            [
                                                'class' => 'btn btn-outline-primary btn-sm rounded shadow-sm',
                                                'escape' => false,
                                                'title' => 'Ver',
                                                'style' => 'border-width:1.5px;'
                                            ]
                                        ) ?>
                                        <?= $this->Html->link(
                                            '<i class="bi bi-pencil"></i>',
                                            ['action' => 'edit', $administrator->id],
                                            [
                                                'class' => 'btn btn-outline-warning btn-sm rounded shadow-sm',
                                                'escape' => false,
                                                'title' => 'Editar',
                                                'style' => 'border-width:1.5px;'
                                            ]
                                        ) ?>
                                        <?= $this->Form->postLink(
                                            '<i class="bi bi-trash"></i>',
                                            ['action' => 'delete', $administrator->id],
                                            [
                                                'confirm' => '¿Seguro que deseas eliminar este administrador?',
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
