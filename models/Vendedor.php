<?php 
    namespace Model;

    class Vendedor extends ActiveRecord {
        protected static $tabla = "vendedores";
        protected static $columnasDB = ["id", "nombre", "apellido", "telefono"];

        public $id;
        public $nombre;
        public $apellido;
        public $telefono;

        public function __construct($args = []) //$args es el array $_POST que le paso a la instancia de esta clase
        {
            $this->id = $args["id"] ?? null;
            $this->nombre = $args["nombre"] ?? "";
            $this->apellido = $args["apellido"] ?? "";
            $this->telefono = $args["telefono"] ?? "";
        }

        public function validar() {
            if(!$this->nombre) {
                self::$errores[] = "El nombre es obligatorio";  //envio el texto a la variable estatica en la clase padre
            }
            if(!$this->apellido) {
                self::$errores[] = "El apellido es obligatorio";
            }
            if(!$this->telefono) {
                self::$errores[] = "El telefono es obligatorio";
            }
            if(!preg_match("/[0-9]{10}/", $this->telefono)) {  //expresion regular que verifica que el valor aceptado va desde el n°0 hasta el 9 y maximo 10 digitos
                self::$errores[] = "El telefono debe tener 10 digitos";

            }
            
            return self::$errores;  //devuelvo a la vista la variable de la clase padre
        }
    }
    
?>