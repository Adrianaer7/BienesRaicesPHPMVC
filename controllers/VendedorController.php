<?php 
    namespace Controllers;
    use MVC\Router;
    use Model\Propiedad;
    use Model\Vendedor;
    
    class VendedorController {
        public static function crear(Router $router) {
            $vendedor = new Vendedor();
            
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                //Instancio la clase
                $vendedor = new Vendedor($_POST["vendedor"]);
        
                //Valido los datos
                $errores = $vendedor->validar();
                
                //Revisar que el array de errores esté vacio
                if(empty($errores)) {
                    //Guardo en la BD
                    $vendedor->guardar();
                }
            }

            $router->render("vendedores/crear", [
                "vendedor" => $vendedor,
                "errores" => $errores
            ]);
        }

        public static function actualizar(Router $router) {
            $id = validarOredireccionar("/admin");

            $errores = Propiedad::getErrores();
            $vendedor = Vendedor::find($id);
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                //Asignar los atributos
                $args = $_POST["vendedor"];  
        
                //Actualizar los atributos en memoria
                $vendedor->sincronizar($args);
        
                //Validar los atributos
                $errores = $vendedor->validar();
                
                //Revisar que el array de errores esté vacio
                if(empty($errores)) {
                    //Guardar en la BD
                    $vendedor->guardar();
                }
            }

            $router->render("vendedores/actualizar", [
                "vendedor" => $vendedor,
                "errores" => $errores
            ]);
        }

        public static function eliminar() {
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $id = ($_POST["id"]);
                $id = filter_var($id, FILTER_VALIDATE_INT);
                
                if($id) {
                    $tipo = $_POST["tipo"];
                    if(validarTipoContenido($tipo)) { 
                        $vendedor = Vendedor::find($id);
                        $vendedor->eliminar();
                    }
        
                }

            }
        }
    }
?>