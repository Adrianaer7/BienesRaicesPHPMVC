<?php 
    require_once __DIR__ . "/../includes/app.php";

    use MVC\Router;
    use Controllers\AuthController;
    use Controllers\PropiedadController;
    use Controllers\VendedorController;
    use Controllers\PaginasController;

    //Instancio la clase
    $router = new Router();

    //Defino las rutas - Zona privada
    //Propiedades
    $router->get("/admin", [PropiedadController::class, "index"]);  //PropiedadController::class indica en que controlador se va a encontra la funcion que le paso a la derecha para que el router pueda ejecutarla
    $router->get("/propiedades/crear", [PropiedadController::class, "crear"]);
    $router->post("/propiedades/crear", [PropiedadController::class, "crear"]);
    $router->get("/propiedades/actualizar", [PropiedadController::class, "actualizar"]);
    $router->post("/propiedades/actualizar", [PropiedadController::class, "actualizar"]);
    $router->post("/propiedades/eliminar", [PropiedadController::class, "eliminar"]);
    //Vendedores
    $router->get("/vendedores/crear", [VendedorController::class, "crear"]);
    $router->post("/vendedores/crear", [VendedorController::class, "crear"]);
    $router->get("/vendedores/actualizar", [VendedorController::class, "actualizar"]);
    $router->post("/vendedores/actualizar", [VendedorController::class, "actualizar"]);
    $router->post("/vendedores/eliminar", [VendedorController::class, "eliminar"]);
    
    //Defino las rutas - Zona publica
    //Paginas
    $router->get("/", [PaginasController::class, "index"]);
    $router->get("/nosotros", [PaginasController::class, "nosotros"]);
    $router->get("/propiedades", [PaginasController::class, "propiedades"]);
    $router->get("/propiedad", [PaginasController::class, "propiedad"]);
    $router->get("/blog", [PaginasController::class, "blog"]);
    $router->get("/entrada", [PaginasController::class, "entrada"]);
    $router->get("/contacto", [PaginasController::class, "contacto"]);
    $router->post("/contacto", [PaginasController::class, "contacto"]);
    //Login y autenticacion
    $router->get("/login", [AuthController::class, "login"]);
    $router->post("/login", [AuthController::class, "login"]);
    $router->get("/logout", [AuthController::class, "logout"]);
    $router->get("/registro", [AuthController::class, "registro"]);
    $router->post("/registro", [AuthController::class, "registro"]);


    //Ejecutar las funciones asociadas a esas rutas
    $router->comprobarRutas();
?>