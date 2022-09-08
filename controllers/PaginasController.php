<?php 
    namespace Controllers;
    use MVC\Router;
    use Model\Propiedad;

    class PaginasController {
        public static function index(Router $router) {
            $propiedades = Propiedad::get(3);   //traigo solo 3 como max
            $inicio = true;
            $router->render("paginas/index", [
                "propiedades" => $propiedades,
                "inicio" => $inicio
            ]);
        }
        public static function nosotros() {
            echo "Index";
        }
        public static function propiedades() {
            echo "Index";
        }
        public static function propiedad() {
            echo "Index";
        }
        public static function blog() {
            echo "Index";
        }
        public static function entrada() {
            echo "Index";
        }
        public static function contacto() {
            echo "Index";
        }
    }

?>