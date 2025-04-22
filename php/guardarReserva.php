<?php
include('conexion.php');
include('enviar_correo.php');

// Obtener los datos enviados por POST
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No se recibieron los datos correctamente.']);
    exit;
}

$rut = $data['rut'];
$nombre = $data['nombre'];
$apellido = $data['apellido'];
$correo = $data['correo'];
$cowork = $data['cowork'];
$fecha = $data['fecha'];
$hora_inicio = $data['horaInicio'];
$hora_fin = $data['horaFin'];
$cantidad_personas = $data['cantidadPersonas']; 
$numero_telefono = $data['telefono'];

// Verificar si el usuario tiene la Tarjeta Vive La Florida
$rut_cliente_sin_dv = substr($rut, 0, 8);

$sql_check_rut = "SELECT * FROM ctrtecnicos WHERE LEFT(ctrtec_id, 8) = ? OR ctrtec_rut = ?";
$stmt_check = $conexion->prepare($sql_check_rut);
$stmt_check->bind_param("ss", $rut_cliente_sin_dv, $rut);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows == 0) {
    echo json_encode([
        'success' => false, 
        'message' => '¡Lo sentimos! No cuentas con la Tarjeta Vive La Florida. Solicítala acercándote a Casa Emprender ubicada en Alonso Ercilla #1380, La Florida. De Lunes a Jueves de 09:00 a 17:45 hrs y Viernes de 09:00 a 16:30 hrs. Recuerda traer tu carnét de identidad y comprobante de domicilio. ¡Te esperamos!'
    ]);
    exit;
}

// Verificar si la fecha está bloqueada
$sql_check_bloqueo = "SELECT * FROM diasBloqueados WHERE fecha = ?";
$stmt_check_bloqueo = $conexion->prepare($sql_check_bloqueo);
$stmt_check_bloqueo->bind_param("s", $fecha);
$stmt_check_bloqueo->execute();
$result_check_bloqueo = $stmt_check_bloqueo->get_result();

if ($result_check_bloqueo->num_rows > 0) {
    $row_bloqueo = $result_check_bloqueo->fetch_assoc();
    echo json_encode([
        'success' => false,
        'message' => 'El día seleccionado está bloqueado por el motivo: ' . $row_bloqueo['motivo']
    ]);
    exit;
}

// Verificar si el usuario ya tiene una reserva pendiente o en lista
$sql_check_reserva = "SELECT * FROM Reservas WHERE rut = ? AND estado IN ('pendiente', 'lista')";
$stmt_check_reserva = $conexion->prepare($sql_check_reserva);
$stmt_check_reserva->bind_param("s", $rut);
$stmt_check_reserva->execute();
$result_check_reserva = $stmt_check_reserva->get_result();

if ($result_check_reserva->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Ya tienes una reserva agendada.']);
    exit;
}

// Verificar la disponibilidad de la hora seleccionada
$sql = "SELECT COUNT(*) as total_reservas FROM Reservas 
        WHERE fecha = ? 
        AND cowork = ? 
        AND estado IN ('pendiente', 'lista')
        AND (
            (hora_inicio <= ? AND hora_fin > ?) 
            OR (hora_inicio < ? AND hora_fin >= ?)
        )";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssss", $fecha, $cowork, $hora_inicio, $hora_inicio, $hora_fin, $hora_fin);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['total_reservas'] >= 6) {
    echo json_encode(['success' => false, 'message' => 'La hora seleccionada no está disponible en este cowork.']);
    exit;
}

// Insertar la reserva en la base de datos
$sql_insert = "INSERT INTO Reservas (rut, nombre_vecino, apellido_vecino, correo_vecino, fecha, hora_inicio, hora_fin, cowork, cantidad_personas, numero_telefono) 
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_insert = $conexion->prepare($sql_insert);
$stmt_insert->bind_param("ssssssssis", $rut, $nombre, $apellido, $correo, $fecha, $hora_inicio, $hora_fin, $cowork, $cantidad_personas, $numero_telefono);
$stmt_insert->execute();

// Verificar si se insertó correctamente
if ($stmt_insert->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Reserva realizada con éxito. Te hemos enviado un correo de confirmación.']);
    // Enviar el correo de confirmación
    enviarCorreoConfirmacion($correo, $nombre, $apellido, $rut, $cowork, $fecha, $hora_inicio, $hora_fin);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al realizar la reserva.']);
}
?>
