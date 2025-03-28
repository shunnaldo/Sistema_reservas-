<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['error' => 'No hay sesión activa']);
    exit();
}

// Asegurar que los datos existen antes de enviarlos
$nombre = $_SESSION['nombre'] ?? "";
$correo = $_SESSION['correo'] ?? "";

echo json_encode([
    'nombre' => $nombre,
    'correo' => $correo
]);
?>
