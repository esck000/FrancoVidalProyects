<!DOCTYPE html>
<html lang="es">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->fetch('title') ?> | Panel Administrador - Artemares</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

    <!-- Iconos -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/fonts/bootstrap-icons.woff2"
          as="font" type="font/woff2" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Preload del logo para evitar “salto” durante render -->
    <link rel="preload" as="image" href="<?= $this->Url->image('Logosinfondo.png') ?>">

    <style>
        /* ===============================================================
           ESTILOS BASE
           =============================================================== */

        html, body {
            background-color: #F7FAFC;
            font-family: "Segoe UI", sans-serif;
            color: #212529;
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        /* Oculta transiciones globales hasta carga completa */
        body:not(.body-loaded) * {
            transition: none !important;
        }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background-color: #0E1B2B;
        }

        /* Contenedor del logo: espacio fijo evita contracción */
        .sidebar .navbar-brand {
            width: 100%;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 2rem;
            margin-top: 1rem;
        }

        /* Dimensiones fijas del logo evitan el "salto" inicial */
        .sidebar .navbar-brand img {
            display: block;
            max-height: 40px;
            width: auto;
            object-fit: contain;
            pointer-events: none;
            user-select: none;
        }

        .sidebar a {
            color: #E9EEF2;
            text-decoration: none;
            display: block;
            padding: 12px 18px;
            border-radius: 4px;
            margin: 4px 0;
            font-weight: 500;
        }

        .sidebar a.active {
            background-color: #009FE3;
            color: #fff;
        }

        .sidebar a:hover {
            background-color: #007ab3;
            color: #fff;
        }

        /* Navbar superior: altura fija y sin reflow */
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #dee2e6;
            min-height: 64px;
            display: flex;
            align-items: center;
        }

        .navbar-text {
            color: #4a5568;
            font-size: 1rem;
        }

        /* Botones */
        .btn-primary {
            background-color: #009FE3;
            border: none;
        }

        .btn-primary:hover {
            background-color: #007ab3;
        }

        /* Tablas */
        .table thead {
            background-color: #009FE3;
            color: white;
        }

        main {
            padding: 2rem;
        }
    </style>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<body>
    <!-- Script para habilitar transiciones solo tras carga completa -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.body.classList.add("body-loaded");
        });
    </script>

    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar d-flex flex-column p-3">
            <div class="navbar-brand user-select-none">
                <?= $this->Html->image('Logosinfondo.png', [
                    'alt' => 'Artemares',
                    'style' => '
                        max-height: 40px;
                        width: auto;
                        object-fit: contain;
                    '
                ]) ?>
            </div>
            
            <a href="<?= $this->Url->build(['controller' => 'Dashboard', 'action' => 'index']) ?>"
                class="<?= $this->request->getParam('controller') === 'Dashboard' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>        

            <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'index']) ?>"
               class="<?= $this->request->getParam('controller') === 'Products' ? 'active' : '' ?>">
               <i class="bi bi-box-seam me-2"></i>Productos
            </a>

            <a href="<?= $this->Url->build(['controller' => 'Categories', 'action' => 'index']) ?>"
               class="<?= $this->request->getParam('controller') === 'Categories' ? 'active' : '' ?>">
               <i class="bi bi-tags me-2"></i>Categorías
            </a>

            <a href="<?= $this->Url->build(['controller' => 'Recipes', 'action' => 'index']) ?>"
               class="<?= $this->request->getParam('controller') === 'Recipes' ? 'active' : '' ?>">
               <i class="bi bi-journal-text me-2"></i>Recetas
            </a>

            <a href="<?= $this->Url->build(['controller' => 'Orders', 'action' => 'index']) ?>"
               class="<?= $this->request->getParam('controller') === 'Orders' ? 'active' : '' ?>">
               <i class="bi bi-cart-check me-2"></i>Pedidos
            </a>

            <a href="<?= $this->Url->build(['controller' => 'CashBalances', 'action' => 'index']) ?>"
                class="<?= $this->request->getParam('controller') === 'CashBalances' ? 'active' : '' ?>">
                <i class="bi bi-cash-stack me-2"></i>Cuadratura de Caja
            </a>

            <a href="<?= $this->Url->build(['controller' => 'Administrators', 'action' => 'index']) ?>"
               class="<?= $this->request->getParam('controller') === 'Administrators' ? 'active' : '' ?>">
               <i class="bi bi-person-gear me-2"></i>Administrador
            </a>
        </nav>

        <!-- Contenido principal -->
        <div class="flex-grow-1">
            <nav class="navbar navbar-expand-lg shadow-sm px-4">
                <div class="container-fluid d-flex justify-content-between align-items-center">
                    <span class="navbar-text mb-0">
                        <i class="bi bi-speedometer2 me-2"></i>Panel de Administración
                    </span>
                    <div class="d-flex align-items-center">
                        <span class="text-secondary me-3">
                            <i class="bi bi-person-circle me-1"></i>Admin
                        </span>
                        <?= $this->Form->postLink(
                            '<i class="bi bi-box-arrow-right"></i> Salir',
                            ['controller' => 'Administrators', 'action' => 'logout'],
                            [
                                'class' => 'btn btn-outline-danger btn-sm',
                                'escape' => false,
                                'confirm' => '¿Seguro que quieres cerrar sesión?'
                            ]
                        ) ?>
                    </div>
                </div>
            </nav>

            <main>
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </main>
        </div>
    </div>
</body>
</html>
