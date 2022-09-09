<?php 

    namespace Controllers;
    use MVC\Router;
    use Model\Propiedad;
    use PHPMailer\PHPMailer\PHPMailer;

    class PaginasController {
        public static function index(Router $router) {
            $propiedades = Propiedad::get(3);   //traigo solo 3 como max
            $pagina = "Inicio";
            $inicio = true;
            $router->render("paginas/index", [
                "propiedades" => $propiedades,
                "pagina" => $pagina,
                "inicio" => $inicio
            ]);
        }
        public static function nosotros(Router $router) {
            $pagina = "Nosotros";
            $router->render("paginas/nosotros", [
                "pagina" => $pagina
            ]);
        }
        public static function propiedades(Router $router) {
            $propiedades = Propiedad::all();
            $pagina = "Anuncios";
            $router->render("paginas/propiedades", [
                "propiedades" => $propiedades,
                "pagina" => $pagina
            ]);
        }
        public static function propiedad(Router $router) {
            $id = validarOredireccionar("/propiedades");
            $propiedad = Propiedad::find($id);
            $pagina = "Propiedad";
            $router->render("paginas/propiedad", [
                "propiedad" => $propiedad,
                "pagina" => $pagina
            ]);
        }
        public static function blog(Router $router) {
            $pagina = "Blog";
            $router->render("paginas/blog", [
                "pagina" => $pagina
            ]);
        }
        public static function entrada(Router $router) {
            $pagina = "Entrada";
            $router->render("paginas/entrada", [
                "pagina" => $pagina
            ]);
        }
        public static function contacto(Router $router) {
            $pagina = "Contacto";
            $mensaje = null;

            if($_SERVER["REQUEST_METHOD"] == "POST") {

                $respuestas = $_POST["contacto"];

                //Crear una instancia de PHPMailer
                $mail = new PHPMailer();

                //Configurar SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.mailtrap.io';
                $mail->Username = '7407a48df5193f';
                $mail->Password = '49bf62d877b4bc';
                $mail->Port = 2525;
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = "tls";

                //Configuracion del contenido del email
                $mail->setFrom("admin@bienesraices.com");
                $mail->addAddress("admin@bienesraices.com", "BienesRaices.com");
                $mail->Subject = "Tienes un nuevo mensaje";
                
                //Habilitar HTML
                $mail->isHTML(true);
                $mail->CharSet = "UTF-8";

                //Definir el contenido
                $contenido = "<html>";
                $contenido .= "<p>Tienes un nuevo mensaje</p>";
                $contenido .= "<p>Nombre: " . $respuestas['nombre'] . "</p>";
                $contenido .= "<p>Mensaje: " . $respuestas['mensaje'] . "</p>";
                $contenido .= "<p>Vende o compra: " . $respuestas['tipo'] . "</p>";
                $contenido .= "<p>Precio: $" . $respuestas['presupuesto'] . "</p>";
                
                //Enviar de forma condicional algunos campos
                $contenido .= "<p>Desea ser contactado por: " . $respuestas['contacto'] . "</p>";
                if($respuestas["contacto"] === "telefono") {
                    $contenido .= "<p>Telefono: " . $respuestas['telefono'] . "</p>";
                    $contenido .= "<p>Fecha: " . $respuestas['fecha'] . "</p>";
                    $contenido .= "<p>Hora: " . $respuestas['hora'] . "</p>";
                } else {
                    $contenido .= "<p>Email: " . $respuestas['email'] . "</p>";
                }
                $contenido .= "</html>";
                $mail->Body = $contenido;
                $mail->AltBody = "Esto es texto alternativo sin HTML";

                //Enviar mail
                if($mail->send()) {
                    $mensaje = "Mensaje enviado";
                } else {
                    $mensaje = "El mensaje no se pudo enviar";
                }

            }
            $router->render("paginas/contacto", [
                "pagina" => $pagina,
                "mensaje" => $mensaje
            ]);
        }
    }

?>