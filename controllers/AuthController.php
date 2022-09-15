<?php 
    namespace Controllers;
    use MVC\Router;
    use Model\Admin;
    use Classes\Email;
use Classes\EmailUsuario;

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
                    $usuario = $auth->existeUsuario("logearse");
                    
                    //Si el usuario no existe
                    if(!$usuario || $usuario->confirmado == 0) {
                        
                        $errores = Admin::getErrores();
                    } else {
                        //Si existe, verificar la contraseña
                        $autenticado = $auth->comprobarPassword($usuario);
                        if(!$autenticado) {
                            $errores = Admin::getErrores();
                        } else {
                            //Autenticar al usuario
                            $auth->autenticar();
                            header("Location: /admin");
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
                $usuario->sincronizar($_POST);

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
                        $urlMensaje = $usuario->token_msj;
                        
                        //Guardo el usuario nuevo en la BD
                        $resultado = $usuario->guardar();

                        //Envio email
                        $email = new EmailUsuario($usuario->email, $usuario->nombre, $usuario->token_confirmar);
                        $email->enviarConfirmacion();

                        if($resultado) {
                            header("Location: /mensaje/${urlMensaje}");
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

       public static function mensaje(Router $router) {
           $pagina = "Mensaje";
           $token = "token_msj";

           //separo la url en arrays segun sus /, y accedo a la posicion 2
           $url = s($_SERVER["PATH_INFO"]);
           $url = explode('/',$url)[2];
           if(!$url) {
              header("Location: /");
           }

           //Instancio admin para poder ejecutar sus funciones
           $usuario = new Admin();

           //verifico que algun usuario tenga ese token de msj
            $usuario->existeTokenUsuario($url, $token);
            
            $router->render("auth/mensaje", [
                "pagina" => $pagina
            ]);
        }

        public static function confirmar(Router $router) {
            $pagina = "Confirmacion de cuenta";
            $token = "token_confirmar";

            //separo la url en arrays segun sus /, y accedo a la posicion 2
            $url = s($_SERVER["PATH_INFO"]);
            $url = explode('/',$url)[2]; 
            if(!$url) {
                header("Location: /");
            }

            //verifico que algun usuario tenga ese token de msj
            $usuario = Admin::existeTokenUsuario($url, $token);
            if($usuario) {
                $usuario->confirmado = 1;
                $usuario->token_msj = "";
                $usuario->token_confirmar = "";
                $usuario->guardar();
                
                
            } else {
                header("Location: /");
            }

            $router->render("auth/confirmar", [
            "pagina" => $pagina
        ]);
        }
    }
?>