<?php
/**
 * Vista: Añadir Categoría
 * Usa el elemento form_card.php estilo Artemares
 */
?>

<?= $this->element('form_card', [
    'form' => $this->Form,
    'entity' => $category,
    'fields' => ['name'],
    'title' => 'Añadir Categoría',
    'icon' => 'bi-plus-circle',
    'actionLabel' => 'Guardar',
    'showDelete' => false
]) ?>
