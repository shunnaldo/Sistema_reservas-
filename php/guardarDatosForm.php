<?php
header('Content-Type: text/html; charset=utf-8');

include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rut = $_POST['rut'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $ped_id = $_POST['pet_id'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];
    $genero = $_POST['genero'];
    $actividad = $_POST['actividad'];
    $ubicacion = $_POST['ubicacion']; 
   

    $sql = "INSERT INTO corage (
        rut, nombre, apellido, fecha_nacimiento, ped_id, telefono, correo, direccion, genero, actividad, ubicacion
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssssssssss", $rut, $nombre, $apellido, $fecha_nacimiento, $ped_id, $telefono, $correo, $direccion, $genero, $actividad, $ubicacion);

        if ($stmt->execute()) {
            echo "<script>alert('Datos guardados correctamente.'); window.location.href = 'Admin/formulario_temporal.php';</script>";
        } else {
            echo "Error al guardar los datos: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error en la preparaci贸n de la consulta: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Acceso no permitido.";
}
?>