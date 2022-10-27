<?php 
    namespace Controllers;
    use MVC\Router;
    use Model\Admin;
    use Classes\Email;
use Classes\EmailUsuario;

    class AuthController {

        //Logearse
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

        //Deslogearse
        public static function logout() {
            session_start();
            $_SESSION = [];

            header("Location: /");
        }

        //Registrarse
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

                        //creo un token para la url al enviar el formulario y otro que se envia por email
                        $usuario->crearToken();
                        
                        //Guardo el usuario nuevo en la BD
                        $resultado = $usuario->guardar();

                        //Envio email
                        $email = new EmailUsuario($usuario->email, $usuario->nombre, $usuario->token_confirmar);
                        $email->enviarConfirmacion();

                        //Redirijo al usuario a una url creada con el token generado
                        if($resultado) {
                            header("Location: /msj-creado?id=" . urldecode($usuario->token_msj));   //urlencode evita caracteres especiales
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

        //Mensaje al crear usuario
        public static function mensajeCreado(Router $router) {
           $pagina = "Mensaje";
           $token = "token_msj";
           $mensaje = "Usuario creado correctamente. Revise su email.";

           //Valido que haya token
           $url = $_GET["id"];
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

        //Confirmo el usuario 
        public static function confirmar(Router $router) {
            $pagina = "Confirmacion de cuenta";
            $token = "token_confirmar";
            $mensaje = "Su cuenta ha sido confirmada. Puede iniciar sesion";

            //Valido que haya token
            $url = $_GET["id"];
            if(!$url) {
                header("Location: /");
            }

            //verifico que algun usuario tenga ese token de msj
            $usuario = Admin::existeTokenUsuario($url, $token);

            //confirmo el usuario vaciando los tokens
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

        //formulario con email para reestablecer contraseña
        public static function olvide(Router $router) {
            $pagina = "Olvidé mi contraseña";
            $errores = [];
            $usuario = new Admin();

            if($_SERVER["REQUEST_METHOD"] === "POST") {
                //envio el email a la memoria
                $usuario = new Admin($_POST);

                //valido que el campo email que envié por el form tenga formato adecuado y no esté vacio
                $errores = $usuario->validarEmail();

                if(!empty($errores)) {
                    $errores = Admin::getErrores();
                } else {
                    //compruebo que el usuario que quiere cambiar la contraseña exista y esté verificado
                    $usuario = $usuario->existeUsuario("cambiarPassword");

                    if($usuario) {
                        $usuario->crearToken();
                        $resultado = $usuario->guardar();

                        $email = new EmailUsuario($usuario->email, $usuario->nombre, $usuario->token_confirmar);
                        $email->enviarInstrucciones();

                        if($resultado) {
                            header("Location: /msj-cambiar?id=" . urlencode($usuario->token_msj));
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

        //Mensaje de pasos a seguir para cambiar la contraseña
        public static function mensajeCambiar(Router $router) {
            $pagina = "Mensaje";
            $token = "token_msj";
            $mensaje = "Revise su email para seguir los pasos.";
 
            //Valido que haya token
            $url = $_GET["id"];
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


        public static function reestablecer(Router $router) {
            $pagina = "Reestablecer contraseña";
            $token = "token_confirmar";
            $errores = [];
            $urlPost = $_ENV['HOST'] . $_SERVER["PATH_INFO"];   //creo esta variable ya que el form de donde envio los datos tiene una url dinamica
            $usuario = new Admin();

            $url = $_GET["id"];
            if(!$url) {
                header("Location: /");
            }
            
            //verifico que el usuario al que quiero cambiar la contraseña exista
            $usuario = Admin::existeTokenUsuario($url, $token);
            if(!$usuario) {
                header("Location: /");
            }

            //vacio el campo password para que el formulario no se llene automaticamente con lo que viene de la bd
            $usuario->password = "";
            
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                //guardo en memoria el usuario traido de la bd mas la contraseña recien creada
                $usuario->sincronizar($_POST);
                //valido que la contraseña nueva cumpla con los requisitos
                $errores = $usuario->validarPassword();

                if(empty($errores)) {
                    $usuario->hashPassword();
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        header("Location: /msj-cambiada?id=" . urldecode($usuario->token_msj));
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

            $url = $_GET["id"]; 
            if(!$url) {
                header("Location: /");
            }

            //verifico que el usuario que cambió la contraseña exista
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