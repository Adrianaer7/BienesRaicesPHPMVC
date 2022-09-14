<?php 

    namespace Controllers;

    use Classes\EmailContacto;
    use MVC\Router;
    use Model\Propiedad;

    class PaginasController {
        public static function index(Router $router) {
            $propiedades = Propiedad::get(3);   //traigo solo 3 como max
            $pagina = "Inicio";
            $inicio = true;

            $router->render("paginas/index", [
                "propiedades" => $propiedades,
                "pagina" => $pagina,
                "inicio" => $inicio
            ]);
        }


        public static function nosotros(Router $router) {
            $pagina = "Nosotros";

            $router->render("paginas/nosotros", [
                "pagina" => $pagina
            ]);
        }


        public static function propiedades(Router $router) {
            $propiedades = Propiedad::all();
            $pagina = "Anuncios";

            $router->render("paginas/propiedades", [
                "propiedades" => $propiedades,
                "pagina" => $pagina
            ]);
        }


        public static function propiedad(Router $router) {
            $id = validarOredireccionar("/propiedades");
            $propiedad = Propiedad::find($id);
            $pagina = "Propiedad";

            $router->render("paginas/propiedad", [
                "propiedad" => $propiedad,
                "pagina" => $pagina
            ]);
        }


        public static function blog(Router $router) {
            $pagina = "Blog";

            $router->render("paginas/blog", [
                "pagina" => $pagina
            ]);
        }


        public static function entrada(Router $router) {
            $pagina = "Entrada";

            $router->render("paginas/entrada", [
                "pagina" => $pagina
            ]);
        }


        public static function contacto(Router $router) {
            $pagina = "Contacto";
            $mensaje = null;

            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = new EmailContacto($_POST["contacto"]);
                $email->enviarFormularioContacto();
            }  
            
            $router->render("paginas/contacto", [
                "pagina" => $pagina,
                "mensaje" => $mensaje
            ]);
        }
    }

?>