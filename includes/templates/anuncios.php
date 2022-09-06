<?php 
    use App\Propiedad;

    
    if($_SERVER["SCRIPT_NAME"] == "/anuncios.php") {    //para saber en que url estoy parado
        $propiedades = Propiedad::all();
    } else {
        $propiedades = Propiedad::get(3);

    }
?>


<div class="contenedor-anuncios">
    <?php foreach($propiedades as $propiedad) { ?>
        <div class="anuncio">
            <div class="contenido-superior-anuncio">
                <picture>
                    <source srcset="build/img/anuncio3.webp" type="image/webp">
                    <source srcset="build/img/anuncio3.jpeg" type="image/jpeg">
                    <img src="/imagenes/<?php echo $propiedad->imagen?>" alt="imagen anuncio" loading="lazy">
                </picture>
        
                <div class="titulo-descripcion">
                    <h3><?php echo $propiedad->titulo?></h3>
                    <p><?php echo $propiedad->descripcion?></p>
                </div>
            </div>
            <div class="contenido-anuncio">
                <p class="precio">$<?php echo $propiedad->precio?></p>
                <ul class="iconos-caracteristicas">
                    <li>
                        <img class="icono" src="build/img/icono_wc.svg" alt="icono wc" loading="lazy">
                        <p><?php echo $propiedad->wc?></p>
                    </li>
                    <li>
                        <img class="icono" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento" loading="lazy">
                        <p><?php echo $propiedad->estacionamiento?></p>
                    </li>
                    <li>
                        <img class="icono" src="build/img/icono_dormitorio.svg" alt="icono dormitorio" loading="lazy">
                        <p><?php echo $propiedad->habitaciones?></p>
                    </li>
                </ul>    
                <a href="anuncio.php?id=<?php echo $propiedad->id?>" class="boton boton-amarillo-block">Ver propiedad</a>
            </div>
        </div>
    <?php } ?>
</div>