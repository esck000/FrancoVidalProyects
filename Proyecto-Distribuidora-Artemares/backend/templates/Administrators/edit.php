<?php
/**
 * Vista: Editar Administrador
 * Estilo Artemares — coherente con el resto del panel
 */
?>
<div class="container-fluid px-4">

    <?= $this->element('form_card', [
        'form' => $this->Form,
        'entity' => $administrator,
        'title' => 'Editar Administrador',
        'icon' => 'bi-person-gear',
        'fields' => ['full_name', 'email', 'username', 'password'],
        'actionLabel' => 'Actualizar',
    ]) ?>

</div>
