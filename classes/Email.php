<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token){
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarEmail(){
        $mail = new PHPMailer(true);

        //configurar SNTP
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 465;
        $mail->Username = '8377db3f47ddc7';
        $mail->Password = 'a01afb30385011';

        //Configurar el contenido del email
        $mail->setFrom("admin@bienesraices.com");
        $mail->addAddress('alejandroSantillan@hotmail.com', 'Alejandro Santillan');
        $mail->Subject = "Confirma tu cuenta";

        //Habilitar el HTML
        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";

        //Definir el contenido
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong>Ha creado tu cuenta en App Salon, Solo debes confirmarla presionando al siguiente enlace</p>";
        $contenido .= "<p>Presiona aqui: <a href='http:localhost:3000/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Enviar el email
        $mail->send();
    }

    public function enviarInstrucciones(){
        $mail = new PHPMailer(true);

        //configurar SNTP
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 465;
        $mail->Username = '8377db3f47ddc7';
        $mail->Password = 'a01afb30385011';

        //Configurar el contenido del email
        $mail->setFrom("admin@bienesraices.com");
        $mail->addAddress('alejandroSantillan@hotmail.com', 'Alejandro Santillan');
        $mail->Subject = "Restablece tu password";

        //Habilitar el HTML
        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";

        //Definir el contenido
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong>Ha solicitado restablecer tu password sigue el siguiente enlace para hacerlo</p>";
        $contenido .= "<p>Presiona aqui: <a href='http:localhost:3000/recuperar?token=" . $this->token . "'>Restablecer Password</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Enviar el email
        $mail->send();
    } 

}
