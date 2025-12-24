<?php
/**
 * Vista: Editar Producto
 * Incluye edición de información nutricional dentro del mismo formulario
 */
?>

<?= $this->element('form_card', [
    'form' => $this->Form,
    'entity' => $product,
    'title' => 'Editar Producto',
    'icon' => 'bi-pencil-square',
    'fields' => [
        'name',
        'description',
        'price',
        'stock',
        'unit_quantity',
        'unit',
        'category_id',
        'image_file',

        // --- Campos nutricionales integrados ---
        'nutritional_information.measurement',
        'nutritional_information.calories',
        'nutritional_information.protein',
        'nutritional_information.total_fat',
        'nutritional_information.carbohydrates',
        'nutritional_information.sodium',
        'nutritional_information.cholesterol'
    ],
    'actionLabel' => 'Actualizar',
    'showDelete' => false,
    'categories' => $categories ?? []
]) ?>
