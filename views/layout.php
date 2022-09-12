<?php 
    if(!isset($_SESSION)) {
        session_start();
    }
    $auth = $_SESSION["login"] ?? false;

    if(!isset($inicio)) {
        $inicio = false;
    }
    if(!isset($pagina)) {
        $pagina = "Admin";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "Bienes Raices - " . $pagina ?></title>
    <link rel="stylesheet" href="../build/css/app.css">
</head>
<body>
    <header class="header <?php echo $inicio ? "inicio" : "";?>"> <!--agrego la clase inicio si en el archivo php que importo este modulo tengo declarado una varibale $inicio-->
        <div class="contenedor contenido-header">
            <div class="barra">
                <a href="/">
                    <img src="/build/img/logo.svg" alt="Logo">
                </a>
                <div class="mobile-menu">
                    <img src="/build/img/barras.svg" alt="icono menu">
                </div>
                <div class="derecha">
                    <img class="dark-mode-boton" src="/build/img/dark-mode.svg" alt="dark mode">
                    <nav class="navegacion">
                        <a href="/nosotros">Nosotros</a>
                        <a href="/propiedades">Anuncios</a>
                        <a href="/blog">Blog</a>
                        <a href="/contacto">Contacto</a>
                        <?php if($auth) { ?>
                            <a href="/logout">Cerrar Sesión</a>
                        <?php } else { ?>
                            <a href="/login">Iniciar Sesión</a>
                        <?php } ?>
                    </nav>
                </div>
            </div>
            <?php echo  $inicio ? "<h1>Venta de casas y departamentos de lujo</h1>" : ""; ?>
        </div>
    </header>

    <?php echo $contenido; ?>   <!--recibo $contenido de la funcion render en el Router-->

    <footer class="footer seccion">
        <div class="contenedor contenido-footer">
            <nav class="navegacion">
                <a href="/nosotros">Nosotros</a>
                <a href="/propiedades">Anuncios</a>
                <a href="/blog">Blog</a>
                <a href="/contacto">Contacto</a>
            </nav>
        </div>

        <p class="copyright">Todos los derechos reservados <?php echo date("Y")?> &copy;</p>
    </footer>

    <script src="../build/js/bundle.min.js"></script> <!--para que soporte imagenes webp-->
</body>
</html>