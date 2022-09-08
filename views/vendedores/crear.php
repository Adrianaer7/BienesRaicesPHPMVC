<main class="contenedor seccion">
        <h1>Crear vendedor</h1>
        <a href="/admin" class="boton boton-verde">Volver</a>
        <?php foreach($errores as $error) { ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php } ?>
        <form 
            class="formulario"
            method="POST"
        >
            <?php  include __DIR__ . "/formulario.php"; ?>
            <input 
                type="submit" 
                class="boton boton-verde"
                value="Crear vendedor" 
            >
        </form>
</main>