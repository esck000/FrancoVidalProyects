<?php
/**
 * Vista: Detalle de Administrador
 * Estilo Artemares — coherente con el resto del panel
 */
?>

<!-- Encabezado -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="fw-semibold mb-1" style="color: #009FE3;">
            <i class="bi bi-person-badge me-2"></i> Detalle del Administrador
        </h3>
        <?= $this->Html->link(
            '<i class="bi bi-arrow-left"></i> Volver',
            ['action' => 'index'],
            ['escape' => false, 'class' => 'btn btn-outline-secondary btn-sm shadow-sm']
        ) ?>
    </div>

    <!-- Línea decorativa azul -->
    <div style="
        height: 3px;
        margin-top: 6px;
        border-radius: 2px;
        background: linear-gradient(90deg, #009FE3 0%, #4CC3FF 100%);
        width: 100%;
    "></div>
</div>

<!-- Card de Detalle -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-4 text-muted">ID</dt>
            <dd class="col-sm-8"><?= h($administrator->id) ?></dd>

            <dt class="col-sm-4 text-muted">Nombre completo</dt>
            <dd class="col-sm-8"><?= h($administrator->full_name) ?></dd>

            <dt class="col-sm-4 text-muted">Correo electrónico</dt>
            <dd class="col-sm-8"><?= h($administrator->email) ?></dd>

            <dt class="col-sm-4 text-muted">Usuario</dt>
            <dd class="col-sm-8"><?= h($administrator->username) ?></dd>

            <dt class="col-sm-4 text-muted">Fecha de creación</dt>
            <dd class="col-sm-8">
                <?= $administrator->created ? $administrator->created->format('d/m/Y H:i') : '-' ?>
            </dd>

            <dt class="col-sm-4 text-muted">Última modificación</dt>
            <dd class="col-sm-8">
                <?= $administrator->modified ? $administrator->modified->format('d/m/Y H:i') : '-' ?>
            </dd>
        </dl>
    </div>
</div>
