<?php 
    namespace Controllers;
    use MVC\Router;
    use Model\Admin;

    class AuthController {
        public static function login(Router $router) {
            $errores = [];
            $pagina = "Login";
        
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                $auth = new Admin($_POST);
                $errores = $auth->validarLogin();

                if(empty($errores)) {
                    //Verificar si el usuario existe
                    $resultado = $auth->existeUsuario("logearse");
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

        public static function registro(Router $router) {
            $pagina = "Registro";
            $errores = [];

            if($_SERVER["REQUEST_METHOD"] === "POST") {
                $usuario = new Admin($_POST);
                debugear($usuario);
                $errores = $usuario->validarRegistro();

                if(empty($errores)) {
                    $resultado = $usuario->existeUsuario("registrarse");
                    if($resultado) {
                        $errores = Admin::getErrores();
                    }
                }
            }
            $router->render("auth/registro", [
                "pagina" => $pagina,
                "errores" => $errores,
                "usuario" => $usuario
            ]);
        }
    }
?>