<?php
session_start(); // Inicia la sesión

// Verifica si el usuario está logueado y tiene el rol adecuado (proponente) y área (DAF)
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'proponente' || $_SESSION['area'] !== 'DAF') {
    // Si no está logueado, no tiene el rol adecuado o el área no es DAF, redirige al login
    header("Location: login.php?error=no_autorizado");
    exit();  // Asegúrate de llamar a exit() para que no se siga ejecutando el script
}

// Obtener id_usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'];  // Obtiene el id_usuario desde la sesión

// Incluir archivo que contiene la consulta y manejo de resultados
include './includes/obtener_fichas_fip.php';

// Aquí ya no es necesario volver a abrir ni cerrar la conexión, ya que lo estás manejando en consultar_fichas.php

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Fichas - Proponente</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
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
            background-color: rgb(0, 234, 255);
            color: white;
        }

        .estado-7 {
            /* Finalizado */
            background-color: #dc3545;
            color: white;
        }

        .badge-estado {
            padding: 0.35em 0.65em;
            font-size: 0.85em;
            font-weight: 600;
            border-radius: 0.25rem;
            min-width: 80px;
            display: inline-block;
            text-align: center;
        }

        .btn-action-group {
            display: flex;
            gap: 5px;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
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
                            <a class="nav-link active" href="#">
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
                            <a class="nav-link" href="./../Comunicaciones/formularioTest.php">
                                <i class="fas fa-plus-circle"></i> Emitir CDP
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
                                <i class="fas fa-home me-2"></i>Mis Fichas
                            </span>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="container-fluid p-4">
                    <!-- Filtros -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="">Seleccione estado</option>
                                    <option value="1" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 1) ? 'selected' : ''; ?>>Borrador</option>
                                    <option value="2" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 2) ? 'selected' : ''; ?>>Realizado</option>
                                    <option value="3" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 3) ? 'selected' : ''; ?>>En Revisión</option>
                                    <option value="4" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 4) ? 'selected' : ''; ?>>Aprobado</option>
                                    <option value="5" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 5) ? 'selected' : ''; ?>>Enviado</option>
                                    <option value="6" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 6) ? 'selected' : ''; ?>>Finalizado</option>
                                    <option value="7" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 7) ? 'selected' : ''; ?>>Rechazado</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : ''; ?>">
                            </div>

                            <div class="col-md-4">
                                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : ''; ?>">
                            </div>

                            <div class="col-12 mt-3 text-center">
                                <button type="submit" class="btn btn-success">Filtrar</button>
                            </div>
                        </div>
                    </form>

                    <!-- Resultados -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Resultados</h5>
                                    <span class="badge bg-light text-success"><?php echo count($fichas); ?> fichas</span>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0 success-hover" id="fichas-table">
                                            <thead class="table-success">
                                                <tr>
                                                    <th class="align-middle">Número FIP</th>
                                                    <th class="align-middle">Nombre Proyecto</th>
                                                    <th class="align-middle">Fecha Presentación</th>
                                                    <th class="align-middle">Duración</th>
                                                    <th class="align-middle">Proponente</th>
                                                    <th class="align-middle">Cargo</th>
                                                    <th class="align-middle">Organización</th>
                                                    <th class="align-middle">Estado</th>
                                                    <th class="align-middle text-end">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (count($fichas) > 0): ?>
                                                    <?php foreach ($fichas as $ficha): ?>
                                                        <tr>
                                                            <td class="fw-semibold"><?php echo $ficha['numero_fip']; ?></td>
                                                            <td><?php echo $ficha['nombre_proyecto']; ?></td>
                                                            <td><?php echo date("d/m/Y", strtotime($ficha['fecha_presentacion'])); ?></td>
                                                            <td><?php echo $ficha['duracion_valor'] . ' ' . $ficha['duracion_tipo']; ?></td>
                                                            <td><?php echo $ficha['nombre'] . " " . $ficha['apellido']; ?></td> 
                                                            <td><?php echo $ficha['rol']; ?></td> 
                                                            <td><?php echo $ficha['area']; ?></td> 
                                                            <td>
                                                                <span class="badge-estado estado-<?php echo $ficha['id_estado']; ?>">
                                                                    <?php echo $ficha['nombre_estado']; ?>
                                                                </span>
                                                            </td>
                                                            <td class="text-end">
                                                                <div class="btn-action-group">
                                                                    <!-- DETALLE FICHA -->
                                                                    <a href="detalleFicha.php?id_proyecto=<?php echo $ficha['id_proyecto']; ?>"
                                                                        class="btn btn-sm btn-outline-success btn-action"
                                                                        title="Ver Ficha FIP">
                                                                        <i class="bi bi-eye-fill"></i>
                                                                    </a>
                                                                    <!-- DETALLE REQUERIMIENTOS -->
                                                                    <a href="detalleFichaRequerimientos.php?id_proyecto=<?php echo $ficha['id_proyecto']; ?>"
                                                                        class="btn btn-sm btn-outline-success btn-action"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="Ver Ficha Requerimientos">
                                                                        <i class="bi bi-clock-history"></i>
                                                                    </a>

                                                                    <!-- ESTADO SOLICITUD -->
                                                                    <a href="estadoSolicitud.php?id_proyecto=<?php echo $ficha['id_proyecto']; ?>"
                                                                        class="btn btn-sm btn-outline-success btn-action"
                                                                        title="Estado">
                                                                        <i class="bi bi-hourglass-split"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="9" class="text-center py-5 text-muted">No se encontraron fichas.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                <nav aria-label="Paginación">
                    <ul class="pagination">
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>">Anterior</a>
                        </li>
                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>
    </div>
    </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>