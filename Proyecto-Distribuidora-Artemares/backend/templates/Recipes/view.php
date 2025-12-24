<?php
/**
 * Vista: Detalle de Receta
 * Estilo Artemares — coherente con Products y Categories
 */
?>

<!-- Encabezado -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="fw-semibold mb-1" style="color: #009FE3;">
            <i class="bi bi-eye me-2"></i> Detalle de la Receta
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
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-4 text-muted">ID</dt>
            <dd class="col-sm-8"><?= h($recipe->id) ?></dd>

            <dt class="col-sm-4 text-muted">Nombre</dt>
            <dd class="col-sm-8"><?= h($recipe->name) ?></dd>

            <dt class="col-sm-4 text-muted">Descripción</dt>
            <dd class="col-sm-8"><?= nl2br(h($recipe->description)) ?></dd>

            <dt class="col-sm-4 text-muted">Ingredientes</dt>
            <dd class="col-sm-8"><?= nl2br(h($recipe->ingredients ?? 'No especificados')) ?></dd>

            <dt class="col-sm-4 text-muted">Creada</dt>
            <dd class="col-sm-8"><?= $recipe->created ? $recipe->created->format('d/m/Y H:i') : '-' ?></dd>

            <dt class="col-sm-4 text-muted">Última modificación</dt>
            <dd class="col-sm-8"><?= $recipe->modified ? $recipe->modified->format('d/m/Y H:i') : '-' ?></dd>
        </dl>
    </div>
</div>

<!-- Imagen del producto -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white border-0 pb-2">
        <h5 class="fw-semibold text-dark mb-0">
            <i class="bi bi-image me-2 text-primary"></i> Imagen de la receta
        </h5>
        <div style="
            height: 2px;
            margin-top: 6px;
            width: 100%;
            background: linear-gradient(90deg, #009FE3 0%, #4CC3FF 100%);
            border-radius: 1px;
        "></div>
    </div>

    <div class="card-body text-center">
        <!-- Imagen -->
        <?php if (!empty($recipe->recipe_image) && !empty($recipe->recipe_image->image_medium)): ?>
                    <img
                        src="data:<?= h($recipe->recipe_image->mime_type_medium ?? 'image/jpeg') ?>;base64,<?= base64_encode(stream_get_contents($recipe->recipe_image->image_medium)) ?>"
                        alt="Imagen de la receta"
                        class="img-fluid rounded"
                        style="max-height: 320px;"
                    />
        <?php else: ?>
            <div class="p-4 text-center text-muted">
                <i class="bi bi-card-image display-6 d-block mb-2 text-secondary"></i>
                <span>Sin imagen disponible</span>
            </div>
        <?php endif; ?>
    </div>
</div>


<!-- Productos asociados -->
<?php if (!empty($recipe->products)): ?>
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white border-0 pb-2">
            <h5 class="fw-semibold text-dark mb-0">
                <i class="bi bi-box-seam me-2 text-primary"></i> Productos relacionados con esta receta
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
                            <th>Nombre</th>
                            <th>Ver</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recipe->products as $product): ?>
                            <tr>
                                <td><?= h($product->id) ?></td>
                                <td><?= h($product->name) ?></td>
                                <td>
                                    <?= $this->Html->link(
                                        '<i class="bi bi-eye"></i>',
                                        ['controller' => 'Products', 'action' => 'view', $product->id],
                                        [
                                            'class' => 'btn btn-outline-primary btn-sm rounded shadow-sm',
                                            'escape' => false,
                                            'title' => 'Ver',
                                            'style' => 'border-width:1.5px;'
                                        ]
                                    ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
    table {
        table-layout: fixed;
        width: 100%;
    }

    th {
        white-space: nowrap;
    }

    td, th {
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

