<h1 class="nombre-pagina">Olvidaste tu password</h1>
<p class="descripcion-pagina">Restablece tu password llenando los campos</p>

<?php include_once __DIR__ . '/../templates/alertas.php' ?>

<form action="/olvide" method='POST' class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id='email' placeholder='Tu Email' name='email'>
    </div>

    <input type="submit" value="Restablecer password" class='boton'>
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesion</a>
    <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crea una</a>
</div>