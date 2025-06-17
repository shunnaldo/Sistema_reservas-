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
        .section-header {
            background-color: #f8f9fa;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-left: 4px solid #ffc107
        }

        .required-field::after {
            content: " *";
            color: red;
        }

        /* Ajustes para el sidebar */
        body {
            padding-left: 170px;
            /* Ancho del sidebar + margen */
            transition: all 0.3s;
        }

        .main-content {
            padding: 20px;
            width: 100%;
        }

        @media (max-width: 992px) {
            body {
                padding-left: 0;
            }
        }

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
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php include 'sideBardComunicaciones.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid py-4">
            <div class="row mb-4">
                <div class="col">
                    <br>
                    <br>
                    <h1 class="text-center">Formulario de Iniciativa de Proyecto (FIP)</h1>
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
                                    <span class="badge <?php echo 'bg-' . strtolower($data['estado']); ?> text-dark fs-6">
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
                            <div class="timeline">
                                <div class="timeline-item <?php echo ($data['estado'] == 'En Revisión') ? 'current' : ''; ?>">
                                    <h5>En Revisión Técnica</h5>
                                    <p class="text-muted"><?php echo date("d/m/Y", strtotime($data['fecha_presentacion'])); ?></p>
                                    <p>El equipo técnico está evaluando la viabilidad del proyecto.</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles de contacto -->
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <strong>Información de Contacto</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Responsable del proyecto</h5>
                                    <p><i class="bi bi-person-fill"></i> <strong>María González</strong></p>
                                    <p><i class="bi bi-envelope-fill"></i> maria.gonzalez@ejemplo.com</p>
                                    <p><i class="bi bi-telephone-fill"></i> +56 9 8765 4321</p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Contacto en COFODEP</h5>
                                    <p><i class="bi bi-person-fill"></i> <strong>Juan Pérez</strong> (Evaluador)</p>
                                    <p><i class="bi bi-envelope-fill"></i> proyectos@cofodep.cl</p>
                                    <p><i class="bi bi-telephone-fill"></i> +56 2 2345 6789</p>
                                </div>
                            </div>
                            <hr>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle-fill"></i> Para cualquier consulta sobre el estado de su solicitud, puede contactar al equipo de proyectos en el correo electrónico indicado.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial de Estados -->
            <div class="tab-pane fade" id="history" role="tabpanel">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Responsable</th>
                            <th>Comentarios</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php include 'obtener_estados_proyectos.php'; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../js/sidebar.js"></script>
</body>

</html>