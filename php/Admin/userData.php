<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['error' => 'No hay sesión activa']);
    exit();
}

// Asegurar que los datos existen antes de enviarlos
$nombre = $_SESSION['nombre'] ?? null;
$correo = $_SESSION['correo'] ?? null;

if (!$nombre || !$correo) {
    echo json_encode(['error' => 'Datos de sesión incompletos']);
    exit();
}

echo json_encode([
    'nombre' => $nombre,
    'correo' => $correo
]);
?>
