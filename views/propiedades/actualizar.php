<main class="contenedor seccion">
        <h1>Actualizar propiedad</h1>
        <a href="/admin" class="boton boton-verde">Volver</a>
        <?php foreach($errores as $error) { ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php } ?>
        <form 
            class="formulario"
            method="POST"
            enctype="multipart/form-data"
        >
            <?php  include __DIR__ . "/formulario.php"; ?>
            <input 
                type="submit" 
                class="boton boton-verde"
                value="Guardar cambios" 
            >
        </form>
</main>