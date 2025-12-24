<?php
/**
 * Vista: Editar Receta
 * Usa la plantilla form_card genérica (estilo Artemares)
 */
?>
<div class="container-fluid px-4">

    <?= $this->element('form_card', [
        'form' => $this->Form,
        'entity' => $recipe,
        'title' => 'Editar Receta',
        'icon' => 'bi-egg-fried',
        'fields' => [
            'name',
            'description',
            'ingredients',
            'image_file',
            'products._ids' // Productos asociados
        ],
        'actionLabel' => 'Actualizar',
        'showDelete' => false,
        'categories' => $products // lista de productos para el selector múltiple
    ]) ?>

</div>
