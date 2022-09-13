<?php 
    namespace Controllers;
    use MVC\Router;
    use Model\Propiedad;
    use Model\Vendedor;
    use Intervention\Image\ImageManagerStatic as Image;
    
    class PropiedadController {
        public static function index(Router $router) {  //Router por que le aclaro que tipo de parametro le paso. Con $router traigo la estructura con los datos y metodos de la instancia de la clase creada en el index
            $resultado = $_GET["resultado"] ?? null;

            //Consulto la bd
            $propiedades = Propiedad::all();
            $vendedores = Vendedor::all();
            //Le paso las propiedades a la vista
            $router->render("propiedades/admin", [
                "propiedades" => $propiedades,
                "vendedores" => $vendedores,
                "resultado" => $resultado
            ]);
        }

        public static function crear(Router $router) {
            $propiedad = new Propiedad();
            $vendedores = Vendedor::all();
            
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                //Instancio la clase
                $propiedad = new Propiedad($_POST["propiedad"]);    //en el name de cada input, le agrego propiedad["name"] para que el $_POST cree un array con todos los datos.
        
                //Generar un nombre unico para cada archivo
                $extension = pathinfo($_FILES["propiedad"]["name"]["imagen"], PATHINFO_EXTENSION); //La función pathinfo recibe en primer lugar una cadena, la cual representa al nombre del archivo. Y como segundo argumento una constante indicando qué información queremos extraer.
                $nombreImagen = md5(uniqid(rand(), true)).".$extension";  //mk5 devuelve un hash estatico. iniqid genera aleatorios
        
                //Realizar resize a la imagen con Intervention
                if($_FILES["propiedad"]["tmp_name"]["imagen"]) {  //si existe una imagen
                    $image = Image::make($_FILES["propiedad"]["tmp_name"]["imagen"])->fit(800,600);  //hago un recorte de resolucion de la imagen
                    $propiedad->setImagen($nombreImagen);   //envio el nombre de la imagen a la propiedad de la clase
                }
        
                //Valido los datos
                $errores = $propiedad->validar();
                
                //Revisar que el array de errores esté vacio
                if(empty($errores)) {
                    //Crear carpeta para las imagenes si no existe
                    if(!is_dir(CARPETA_IMAGENES)) { //carpeta_imagenes viene de funciones.php
                        mkdir(CARPETA_IMAGENES);
                    }
        
                    //Guarda la imagen en la carpeta
                    $image->save(CARPETA_IMAGENES . $nombreImagen);
                     
                    //Guardo en la BD
                    $resultado = $propiedad->guardar();
                    if($resultado) {
                        header("Location: /admin?resultado=1");
                    }
                }
            }

            $router->render("propiedades/crear", [
                "propiedad" => $propiedad,
                "vendedores" => $vendedores,
                "errores" => $errores,
            ]);
        }

        public static function actualizar(Router $router) {
            $id = validarOredireccionar("/admin");

            $propiedad = Propiedad::find($id);
            $vendedores = Vendedor::all();
            $errores = Propiedad::getErrores();

            if($_SERVER["REQUEST_METHOD"] === "POST") {
                //Asignar los atributos
                $args = $_POST["propiedad"];  
        
                //Actualizar los atributos en memoria
                $propiedad->sincronizar($args); //modifico el objeto original por el objeto que está en memoria
        
                //Subida de archivos
                //Generar un nombre unico para cada archivo
                $extension = pathinfo($_FILES["propiedad"]["name"]["imagen"], PATHINFO_EXTENSION); 
                $nombreImagen = md5(uniqid(rand(), true)).".$extension";  
                
                //Validar los atributos
                $errores = $propiedad->validar();
                
                //Revisar que el array de errores esté vacio
                if(empty($errores)) {
                    //Realizar resize a la imagen con Intervention
                    if($_FILES["propiedad"]["tmp_name"]["imagen"]) {  
                        $image = Image::make($_FILES["propiedad"]["tmp_name"]["imagen"])->fit(800,600);  
                        $propiedad->setImagen($nombreImagen);   
                        
                        //Almacenar la imagen
                        $image->save(CARPETA_IMAGENES . $nombreImagen);
                    }
                 
                    //Guardar en la BD
                    $propiedad->guardar();
                }
            }
            

            $router->render("propiedades/actualizar", [
                "propiedad" => $propiedad,
                "vendedores" => $vendedores,
                "errores" => $errores
            ]);
        }

        public static function eliminar() {
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $id = ($_POST["id"]);
                $id = filter_var($id, FILTER_VALIDATE_INT);
                
                if($id) {
                    $tipo = $_POST["tipo"]; //contiene vendedor o propiedad
                    if(validarTipoContenido($tipo)) {   //validarTipoContenido tiene un array con vendedor y propiedad. Valida que el tipo que le paso exista en ese array
                        $propiedad = Propiedad::find($id);
                        $propiedad->eliminar();
                    }
        
                }

            }
        }
    }
?>