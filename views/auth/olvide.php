<div class="contenedor contenido-centrado">
    <h3>Olvidé mi contraseña</h3>
    <form method="POST" class="formulario" action="/olvide">
        <label for="nombre">Email</label>
        <input 
            type="email" 
            placeholder="Tu email" 
            id="email"
            name="email"
            autocomplete="off"
            value="<?php echo s($usuario->email) ?>"
        >
        <input 
                type="submit" 
                class="boton boton-verde"
                value="Enviar email"
            >
    </form>
</div>
