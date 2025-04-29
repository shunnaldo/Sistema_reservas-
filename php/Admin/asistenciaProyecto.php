<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['rol'] !== 'proyecto') {
    header("Location: loginAdmin.php?error=No tienes permisos para entrar a este apartado.");
    exit;
}

include_once __DIR__ . "/../conexion.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asistencia por Taller</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../css/asistenciaProyecto.css">
</head>
<body>

<div id="navbarproyecto-container"></div>

<div class="container-fluid">
    <div class="container-center">
        <h1>Asistencia por Taller</h1>


        <div class="filtro-form">
            <label for="fecha">Filtrar por fecha:</label>
            <input type="date" id="fecha">
        </div>

        <div id="asistencia-container">
            <!-- Aquí se cargará el contenido dinámicamente -->
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const fechaInput = document.getElementById('fecha');
    const container = document.getElementById('asistencia-container');

    function cargarAsistencia(fecha = '') {
        fetch('asistenciaData.php' + (fecha ? '?fecha=' + fecha : ''))
            .then(res => res.text())
            .then(html => container.innerHTML = html)
            .catch(err => container.innerHTML = '<p>Error cargando la asistencia.</p>');
    }

    // Cargar asistencia inicial
    cargarAsistencia();

    // Actualizar al cambiar la fecha
    fechaInput.addEventListener('change', () => {
        cargarAsistencia(fechaInput.value);
    });
});
</script>

<script src="../../js/sidebarproyecto.js"></script>
<script src="../../js/sidebar.js"></script>
</body>
</html>
