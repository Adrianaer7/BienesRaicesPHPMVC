<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class EmailContacto extends Email {

    public $nombre;
    public $mensaje;
    public $tipo;
    public $presupuesto;
    public $contacto;
    public $telefono;
    public $email;
    public $fecha;
    public $hora;


    public function __construct($args = [])
    {
        $this->nombre = $args["nombre"] ?? "";
        $this->mensaje = $args["mensaje"] ?? "";
        $this->tipo = $args["tipo"] ?? "";
        $this->presupuesto = $args["presupuesto"] ?? "";
        $this->contacto = $args["contacto"] ?? "";
        $this->telefono = $args["telefono"] ?? "";
        $this->email = $args["email"] ?? "";
        $this->fecha = $args["fecha"] ?? "";
        $this->hora = $args["hora"] ?? "";
    }

    public function enviarFormularioContacto() {
        //Crear una instancia de PHPMailer
        $mail = new PHPMailer();

        //Configurar SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        $mail->Port = $_ENV['EMAIL_PORT'];
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
        $contenido .= "<p>Nombre: " . $this->nombre . "</p>";
        $contenido .= "<p>Mensaje: " . $this->mensaje . "</p>";
        $contenido .= "<p>Vende o compra: " . $this->tipo . "</p>";
        $contenido .= "<p>Precio: $" . $this->presupuesto . "</p>";
        
        //Enviar de forma condicional algunos campos
        $contenido .= "<p>Desea ser contactado por: " . $this->contacto . "</p>";
        if($this->contacto === "telefono") {
            $contenido .= "<p>Telefono: " . $this->telefono . "</p>";
            $contenido .= "<p>Fecha: " . $this->fecha . "</p>";
            $contenido .= "<p>Hora: " . $this->hora . "</p>";
        } else {
            $contenido .= "<p>Email: " . $this->email . "</p>";
        }
        $contenido .= "</html>";
        $mail->Body = $contenido;
        $mail->AltBody = "Esto es texto alternativo sin HTML";

        //Enviar mail
        $mail->send();
           
    }
    
}

?>