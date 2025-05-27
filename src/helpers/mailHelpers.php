<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function enviarCorreoConfirmacion($emailCliente, $pedido, $productos)
{
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Cambia esto según tu hosting
        $mail->SMTPAuth = true;
        $mail->Username = 'nievaslaura82@gmail.com';
        $mail->Password = 'zpwx sjgi wagk ncgc';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Destinatario
        $mail->setFrom('nievaslaura82@gmail.com', 'Tu Tienda Online');
        $mail->addAddress($emailCliente);

        // Asunto y cuerpo del mensaje
        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de tu pedido #' . $pedido->id;
        
        $body = "<h1>¡Gracias por tu compra!</h1>";
        $body .= "<p>Tu pedido ha sido confirmado con éxito. Total a pagar: <strong>{$pedido->coste} €</strong></p>";
        
        $body .= "<h3>Detalles del pedido:</h3><ul>";
       
        while ($producto = $productos->fetch_assoc()) {
            $body .= "<li>{$producto['nombre']} - Cantidad: {$producto['stock']} - Precio: {$producto['precio']}€</li>";
        }
        

        $body .= "</ul><p>Pronto recibirás más detalles sobre el envío.</p>";
        $mail->Body = $body;

        // Enviar correo
        $mail->send();
        return true;

    } catch (Exception $e) {
        echo "Error al enviar correo: " . $mail->ErrorInfo;
        return false;
    }
}
