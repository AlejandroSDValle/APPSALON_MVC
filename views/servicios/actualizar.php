<h1 class="nombre-pagina">Actualizar Servicio</h1>
<p class="descripcion-pagina">Actualiza tus servicios</p>

<?php

include_once __DIR__ . '/../templates/alertas.php';
include_once __DIR__ . '/../templates/barra.php';
?>

<form method='POST' class='formulario'>
    
    <?php include_once __DIR__ . '/formulario.php'; ?>
    <input type="submit" value="Actualizar servicio" class='boton'>
</form>