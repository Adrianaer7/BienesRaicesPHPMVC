<?php 
    namespace MVC;

    class Router {
        public $rutasGET = [];
        public $rutasPOST = [];

        //Me traigo todas las URL con sus respectivas funciones que voy registrando en el index.php y las guardo en el array de get o post
        public function get($url, $fn) {
            $this->rutasGET[$url] = $fn;
        }
        public function post($url, $fn) {
            $this->rutasPOST[$url] = $fn;
        }

        //Comprobar rutas validas
        public function comprobarRutas() {
            session_start();    //para poder acceder al $_SESSION
            $auth = $_SESSION["login"] ?? false;

            //Arreglo de rutas protegidas
            $rutas_protegidas = ["/admin", "/propiedades/crear", "/propiedades/actualizar", "/propiedades/eliminar", "/vendedores/crear", "/vendedores/actualizar", "/vendedores/eliminar"];

            $urlActual = $_SERVER["PATH_INFO"] ?? "/";  //obtengo la ruta donde estoy parado, si estoy parado en la raiz, osea localhost:3000 entonces le agrego "/". Al array $_SERVER puedo acceder desde cualquier parte del proyecto ya que viene desde el servidor localhost:3000
            $metodo = $_SERVER["REQUEST_METHOD"];   //guardo el tipo de metodo

            //PHP solo soporta metodo GET y POST
            if($metodo == "GET") {
                $fn = $this->rutasGET[$urlActual] ?? null;  //guardo la funcion que tiene la url en donde estoy parado, si no existe la url, le asigno null
            } else {
                $fn = $this->rutasPOST[$urlActual] ?? null;  
            }

            //Proteger las rutas
            if(in_array($urlActual, $rutas_protegidas) && !$auth) { //si la url donde estoy es igual a alguna de las rutas privadas y no inicié sesion, redirijo al home
                header("Location: /");
            }

            //Si la URL existe
            if($fn) {
                call_user_func($fn, $this); //call_user_func() llama a la funcion que está en la variable fn. Permite que se ejecuten funciones de esta clase en otros archivos. Con $this le paso los datos en memoria de esta clase a la funcion que se esté ejecutando
            } else {
                echo "Pagina no encontrada";
            }
        }

        //Muestra una vista
        public function render($view, $datos = []) { //traigo el $view(string) de PropiedadController. Si no le paso datos, lo inicializo vacio
            foreach($datos as $key => $value) {
                $$key = $value; //$$ quiere decir variable de variable. Creo la variable $$key(el cual su nombre es el valor del $key) y le paso lo que contiene el valor de $key 
            }
            
            ob_start(); //inicia un almacenamiento en memoria
            include __DIR__ . "/views/$view.php";   //muestro el archivo html

            $contenido = ob_get_clean();    //limpio la vista en memoria
            include __DIR__ . "/views/layout.php";  //envio la variable contenido (que contiene el html) al layout
        }
    }
?>