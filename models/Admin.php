<?php 
    namespace Model;

    class Admin extends ActiveRecord {
        //Base e datos
        protected static $tabla = "usuarios";
        protected static $columnasDB = ["id", "email", "password"];

        public $id;
        public $email;
        public $password;

        public function __construct($args = [])
        {
            $this->id = $args["id"] ?? null;
            $this->email = $args["email"] ?? "";
            $this->password = $args["password"] ?? "";
        }

        //validar campos
        public function validar() {
            if(!$this->email) {
                self::$errores[] = "El email es obligatorio";
            }
            if(!$this->password) {
                self::$errores[] = "La contraseña es obligatorio";
            }
            return self::$errores;
        }

        //revisar si el usuario existe
        public function existeUsuario() {
            $query = "SELECT * FROM " . self::$tabla . " WHERE email = '$this->email'" . " LIMIT 1";
            $resultado = self::$db->query($query); 
            if(!$resultado->num_rows) {
                self::$errores[] = "El usuario no existe";
                return;
            }
            return $resultado;
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