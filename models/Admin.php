<?php 
    namespace Model;

    class Admin extends ActiveRecord {
        //Base e datos
        protected static $tabla = "usuarios";
        protected static $columnasDB = ["id", "email", "password", "nombre", "confirmar_password", "confirmado", "token"];

        public $id;
        public $email;
        public $password;
        public $nombre;
        public $confirmar_password;
        public $confirmado;
        public $token;


        public function __construct($args = [])
        {
            $this->id = $args["id"] ?? null;
            $this->email = $args["email"] ?? "";
            $this->password = $args["password"] ?? "";
            $this->nombre = $args['nombre'] ?? "";
            $this->confirmar_password = $args['confirmar'] ?? "";
            $this->confirmado = $args['confirmado'] ?? 0;
            $this->token = $args['token'] ?? "";


        }

        public function vaciarForm() {
            $this->id =  null;
            $this->password =  "";
            $this->confirmar_password = "";
            $this->confirmado =  0;
            $this->token = "";
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
            $resultado = self::$db->query($query); 
            if($accion === "logearse") {
                if(!$resultado->num_rows) {
                    self::$errores[] = "El usuario no existe";
                    return;
                }
                return $resultado;
            } else {
                if($resultado->num_rows) {
                    self::$errores[] = "El usuario ya existe";
                    return $resultado;
                }
            }
        }

        public function hashPassword() : void {
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        }

        public function crearToken() : void {
            $this->token = uniqid();
        }

        //si el usuario existe, compruebo password
        public function comprobarPassword($resultado) {
            $usuario = $resultado->fetch_object();  //fetch_object() trae los datos del usuario
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
            
            header("Location: /admin");
        }
    }
?>