<?php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'proponente' || $_SESSION['area'] !== 'DAF') {
    header("Location: ../public/login.php?error=no_autorizado");
    exit();
}


require_once __DIR__ . '/../Comunicaciones/bd/conexion_test.php';

$sql_count = "SELECT COUNT(p.id_proyecto) AS cantidad_fichas
              FROM proyecto p
              LEFT JOIN estado_fip es ON p.id_estado_actual = es.id_estado
              WHERE es.nombre_estado = 'Enviado'";

$stmt_count = $conn->prepare($sql_count);
if (!$stmt_count) {
    die("Error en prepare SQL de conteo: " . $conn->error);
}
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$data_count = $result_count->fetch_assoc();
$cantidad_fichas = $data_count['cantidad_fichas'];


$sql_projects = "SELECT 
                    p.numero_fip, 
                    p.nombre AS nombre_proyecto,
                    p.id_usuario, 
                    p.fecha_presentacion,
                    u.nombre AS nombre_usuario, 
                    u.apellido AS apellido_usuario, 
                    u.rol, 
                    u.area,
                    p.id_proyecto
                FROM proyecto p
                LEFT JOIN estado_fip es ON p.id_estado_actual = es.id_estado
                LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario
                WHERE es.nombre_estado = 'Enviado'";

// Preparar y ejecutar la consulta de proyectos
$stmt_projects = $conn->prepare($sql_projects);
if (!$stmt_projects) {
    die("Error en prepare SQL de proyectos: " . $conn->error);
}
$stmt_projects->execute();
$result_projects = $stmt_projects->get_result();

// Cerrar la conexión
$stmt_count->close();
$stmt_projects->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Fichas Enviadas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header {
            background-color: #198754;
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            border: none;
        }

        .card-header {
            background-color: #28a745;
            color: white;
            border-radius: 10px 10px 0 0 !important;
            font-weight: bold;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }

        table {
            margin-bottom: 0;
        }

        th {
            background-color: #198754;
            color: white;
            text-align: center;
            vertical-align: middle;
        }

        td {
            vertical-align: middle;
        }

        .btn-details {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .btn-details:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .count-badge {
            font-size: 1.5rem;
            background-color: #28a745;
            padding: 10px 20px;
            border-radius: 50px;
            color: white;
            display: inline-block;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .status-label {
            font-weight: bold;
            color: #28a745;
        }

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
                            <a class="nav-link active" href="dasboard_daf.php">
                                <i class="fas fa-home"></i> Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="visualizar_fichas.php">
                                <i class="fas fa-file-alt"></i> Solicitudes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../comunicaciones/includes/logout.php">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </li>

                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto main-content p-4">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
                    <div class="container-fluid">
                        <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="d-flex align-items-center">
                            <span class="navbar-brand mb-0 h1">
                                <i class="fas fa-home me-2"></i>Inicio
                            </span>
                        </div>
                    </div>
                </nav>
<br><br>
                <div class="container">
                  
                    <!-- Card para mostrar los detalles de los proyectos -->
                    <div class="card">
                        <div class="card-header text-center">
                            <h3 class="mb-0">Detalles de Proyectos</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>N° FIP</th>
                                            <th>Nombre del Proyecto</th>
                                            <th>Usuario</th>
                                            <th>Área</th>
                                            <th>Rol</th>
                                            <th>Fecha Presentación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($data_projects = $result_projects->fetch_assoc()) {
                                            echo "<tr>
                                        <td>{$data_projects['numero_fip']}</td>
                                        <td>{$data_projects['nombre_proyecto']}</td>
                                        <td>{$data_projects['nombre_usuario']} {$data_projects['apellido_usuario']}</td>
                                        <td>{$data_projects['area']}</td>
                                        <td>{$data_projects['rol']}</td>
                                        <td>{$data_projects['fecha_presentacion']}</td>
                                        <td class='text-center'>
                                            <a href='obtener_detalle_fichaDaf.php?id_proyecto={$data_projects['id_proyecto']}' class='btn btn-details'>
                                                <i class='fas fa-eye'></i> Ver Detalles
                                            </a>
                                        </td>
                                      </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Font Awesome para iconos -->
                <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
                <!-- Bootstrap JS -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>