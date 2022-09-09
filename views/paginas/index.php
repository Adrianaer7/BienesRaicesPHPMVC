<main class="contenedor seccion">
        <h1>Más sobre nosotros</h1>
        <?php include "iconos.php"; ?>
        </div>
    </main>

    <section class="contenedor seccion">
        <h2>Casas y Departamentos en venta</h2>
        <?php include "listado.php"; ?>

        <div class="alinear-derecha">
            <a href="propiedades"  class="boton-verde">Ver todas</a>
        </div>
    </section>

    <section class="imagen-contacto">
        <h2>Encuentra la casa de tus sueños</h2>
        <p>Llena el formulario de contacto y un asesor se contactará contigo para mas informacion</p>
        <a href="contacto" class="boton-amarillo">Contáctanos</a>
    </section>

    <div class="contenedor seccion seccion-inferior">
        <section class="blog">
            <h3>Nuestro blog</h3>
            <article class="entrada-blog">
                <div class="imagen">
                    <picture>
                        <source srcset="build/img/blog1.webp" type="image/webp">
                        <source srcset="build/img/blog1.jpeg" type="image/jpeg">
                        <img src="build/img/blog1.jpg" alt="entrada blog" loading="lazy">
                    </picture>
                </div>
                <div class="texto-entrada">
                    <a href="entrada">
                        <h4>Terraza en el techo de tu casa</h4>
                        <p class="informacion-meta">Escrito el: <span>04/08/2022</span> por <span>Admin</span></p>
                        <p>Consejos para construir una terraza en el techo de tu casa con los mejores materiales</p>
                    </a>
                </div>
            </article>
            <article class="entrada-blog">
                <div class="imagen">
                    <picture>
                        <source srcset="build/img/blog2.webp" type="image/webp">
                        <source srcset="build/img/blog2.jpeg" type="image/jpeg">
                        <img src="build/img/blog2.jpg" alt="entrada blog" loading="lazy">
                    </picture>
                </div>
                <div class="texto-entrada">
                    <a href="entrada">
                        <h4>Guía para la decoracion de tu hogar</h4>
                        <p class="informacion-meta">Escrito el: <span>04/08/2022</span> por <span>Admin</span></p>
                        <p>Consejos para mejorar la decoracion de tu hogar de la manera mas facil y menos costosa que puedas ver</p>
                    </a>
                </div>
            </article>
        </section>
        <section class="testimoniales">
            <h3>Testimoniales</h3>
            <div class="testimonial">
                <blockquote>
                    El personal se comportó de una excelente forma , muy buena atención y la casa que me dieron cumple con todas mis expectativas
                </blockquote>
                <p>- Adrian Roldan</p>
            </div>
        </section>
    </div>