<?php
include('conexion.php');

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

$rut_cliente_sin_dv = substr($rut, 0, 8);

$sql_check_rut = "SELECT * FROM ctrtecnicos WHERE LEFT(ctrtec_rut, 8) = ?";
$stmt_check = $conexion->prepare($sql_check_rut);
$stmt_check->bind_param("s", $rut_cliente_sin_dv);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Lo sentimos! Usted no cuenta con tarjeta vecina.']);
    exit;
}

$sql = "SELECT * FROM Reservas 
        WHERE fecha = ? 
        AND cowork = ? 
        AND ((hora_inicio <= ? AND hora_fin > ?) 
        OR (hora_inicio < ? AND hora_fin >= ?))";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssss", $fecha, $cowork, $hora_inicio, $hora_inicio, $hora_fin, $hora_fin);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'La hora seleccionada ya está ocupada en este cowork.']);
} else {
    $sql_insert = "INSERT INTO Reservas (rut, nombre_vecino, apellido_vecino, correo_vecino, fecha, hora_inicio, hora_fin, cowork) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt_insert = $conexion->prepare($sql_insert);
    $stmt_insert->bind_param("ssssssss", $rut, $nombre, $apellido, $correo, $fecha, $hora_inicio, $hora_fin, $cowork);
    $stmt_insert->execute();

    if ($stmt_insert->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Reserva realizada con éxito.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al realizar la reserva.']);
    }


}
?>