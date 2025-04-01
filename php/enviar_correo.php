<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'conexion.php'; // Conexión a la base de datos

function enviarCorreoConfirmacion($idReserva) {
    global $conexion;

    // Buscar los datos de la reserva en la BD
    $sql = "SELECT * FROM Reservas WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idReserva);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        return false; // No se encontró la reserva
    }

    $reserva = $resultado->fetch_assoc();
    $correo = $reserva['correo_vecino'];
    $nombre = $reserva['nombre_vecino'];
    $apellido = $reserva['apellido_vecino'];
    $rut = $reserva['rut'];
    $cowork = $reserva['cowork'];
    $fecha = $reserva['fecha'];
    $hora_inicio = $reserva['hora_inicio'];
    $hora_fin = $reserva['hora_fin'];

    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Cambia esto según tu proveedor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'benjaminparramolina@gmail.com'; // Tu correo
        $mail->Password = 'fjqg gilv jpkp henv'; // Usa una "contraseña de aplicación"
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom('benjaminparramolina@gmail.com', 'Reservas Cowork');
        $mail->addAddress($correo, "$nombre $apellido");

        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de Reserva';
        $mail->Body = "<h2>¡Tu reserva ha sido confirmada!</h2>
                      <p><strong>Nombre:</strong> $nombre $apellido</p>
                      <p><strong>RUT:</strong> $rut</p>
                      <p><strong>Cowork:</strong> $cowork</p>
                      <p><strong>Fecha:</strong> $fecha</p>
                      <p><strong>Hora:</strong> $hora_inicio - $hora_fin</p>
                      <p>Gracias por reservar con nosotros.</p>";

        // Enviar correo
        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}
?>
