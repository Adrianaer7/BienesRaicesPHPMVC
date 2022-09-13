<main class="contenedor seccion contenido-centrado">
        <h1>Crea una nueva cuenta</h1>
        <?php foreach($errores as $error) {?>
            <div class="alerta error">
                <?php echo $error ?>
            </div>
        <?php } ?>
        <form method="POST" class="formulario">
            <fieldset>
                <legend>Datos de usuario</legend>

                <label for="nombre">Nombre</label>
                <input 
                    type="nombre" 
                    placeholder="Tu nombre" 
                    id="nombre"
                    name="nombre"
                    value="<?php echo s($usuario->nombre) ?>"

                >
                <label for="email">Email</label>
                <input 
                    type="email" 
                    placeholder="Tu email" 
                    id="email"
                    name="email"
                    value="<?php echo s($usuario->email) ?>"

                >

                <label for="password">Contraseña</label>
                <input 
                    type="password" 
                    placeholder="Tu contraseña" 
                    id="password"
                    name="password"
                    value="<?php echo s($usuario->password) ?>"

                >
                <label for="confirmar">Repetir contraseña</label>
                <input 
                    type="password" 
                    placeholder="Repite tu contraseña" 
                    id="confirmar"
                    name="confirmar_password"
                    value="<?php echo s($usuario->confirmar_password) ?>"

                >
            </fieldset>   
            <input 
                type="submit" 
                class="boton boton-verde"
                value="Crear cuenta"
            >
        </form>
        <p class="informacion-meta">O si ya tienes una cuenta, <a href="/login"><span>INICIA SESIÓN</span></a></p>
        
    </main>