<!--bi bi-cash-coin me-2-->
<!-- Encabezado -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="fw-semibold mb-1" style="color: #009FE3;">
            <i class="bi bi-eye me-2"></i> Detalle de la Cuadratura de Caja
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

            <dt class="col-sm-4 text-muted">Fecha de cuadratura</dt>
            <dd class="col-sm-8">
                <?= h($cashBalance->balance_date) ?>
            </dd>

            <dt class="col-sm-4 text-muted">Monto esperado (ventas)</dt>
            <dd class="col-sm-8">
                <?= $this->Number->currency($cashBalance->expected_amount, 'CLP') ?>
            </dd>

            <dt class="col-sm-4 text-muted">Monto actual en caja</dt>
            <dd class="col-sm-8">
                <?= $this->Number->currency($cashBalance->actual_amount, 'CLP') ?>
            </dd>

            <dt class="col-sm-4 text-muted">Diferencia</dt>
            <dd class="col-sm-8">
                <?php if ($cashBalance->difference == 0): ?>
                    <span class="fw-semibold text-success">
                        <?= $this->Number->currency($cashBalance->difference, 'CLP') ?>
                    </span>
                <?php else: ?>
                    <span class="fw-semibold text-danger">
                        <?= $this->Number->currency($cashBalance->difference, 'CLP') ?>
                    </span>
                <?php endif; ?>
            </dd>

            <dt class="col-sm-4 text-muted">Estado</dt>
            <dd class="col-sm-8">
                <?php if ($cashBalance->status === 'OK' || $cashBalance->status === 'conciliada'): ?>
                    <span class="badge bg-success px-3 py-2">Correcta</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark px-3 py-2">Pendiente</span>
                <?php endif; ?>
            </dd>

            <dt class="col-sm-4 text-muted">Descripción</dt>
            <dd class="col-sm-8">
                <?= $cashBalance->description ? h($cashBalance->description) : '<span class="text-muted">Sin descripción</span>' ?>
            </dd>

            <dt class="col-sm-4 text-muted">Creado</dt>
            <dd class="col-sm-8">
                <?= $cashBalance->created ? $cashBalance->created->format('d/m/Y H:i') : '-' ?>
            </dd>

            <dt class="col-sm-4 text-muted">Última modificación</dt>
            <dd class="col-sm-8">
                <?= $cashBalance->modified ? $cashBalance->modified->format('d/m/Y H:i') : '-' ?>
            </dd>

        </dl>
    </div>
</div>
