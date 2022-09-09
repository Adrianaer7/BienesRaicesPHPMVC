<?php 
    namespace Controllers;
    use MVC\Router;
    use Model\Admin;

    class LoginController {
        public static function login(Router $router) {
            $errores = [];
            $pagina = "Login";
        
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                $auth = new Admin($_POST);
                $errores = $auth->validar();

                if(empty($errores)) {
                    //Verificar si el usuario existe

                    //Verificar la contraseña

                    //Autenticar al usuario
                }
            }

            $router->render("auth/login", [
                "pagina" => $pagina,
                "errores" => $errores
            ]);
        }
        public static function logout(Router $router) {
            echo "2";
        }
    }
?>