<?php
use Cake\I18n\FrozenDate;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CashBalance $cashBalance
 * @var float $calculatedExpected
 */
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Cuadratura de caja</h5>
                </div>

                <div class="card-body">

                    <!-- 🤖 Monto calculado por el sistema -->
                    <div class="alert alert-info" id="expected-amount-box">
                        <strong>Monto total calculado por el sistema:</strong><br>
                        <span id="expected-amount">
                            <?= $this->Number->currency($calculatedExpected ?? 0, 'CLP') ?>
                        </span>
                        <small class="d-block text-muted mt-1">
                            Basado en los precios actuales del sistema
                        </small>
                    </div>

                    <?= $this->Form->create($cashBalance) ?>

                    <!-- 📅 Fecha -->
                    <div class="mb-3">
                        <?= $this->Form->control('balance_date', [
                            'label' => 'Fecha de la cuadratura',
                            'type' => 'date',
                            'max' => FrozenDate::today()->format('Y-m-d'),
                            'required' => true,
                            'class' => 'form-control'
                        ]) ?>
                    </div>

                    <!-- ✏️ Expected amount editable -->
                    <div class="mb-3">
                        <?= $this->Form->control('expected_amount', [
                            'label' => 'Monto esperado (opcional)',
                            'type' => 'number',
                            'step' => '0.01',
                            'class' => 'form-control',
                            'placeholder' => 'Si lo deja vacío, se usará el monto calculado por el sistema'
                        ]) ?>
                    </div>

                    <!-- 💰 Monto real -->
                    <div class="mb-3">
                        <?= $this->Form->control('actual_amount', [
                            'label' => 'Monto real en caja',
                            'type' => 'number',
                            'step' => '0.01',
                            'required' => true,
                            'class' => 'form-control'
                        ]) ?>
                    </div>

                    <!-- 📝 Observaciones -->
                    <div class="mb-3">
                        <?= $this->Form->control('description', [
                            'label' => 'Observaciones',
                            'type' => 'textarea',
                            'rows' => 3,
                            'class' => 'form-control'
                        ]) ?>
                    </div>

                    <!-- 💾 Guardar -->
                    <div class="d-grid">
                        <?= $this->Form->button('Guardar cuadratura', [
                            'class' => 'btn btn-success'
                        ]) ?>
                    </div>

                    <?= $this->Form->end() ?>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ⚙️ JS para recalcular monto calculado -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const dateInput = document.querySelector('#balance-date');
    const expectedAmountSpan = document.querySelector('#expected-amount');

    if (!dateInput || !expectedAmountSpan) return;

    dateInput.addEventListener('change', function () {
        const date = this.value;
        if (!date) return;

        fetch(`/cash-balances/expected-amount-by-date?date=${date}`)
            .then(res => res.json())
            .then(data => {
                const amount = data.expectedAmount ?? 0;
                expectedAmountSpan.innerText =
                    new Intl.NumberFormat('es-CL', {
                        style: 'currency',
                        currency: 'CLP'
                    }).format(amount);
            })
            .catch(() => {
                expectedAmountSpan.innerText = '$0';
            });
    });
});
</script>
