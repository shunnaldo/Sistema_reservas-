<?php
include_once __DIR__ . "/../conexion.php";

$rut = $conexion->real_escape_string($_POST['rut']);
$nombre = $conexion->real_escape_string($_POST['nombre']);
$apellido = $conexion->real_escape_string($_POST['apellido']);
$correo = $conexion->real_escape_string($_POST['correo']);
$id_taller = (int)$_POST['taller']; 

if (empty($rut) || empty($nombre) || empty($apellido) || empty($correo) || empty($id_taller)) {
    echo "Error: Todos los campos son obligatorios.";
    exit;
}

$sql = "INSERT INTO asistencia_talleres (nombre, apellido, rut, correo, id_taller)
        VALUES ('$nombre', '$apellido', '$rut', '$correo', $id_taller)";

if ($conexion->query($sql) === TRUE) {
    echo "Asistencia registrada exitosamente.";
} else {
    echo "Error al registrar la asistencia: " . $conexion->error;
}
?>