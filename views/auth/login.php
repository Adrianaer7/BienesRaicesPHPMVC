<main class="contenedor seccion contenido-centrado">
        <h1>Iniciar Sesión</h1>
        <?php foreach($errores as $error) {?>
            <div class="alerta error">
                <?php echo $error ?>
            </div>
        <?php } ?>
        <form method="POST" class="formulario" action="/login">  <!--novalidate es para que no me salgan las alertas de html por campo invalido-->
            <fieldset>
                <legend>Email y contraseña</legend>

                <label for="email">Email</label>
                <input 
                    type="email" 
                    placeholder="Tu email" 
                    id="email"
                    name="email"
                >

                <label for="password">Contraseña</label>
                <input 
                    type="password" 
                    placeholder="Tu contraseña" 
                    id="password"
                    name="password"
                >
            </fieldset>   
            <input 
                type="submit" 
                class="boton boton-verde"
                value="Iniciar Sesión"
            >
        </form>
    </main>