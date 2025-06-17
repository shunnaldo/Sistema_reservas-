<?php
require_once(__DIR__ . '/../Comunicaciones/bd/conexion_test.php');; // Ajusta ruta según tu estructura

// Datos del nuevo usuario
$nombre     = 'Iván';
$apellido   = 'Carreño';
$correo     = 'director@gmail.com';
$clave_plana = '123456'; // Contraseña que usará el usuario
$rol        = 'director'; // 'proponente' o 'director'
$area       = 'Comunicaciones'; // Comunicaciones, DAF, FIT, Director
$id_proponente = 1; // si ya tienes el proponente creado

// Encriptar contraseña
$hash = password_hash($clave_plana, PASSWORD_BCRYPT);

// Preparar SQL
$sql = "INSERT INTO usuarios (nombre, apellido, correo, contrasena, rol, area, id_proponente) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $nombre, $apellido, $correo, $hash, $rol, $area, $id_proponente);

if ($stmt->execute()) {
    echo "✅ Usuario creado correctamente.";
} else {
    echo "❌ Error al crear usuario: " . $stmt->error;
}

$conn->close();
?>
