<?php
/**
 * Vista: Editar Categoría
 * Usa el elemento form_card.php estilo Artemares
 */
?>

<?= $this->element('form_card', [
    'form' => $this->Form,
    'entity' => $category,
    'fields' => ['name'],
    'title' => 'Editar Categoría',
    'icon' => 'bi-pencil-square',
    'actionLabel' => 'Actualizar',
]) ?>
