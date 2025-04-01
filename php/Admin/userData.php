<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['error' => 'No hay sesión activa']);
    exit();
}

// Asegurar que los datos existen antes de enviarlos
$nombre = $_SESSION['nombre'] ?? "";
$correo = $_SESSION['correo'] ?? "";
$rol = $_SESSION['rol'] ?? ""; // Ahora obtenemos el rol correctamente

echo json_encode([
    'nombre' => $nombre,
    'correo' => $correo,
    'rol' => $rol // Incluye el rol en la respuesta
]);
?>
