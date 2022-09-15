<div class="contenedor contenido-centrado">
    <h3>Reestablece tu contraseña</h3>
    <form method="POST" class="formulario" action="<?php echo $url?>">
        <label for="nombre">Nueva contraseña</label>
        <input 
            type="password" 
            placeholder="Tu contraseña nueva" 
            id="password"
            name="password"
            autocomplete="off"
            value="<?php echo s($usuario->password) ?>"
        >

        <label for="nombre">Repetir contraseña</label>
        <input 
            type="password" 
            placeholder="Repite la contraseña nueva" 
            id="confirmar_password"
            name="confirmar_password"
            autocomplete="off"
            value="<?php echo s($usuario->confirmar_password) ?>"
        >
        <input 
                type="submit" 
                class="boton boton-verde"
                value="Guardar nueva contraseña"
            >
    </form>
</div>
