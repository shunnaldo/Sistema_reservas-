<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'conexion.php'; 

function enviarCorreoConfirmacion($correo, $nombre, $apellido, $rut, $cowork, $fecha, $hora_inicio, $hora_fin) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'mail.fomentolaflorida.cl';
        $mail->SMTPAuth = true;
        $mail->Username = 'casaemprender2025@fomentolaflorida.cl';
        $mail->Password = 'TvS9nSQmp4nJT7Q';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->SMTPDebug = 0; // Puedes poner 2 para debug o 0 para producción
        $mail->Debugoutput = 'html';

        // Configuración del correo
        $mail->setFrom('casaemprender2025@fomentolaflorida.cl', 'Reservas Cowork');
        $mail->addAddress($correo, "$nombre $apellido");

        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de Reserva';
        $mail->Body = '
        <div style="font-family: Arial, sans-serif; color: #333;">
            <div style="max-width: 600px; margin: auto; border: 1px solid #e0e0e0; border-radius: 10px; overflow: hidden;">
                
                <!-- Logo superior -->

    
                <!-- Encabezado -->
                <div style="text-align: center; background-color: #0a4b78; color: white; padding: 15px;">
                    <h2 style="margin: 0;">Confirmación de Reserva</h2>
                </div>
    
                <!-- Cuerpo del mensaje -->
                <div style="padding: 20px; background-color: #ffffff;">
                    <p>Estimado/a <strong>' . htmlspecialchars($nombre) . ' ' . htmlspecialchars($apellido) . '</strong>,</p>
                    <p>Te confirmamos que tu reserva ha sido registrada con éxito. A continuación, te dejamos los detalles:</p>
                    
                    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                        <tr>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>RUT:</strong></td>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;">' . htmlspecialchars($rut) . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Espacio reservado:</strong></td>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;">' . htmlspecialchars($cowork) . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Fecha:</strong></td>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;">' . htmlspecialchars($fecha) . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;"><strong>Horario:</strong></td>
                            <td style="padding: 8px;">' . htmlspecialchars($hora_inicio) . ' - ' . htmlspecialchars($hora_fin) . '</td>
                        </tr>
                    </table>
    
                    <p style="margin-top: 20px;">Si tienes alguna duda o necesitas modificar tu reserva, no dudes en contactarnos.</p>
                    
                    <p style="margin-top: 30px;">Saludos cordiales,<br><strong>Equipo Cofodep – Fomento La Florida</strong></p>
                </div>
    
                <!-- Footer con logo inferior -->
                <div style="background-color: #f0f0f0; text-align: center; padding: 15px;">
                    <img src="https://teatrolaflorida.cl/wp-content/uploads/2024/10/COFODEP-1024x194.png" alt="Logo Cowork" style="max-height: 40px; margin-bottom: 10px;"><br>
                    <span style="font-size: 12px; color: #666;">Este correo fue enviado automáticamente. Por favor, no respondas a este mensaje.</span>
                </div>
            </div>
</div>';

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}
?>