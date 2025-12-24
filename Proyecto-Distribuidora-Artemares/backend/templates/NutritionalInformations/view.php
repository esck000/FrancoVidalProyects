<?php
/**
 * Vista: Detalle de Información Nutricional
 * Estilo Artemares — coherente con el resto del panel
 */
?>

<!-- Encabezado -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="fw-semibold mb-1" style="color: #009FE3;">
            <i class="bi bi-eye me-2"></i> Detalle de Información Nutricional
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
            <dt class="col-sm-4 text-muted">ID</dt>
            <dd class="col-sm-8"><?= h($nutritionalInformation->id) ?></dd>

            <dt class="col-sm-4 text-muted">Producto</dt>
            <dd class="col-sm-8"><?= h($nutritionalInformation->product->name ?? 'Sin producto asociado') ?></dd>

            <dt class="col-sm-4 text-muted">Medición</dt>
            <dd class="col-sm-8"><?= h($nutritionalInformation->measurement) ?></dd>

            <dt class="col-sm-4 text-muted">Calorías</dt>
            <dd class="col-sm-8"><?= h($nutritionalInformation->calories) ?> kcal</dd>

            <dt class="col-sm-4 text-muted">Proteína</dt>
            <dd class="col-sm-8"><?= h($nutritionalInformation->protein) ?> g</dd>

            <dt class="col-sm-4 text-muted">Grasas Totales</dt>
            <dd class="col-sm-8"><?= h($nutritionalInformation->total_fat) ?> g</dd>

            <dt class="col-sm-4 text-muted">Carbohidratos</dt>
            <dd class="col-sm-8"><?= h($nutritionalInformation->carbohydrates) ?> g</dd>

            <dt class="col-sm-4 text-muted">Sodio</dt>
            <dd class="col-sm-8"><?= h($nutritionalInformation->sodium) ?> mg</dd>

            <dt class="col-sm-4 text-muted">Colesterol</dt>
            <dd class="col-sm-8"><?= h($nutritionalInformation->cholesterol) ?> mg</dd>

            <dt class="col-sm-4 text-muted">Creado</dt>
            <dd class="col-sm-8"><?= $nutritionalInformation->created ? $nutritionalInformation->created->format('d/m/Y H:i') : '-' ?></dd>

            <dt class="col-sm-4 text-muted">Última modificación</dt>
            <dd class="col-sm-8"><?= $nutritionalInformation->modified ? $nutritionalInformation->modified->format('d/m/Y H:i') : '-' ?></dd>
        </dl>
    </div>
</div>
