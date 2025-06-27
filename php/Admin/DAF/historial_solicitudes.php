<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'proponente' || $_SESSION['area'] !== 'DAF') {
    header("Location: login.php?error=no_autorizado");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Incluir archivo que contiene la consulta y manejo de resultados
include './includes/obtener_fichas_fip.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Solicitudes - Proponente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
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
        .main-content {
            background-color: #f8f9fa;
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
        .table-responsive {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .status-approved {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #842029;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #664d03;
        }
        .table th {
            background-color: #198754;
            color: white;
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
                        <h4>Panel DAF</h4>
                    </div>
                    <ul class="nav flex-column px-3">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-home"></i> Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./ver_fichas.php">
                                <i class="fas fa-file-alt"></i>Propuestas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./../Comunicaciones/formularioTest.php">
                                <i class="fas fa-plus-circle"></i> Nueva Propuesta
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="./../Comunicaciones/historial_solicitudes.php">
                                <i class="fas fa-history"></i> Historial
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
                <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
                    <div class="container-fluid">
                        <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="d-flex align-items-center">
                            <span class="navbar-brand mb-0 h1">
                                <i class="fas fa-history me-2"></i>Historial de Solicitudes
                            </span>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="container-fluid p-4">
                    <?php if (isset($_GET['status'])): ?>
                        <div class="alert alert-<?= $_GET['status'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
                            <?= $_GET['status'] === 'success' 
                                ? ($_SESSION['success'] ?? 'Operación completada con éxito')
                                : ($_SESSION['error'] ?? 'Ocurrió un error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php 
                        unset($_SESSION['success']);
                        unset($_SESSION['error']);
                        ?>
                    <?php endif; ?>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Proyecto</th>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                            <th>Observaciones</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($resultados as $solicitud): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($solicitud['id_historial']) ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($solicitud['nombre_proyecto']) ?></strong><br>
                                                <small class="text-muted">ID: <?= htmlspecialchars($solicitud['id_proyecto']) ?></small>
                                            </td>
                                            <td><?= date('d/m/Y H:i', strtotime($solicitud['fecha_decision'])) ?></td>
                                            <td>
                                                <?php 
                                                $badgeClass = '';
                                                if ($solicitud['estado_proyecto'] === 'Aprobado') {
                                                    $badgeClass = 'status-approved';
                                                } elseif ($solicitud['estado_proyecto'] === 'Rechazado') {
                                                    $badgeClass = 'status-rejected';
                                                } else {
                                                    $badgeClass = 'status-pending';
                                                }
                                                ?>
                                                <span class="status-badge <?= $badgeClass ?>">
                                                    <?= htmlspecialchars($solicitud['estado_proyecto']) ?>
                                                </span>
                                            </td>
                                            <td><?= $solicitud['observaciones'] ? htmlspecialchars($solicitud['observaciones']) : '-' ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="tooltip" 
                                                        title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Activar tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>