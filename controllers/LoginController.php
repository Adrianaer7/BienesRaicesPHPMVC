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
                    $resultado = $auth->existeUsuario();
                    if(!$resultado) {
                        $errores = Admin::getErrores();
                    } else {
                        //Verificar la contraseña
                        $autenticado = $auth->comprobarPassword($resultado);
                        if(!$autenticado) {
                            $errores = Admin::getErrores();
                        } else {
                            //Autenticar al usuario
                            $auth->autenticar();
                        }
                    }
                }
            }

            $router->render("auth/login", [
                "pagina" => $pagina,
                "errores" => $errores
            ]);
        }
        public static function logout(Router $router) {
            session_start();
            $_SESSION = [];

            header("Location: /");
        }
    }
?>