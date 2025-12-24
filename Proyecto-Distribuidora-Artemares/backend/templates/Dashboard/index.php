<?php
/**
 * Vista: Dashboard
 */
?>

<h1 class="mb-4">Tablero</h1>
<div class="row">
    <!-- Total Productos -->
    <div class="col-md-3">
        <div class="card shadow-sm border-start border-4 border-primary mb-3">
            <div class="card-body d-flex align-items-center">
                <div class="me-3 fs-2 text-primary">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <div class="text-uppercase small text-muted">
                        Total de Productos
                    </div>
                    <div class="fs-2 fw-bold">
                        <?= $totalProducts ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Categorías -->
    <div class="col-md-3">
        <div class="card shadow-sm border-start border-4 border-success mb-3">
            <div class="card-body d-flex align-items-center">
                <div class="me-3 fs-2 text-success">
                    <i class="bi bi-tags"></i>
                </div>
                <div>
                    <div class="text-uppercase small text-muted">
                        Total de Categorías
                    </div>
                    <div class="fs-2 fw-bold">
                        <?= $totalCategories ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pedidos Pendientes -->
    <div class="col-md-3">
        <div class="card shadow-sm border-start border-4 border-warning mb-3">
            <div class="card-body d-flex align-items-center">
                <div class="me-3 fs-2 text-warning">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <div class="text-uppercase small text-muted">
                        Pedidos Pendientes
                    </div>
                    <div class="fs-2 fw-bold text-warning">
                        <?= $pendingOrders->count() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pedidos Cerrados Hoy -->
    <div class="col-md-3">
        <div class="card shadow-sm border-start border-4 border-info mb-3">
            <div class="card-body d-flex align-items-center">
                <div class="me-3 fs-2 text-info">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <div class="text-uppercase small text-muted">
                        Pedidos cerrados hoy
                    </div>
                    <div class="fs-2 fw-bold">
                        <?= $closedOrdersToday ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Accesos rápidos -->
<h3 class="mb-3">Accesos Rápidos</h3>
<div class="mb-4">
    <?php foreach ($quickAccess as $link): ?>
        <a
            class="btn btn-outline-primary me-2 mb-2 d-inline-flex align-items-center gap-2"
            href="<?= $this->Url->build($link['url']) ?>"
        >
            <i class="bi-lightning-charge"></i>
            <?= h($link['label']) ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- Pedidos Pendientes -->
<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold text-dark">
            <i class="bi bi-clock-history me-2 text-info"></i>
            Pedidos pendientes
        </h5>

        <span class="badge badge-soft-info">
            <?= $pendingOrders->count() ?> pendientes
        </span>
    </div>

    <div class="card-body p-0">

        <?php if ($pendingOrders->isEmpty()): ?>
            <p class="text-muted text-center py-4 mb-0">
                No hay pedidos pendientes.
            </p>
        <?php else: ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 pending-orders-table">

                    <thead>
                        <tr class="text-muted small text-center">
                            <th class="fw-semibold">Pedido</th>
                            <th class="fw-semibold">Estado</th>
                            <th class="fw-semibold">Fecha</th>
                            <th class="fw-semibold">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($pendingOrders as $order): ?>
                            <tr>
                                <td class="text-center fw-semibold text-dark">
                                    <?= $order->id ?>
                                </td>

                                <td class="text-center">
                                    <span class="badge badge-soft-info">
                                        En proceso
                                    </span>
                                </td>

                                <td class="text-center text-muted">
                                    <?= $order->created->format('d-m-Y') ?>
                                </td>

                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">

                                        <?= $this->Html->link(
                                            '<i class="bi bi-eye"></i>',
                                            ['controller' => 'Orders', 'action' => 'view', $order->id],
                                            ['class' => 'btn btn-outline-primary', 'escape' => false, 'title' => 'Ver']
                                        ) ?>

                                        <?= $this->Html->link(
                                            '<i class="bi bi-pencil"></i>',
                                            ['controller' => 'Orders', 'action' => 'edit', $order->id],
                                            ['class' => 'btn btn-outline-warning', 'escape' => false, 'title' => 'Editar']
                                        ) ?>

                                        <?= $this->Form->postLink(
                                            '<i class="bi bi-trash"></i>',
                                            ['controller' => 'Orders', 'action' => 'delete', $order->id],
                                            [
                                                'confirm' => '¿Seguro que deseas eliminar este pedido?',
                                                'class' => 'btn btn-outline-danger',
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

        <?php endif; ?>

    </div>
</div>

<div class="row g-4">
    <!-- Productos más vendidos -->
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-bar-chart-fill me-2 text-primary"></i>
                    4 productos más vendidos (histórico)
                </h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 top-products-table">

                        <thead>
                            <tr class="text-muted">
                                <th class="fw-semibold">Producto</th>
                                <th class="fw-semibold text-center">Cantidad vendida</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($productsMostSold as $item): ?>
                                <tr>
                                    <td class="fw-semibold">
                                        <?= h($item->_matchingData['Products']->name) ?>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge badge-soft-primary">
                                            <?= $item->total_quantity ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

        <!-- Productos por Categoría -->
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-tags-fill me-2 text-success"></i>
                    Productos por categoría
                </h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 products-category-table">

                        <thead>
                            <tr class="text-muted ">
                                <th class="fw-semibold">Categoría</th>
                                <th class="fw-semibold text-center">Total de productos</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($productsByCategory as $item): ?>
                                <tr>
                                    <td class="fw-semibold">
                                        <?= h($item->name) ?>
                                    </td>

                                    <td class="text-end text-center">
                                        <span class="badge badge-soft-success">
                                            <?= $item->total_products ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pedidos recientes -->
<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-white">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-clock me-2 text-secondary"></i>
            Pedidos recientes
        </h5>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0 recent-orders-table">

                <thead>
                    <tr class="text-muted fs-6">
                        <th class="fw-semibold">Pedido</th>
                        <th class="fw-semibold text-center">Estado</th>
                        <th class="fw-semibold text-center">Fecha</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($recentOrders as $order): ?>

                        <?php
                        $statuses = [
                            'in_process' => ['label' => 'En proceso', 'class' => 'info'],
                            'closed'     => ['label' => 'Cerrado', 'class' => 'success'],
                            'cancelled'  => ['label' => 'Cancelado', 'class' => 'danger'],
                        ];
                        $status = $statuses[$order->status] ?? [
                            'label' => ucfirst($order->status),
                            'class' => 'secondary'
                        ];
                        ?>

                        <tr>
                            <td class="fw-semibold text-dark">
                                <?= $order->id ?>
                            </td>

                            <td class="text-center">
                                <span class="badge badge-soft-<?= $status['class'] ?>">
                                    <?= $status['label'] ?>
                                </span>
                            </td>

                            <td class="text-center text-muted">
                                <?= $order->created->format('d-m-Y') ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>


<div class="row mb-4">
    <!-- Ventas Totales por Producto - Últimos 7 Días -->
     <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ventas Totales por Producto (Últimos 7 Días)</h5>

                <a href="/reports/sales-by-product.xlsx"
                class="btn btn-outline-success btn-sm">
                    <i class="bi bi-file-earmark-excel"></i>
                    Exportar Excel
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($salesLast7DaysByProduct)): ?>
                    <canvas id="salesByProductChart" width="800" height="400"></canvas>

                    <!-- Chart.js desde CDN -->
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                    const ctxProducts = document.getElementById('salesByProductChart').getContext('2d');

                    const productLabels = [
                        <?php foreach ($salesLast7DaysByProduct as $item): ?>
                            "<?= h($item->_matchingData['Products']->name) ?>",
                        <?php endforeach; ?>
                    ];

                    const productData = [
                        <?php foreach ($salesLast7DaysByProduct as $item): ?>
                            <?= $item->total_quantity ?>,
                        <?php endforeach; ?>
                    ];

                    new Chart(ctxProducts, {
                        type: 'bar',
                        data: {
                            labels: productLabels,
                            datasets: [{
                                label: 'Cantidad Vendida',
                                data: productData,
                                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            indexAxis: 'y', 
                            responsive: true,
                            plugins: {
                                legend: { display: false },
                                title: { display: true, text: 'Top 5 productos' }
                            },
                            scales: {
                                x: { beginAtZero: true }
                            }
                        }
                    });
                    </script>
                <?php else: ?>
                    <p class="text-muted mb-0">No hay ventas registradas en los últimos 7 días.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Ventas Totales por Producto - Últimos 30 Días -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ventas Totales por Producto (Últimos 30 Días)</h5>

                <a href="/reports/sales-by-product-month.xlsx"
                class="btn btn-outline-success btn-sm">
                    <i class="bi bi-file-earmark-excel"></i>
                    Exportar Excel
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($salesLast30DaysTop5)): ?>
                    <canvas id="salesByProduct30DaysChart" width="800" height="400"></canvas>

                    <!-- Chart.js desde CDN -->
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                    const ctxProducts30 = document.getElementById('salesByProduct30DaysChart').getContext('2d');

                    const productLabels30 = [
                        <?php foreach ($salesLast30DaysTop5 as $item): ?>
                            "<?= h($item->_matchingData['Products']->name) ?>",
                        <?php endforeach; ?>
                    ];

                    const productData30 = [
                        <?php foreach ($salesLast30DaysTop5 as $item): ?>
                            <?= $item->total_quantity ?>,
                        <?php endforeach; ?>
                    ];

                    new Chart(ctxProducts30, {
                        type: 'bar',
                        data: {
                            labels: productLabels30,
                            datasets: [{
                                label: 'Cantidad Vendida',
                                data: productData30,
                                backgroundColor: 'rgba(255, 159, 64, 0.7)',
                                borderColor: 'rgba(255, 159, 64, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            plugins: {
                                legend: { display: false },
                                title: { display: true, text: 'Top 5 productos' }
                            },
                            scales: {
                                x: { beginAtZero: true }
                            }
                        }
                    });
                    </script>
                <?php else: ?>
                    <p class="text-muted mb-0">No hay ventas registradas en los últimos 30 días.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>    
</div>
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Ventas Totales por Categoría (Últimos 7 Días)</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($salesLast7DaysByCategory)): ?>
                    <canvas id="salesByCategory7DaysChart" width="800" height="400"></canvas>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                    const ctxCat7 = document.getElementById('salesByCategory7DaysChart').getContext('2d');

                    const categoryLabels7 = [
                        <?php foreach ($salesLast7DaysByCategory as $item): ?>
                            "<?= h($item->_matchingData['Categories']->name) ?>",
                        <?php endforeach; ?>
                    ];

                    const categoryData7 = [
                        <?php foreach ($salesLast7DaysByCategory as $item): ?>
                            <?= $item->total_quantity ?>,
                        <?php endforeach; ?>
                    ];

                    new Chart(ctxCat7, {
                        type: 'bar',
                        data: {
                            labels: categoryLabels7,
                            datasets: [{
                                label: 'Cantidad Vendida',
                                data: categoryData7,
                                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            plugins: {
                                legend: { display: false },
                                title: { display: true, text: 'Top 5 categorías' }
                            },
                            scales: {
                                x: { beginAtZero: true }
                            }
                        }
                    });
                    </script>
                <?php else: ?>
                    <p class="text-muted mb-0">No hay ventas registradas en los últimos 7 días.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Ventas Totales por Categoría (Últimos 30 Días)</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($salesLast30DaysByCategory)): ?>
                    <canvas id="salesByCategory30DaysChart" width="800" height="400"></canvas>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                    const ctxCat30 = document.getElementById('salesByCategory30DaysChart').getContext('2d');

                    const categoryLabels30 = [
                        <?php foreach ($salesLast30DaysByCategory as $item): ?>
                            "<?= h($item->_matchingData['Categories']->name) ?>",
                        <?php endforeach; ?>
                    ];

                    const categoryData30 = [
                        <?php foreach ($salesLast30DaysByCategory as $item): ?>
                            <?= $item->total_quantity ?>,
                        <?php endforeach; ?>
                    ];

                    new Chart(ctxCat30, {
                        type: 'bar',
                        data: {
                            labels: categoryLabels30,
                            datasets: [{
                                label: 'Cantidad Vendida',
                                data: categoryData30,
                                backgroundColor: 'rgba(255, 159, 64, 0.7)',
                                borderColor: 'rgba(255, 159, 64, 1)',
                                borderWidth: 1
                            }] 
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            plugins: {
                                legend: { display: false },
                                title: { 
                                    display: true, 
                                    text: 'Top 5 categorías' 
                                }
                            },
                            scales: {
                                x: { beginAtZero: true }
                            }
                        }
                    });
                    </script>
                <?php else: ?>
                    <p class="text-muted mb-0">No hay ventas registradas en los últimos 30 días.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Evolución de ventas -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Evolución de Ventas</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($salesEvolution)): ?>
            <canvas id="salesChart" width="800" height="400"></canvas>

            <!-- Chart.js desde CDN -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
            const ctx = document.getElementById('salesChart').getContext('2d');

            const labels = [
                <?php foreach ($salesEvolution as $item): ?>
                    "<?= !empty($item->date) ? (new \DateTime($item->date))->format('d-m-Y') : '' ?>",
                <?php endforeach; ?>
            ];

            const data = [
                <?php foreach ($salesEvolution as $item): ?>
                    <?= $item->total_quantity ?? 0 ?>,
                <?php endforeach; ?>
            ];

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Cantidad Vendida',
                        data: data,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Evolución de Ventas' }
                    },
                    scales: { y: { beginAtZero: true } }
                }
            });
            </script>
        <?php else: ?>
            <p class="text-muted mb-0">No hay ventas registradas para mostrar la evolución.</p>
        <?php endif; ?>
    </div>
</div>

<style>
   /* Tabla pedidos pendientes */
    .pending-orders-table th,
    .pending-orders-table td {
        border-bottom: 1px solid #eef1f4;
    }

    .pending-orders-table tbody tr:last-child td {
        border-bottom: none;
    }

    .pending-orders-table tbody tr:hover {
        background-color: rgba(0,0,0,.015);
    }

    .recent-orders-table th,
    .recent-orders-table td {
        padding: .75rem 1.25rem; /* ↑ más aire horizontal */
    }
    
    /* Badge soft */
    .badge-soft-info {
        background-color: rgba(13, 202, 240, 0.15);
        color: #0dcaf0;
        font-weight: 500;
        padding: .45em .9em;
        border-radius: 8px;
    }

    /* Botones más finos */
    .pending-orders-table .btn {
        border-width: 1px;
        padding: .25rem .45rem;
    }

    .top-products-table th,
    .top-products-table td {
        border-bottom: 1px solid #eef1f4;
    }

    .top-products-table tbody tr:last-child td {
        border-bottom: none;
    }

    .top-products-table tbody tr:hover {
        background-color: rgba(0,0,0,.015);
    }

    /* Badge cantidad vendida */
    .badge-soft-primary {
        background-color: rgba(13, 110, 253, 0.15);
        color: #0d6efd;
        font-weight: 600;
        padding: .4em .75em;
        border-radius: 8px;
    }
    .products-category-table th,
    .products-category-table td {
        border-bottom: 1px solid #eef1f4;
    }

    .products-category-table tbody tr:last-child td {
        border-bottom: none;
    }

    .products-category-table tbody tr:hover {
        background-color: rgba(0,0,0,.015);
    }

    /* Badge total productos */
    .badge-soft-success {
        background-color: rgba(25, 135, 84, 0.15);
        color: #198754;
        font-weight: 600;
        padding: .4em .75em;
        border-radius: 8px;
    }
    .recent-orders-table th,
    .recent-orders-table td {
        border-bottom: 1px solid #eef1f4;
    }

    .recent-orders-table tbody tr:last-child td {
        border-bottom: none;
    }

    .recent-orders-table tbody tr:hover {
        background-color: rgba(0,0,0,.015);
    }

    /* Badges soft por estado */
    .badge-soft-info {
        background-color: rgba(13, 202, 240, 0.15);
        color: #0dcaf0;
        font-weight: 500;
        padding: .45em .9em;
        border-radius: 8px;
    }

    .badge-soft-success {
        background-color: rgba(25, 135, 84, 0.15);
        color: #198754;
        font-weight: 500;
        padding: .45em .9em;
        border-radius: 8px;
    }

    .badge-soft-danger {
        background-color: rgba(220, 53, 69, 0.15);
        color: #dc3545;
        font-weight: 500;
        padding: .45em .9em;
        border-radius: 8px;
    }

    .badge-soft-secondary {
        background-color: rgba(108, 117, 125, 0.15);
        color: #6c757d;
        font-weight: 500;
        padding: .45em .9em;
        border-radius: 8px;
    }
    
</style>