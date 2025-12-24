<?php
/**
 * Vista: Inicio de sesión de Administrador
 * Estilo Artemares — simple, centrado y limpio
 */
?>
<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh; background: #f6f9fc;">
    <div class="card shadow-sm border-0" style="width: 400px; border-radius: 12px;">
        <div class="card-header text-center bg-white border-0 py-4">
            <?= $this->Html->image('Logosinfondo.png', [
                'alt' => 'Artemares',
                'style' => 'max-width: 160px; filter: drop-shadow(0 0 6px rgba(0,160,255,0.3));'
            ]) ?>
            <h4 class="fw-semibold mt-3 mb-0" style="color: #009FE3;">
                <i class="bi bi-shield-lock me-2"></i> Iniciar sesión
            </h4>
            <p class="text-muted small mb-0">Panel de Administración</p>
        </div>

        <div class="card-body px-4 py-4">
            <?= $this->Flash->render() ?>

            <?= $this->Form->create(null, ['class' => 'needs-validation']) ?>
                <div class="mb-3">
                    <?= $this->Form->control('username', [
                        'label' => 'Usuario',
                        'class' => 'form-control',
                        'required' => true,
                        'placeholder' => 'Ingresa tu usuario'
                    ]) ?>
                </div>
                <div class="mb-3">
                    <?= $this->Form->control('password', [
                        'label' => 'Contraseña',
                        'class' => 'form-control',
                        'required' => true,
                        'placeholder' => '••••••••'
                    ]) ?>
                </div>

                <div class="d-grid mt-4">
                    <?= $this->Form->button(
                        '<i class="bi bi-box-arrow-in-right me-1"></i> Entrar',
                        ['escapeTitle' => false, 'class' => 'btn btn-primary py-2 fw-semibold']
                    ) ?>
                </div>
            <?= $this->Form->end() ?>
        </div>

        <div class="card-footer text-center bg-white border-0 pb-4">
            <?= $this->Html->link(
                '<i class="bi bi-person-plus me-1"></i> Registrar nuevo administrador',
                ['action' => 'add'],
                ['escape' => false, 'class' => 'text-decoration-none', 'style' => 'color:#009FE3; font-weight:500;']
            ) ?>
        </div>
    </div>
</div>
