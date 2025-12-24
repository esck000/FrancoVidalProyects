<?php
/**
 * Vista: Agregar Producto
 */
?>

<?= $this->element('form_card', [
    'form' => $this->Form,
    'entity' => $product,
    'title' => 'Agregar Producto',
    'icon' => 'bi-plus-circle',
    'fields' => [
        'name',
        'description',
        'price',
        'stock',
        'unit_quantity',
        'unit',
        'category_id',
        'image_file',

        // --- Información Nutricional ---
        'nutritional_information.measurement',
        'nutritional_information.calories',
        'nutritional_information.protein',
        'nutritional_information.total_fat',
        'nutritional_information.carbohydrates',
        'nutritional_information.sodium',
        'nutritional_information.cholesterol'
    ],
    'actionLabel' => 'Guardar',
    'showDelete' => false,
    'categories' => $categories ?? []
]) ?>
