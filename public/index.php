<?php 
    require_once __DIR__ . "/../includes/app.php";
    use MVC\Router;
    use Controllers\PropiedadController;

    //Instancio la clase
    $router = new Router();

    //Defino las rutas
    $router->get("/admin", [PropiedadController::class, "index"]);  //PropiedadController::class busca en qué clase se encuentra el metodo PropiedadController, y ejecuta la funcion que hay en esa clase
    $router->get("/propiedades/crear", [PropiedadController::class, "crear"]);
    $router->post("/propiedades/crear", [PropiedadController::class, "crear"]);
    $router->get("/propiedades/actualizar", [PropiedadController::class, "actualizar"]);

    //Ejecutar las funciones asociadas a esas rutas
    $router->comprobarRutas();
?>