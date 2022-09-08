<fieldset>
    <legend>Informacion general</legend>

    <label for="nombre">Nombre:</label>
    <input 
        type="text" 
        name="vendedor[nombre]"
        id="nombre" 
        placeholder="Nombre vendedor" 
        value="<?php echo s($vendedor->nombre); ?>"
    >

    <label for="apellido">Apellido:</label>
    <input 
        type="text" 
        name="vendedor[apellido]" 
        id="apellido" 
        placeholder="Apellido vendedor" 
        value="<?php echo s($vendedor->apellido) ?>"
    >    
</fieldset>

<fieldset>
    <legend>Informacion extra</legend>

    <label for="telefono">Telefono:</label>
    <input 
        type="number" 
        name="vendedor[telefono]" 
        id="telefono" 
        placeholder="Telefono vendedor" 
        value="<?php echo s($vendedor->telefono) ?>"
    >
</fieldset>