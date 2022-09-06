<?php 
    //si no se inicio sesion porque no se importó ni ejecutó estadoAutenticado(), la inicio manualmente
    if(!isset($_SESSION)) { //la sesion se inicia en este header y en la pagina que importe la funcion estadoAutenticado(). Si se importa acá y en esa pagina, lanza un msj indicandote que ya estaba inciada la sesion. Entonces con este if, la sesion se va a iniciar solamente si no hay una sesion iniciada previamente
        session_start();
    }
    $auth = $_SESSION["login"] ?? false; //devuelve false si el usuario no está autenticado
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pagina ?></title>
    <link rel="stylesheet" href="/build/css/app.css">
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
                        <a href="nosotros.php">Nosotros</a>
                        <a href="anuncios.php">Anuncios</a>
                        <a href="blog.php">Blog</a>
                        <a href="contacto.php">Contacto</a>
                        <?php if($auth) { ?>
                            <a href="/cerrar-sesion.php">Cerrar Sesión</a>
                        <?php } else { ?>
                            <a href="/login.php">Iniciar Sesión</a>
                        <?php } ?>
                    </nav>
                </div>
            </div>
            <?php echo  $inicio ? "<h1>Venta de casas y departamentos de lujo</h1>" : ""; ?>
        </div>
    </header>