<?php
// Iniciar la sesión
session_start();

// Verificar si el administrador está logueado y si tiene permisos de administrador
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../css/noticiasAdmin.css">

</head>
<body>

    <div id="navbarAdmin-container"></div>

    <div class="container-fluid">
        <div class="container-fluid">


        

        </div>
    </div>


    <script src="../../js/navbarAdmin.js"></script>
    <script src="../../js/sidebar.js"></script>  

</body>
</html>