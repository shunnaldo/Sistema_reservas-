<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['rol'] !== 'proyecto') {
    header("Location: loginAdmin.php?error=No tienes permisos para entrar a este apartado.");
    exit;
}

// Verificar si el administrador está logueado
if (!isset($_SESSION['admin_id'])) {
    header("Location: loginAdmin.php");
    exit;
}
// Incluir la conexión a la base de datos
include_once __DIR__ . "/../conexion.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div id="navbarproyecto-container"></div>

<h1>ola</h1>

<script src="../../js/sidebarproyecto.js"></script>
<script src="../../js/sidebar.js"></script>  

</body>
</html>