<?php
/**
 * Vista: Editar Información Nutricional
 * Usa la plantilla form_card genérica con estilo Artemares
 */
?>

<div class="container-fluid px-4">
    <?= $this->element('form_card', [
        'form' => $this->Form,
        'entity' => $nutritionalInformation,
        'title' => 'Editar Información Nutricional',
        'icon' => 'bi-clipboard-data',
        'fields' => [
            'product_id',
            'measurement',
            'calories',
            'protein',
            'total_fat',
            'carbohydrates',
            'sodium',
            'cholesterol'
        ],
        'actionLabel' => 'Actualizar',
    ]) ?>
</div>
