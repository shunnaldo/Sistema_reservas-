<?php
session_start(); // Esto debe estar al principio de cada archivo PHP que utilice sesiones

// Verificar que el usuario está logueado y tiene los permisos adecuados
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'proponente' || $_SESSION['area'] !== 'Comunicaciones') {
    header("Location: login.php?error=no_autorizado");
    exit();
}

// Verificar si el id_proyecto está presente en la URL
if (!isset($_GET['id_proyecto']) || empty($_GET['id_proyecto'])) {
    echo "<p>Proyecto no encontrado o no tienes acceso a este proyecto.</p>";
    exit();
}

// Asignar id_proyecto desde la URL
$id_proyecto = $_GET['id_proyecto']; // Recuperamos el id del proyecto desde la URL

// Verificar si los datos del proyecto están disponibles

require_once 'includes/obtener_detalle_ficha_requerimientos.php';


if (!$data) {
    echo "<p>Proyecto no encontrado o no tienes acceso a este proyecto.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Ficha FIP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background-color: #198754;
            color: white;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 5px;
        }

        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: white;
            color: #198754;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .main-content {
            background-color: #f8f9fa;
        }

        /* Styling sections */
        .section-header {
            color: #28a745;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .data-label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .data-value {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #28a745;
            margin-bottom: 15px;
            min-height: 20px;
        }

        /* Styling for cards */
        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            font-size: 1.2rem;
        }
    </style>
</head>


<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar p-0">
                <div class="position-sticky pt-3">
                    <div class="text-center p-4">
                        <i class="fas fa-user-shield fa-3x mb-3"></i>
                        <h4>Panel Proponente</h4>
                        <div class="d-flex align-items-center justify-content-center mt-3">
                            <div class="user-avatar me-2">
                                J
                            </div>
                            <span>
                                <?= htmlspecialchars($_SESSION['nombre']) ?>
                                <?= isset($_SESSION['apellido']) ? ' ' . htmlspecialchars($_SESSION['apellido']) : '' ?>
                            </span>

                        </div>
                    </div>

                    <ul class="nav flex-column px-3">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
                                <i class="fas fa-home"></i> Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./ver_fichas.php">
                                <i class="fas fa-file-alt"></i> Mis Propuestas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./formularioTest.php">
                                <i class="fas fa-plus-circle"></i> Nueva Propuesta
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./includes/logout.php">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto main-content p-0">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
                    <div class="container-fluid">
                        <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="d-flex align-items-center">
                            <span class="navbar-brand mb-0 h1">
                                <i class="fas fa-home me-2"></i>Ficha de Requerimientos
                            </span>
                        </div>
                    </div>
                </nav>

                <div class="container-fluid p-4">
                    <!-- SECCIÓN 1: INFORMACIÓN GENERAL -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <strong>1. Información General</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- N° Requerimiento -->
                                <div class="col-md-4">
                                    <label for="numero_requerimiento" class="form-label">N° Requerimiento</label>
                                    <p class="form-control-plaintext" id="numero_requerimiento"><?php echo isset($data['numero_fip']) ? $data['numero_fip'] : 'N/A'; ?></p>
                                </div>

                                <!-- Fecha -->
                                <div class="col-md-4">
                                    <label for="fecha" class="form-label required-field">Fecha</label>
                                    <p class="form-control-plaintext" id="fecha"><?php echo isset($data['fecha_presentacion']) ? $data['fecha_presentacion'] : 'N/A'; ?></p>
                                </div>

                                <!-- Tipo de Requerimiento -->
                                <div class="col-md-4">
                                    <label for="tipo_requerimiento" class="form-label required-field">Tipo de Requerimiento</label>
                                    <p class="form-control-plaintext" id="tipo_requerimiento"><?php echo isset($data['tipo_requerimiento']) ? $data['tipo_requerimiento'] : 'N/A'; ?></p>
                                </div>

                                <!-- Área Requerente -->
                                <div class="col-md-6">
                                    <label for="requerente" class="form-label required-field">Área Requerente</label>
                                    <p class="form-control-plaintext" id="requerente"><?php echo isset($data['area_requerente']) ? $data['area_requerente'] : 'N/A'; ?></p>
                                </div>

                                <!-- Encargado del Requerimiento -->
                                <div class="col-md-6">
                                    <label for="encargado" class="form-label required-field">Encargado de Requerimiento</label>
                                    <p class="form-control-plaintext" id="encargado"><?php echo isset($data['encargado_requerimiento']) ? $data['encargado_requerimiento'] : 'N/A'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- SECCIÓN 2: DETALLE DE MATERIALES/SERVICIOS -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <strong>2. Detalle de Materiales/Servicios</strong>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mt-4">
                                    <thead>
                                        <tr>
                                            <th>Cantidad</th>
                                            <th>Producto/Servicio</th>
                                            <th>Especificaciones Técnicas</th>
                                            <th>Unidad</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Verificar que el array $materiales tiene datos
                                        if (count($materiales) > 0) {
                                            // Iterar sobre cada material en el array
                                            foreach ($materiales as $item) {
                                                echo "<tr>";
                                                echo "<td>" . $item['cantidad'] . "</td>";
                                                echo "<td>" . $item['producto_servicio'] . "</td>";
                                                echo "<td>" . $item['especificaciones_tecnicas'] . "</td>";
                                                echo "<td>" . $item['unidad'] . "</td>";
                                                echo "<td>" . $item['observaciones'] . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'>No hay detalles de materiales disponibles.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>



                    <!-- SECCIÓN 3: FIRMAS -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <strong>3. Declaración y Firma</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nombre_solicitante" class="form-label required-field">Nombre del Solicitante</label>
                                    <p class="form-control-plaintext" id="nombre_solicitante"><?php echo $data['encargado_requerimiento']; ?></p>
                                </div>

                                <div class="col-md-6">
                                    <label for="fecha_firma" class="form-label required-field">Fecha</label>
                                    <p class="form-control-plaintext" id="fecha_firma"><?php echo $data['fecha_presentacion']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="includes/descargar_requerimientos_csv.php?id_proyecto=<?= $id_proyecto ?>" class="btn btn-outline-success mb-4">
                                <i class="fas fa-download me-2"></i> Exportar PDF
                            </a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>