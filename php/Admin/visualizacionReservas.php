<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: loginAdmin.php");
    exit;
}

require_once '../conexion.php';

// Configurar zona horaria
date_default_timezone_set('America/Santiago');
$now = date('Y-m-d H:i:s');

// Actualizar estado solo si es necesario
$updateQuery = "
    UPDATE Reservas 
    SET estado = CASE 
        WHEN TIMESTAMP(fecha, hora_inicio) > NOW() AND estado != 'pendiente' THEN 'pendiente'
        WHEN TIMESTAMP(fecha, hora_inicio) <= NOW() AND TIMESTAMP(fecha, hora_fin) >= NOW() AND estado != 'lista' THEN 'lista'
        WHEN TIMESTAMP(fecha, hora_fin) < NOW() AND estado != 'finalizada' THEN 'finalizada'
        ELSE estado
    END;
";
$conexion->query($updateQuery);

// Obtener reservas actualizadas
$sql = "SELECT * FROM Reservas ORDER BY 
        FIELD(estado, 'lista', 'pendiente', 'finalizada')";

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

    <input type="date" id="filtroFecha" class="form-control w-25 ms-3">
    <input type="text" id="filtroRut" class="form-control w-25 ms-3" placeholder="Filtrar por RUT">
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
                                    <input type='checkbox' class='check-asistencia' data-id='{$row['id']}' 
                                        onclick='confirmarAsistencia(this)' " . ($row['check_asistencia'] ? "checked" : "") . ">
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
    ["../../js/navbarAdmin.js", "../../js/sidebar.js"].forEach(src => {
        let script = document.createElement("script");
        script.src = src;
        document.body.appendChild(script);
    });
}

// Función para cargar scripts de Staff después de insertar el navbar
function loadStaffScripts() {
    ["../../js/sidebarStaff.js", "../../js/sidebar.js"].forEach(src => {
        let script = document.createElement("script");
        script.src = src;
        document.body.appendChild(script);
    });
}



 // Ajax
document.addEventListener("DOMContentLoaded", function () {
    setInterval(() => {
        fetch('actualizarReservas.php')
            .then(response => response.text())
            .then(data => {
                document.querySelector("#reservasTable tbody").innerHTML = data;
            })
            .catch(error => console.error("Error al actualizar reservas:", error));
    }, 300000); // Se actualiza cada 30 segundos
});

document.addEventListener("DOMContentLoaded", function () {
    function filtrarReservas() {
        let filtroRut = document.getElementById("filtroRut").value.toLowerCase().trim();
        let filtroFecha = document.getElementById("filtroFecha").value;
        let filas = document.querySelectorAll("#reservasTable tbody tr");

        filas.forEach(fila => {
            let rut = fila.cells[2].textContent.toLowerCase().trim(); // Columna RUT
            let fechaReserva = fila.cells[4].textContent.trim(); // Columna Fecha

            let mostrarPorRut = rut.includes(filtroRut) || filtroRut === "";
            let mostrarPorFecha = filtroFecha === "" || fechaReserva === filtroFecha;

            // Mostrar u ocultar la fila según los filtros
            fila.style.display = (mostrarPorRut && mostrarPorFecha) ? "" : "none";
        });
    }

    // Asignar eventos a los filtros
    document.getElementById("filtroRut").addEventListener("input", filtrarReservas);
    document.getElementById("filtroFecha").addEventListener("change", filtrarReservas);
});

function confirmarAsistencia(checkbox) {
    let id = checkbox.getAttribute("data-id");
    let estado = checkbox.checked ? 1 : 0;

    let confirmacion = confirm("¿Está seguro de confirmar la asistencia?");
    if (!confirmacion) {
        checkbox.checked = !estado; // Revertir si cancela
        return;
    }

    fetch('updateAsistencia.php', {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `id=${id}&check_asistencia=${estado}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Asistencia actualizada con éxito");
        } else {
            alert("Error al actualizar asistencia");
            checkbox.checked = !estado; // Revertir si hay error
        }
    })
    .catch(error => {
        console.error("Error:", error);
        checkbox.checked = !estado;
    });
}



</script>



</body>
</html>
