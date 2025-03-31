<?php

// Iniciar la sesión
session_start();

// Verificar si el administrador está logueado
if (!isset($_SESSION['admin_id'])) {
    header("Location: loginAdmin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div id="navbarAdmin-container"></div>



    <script src="../../js/navbarAdmin.js"></script>
    <script src="../../js/sidebar.js"></script>  
</body>
</html>