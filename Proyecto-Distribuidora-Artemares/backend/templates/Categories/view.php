<?php
/**
 * Vista: Detalle de Categoría
 * Estilo Artemares — coherente con Products
 */
?>

<!-- Encabezado -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="fw-semibold mb-1" style="color: #009FE3;">
            <i class="bi bi-eye me-2"></i> Detalle de la Categoría
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

<!-- Card principal con los datos -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-4 text-muted">ID</dt>
            <dd class="col-sm-8"><?= h($category->id) ?></dd>

            <dt class="col-sm-4 text-muted">Nombre</dt>
            <dd class="col-sm-8"><?= h($category->name) ?></dd>

            <dt class="col-sm-4 text-muted">Creado</dt>
            <dd class="col-sm-8"><?= $category->created ? $category->created->format('d/m/Y H:i') : '-' ?></dd>

            <dt class="col-sm-4 text-muted">Última modificación</dt>
            <dd class="col-sm-8"><?= $category->modified ? $category->modified->format('d/m/Y H:i') : '-' ?></dd>
        </dl>
    </div>
</div>

<!-- Listado de productos asociados -->
<?php if (!empty($category->products)): ?>
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white border-0 pb-2">
            <h5 class="fw-semibold text-dark mb-0">
                <i class="bi bi-box-seam me-2 text-primary"></i> Productos de esta categoría
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($category->products as $product): ?>
                            <tr>
                                <td><?= h($product->id) ?></td>
                                <td><?= h($product->name) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>
