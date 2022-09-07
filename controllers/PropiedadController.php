<?php 
    namespace Controllers;
    use MVC\Router;
    use Model\Propiedad;
    use Model\Vendedor;

    
    class PropiedadController {
        public static function index(Router $router) {  //Router por que le aclaro que tipo de parametro le paso. Con $router traigo la estructura con los datos y metodos de la instancia de la clase creada en el index
            //Consulto la bd
            $propiedades = Propiedad::all();
            $resultado = null;  //valor de la url por si realizo algun crud

            //Le paso las propiedades a la vista
            $router->render("propiedades/admin", [
                "propiedades" => $propiedades,
                "resultado" => $resultado
            ]);
        }

        public static function crear(Router $router) {
            $propiedad = new Propiedad();
            $vendedores = Vendedor::all();
            
            $router->render("propiedades/crear", [
                "propiedad" => $propiedad,
                "vendedores" => $vendedores
            ]);
        }

        public static function actualizar() {
            echo "actualizar propiedad";
        }
    }
?>