<?php 
    namespace Model;

    class ActiveRecord {
        //BD
        protected static $db;   //protected porque solo se puede acceder a ella en la clase. Static porque no se va a crear una instancia por cada objeto nuevo, la conexion a la bd siempre es con las mismas credenciales. Esto ahorra ram
        protected static $columnasDB = [];
        protected static $tabla = "";

        //Errores
        protected static $errores = [];

        
        //DEFINIR CONEXION A LA BD
        public static function setDB($database) {   //recibo $dabatase del app.php que es donde se ejecuta este metodo
            self::$db = $database;  //con self:: envio database a la variable estatica que hay en la clase 
        }


        //Subida de archivos
        public function setImagen($imagen) {
            //Eliminar imagen previa al seleccionar una nueva
            if(!is_null($this->id)) { //si existe y tiene valor la id es porque estoy editando
                $this->borrarImagen();
            }
            //Asignar al atributo de imagen el nombre de la imagen
            if($imagen) {
                $this->imagen = $imagen;
            }
        }

        //Identificar y unir los atributos de la BD
        public function atributos() : array {
            $atributos = [];
            foreach(static::$columnasDB as  $columna) {
                if($columna === "id") continue; //al id no hay que sanitizarlo, por eso no lo guardo en el array atributos. El continue hace que no se ejecute lo que esté debajo, y el foreach siga con el siguiente elemento
                $atributos[$columna] = $this->$columna; //$this es la instancia del objeto en memoria, en este caso (propiedad) y $columna es el valor que contiene la columna de ese $this, seria asi: $propiedad["Casa en la playa"]. A esto se lo agrego al array atributo, el cual en cada columna ($atributos["nombre]) va recibiendo $propiedad["Casa en la playa"].
            }
            return $atributos;
        }

        public function sanitizarAtributos() : array {
            $atributos = $this->atributos();
            $sanitizado = [];

            foreach($atributos as $key => $value ) {    //key es el nombre de la columna, value es el valor que almacena esa columna
                $sanitizado[$key] = self::$db->escape_string($value);   //sanitizo cada valor
            }
            return $sanitizado;
        }

        //GUARDAR LOS DATOS EN LA BD
        public function guardar() {
            if((!is_null($this->id))) {  //Actualizar
                $this->actualizar();
            } else {    //Crear nuevo
                $this->crear();
            }
        }

        public function crear() {
            //Guardar valores sanitizados
            $atributos = $this->sanitizarAtributos();   //ejecuto la funcion de sanitizar y con this-> accedo a los valores que me retorna esa funcion, a esos valores los guardo en atributos
            //Insertar en la BD
            $query = "INSERT INTO " . static::$tabla . " (";    //con static:: accedo a la variable estatica que hay en el hijo de esta clase. O sea propiedades o vendedores
            $query .= join(', ', array_keys($atributos));   //con el .= concateno en un string lo que haya en este renglon más lo que habia en el anterior. Join es como split de js. array_keys son las llaves o columnas del array atributos
            $query .= ") VALUES ('";
            $query .= join("', '", array_values($atributos));   //array_values son los valores de las columnas
            $query .= "')";
            $resultado = self::$db->query($query);  //resultado devuelve true. Con db->query accedo al metodo que forma parte de la instancia de mysqli y sirve para hacer la consulta
            
            if($resultado) {
                header("Location: /admin?resultado=1");
            }
        }

        public function actualizar() {
            //Guardar valores sanitizados
            $atributos = $this->sanitizarAtributos();
            //Insertar en la BD
            $valores = [];
            foreach($atributos as $key => $value) {
                $valores[] = "$key='$value'";
            }
            $query = "UPDATE " . static::$tabla . " SET ";
            $query .= join(", ", $valores);
            $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "'";
            $query .= " LIMIT 1 ";
            $resultado = self::$db->query($query);
            
            if($resultado) {
                header("Location: /admin?resultado=2");
            }
        }

        //VALIDACION
        public static function getErrores() : array {
            return static::$errores;
        }

        //Esta funcion de momento no es necesaria ya que se utiliza en la clase propiedad
        public function validar() {
            static::$errores = [];  //en cada clase que utilize esta funcion, le declaro desde aca un arreglo vacio para errores
            return static::$errores;
        }

        

        //LISTAR TODAS LAS PROPIEDADES
        public static function all() : array {
           $query = "SELECT * FROM " . static::$tabla;  
           //Consulto la bd y cambio el array con arrays asociativos que me devuelve sql, por un array de objetos
           $resultado = self::consultarSQL($query);

           //Envio el resultado al index
           return $resultado;
        }

        //Obtiene un numero determinado de registros
        public static function get($cantidad) : array {
            $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;  
            //Consulto la bd y cambio el array con arrays asociativos que me devuelve sql, por un array de objetos
            $resultado = self::consultarSQL($query);
 
            //Envio el resultado al index
            return $resultado;
         }

        public static function consultarSQL($query) : array {   //traigo el string
            //Consultar la BD
            $resultado = self::$db->query($query);

            //Iterar los resultados
            $array = [];
            while($registro = $resultado->fetch_assoc()) {  //por cada elemento que hay en $resultado
                $array[] = static::crearObjeto($registro);    //lo inserto en el array en forma de objeto
            }
            
            //Liberar memoria
            $resultado->free();

            //Retornar los resultados
            return $array;
        }

        protected static function crearObjeto($registro) : object {
            $objeto = new static;   //crea una variable con los mismos atributos que $tabla tiene en propiedades o vendedores. Si le pongo new self, crea una variable con los atributos de $tabla tiene en esta clase
            foreach($registro as $key => $value) {  //recorro el array asociativo
                if(property_exists($objeto, $key)) {    //revisa que una propiedad o atributo del array exista
                    $objeto->$key = $value; //le asigno el valor que hay en el array asociativo a la columna del objeto
                }
            }
            return $objeto;
        }

        //BUSCAR PROPIEDAD POR ID
        public static function find($id) : object {
            $query = "SELECT * FROM " . static::$tabla . " WHERE id = ${id}";
            $resultado = self::consultarSQL($query);    //devuelve array con 1 objeto
            return array_shift($resultado); //array_shift devuelve la primera posicion del array
        }

        //ACTUALIZAR - MODIFICAR EL OBJETO EN MEMORIA
        public function sincronizar($args = []) {   //el array $args trae todas las propiedades/atributos del objeto. Si no existen, lo inicio vacio
            foreach($args as $key => $value) {  //recorro cada una de las propiedades
                if(property_exists($this, $key) && !is_null($value)) {  //mientras la columna($key) exista en el objeto($this) y la columna no esté vacia
                    $this->$key = $value;   //le paso el valor de la columna al objeto en memoria
                } 
            }           
        }

        //ELIMINAR
        public function eliminar() {
            $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1 ";
            $resultado = self::$db->query($query);

            if($resultado) {
                $this->borrarImagen();
                header("Location: /admin?resultado=3");
            }
        }

        public function borrarImagen() {
            //Comprobar si existe un archivo
            $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen); //this->imagen contiene el nombre de la imagen que traigo de la bd
            if($existeArchivo) {
                unlink(CARPETA_IMAGENES . $this->imagen);
            }
        }
    }
    

?>