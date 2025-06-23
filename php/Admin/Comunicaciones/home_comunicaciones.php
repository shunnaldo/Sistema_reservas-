<?php
session_start(); // Inicia la sesión

// Verifica si el usuario está logueado y tiene el rol adecuado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'proponente' || $_SESSION['area'] !== 'Comunicaciones') {
    // Si no está logueado o no tiene el rol adecuado, redirige al login
    header("Location: /CasaEmprender/Sistema_reservas-/PHP/Admin/Public/login.php");
    exit();  // Asegúrate de llamar a exit() para que no se siga ejecutando el script
}
include './includes/notificaciones_recientes.php';

include './includes/contador_propuestas.php';

// Variables para los diferentes estados
$aprobado = isset($estado_proyectos['Aprobado']) ? $estado_proyectos['Aprobado'] : 0;
$borrador = isset($estado_proyectos['Borrador']) ? $estado_proyectos['Borrador'] : 0;
$en_revision = isset($estado_proyectos['En Revisión']) ? $estado_proyectos['En Revisión'] : 0;
$enviado = isset($estado_proyectos['Enviado']) ? $estado_proyectos['Enviado'] : 0;
$finalizado = isset($estado_proyectos['Finalizado']) ? $estado_proyectos['Finalizado'] : 0;
$realizado = isset($estado_proyectos['Realizado']) ? $estado_proyectos['Realizado'] : 0;
$rechazado = isset($estado_proyectos['Rechazado']) ? $estado_proyectos['Rechazado'] : 0;
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Proponente</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                            <a class="nav-link" href="././formularioTest.php">
                                <i class="fas fa-plus-circle"></i> Nueva Propuesta
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-bell"></i> Loren
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-chart-line"></i> Lorem
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-cog"></i> Lorem
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <!-- Enlace de Logout -->
                            <a class="nav-link text-warning" href="./includes/logout.php">
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
                                <i class="fas fa-home me-2"></i>Inicio
                            </span>
                        </div>

                        <div class="d-flex">
                            <div class="dropdown">
                                <a href="#" class="text-white dropdown-toggle" id="userDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i>
                                    <span>
                                        <?= htmlspecialchars($_SESSION['nombre']) ?>
                                        <?= isset($_SESSION['apellido']) ? ' ' . htmlspecialchars($_SESSION['apellido']) : '' ?>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Perfil</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Configuración</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item text-danger" href="./includes/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="container-fluid p-4">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Bienvenido<span>
                                            <?= htmlspecialchars($_SESSION['nombre']) ?>
                                            <?= isset($_SESSION['apellido']) ? ' ' . htmlspecialchars($_SESSION['apellido']) : '' ?>
                                        </span></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p>Este es tu panel de control como proponente. Desde aquí puedes:</p>
                                            <ul>
                                                <li>Crear nuevas propuestas</li>
                                                <li>Administrar tus propuestas existentes</li>
                                                <li>Ver el estado de tus propuestas</li>
                                                <li>Recibir notificaciones importantes</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Ilustración" class="img-fluid" style="max-height: 200px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Resumen de Propuestas -->

                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-success">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Mis Propuestas</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <!-- Mostramos los estados en una fila -->
                                        <div class="col">
                                            <span class="badge bg-success"><?= $aprobado ?> Aprobadas</span>
                                            <span class="badge bg-danger"><?= $rechazado ?> Rechazadas</span>
                                            <span class="badge bg-warning"><?= $en_revision ?> En Revisión</span>
                                            <span class="badge bg-secondary"><?= $borrador ?> Borrador</span>
                                            <span class="badge bg-info"><?= $enviado ?> Enviado</span>
                                            <span class="badge bg-dark"><?= $finalizado ?> Finalizado</span>
                                            <span class="badge bg-primary"><?= $realizado ?> Realizado</span>
                                        </div>
                                    </div>

                                    <!-- Gráfico -->
                                    <div class="mt-4">
                                        <canvas id="propuestasChart" height="150"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Notificaciones Recientes -->

                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-success">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Notificaciones Recientes</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($notificaciones as $notificacion): ?>
                                            <a  class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1"><?= htmlspecialchars($notificacion['observaciones']) ?></h6>
                                                    <small class="text-success"><?= date('d/m/Y', strtotime($notificacion['fecha_cambio'])) ?></small>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="text-center p-3">
                                        <a href="#" class="btn btn-success btn-sm">
                                            <i class="fas fa-list me-1"></i> Ver Todas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones Rápidas -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Acciones Rápidas</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap justify-content-center">
                                            <a href="././formularioTest.php" class="btn btn-success m-2">
                                                <i class="fas fa-plus-circle me-1"></i> Nueva Propuesta
                                            </a>
                                            <a href="#" class="btn btn-outline-success m-2">
                                                <i class="fas fa-question-circle me-1"></i> Centro de Ayuda
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap 5 JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            // Gráfico de propuestas
            const ctx = document.getElementById('propuestasChart').getContext('2d');
            const propuestasChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Aprobadas', 'Rechazadas', 'En Revisión', 'Borrador', 'Enviado', 'Finalizado', 'Realizado'],
                    datasets: [{
                        data: [<?= $aprobado ?>, <?= $rechazado ?>, <?= $en_revision ?>, <?= $borrador ?>, <?= $enviado ?>, <?= $finalizado ?>, <?= $realizado ?>],
                        backgroundColor: [
                            '#198754', // Aprobadas
                            '#dc3545', // Rechazadas
                            '#ffc107', // En Revisión
                            '#6c757d', // Borrador
                            '#17a2b8', // Enviado
                            '#28a745', // Finalizado
                            '#007bff' // Realizado
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        </script>

</body>

</html>