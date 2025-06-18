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

$stmt_count->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fichas Enviadas - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card-fichas {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            transition: transform 0.3s;
        }

        .card-fichas:hover {
            transform: translateY(-5px);
        }

        .card-header-fichas {
            background-color: #198754;
            color: white;
            border-radius: 10px 10px 0 0 !important;
            font-weight: 600;
        }

        .count-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #28a745;
        }

        .btn-outline-custom {
            color: #28a745;
            border-color: #28a745;
        }

        .btn-outline-custom:hover {
            background-color: #28a745;
            color: white;
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
                            <a class="nav-link active" href="dashboard_daf.php">
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

                <div class="container py-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card card-fichas mb-4">
                                <div class="card-header card-header-fichas text-center py-3">
                                    <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Fichas Enviadas</h5>
                                </div>
                                <div class="card-body text-center py-4">
                                    <div class="count-number mb-2"><?php echo $cantidad_fichas; ?></div>
                                    <p class="text-muted mb-0">Proyectos en revisión</p>
                                </div>
                                <div class="card-footer bg-white text-center py-3">
                                    <a href="visualizar_fichas.php" class="btn btn-sm btn-outline-custom">
                                        <i class="fas fa-list me-1"></i> Ver todas
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>