<?php
/**
 * Vista: Agregar Receta
 * Usa la plantilla form_card genérica (estilo Artemares)
 */
?>
<div class="container-fluid px-4">

    <?= $this->element('form_card', [
        'form' => $this->Form,
        'entity' => $recipe,
        'title' => 'Agregar Receta',
        'icon' => 'bi-journal-plus',
        'fields' => [
            'name',
            'description',
            'ingredients',
            'image_file',
            'products._ids' // campo para seleccionar productos asociados
        ],
        'actionLabel' => 'Guardar',
        'categories' => $products // lista de productos (reutilizamos esta variable)
    ]) ?>

</div>
