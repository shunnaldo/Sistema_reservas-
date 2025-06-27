<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'proponente' || $_SESSION['area'] !== 'DAF') {
    header("Location: login.php");
    exit();
}

$id_proyecto = $_GET['id_proyecto'] ?? null;
if (!$id_proyecto) {
    echo "ID de proyecto no válido.";
    exit();
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Estado de Solicitud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        /* Nuevos estilos para la sección de confirmación */
        .confirmation-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .confirmation-header {
            background-color: #198754;
            color: white;
            padding: 1.5rem;
        }

        .confirmation-body {
            padding: 2rem;
        }

        .confirmation-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #198754;
        }

        .confirmation-title {
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .confirmation-text {
            margin-bottom: 2rem;
            color: #555;
        }

        .btn-confirm {
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            min-width: 120px;
        }

        .confirmation-checkbox {
            margin: 1.5rem 0;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #198754;
        }

        .btn-group-confirm {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .btn-group-confirm {
                flex-direction: column;
            }

            .btn-group-confirm .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (se mantiene igual) -->
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
                <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h1">
                            <i class="fas fa-hourglass-half me-2"></i>Confirmar Proyecto
                        </span>
                    </div>
                </nav>

                <div class="container-fluid p-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card confirmation-card">
                                <div class="confirmation-header text-center">
                                    <h4 class="mb-0">Confirmar acción para el proyecto #<?php echo htmlspecialchars($id_proyecto); ?></h4>
                                </div>
                                <div class="confirmation-body">
                                    <div class="text-center">
                                        <i class="fas fa-exclamation-circle confirmation-icon"></i>
                                        <h4 class="confirmation-title">¿Qué acción deseas realizar?</h4>
                                        <p class="confirmation-text">
                                            Por favor confirma tu decisión para el proyecto. Esta acción será registrada y no podrá ser revertida.
                                        </p>
                                    </div>

                                    <!-- Formulario para enviar datos -->
                                    <!-- FORMULARIO PARA APROBAR -->
                                    <form action="includes/procesar_estado_proyecto.php" method="POST">
                                        <input type="hidden" name="id_proyecto" value="<?php echo htmlspecialchars($id_proyecto); ?>">
                                        <input type="hidden" name="accion" value="aprobar">

                                        <div class="form-check my-3">
                                            <input class="form-check-input" type="checkbox" id="confirmacion_aprobar" name="confirmacion" required>
                                            <label class="form-check-label" for="confirmacion_aprobar">
                                                Confirmo que he revisado cuidadosamente este proyecto y entiendo que esta acción es irreversible.
                                            </label>
                                        </div>

                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check-circle me-2"></i> Aprobar
                                            </button>

                                            <!-- Botón para abrir el modal de rechazo -->
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#motivoRechazoModal">
                                                <i class="fas fa-times-circle me-2"></i> Rechazar
                                            </button>

                                            <a href="ver_fichas.php" class="btn btn-outline-secondary">
                                                <i class="fas fa-arrow-left me-2"></i> Cancelar
                                            </a>
                                        </div>
                                    </form>

                                    <!-- FORMULARIO PARA RECHAZAR DENTRO DEL MODAL -->
                                    <form action="includes/procesar_estado_proyecto.php" method="POST">
                                        <input type="hidden" name="id_proyecto" value="<?php echo htmlspecialchars($id_proyecto); ?>">
                                        <input type="hidden" name="accion" value="rechazar">

                                        <div class="modal fade" id="motivoRechazoModal" tabindex="-1" aria-labelledby="motivoRechazoModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg">
                                                    <div class="modal-header bg-danger text-white">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            <h5 class="modal-title mb-0" id="motivoRechazoModalLabel">Motivo de Rechazo</h5>
                                                        </div>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body py-4">
                                                        <div class="alert alert-warning d-flex align-items-center mb-4">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            <div>
                                                                Por favor, proporcione una explicación clara del motivo de rechazo para que el proponente pueda realizar las correcciones necesarias.
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="motivo_rechazo" class="form-label fw-semibold">Motivo:</label>
                                                            <textarea class="form-control border-2 border-danger-subtle"
                                                                id="motivo_rechazo"
                                                                name="motivo_rechazo"
                                                                rows="5"
                                                                placeholder="Describa el motivo del rechazo..."
                                                                required></textarea>
                                                            <div class="form-text mt-1">Este texto será enviado al proponente del proyecto.</div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer border-top-0">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-1"></i> Cancelar
                                                        </button>
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-ban me-1"></i> Confirmar Rechazo
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>