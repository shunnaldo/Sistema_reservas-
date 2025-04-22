<?php
// Iniciar la sesión
session_start();

// Verificar si el administrador está logueado y si tiene permisos de administrador
if (!isset($_SESSION['admin_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: loginAdmin.php?error=No tienes permisos para registrar usuarios.");
    exit;
}


if (!isset($_SESSION['admin_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: loginAdmin.php?error=No tienes permisos para registrar usuarios.");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<form method="POST" action="bloquear_dias.php">
            <label>Fecha a bloquear:</label>
            <input type="date" name="fecha" required>
          
            <label>Motivo (opcional):</label>
            <input type="text" name="motivo">
          
            <button type="submit">Bloquear Día</button>
          </form>
          
    
</body>
</html>