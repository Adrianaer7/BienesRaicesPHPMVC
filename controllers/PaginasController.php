<?php 
    namespace Controllers;

    use MVC\Router;

    class PaginasController {
        public static function index(Router $router) {
            $router->render("paginas/index", [
                
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