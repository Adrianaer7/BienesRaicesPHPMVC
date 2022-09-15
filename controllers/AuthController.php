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
                            header("Location: /msj-creado/$urlMensaje");
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

       public static function mensajeCreado(Router $router) {
           $pagina = "Mensaje";
           $token = "token_msj";
           $mensaje = "Usuario creado correctamente. Revise su email.";

           //separo la url en arrays segun sus /, y accedo a la posicion 2
           $url = s($_SERVER["PATH_INFO"]);
           $url = explode('/',$url)[2];
           if(!$url) {
              header("Location: /");
           }

           //Instancio admin para poder ejecutar sus funciones
           $usuario = new Admin();

           //verifico que algun usuario tenga ese token de msj
            $usuario = $usuario->existeTokenUsuario($url, $token);
            if(!$usuario) {
                header("Location: /");
            }
            $router->render("auth/mensaje", [
                "pagina" => $pagina,
                "mensaje" => $mensaje
            ]);
        }

        public static function mensajeCambiar(Router $router) {
            $pagina = "Mensaje";
            $token = "token_msj";
            $mensaje = "Revise su email para seguir los pasos.";
 
            //separo la url en arrays segun sus /, y accedo a la posicion 2
            $url = s($_SERVER["PATH_INFO"]);
            $url = explode('/',$url)[2];
            if(!$url) {
               header("Location: /");
            }
 
            //Instancio admin para poder ejecutar sus funciones
            $usuario = new Admin();
 
            //verifico que algun usuario tenga ese token de msj
            $usuario = $usuario->existeTokenUsuario($url, $token);
            if(!$usuario) {
                header("Location: /");
            }
            $router->render("auth/mensaje", [
                "pagina" => $pagina,
                "mensaje" => $mensaje
            ]);
         }

        public static function confirmar(Router $router) {
            $pagina = "Confirmacion de cuenta";
            $token = "token_confirmar";
            $mensaje = "Su cuenta ha sido confirmada. Puede iniciar sesion";

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

            $router->render("auth/mensaje", [
            "pagina" => $pagina,
            "mensaje" => $mensaje
            ]);
        }

        public static function olvide(Router $router) {
            $pagina = "Olvidé mi contraseña";
            $errores = [];
            $usuario = new Admin();

            if($_SERVER["REQUEST_METHOD"] === "POST") {
                $usuario = new Admin($_POST);
                $errores = $usuario->validarEmail();
                if(!empty($errores)) {
                    $errores = Admin::getErrores();
                } else {
                    $usuario = $usuario->existeUsuario("cambiarPassword");
                    if($usuario) {
                        $usuario->crearToken();
                        $urlMensaje = $usuario->token_msj;
                        $resultado = $usuario->guardar();

                        $email = new EmailUsuario($usuario->email, $usuario->nombre, $usuario->token_confirmar);
                        $email->enviarInstrucciones();

                        if($resultado) {
                            header("Location: /msj-cambiar/$urlMensaje");
                        }
                    } 
                }
            }

            $router->render("auth/olvide", [
                "pagina" => $pagina,
                "errores" => $errores,
                "usuario" => $usuario
            ]);
        }

        public static function reestablecer(Router $router) {
            $pagina = "Reestablecer contraseña";
            $token = "token_confirmar";
            $errores = [];
            $urlPost = $_ENV['HOST'] . $_SERVER["PATH_INFO"];
            $usuario = new Admin();

            $url = s($_SERVER["PATH_INFO"]);
            $url = explode('/',$url)[2]; 
            if(!$url) {
                header("Location: /");
            }
            
            $usuario = Admin::existeTokenUsuario($url, $token);
            if(!$usuario) {
                header("Location: /");
            }
            $usuario->password = "";
            
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                $usuario->sincronizar($_POST);
                $errores = $usuario->validarPassword();
                if(empty($errores)) {
                    $usuario->hashPassword();
                    $urlMensaje = $usuario->token_msj;
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        header("Location: /msj-cambiada/$urlMensaje");
                    }
                } else {
                    $errores = Admin::getErrores();
                }
                                    
            }

            $router->render("auth/form-password", [
                "pagina" => $pagina,
                "usuario" =>$usuario,
                "errores" =>$errores,
                "url" => $urlPost
            ]);
        }

        public static function cambiada(Router $router) {
            $pagina = "Contraseña cambiada";
            $mensaje = "Contraseña cambiada correctamente.";
            $token = "token_msj";
            $usuario = new Admin();

            $url = s($_SERVER["PATH_INFO"]);
            $url = explode('/',$url)[2]; 
            if(!$url) {
                header("Location: /");
            }
            $usuario = Admin::existeTokenUsuario($url, $token);
            if($usuario) {
                $usuario->token_msj = "";
                $usuario->token_confirmar = "";
                $usuario->guardar();
            } else {
                header("Location: /");
            }

            $router->render("auth/mensaje", [
                "pagina" => $pagina,
                "mensaje" => $mensaje
            ]);
        }
    }
?>