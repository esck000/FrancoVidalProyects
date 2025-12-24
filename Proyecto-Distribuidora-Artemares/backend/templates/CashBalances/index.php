<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Collection\CollectionInterface $cashBalances
 */
?>

<div class="container-fluid px-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <div class="d-flex gap-2">
            <?= $this->Html->link(
                '<i class="bi bi-plus-circle me-1"></i> Añadir cuadratura',
                ['action' => 'add'],
                ['class' => 'btn btn-primary shadow-sm', 'escape' => false]
            ) ?>

            <?= $this->Html->link(
                '<i class="bi bi-receipt me-1"></i> Ver ventas del día',
                ['action' => 'today'],
                ['class' => 'btn btn-outline-primary shadow-sm', 'escape' => false]
            ) ?>
        </div>
        
    </div>

    <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-semibold mb-3 text-muted">
                    Últimos 7 días
                </h6>

                <canvas id="cashBalanceChart" height="100"></canvas>
            </div>
    </div>

    <!-- Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <!-- Header con gradiente -->
                    <thead style="background: linear-gradient(90deg, #009FE3 0%, #4CC3FF 100%); color: #fff;">
                        <tr>
                            <th class="text-start">Fecha</th>
                            <th class="text-start">Monto esperado</th>
                            <th class="text-start">Monto actual</th>
                            <th class="text-start">Diferencia</th>
                            <th class="text-start">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($cashBalances->isEmpty()): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No hay cuadraturas registradas
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($cashBalances as $cashBalance): ?>
                            <tr>
                                <td><?= h($cashBalance->balance_date) ?></td>

                                <td class="text-start">
                                    <?= $this->Number->currency($cashBalance->expected_amount, 'CLP') ?>
                                </td>

                                <td class="text-start">
                                    <?= $this->Number->currency($cashBalance->actual_amount, 'CLP') ?>
                                </td>

                                <td class="text-start">
                                    <?php if ($cashBalance->difference == 0): ?>
                                        <span class="fw-semibold text-success">
                                            <?= $this->Number->currency($cashBalance->difference, 'CLP') ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="fw-semibold text-danger">
                                            <?= $this->Number->currency($cashBalance->difference, 'CLP') ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-start">
                                    <?php if ($cashBalance->status === 'conciliada' || $cashBalance->status === 'OK'): ?>
                                        <span class="badge bg-success px-3 py-2">
                                            Correcta
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            Pendiente
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <?= $this->Html->link(
                                            '<i class="bi bi-eye"></i>',
                                            ['action' => 'view', $cashBalance->id],
                                            [
                                                'class' => 'btn btn-outline-primary btn-sm rounded shadow-sm',
                                                'escape' => false,
                                                'title' => 'Ver detalle',
                                                'style' => 'border-width:1.5px;'
                                            ]
                                        ) ?>

                                        <?= $this->Html->link(
                                            '<i class="bi bi-pencil"></i>',
                                            ['action' => 'edit', $cashBalance->id],
                                            [
                                                'class' => 'btn btn-outline-warning btn-sm rounded shadow-sm',
                                                'escape' => false,
                                                'title' => 'Editar',
                                                'style' => 'border-width:1.5px;'
                                            ]
                                        ) ?>        

                                        <?= $this->Form->postLink(
                                            '<i class="bi bi-trash"></i>',
                                            ['action' => 'delete', $cashBalance->id],
                                            [
                                                'confirm' => '¿Seguro que deseas eliminar esta cuadratura de caja?',
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

</div>

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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('cashBalanceChart');

    if (!canvas) {
        console.error('No se encontró el canvas');
        return;
    }

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [
                {
                    label: 'Monto esperado',
                    data: <?= json_encode($expectedData) ?>,
                    backgroundColor: 'rgba(0, 159, 227, 0.7)',
                    borderRadius: 6
                },
                {
                    label: 'Monto actual',
                    data: <?= json_encode($actualData) ?>,
                    backgroundColor: 'rgba(5, 39, 55, 0.7)',
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ctx.dataset.label + ': $ ' +
                                ctx.parsed.y.toLocaleString('es-CL');
                        }
                    }
                }
            },
            scales: {
                x: {
                    stacked: false   // barras una al lado de la otra
                },
                y: {
                    ticks: {
                        callback: value => '$ ' + value.toLocaleString('es-CL')
                    }
                }
            }
        }
    });
});
</script>


