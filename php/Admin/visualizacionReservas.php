<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: loginAdmin.php");
    exit;
}

if (file_exists('../conexion.php')) {
    include('../conexion.php');
} else {
    die("Error: No se pudo encontrar el archivo de conexión");
}

if (!$conexion) {
    die("Error de conexión a la base de datos");
}

// Obtener la fecha y hora actual en el mismo formato de la base de datos
$now = date('Y-m-d H:i:s');

// Actualizar el estado de las reservas dinámicamente en la base de datos
$updateQuery = "
    UPDATE Reservas 
    SET estado = CASE 
        WHEN CONCAT(fecha, ' ', hora_inicio) > '$now' THEN 'pendiente' 
        WHEN CONCAT(fecha, ' ', hora_inicio) <= '$now' AND CONCAT(fecha, ' ', hora_fin) >= '$now' THEN 'lista' 
        ELSE 'finalizada' 
    END";

$conexion->query($updateQuery);

// Obtener las reservas actualizadas
$sql = "SELECT id, nombre_vecino, apellido_vecino, rut, correo_vecino, fecha, hora_inicio, hora_fin, fecha_creacion, cowork, estado, check_asistencia FROM Reservas";
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
                    <th>Lugar de Trabajo</th>
                    <th>Estado</th>
                    <th>Asistencia</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $estadoClass = '';

                        // Asignar clase CSS según el estado
                        switch ($row['estado']) {
                            case 'pendiente':
                                $estadoClass = 'table-warning'; // Amarillo
                                break;
                            case 'lista':
                                $estadoClass = 'table-success'; // Verde
                                break;
                            case 'finalizada':
                                $estadoClass = 'table-danger'; // Rojo
                                break;
                        }

                        echo "<tr class='$estadoClass'>
                                <td>{$row['nombre_vecino']}</td>
                                <td>{$row['apellido_vecino']}</td>
                                <td>{$row['rut']}</td>
                                <td>{$row['correo_vecino']}</td>
                                <td>{$row['fecha']}</td>
                                <td>{$row['hora_inicio']}</td>
                                <td>{$row['hora_fin']}</td>
                                <td>{$row['fecha_creacion']}</td>
                                <td>{$row['cowork']}</td>
                                <td>{$row['estado']}</td>
                                <td>
                                    <input type='checkbox' class='check-asistencia' data-id='{$row['id']}' ".($row['check_asistencia'] ? "checked" : "").">
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No hay reservas disponibles</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Este script obtiene los datos del usuario desde el archivo userData.php
fetch('userData.php')
.then(response => response.json())
.then(data => {
   console.log("Respuesta del servidor:", data);
   if (data.error) {
         console.error("Error:", data.error);
   } else {
         document.getElementById('user-name').textContent = data.nombre || "Usuario desconocido";
         document.getElementById('user-email').textContent = data.correo || "Correo no disponible";
   }
})
.catch(error => console.error('Error al obtener los datos del usuario:', error));

// Función para cargar las reservas con AJAX
function loadReservas() {
    fetch('getReservas.php')
        .then(response => response.json())
        .then(data => {
            let tableBody = document.querySelector('#reservasTable tbody');
            tableBody.innerHTML = '';

            if (data.length > 0) {
                data.forEach(row => {
                    let tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${row.nombre_vecino}</td>
                        <td>${row.apellido_vecino}</td>
                        <td class='rut'>${row.rut}</td>
                        <td>${row.correo_vecino}</td>
                        <td class='fecha'>${row.fecha}</td>
                        <td>${row.hora_inicio}</td>
                        <td>${row.hora_fin}</td>
                        <td>${row.fecha_creacion}</td>
                        <td>${row.cowork}</td>
                    `;
                    tableBody.appendChild(tr);
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="9">No hay reservas disponibles</td></tr>';
            }
        })
        .catch(error => console.error('Error al cargar reservas:', error));
}

// Cargar reservas al inicio y cada 30 segundos
loadReservas();
setInterval(loadReservas, 30000);

// Este script maneja la carga dinámica de los navbar y sidebar dependiendo del rol del usuario
document.addEventListener("DOMContentLoaded", function () {
    fetch('userData.php')
    .then(response => response.json())
    .then(data => {
        console.log("Datos del usuario:", data);

        if (data.error) {
            console.error("Error:", data.error);
            return;
        }

        let rol = data.rol.trim().toLowerCase();
        console.log("Rol identificado:", rol);

        let navbarContainer = document.createElement("div");
        navbarContainer.id = "navbar-container";
        document.body.prepend(navbarContainer); // Agregar navbar al inicio del body

        if (rol === "admin") {
            console.log("Cargando navbar para ADMIN...");
            fetch("sidebarAdmin.html")  // Asegúrate de que este archivo existe
                .then(response => response.text())
                .then(html => {
                    navbarContainer.innerHTML = html;
                    loadAdminScripts(); // Cargar scripts específicos
                })
                .catch(error => console.error("Error cargando navbarAdmin:", error));

        } else if (rol === "staff") {
            console.log("Cargando navbar para STAFF...");
            fetch("sidebarStaff.html") // Asegúrate de que este archivo existe

                .then(response => response.text())
                .then(html => {
                    navbarContainer.innerHTML = html;
                    loadStaffScripts(); // Cargar scripts específicos
                })

                .catch(error => console.error("Error cargando navbarStaff:", error));
        } else {
            console.log("Rol no identificado, no se carga ningún navbar.");
        }
    })
    .catch(error => console.error('Error al obtener los datos del usuario:', error));
});

// Función para cargar scripts de Admin después de insertar el navbar
function loadAdminScripts() {
    let script = document.createElement("script");
    script.src = "../../js/navbarAdmin.js";
    script.src = "../../js/sidebar.js";
    document.body.appendChild(script);
}

// Función para cargar scripts de Staff después de insertar el navbar
function loadStaffScripts() {
    let script = document.createElement("script");
    script.src = "../../js/sidebarStaff.js";
    script.src = "../../js/sidebar.js";
    document.body.appendChild(script);
}


$(document).ready(function() {
    $('.check-asistencia').on('change', function() {
        let id = $(this).data('id');
        let estado = $(this).is(':checked') ? 1 : 0;
        $.ajax({
            url: 'updateAsistencia.php',
            type: 'POST',
            data: { id: id, check_asistencia: estado },
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function() {
        setInterval(function() {
            location.reload(); // Recarga la página cada minuto para actualizar estados
        }, 60000);
    });
</script>

</body>
</html>
