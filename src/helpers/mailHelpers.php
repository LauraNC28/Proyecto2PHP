<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Cargar automáticamente las clases de PHPMailer desde Composer
require '../vendor/autoload.php';

/**
 * Envía un correo de confirmación de pedido al cliente con el resumen del pedido.
 *
 * @param string $emailCliente  Correo electrónico del destinatario.
 * @param object $pedido        Objeto del pedido que contiene información como ID y coste total.
 * @param mysqli_result $productos  Resultado de la consulta con los productos asociados al pedido.
 *
 * @return bool Devuelve true si el correo fue enviado con éxito, false si hubo error.
 */
function enviarCorreoConfirmacion($emailCliente, $pedido, $productos)
{
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'nievaslaura82@gmail.com'; // Remitente del correo
        $mail->Password = 'zpwx sjgi wagk ncgc';      // Recomendado usar variables de entorno en producción
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del remitente y destinatario
        $mail->setFrom('nievaslaura82@gmail.com', 'Tu Tienda Online');
        $mail->addAddress($emailCliente);

        // Contenido del correo en HTML
        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de tu pedido #' . $pedido->id;

        // Construcción del cuerpo del correo
        $body = "<h1>¡Gracias por tu compra!</h1>";
        $body .= "<p>Tu pedido ha sido confirmado con éxito. Total a pagar: <strong>{$pedido->coste} €</strong></p>";
        $body .= "<h3>Detalles del pedido:</h3><ul>";

        // Itera sobre los productos y agrega una línea por cada uno
        while ($producto = $productos->fetch_assoc()) {
            $body .= "<li>{$producto['nombre']} - Cantidad: {$producto['stock']} - Precio: {$producto['precio']}€</li>";
        }

        $body .= "</ul><p>Pronto recibirás más detalles sobre el envío.</p>";
        $mail->Body = $body;

        // Enviar el correo
        $mail->send();
        return true;

    } catch (Exception $e) {
        echo "Error al enviar correo: " . $mail->ErrorInfo;
        return false;
    }
}

/**
 * Envía un correo al usuario con un enlace para verificar su cuenta.
 *
 * @param string $emailDestino  Correo electrónico del nuevo usuario registrado.
 * @param string $token         Token generado para verificar la cuenta.
 *
 * @return void
 */
function enviarCorreoVerificacion($emailDestino, $token)
{
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'nievaslaura82@gmail.com';
        $mail->Password = 'zpwx sjgi wagk ncgc'; // Reemplazar por variable de entorno en producción
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del remitente y destinatario
        $mail->setFrom('nievaslaura82@gmail.com', 'Tu Tienda Online');
        $mail->addAddress($emailDestino);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Verifica tu cuenta';

        // Generación de la URL de verificación
        $verificacionUrl = base_url . "usuario/verificar&token=$token";

        // Cuerpo del mensaje en HTML
        $mail->Body = "
            <h2>Verifica tu cuenta</h2>
            <p>Gracias por registrarte. Haz clic en el siguiente enlace para verificar tu cuenta:</p>
            <a href='$verificacionUrl'>$verificacionUrl</a>
            <p>Si no te registraste, puedes ignorar este correo.</p>
        ";

        // Versión alternativa del mensaje (sin HTML)
        $mail->AltBody = "Copia y pega esta URL en tu navegador: $verificacionUrl";

        // Enviar el correo
        $mail->send();

    } catch (Exception $e) {
        echo "Error al enviar correo de verificación: {$mail->ErrorInfo}";
    }
}