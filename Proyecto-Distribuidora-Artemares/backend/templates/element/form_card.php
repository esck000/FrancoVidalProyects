<?php
/**
 * Elemento genérico para formularios (estilo Artemares)
 * Compatible con controladores base (Bake)
 */

$fieldOptions = $fieldOptions ?? [];

/**
 * Diccionario de etiquetas en español
 */
$labels = [

    // --- Productos ---
    'name' => 'Nombre',
    'description' => 'Descripción',
    'price' => 'Precio',
    'stock' => 'Stock',
    'unit_quantity' => 'Cantidad por unidad',
    'unit' => 'Unidad',
    'category_id' => 'Categoría',

    // --- Recetas ---
    'ingredients' => 'Ingredientes',
    'products._ids' => 'Productos relacionados',
    'products-ids' => 'Productos relacionados',
    'image_file' => 'Imagen asociada',

    // --- Información nutricional ---
    'measurement' => 'Medición',
    'calories' => 'Calorías',
    'protein' => 'Proteína',
    'total_fat' => 'Grasas Totales',
    'carbohydrates' => 'Carbohidratos',
    'sodium' => 'Sodio',
    'cholesterol' => 'Colesterol',

    // --- Administradores ---
    'full_name' => 'Nombre completo',
    'email' => 'Correo electrónico',
    'username' => 'Usuario',
    'password' => 'Contraseña',
    
    // --- Cuadratura de caja
    'expected_amount'=>'Monto esperado',
    'actual_amount'=>'Monto actual',
    'description'=>'Descripción',
];
?>

<style>
.select-multiple {
    width: 100%;
    border: 1px solid #ced4da;
    border-radius: 6px;
    padding: 0.5rem;
    min-height: 100px;
    background-color: #fff;
    transition: box-shadow 0.2s ease-in-out;
}
.select-multiple:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 159, 227, 0.25);
    outline: none;
}
</style>

<div class="card shadow-sm border-0 mb-4">

    <!-- Header -->
    <div class="card-header bg-white border-0 pb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-semibold" style="color:#009FE3;">
                <?php if (!empty($icon)): ?>
                    <i class="bi <?= h($icon) ?> me-2"></i>
                <?php endif; ?>
                <?= h($title ?? 'Formulario') ?>
            </h4>

            <?= $this->Html->link(
                '<i class="bi bi-arrow-left"></i> Volver',
                ['action' => 'index'],
                ['escape' => false, 'class' => 'btn btn-outline-secondary btn-sm']
            ) ?>
        </div>

        <div style="
            height:3px;
            margin-top:10px;
            border-radius:2px;
            background:linear-gradient(90deg,#009FE3 0%,#4CC3FF 100%);
        "></div>
    </div>

    <!-- Body -->
    <div class="card-body">
        <?= $form->create($entity, ['class' => 'row g-3', 'type' => 'file']) ?>

        <?php foreach ($fields as $field): ?>

            <?php
                // ----------------------------
                // SOPORTE PARA CAMPOS ANIDADOS
                // ----------------------------
                if (str_ends_with($field, '._ids')) {

                    $label = $labels[$field] ?? 'Seleccionar';

                    $options = [
                        'type' => 'select',
                        'multiple' => true,
                        'options' => $categories ?? [],
                        'class' => 'select-multiple',
                        'label' => $label
                    ];
                }
                else if (strpos($field, '.') !== false) {

                    $parts = explode('.', $field);
                    $entityName = $parts[0];
                    $realField  = $parts[1];

                    $label = $labels[$realField] ?? ucfirst(str_replace('_', ' ', $realField));

                    $options = [
                        'class' => 'form-control',
                        'label' => $label,
                        'name' => "{$entityName}[{$realField}]"
                    ];

                    if (!empty($entity->{$entityName}) && isset($entity->{$entityName}->{$realField})) {
                        $options['value'] = $entity->{$entityName}->{$realField};
                    }

                } else {

                    $label = $labels[$field] ?? ucfirst(str_replace('_',' ', $field));

                    $options = [
                        'class' => 'form-control',
                        'label' => $label
                    ];

                    if (str_contains($field, 'description') || str_contains($field, 'ingredients')) {
                        $options['type'] = 'textarea';
                        $options['rows'] = 4;

                    } elseif (str_contains($field, 'category')) {
                        $options['options'] = $categories ?? [];
                        $options['empty'] = 'Selecciona una categoría';

                    } elseif (str_contains($field, 'image') || $field === 'image_file') {

                        $options['type'] = 'file';
                        $options['accept'] = 'image/*';

                        $hasImg = !empty($entity->product_image?->image_medium)
                                  || !empty($entity->recipe_image?->image_medium);

                        $options['after'] = $hasImg
                            ? '<small class="text-muted">Sube una nueva imagen para reemplazar la actual.</small>'
                            : '<small class="text-muted">Selecciona una imagen para subir.</small>';
                    }
                }
            ?>

            <div class="col-md-6">
                <?= $form->control($field, $options) ?>
            </div>

        <?php endforeach; ?>

        <!-- ================== IMAGEN ACTUAL (CORREGIDA) ================== -->
        <?php
            $image = $entity->product_image->image_medium
                ?? $entity->recipe_image->image_medium
                ?? null;

            if (is_resource($image)) {
                $imageData = base64_encode(stream_get_contents($image));
            } elseif (is_string($image)) {
                $imageData = base64_encode($image);
            } else {
                $imageData = null;
            }

            $mimeType = $entity->product_image->mime_type_medium
                ?? $entity->recipe_image->mime_type_medium
                ?? 'image/jpeg';
        ?>

        <?php if ($imageData): ?>
            <div class="col-md-6">
                <label class="form-label fw-semibold text-secondary">Imagen actual</label>

                <div class="border rounded p-2 bg-light text-center">
                    <img
                        src="data:<?= h($mimeType) ?>;base64,<?= $imageData ?>"
                        class="img-fluid rounded"
                        style="max-width:100%;height:auto;"
                    />
                </div>

                <div class="form-check mt-2">
                    <?= $form->checkbox('remove_image', [
                        'class' => 'form-check-input',
                        'id' => 'removeImage'
                    ]) ?>
                    <label for="removeImage" class="form-check-label text-muted">
                        Eliminar imagen actual
                    </label>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-12 d-flex justify-content-end mt-4">

            <?php if (!empty($showDelete) && $showDelete): ?>
                <?= $form->postLink(
                    '<i class="bi bi-trash"></i> Eliminar',
                    ['action' => 'delete', $entity->id],
                    [
                        'escape' => false,
                        'class' => 'btn btn-outline-danger me-auto px-4',
                        'confirm' => '¿Seguro que deseas eliminar este registro?'
                    ]
                ) ?>
            <?php endif; ?>

            <?= $form->button(
                '<i class="bi bi-save"></i> ' . ($actionLabel ?? 'Guardar'),
                ['escapeTitle' => false, 'class' => 'btn btn-primary px-4']
            ) ?>
        </div>

        <?= $form->end() ?>
    </div>
</div>
