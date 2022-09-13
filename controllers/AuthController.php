<?php 
    namespace Controllers;
    use MVC\Router;
    use Model\Admin;

    class AuthController {
        public static function login(Router $router) {
            $errores = [];
            $pagina = "Login";
        
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                //Creo la instancia
                $auth = new Admin($_POST);

                //Valido campos
                $errores = $auth->validarLogin();

                if(empty($errores)) {
                    //Verificar si el usuario existe
                    $resultado = $auth->existeUsuario("logearse");

                    //Si el usuario no existe
                    if(!$resultado) {
                        $errores = Admin::getErrores();
                    } else {
                        //Si existe, verificar la contraseña
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
        public static function logout() {
            session_start();
            $_SESSION = [];

            header("Location: /");
        }

        public static function registro(Router $router) {
            $pagina = "Registro";
            $errores = [];
            $usuario = new Admin;

            if($_SERVER["REQUEST_METHOD"] === "POST") {
                
                //Guardo el objeto en memoria para que no se vacien los campos
                $usuario = new Admin($_POST);

                //Si hay errores
                $errores = $usuario->validarRegistro();

                if(empty($errores)) {
                    //verificar si el usuario existe
                    $existeUsuario = $usuario->existeUsuario("registrarse");

                    //si existe
                    if($existeUsuario) {
                        $errores = Admin::getErrores();
                    } else {
                        //hasheo la contraseña
                        $usuario->hashPassword();

                        //creo un token
                        $usuario->crearToken();

                        //Guardo el usuario nuevo en la BD
                        $resultado = $usuario->guardar();

                        if($resultado) {
                            $errores[] = "Cuenta creada exitosamente. Por favor comprueba tu email";
                            
                        }
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