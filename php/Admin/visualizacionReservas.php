<?php

// Iniciar la sesión
session_start();

// Verificar si el administrador está logueado
if (!isset($_SESSION['admin_id'])) {
    header("Location: loginAdmin.php");
    exit;
}

// Verificar si existe el archivo antes de incluirlo
if (file_exists('../conexion.php')) {
    include('../conexion.php');
} else {
    die("Error: No se pudo encontrar el archivo de conexión");
}

// Verificar la conexión
if (!$conexion) {
    die("Error de conexión a la base de datos");
}

// Realizar la consulta
$sql = "SELECT nombre_vecino, apellido_vecino, rut, correo_vecino, fecha, hora_inicio, hora_fin, fecha_creacion, cowork FROM Reservas";
$result = $conexion->query($sql);



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualización de Reservas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/visualizacionReservas.css">
</head>
<body>



<div id="navbarAdmin-container"></div>

<div class="container-fluid">
    <!-- Contenedor del título y filtros -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <h1 class="text-center flex-grow-1">Visualización de Reservas</h1>

        <div class="d-flex gap-2">
            <input type="text" id="searchInput" class="form-control w-50" placeholder="Buscar por RUT...">
            <input type="date" id="dateFilter" class="form-control w-50">
        </div>
    </div>

    <!-- Contenedor para la tabla -->
    <div class="table-container mt-4">
        <table class="table table-bordered" id="reservasTable">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>RUT</th>
                    <th>Correo</th>
                    <th>Fecha</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Fecha de Creación</th>
                    <th>Lugar de trabajo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row['nombre_vecino'] . "</td>
                                <td>" . $row['apellido_vecino'] . "</td>
                                <td class='rut'>" . $row['rut'] . "</td>
                                <td>" . $row['correo_vecino'] . "</td>
                                <td class='fecha'>" . $row['fecha'] . "</td>
                                <td>" . $row['hora_inicio'] . "</td>
                                <td>" . $row['hora_fin'] . "</td>
                                <td>" . $row['fecha_creacion'] . "</td>
                                <td>" . $row['cowork'] . "</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No hay reservas disponibles</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script src="../../js/navbarAdmin.js"></script>
<script src="../../js/sidebar.js"></script>  



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('searchInput').addEventListener('keyup', filterTable);
document.getElementById('dateFilter').addEventListener('change', filterTable);

function filterTable() {
    let inputRut = document.getElementById('searchInput').value.toLowerCase();
    let inputFecha = document.getElementById('dateFilter').value;
    let rows = document.querySelectorAll('#reservasTable tbody tr');

    rows.forEach(row => {
        let rut = row.querySelector('.rut').textContent.toLowerCase();
        let fecha = row.querySelector('.fecha').textContent;

        let matchRut = rut.includes(inputRut);
        let matchFecha = inputFecha === "" || fecha === inputFecha;

        row.style.display = (matchRut && matchFecha) ? '' : 'none';
    });
}
</script>

</body>
</html>
