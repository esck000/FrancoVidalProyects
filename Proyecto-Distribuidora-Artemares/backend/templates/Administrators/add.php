<?php
/**
 * Vista: Agregar Administrador
 * Estilo Artemares — conserva los campos originales (full_name, email, username, password)
 */
?>
<div class="container-fluid px-4">

    <?= $this->element('form_card', [
        'form' => $this->Form,
        'entity' => $administrator,
        'title' => 'Registrar nuevo Administrador',
        'icon' => 'bi-person-plus',
        'fields' => ['full_name', 'email', 'username', 'password'],
        'actionLabel' => 'Registrar'
    ]) ?>

</div>

