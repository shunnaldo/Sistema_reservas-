<?php
session_start(); // Inicia la sesión

// Verifica si el usuario está logueado y tiene el rol adecuado (proponente) y área (DAF)
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'proponente' || $_SESSION['area'] !== 'DAF') {
    // Si no está logueado, no tiene el rol adecuado o el área no es DAF, redirige al login
    header("Location: login.php?error=no_autorizado");
    exit();  // Asegúrate de llamar a exit() para que no se siga ejecutando el script
}
include './includes/notificaciones_recientes.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - DAF</title>
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
                        <h4>Panel DAF</h4>
                        <div class="d-flex align-items-center justify-content-center mt-3">
                            <div class="user-avatar me-2">
                                D
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
                                <i class="fas fa-file-alt"></i> Ver Propuestas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./informes.php">
                                <i class="fas fa-chart-line"></i> Informes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-cogs"></i> Configuración
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
                                            <p>Este es tu panel de control como Director. Desde aquí puedes:</p>
                                            <ul>
                                                <li>Ver y aprobar propuestas</li>
                                                <li>Ver informes detallados</li>
                                                <li>Administrar configuraciones</li>
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
                                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Propuestas Pendientes</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="p-3 bg-light rounded">
                                                <h3 class="text-success">5</h3>
                                                <small>Pendientes</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-3 bg-light rounded">
                                                <h3 class="text-success">3</h3>
                                                <small>Aprobadas</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-3 bg-light rounded">
                                                <h3 class="text-success">2</h3>
                                                <small>Rechazadas</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <canvas id="propuestasChart" height="150"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-success">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Notificaciones Recientes</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        <?php

                                        // Mostrar las notificaciones de los proyectos recientes
                                        if (!empty($proyectos)):
                                            foreach ($proyectos as $proyecto): ?>
                                                <a class="list-group-item list-group-item-action">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h6 class="mb-1">Proyecto #<?php echo $proyecto['numero_fip']; ?></h6>
                                                        <small class="text-success"><?php echo date("d/m/Y", strtotime($proyecto['fecha_presentacion'])); ?></small>
                                                    </div>
                                                    <p class="mb-1">El proyecto "Proyecto <?php echo $proyecto['nombre_proyecto']; ?> con número <?php echo $proyecto['numero_fip']; ?>" está <strong>por revisar</strong>.</p>
                                                </a>
                                            <?php endforeach;
                                        else: ?>
                                            <p class="text-center py-3">No hay proyectos recientes para revisar.</p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-center p-3">
                                        <a href="#" class="btn btn-success btn-sm">
                                            <i class="fas fa-list me-1"></i> Ver Todas
                                        </a>
                                    </div>
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
                                        <a href="#" class="btn btn-success m-2">
                                            <i class="fas fa-check-circle me-1"></i> Aprobar Propuesta
                                        </a>
                                        <a href="#" class="btn btn-outline-success m-2">
                                            <i class="fas fa-chart-line me-1"></i> Ver Informes
                                        </a>
                                        <a href="#" class="btn btn-outline-success m-2">
                                            <i class="fas fa-cogs me-1"></i> Configuración
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
                labels: ['Pendientes', 'Aprobadas', 'Rechazadas'],
                datasets: [{
                    data: [5, 3, 2],
                    backgroundColor: [
                        '#ffc107',
                        '#198754',
                        '#dc3545'
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