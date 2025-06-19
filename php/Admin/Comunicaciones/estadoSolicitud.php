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
require_once 'includes/obtener_estados_proyecto.php'; // Este archivo contiene la consulta SQL y llena $data

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
    <title>Estado de Solicitud</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <style>
        /* Estilos generales */
        body {
            overflow-x: hidden;
            background-color: #f8f9fa;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            background-color: #198754;
            color: white;
            z-index: 1000;
            transition: all 0.3s;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 5px;
            padding: 10px 15px;
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

        /* Contenido principal */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
            min-height: 100vh;
        }

        /* Navbar superior */
        .top-navbar {
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* Ajustes para móviles */
        @media (max-width: 991.98px) {
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }

            .main-content {
                margin-left: 0;
            }
        }

        /* Estilos específicos del contenido */
        .status-card {
            border-left: 4px solid;
            transition: all 0.3s ease;
        }

        .status-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .status-pending {
            border-left-color: #ffc107;
        }

        .status-approved {
            border-left-color: #28a745;
        }

        .status-rejected {
            border-left-color: #dc3545;
        }

        .status-review {
            border-left-color: #17a2b8;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -30px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #6c757d;
            border: 2px solid #fff;
        }

        .timeline-item.completed::before {
            background: #28a745;
        }

        .timeline-item.current::before {
            background: #007bff;
            width: 16px;
            height: 16px;
            left: -32px;
            top: 3px;
        }

        /* Colores para los estados */
        .estado-1 {
            /* Borrador */
            background-color: #6c757d;
            color: white;
        }

        .estado-2 {
            /* Realizado */
            background-color: #0d6efd;
            color: white;
        }

        .estado-3 {
            /* En Revisión */
            background-color: #ffc107;
            color: #212529;
        }

        .estado-4 {
            /* Aprobado */
            background-color: #198754;
            color: white;
        }

        .estado-5 {
            /* Enviado */
            background-color: #fd7e14;
            color: white;
        }

        .estado-6 {
            /* Finalizado */
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
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
                    <a class="nav-link text-warning" href="./includes/logout.php">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm top-navbar">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="d-flex align-items-center">
                    <span class="navbar-brand mb-0 h1">
                        <i class="fas fa-home me-2"></i>Inicio
                    </span>
                </div>

                <div class="d-flex">
                    <div class="dropdown">
                        <a href="#" class="text-white dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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
        <div class="container-fluid px-4 py-3">
            <div class="row mb-4">
                <div class="col">
                    <h1 class="text-center mt-3">Formulario de Iniciativa de Proyecto (FIP)</h1>
                    <h2 class="text-center h5 text-muted">Suplemento componente PPUP.1C</h2>
                    <p class="text-center fst-italic">Protocolo y Procedimiento de la Unidad de Proyectos COFODEP</p>
                </div>
            </div>

            <!-- Estado general -->
            <div class="row mb-4">
                <div class="col-md-8 mx-auto">
                    <div class="card status-card <?php echo 'status-' . strtolower($data['estado']); ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Proyecto: <span id="project-name"><?php echo $data['nombre_proyecto']; ?></span></h5>
                                    <p class="card-text">ID de solicitud: <strong><?php echo $data['numero_fip']; ?></strong></p>
                                    <p class="card-text">Fecha de envío: <strong><?php echo date("d/m/Y", strtotime($data['fecha_presentacion'])); ?></strong></p>
                                </div>
                                <div class="text-end">
                                    <span class="badge 
        <?php
        // Compara el estado y aplica la clase correspondiente
        switch (strtolower($data['estado'])) {
            case 'borrador':
                echo 'estado-1';
                break;
            case 'realizado':
                echo 'estado-2';
                break;
            case 'en revisión':
                echo 'estado-3';
                break;
            case 'aprobado':
                echo 'estado-4';
                break;
            case 'enviado':
                echo 'estado-5';
                break;
            case 'finalizado':
                echo 'estado-6';
                break;
            default:
                echo 'estado-1'; // Por defecto, poner un estado si no coincide
                break;
        }
        ?> fs-6">
                                        <?php echo $data['estado']; ?>
                                    </span>
                                    <p class="text-muted mb-0">Última actualización: Hoy</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Progreso -->
            <div class="row mb-4">
                <div class="col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <strong>Progreso de la Solicitud</strong>
                        </div>
                        <div class="card-body">

                            <!-- Historial de estados -->
                            <div class="timeline">
                                <?php
                                // Verificar si hay resultados del historial
                                if ($data['historial']->num_rows > 0) {
                                    while ($row = $data['historial']->fetch_assoc()) {
                                        $fechaCambio = date("d/m/Y H:i", strtotime($row['fecha_cambio']));
                                        $estado = $row['estado'];
                                        $responsable = $row['usuario_modificador'];
                                        $comentarios = $row['observaciones'];

                                        // Establecer clase de estado para el historial
                                        $statusClass = strtolower($estado);

                                        echo "<div class='timeline-item {$statusClass}'>";
                                        echo "<h5>{$estado}</h5>";
                                        echo "<p class='text-muted'>{$fechaCambio}</p>";
                                        echo "<p>{$comentarios}</p>";
                                        echo "<p><strong>Responsable:</strong> {$responsable}</p>";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "<p>No hay cambios de estado registrados para este proyecto.</p>";
                                }
                                ?>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Script para manejar el sidebar en móviles
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggler = document.querySelector('[data-bs-target="#sidebarMenu"]');
            const sidebar = document.querySelector('.sidebar');

            sidebarToggler.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });

            // Ajustar el margen superior del contenido cuando el navbar es fijo
            const navbarHeight = document.querySelector('.top-navbar').offsetHeight;
            document.querySelector('.main-content').style.paddingTop = navbarHeight + 'px';
        });
    </script>
</body>

</html>