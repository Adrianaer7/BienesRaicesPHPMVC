<?php 
    namespace Model;

    class Admin extends ActiveRecord {
        //Base e datos
        protected static $tabla = "usuarios";
        protected static $columnasDB = ["id", "email", "password", "nombre", "confirmar_password", "confirmado", "token_msj", "token_confirmar"];

        public $id;
        public $email;
        public $password;
        public $nombre;
        public $confirmar_password;
        public $confirmado;
        public $token_msj;
        public $token_confirmar;


        public function __construct($args = [])
        {
            $this->id = $args["id"] ?? null;
            $this->email = $args["email"] ?? "";
            $this->password = $args["password"] ?? "";
            $this->nombre = $args['nombre'] ?? "";
            $this->confirmar_password = $args['confirmar'] ?? "";
            $this->confirmado = $args['confirmado'] ?? 0;
            $this->token_msj = $args['token_msj'] ?? "";
            $this->token_confirmar = $args['token_confirmar'] ?? "";
        }


        //validar campos
        public function validarLogin() {
            if(!$this->email) {
                self::$errores[] = "El email es obligatorio";
            }
            if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                self::$errores[] = 'Email no válido';
            }
            if(!$this->password) {
                self::$errores[] = "La contraseña es obligatoria";
            }
            return self::$errores;
        }

        public function validarRegistro() {
            if(!$this->nombre) {
                self::$errores[] = "El nombre es obligatorio";
            }
            if(!$this->email) {
                self::$errores[] = "El email es obligatorio";
            }
            if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                self::$errores[] = 'Email no válido';
            }
            if(!$this->password) {
                self::$errores[] = "La contraseña es obligatoria";
            }
            if(strlen($this->password) < 6) {
                self::$errores[] = "La contraseña debe tener al menos 6 caracteres";
            }
            if(!$this->confirmar_password) {
                self::$errores[] = "Repite la contraseña";
            }
            if($this->password !== $this->confirmar_password) {
                self::$errores[] = "Las contraseñas deben ser iguales";
            }
            return self::$errores;
        }

        //revisar si el usuario existe
        public function existeUsuario($accion) {
            $query = "SELECT * FROM " . self::$tabla . " WHERE email = '$this->email'" . " LIMIT 1";
            $resultado = self::consultarSQL($query); 
            $usuario = (array_shift($resultado));
            
            if($accion === "logearse") {
                if(!$usuario || $usuario->confirmado == 0) {
                    self::$errores[] = "El usuario no existe o su cuenta no ha sido verificada";
                    return $usuario;
                }
                return $usuario;
            } 
            if($accion === "registrarse") {
                if($usuario) {
                    self::$errores[] = "El usuario ya existe";
                    return $usuario;
                }
            }
            if($accion === "cambiarPassword") {
                if(!$usuario || $usuario->confirmado == 0) {
                    self::$errores[] = "El usuario no existe o su cuenta no ha sido verificada";
                    return $usuario;
                }
                return $usuario;
            }
        }

        public static function existeTokenUsuario($url, $token) {
            $query = "SELECT * FROM " . self::$tabla . " WHERE $token = '$url'" . " LIMIT 1";
            $resultado = self::consultarSQL($query);
            return array_shift( $resultado );
        }

        public function hashPassword() : void {
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        }

        public function crearToken() : void {
            $this->token_msj = md5(uniqid(rand(), true));
            $this->token_confirmar = md5(uniqid(rand(), true));
        }

        //si el usuario existe, compruebo password
        public function comprobarPassword($usuario) {
            $autenticado = password_verify($this->password, $usuario->password);    //password_verify() comprueba que la contraseña del input que ingreso coincida con el hash de la bd
            if(!$autenticado) {
                self::$errores[] = "Contraseña incorrecta";
            }
            return $autenticado;
        }

        public function autenticar() {
            session_start();
            //Llenar el arreglo de session
            $_SESSION["usuario"] = $this->email;
            $_SESSION["login"] = true;
        }

        // Valida un email
        public function validarEmail() {
            if(!$this->email) {
                self::$errores[] = 'El Email es Obligatorio';
            }
            if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                self::$errores[] = 'Email no válido';
            }
            return self::$errores;
        }

        public function validarPassword() {
            if(!$this->password) {
                self::$errores[] = 'El Password no puede ir vacio';
            }
            if(strlen($this->password) < 6) {
                self::$errores[] = 'El password debe contener al menos 6 caracteres';
            }
            return self::$errores;
        }
    }
?>