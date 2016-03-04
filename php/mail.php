<?php
    // Especificar correctamente el path al archivo class.phpmailer.php
    require_once('../Classes/PHPMailer/class.phpmailer.php');

    $mail             = new PHPMailer();

    $body             = "Prueba de envio"; // Cuerpo del mensaje
    $mail->IsSMTP(); // Usar SMTP para enviar
    $mail->SMTPDebug  = 0; // habilita información de depuración SMTP (para pruebas)
                           // 1 = errores y mensajes
                           // 2 = sólo mensajes
    $mail->SMTPAuth   = true; // habilitar autenticación SMTP
    $mail->Host       = "200.105.227.214"; // establece el servidor SMTP
    $mail->Port       = 25; // configura el puerto SMTP utilizado
    $mail->SMTPSecure = "";
    $mail->Username   = "echulde"; // nombre de usuario UGR
    $mail->Password   = "BooksEC672014"; // contraseña del usuario UGR
 
    $mail->SetFrom('edisonjct@gmail.com', 'Edison Chulde, etc.');
    $mail->Subject    = "Mensaje de prueba";
    $mail->MsgHTML($body); // Fija el cuerpo del mensaje

    $address = "edisonjct@gmail.com"; // Dirección del destinatario
    $mail->AddAddress($address, "Yo");

    if(!$mail->Send()) {
        echo "Error: " . $mail->ErrorInfo;
    }
    else {
        echo "¡Mensaje enviado!";
    }
?>