<?php
session_start(); // Esto debe estar al principio de cada archivo PHP que utilice sesiones

// Verificar que el usuario está logueado y tiene los permisos adecuados
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'proponente' || $_SESSION['area'] !== 'DAF') {
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

// Incluir archivo que obtiene los detalles del proyecto
require_once 'includes/obtener_detalle_ficha.php'; // Este archivo contiene la consulta SQL y llena $data

// Verificar si los datos del proyecto existen
if (empty($data)) { // Si $data está vacío, significa que no se encontró el proyecto
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

                <!-- Page Content -->
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h1 class="mb-4 text-center">Detalle de Ficha FIP</h1>
                            <!-- Botón para generar PDF -->
                            <a href="includes/descargar_detalle_ficha.php?id_proyecto=<?php echo $id_proyecto; ?>" class="btn btn-outline-success mb-4">
                                <i class="fas fa-download me-2"></i> Exportar PDF
                            </a>


                        </div>
                    </div>

                    <!-- SECCIÓN 1: Información General -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <strong>1. Información General del Proyecto</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="data-label required-field">Nombre del Proyecto</label>
                                    <p class="form-control-plaintext">
                                        <?= htmlspecialchars($data['nombre_proyecto'] ?? 'Nombre no disponible') ?>
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <label class="data-label required-field">Fecha de Presentación</label>
                                    <p class="form-control-plaintext">
                                        <?= htmlspecialchars($data['fecha_presentacion'] ?? 'Fecha no disponible') ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="data-label required-field">Número FIP °</label>
                                    <p class="form-control-plaintext">
                                        <?= htmlspecialchars($data['numero_fip'] ?? 'Numero fip no disponible') ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="data-label required-field">Nombre Completo</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['nombre_usuario'] . ' ' . $data['apellido_usuario']) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="data-label required-field">Correo Electrónico </label>
                                    <p class="form-control-plaintext">
                                        <?= htmlspecialchars($data['correo_usuario'] ?? 'Correo no disponible') ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="data-label required-field">Telefono</label>
                                    <p class="form-control-plaintext">
                                        <?= htmlspecialchars($data['telefono'] ?? 'Telefono no disponible') ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="data-label required-field">Cargo </label>
                                    <p class="form-control-plaintext">
                                        <?= htmlspecialchars($data['cargo'] ?? 'Cargo no disponible') ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="data-label required-field">Organización</label>
                                    <p class="form-control-plaintext">
                                        <?= htmlspecialchars($data['organizacion'] ?? 'Organizacion no disponible') ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="data-label required-field">Dirección</label>
                                    <p class="form-control-plaintext">
                                        <?= htmlspecialchars($data['direccion'] ?? 'Direccion no disponible') ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 1.1: Sectores Estratégicos -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <strong>1.1 Sectores Estratégicos</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['sectores_estrategicos']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 2: Descripción del Proyecto -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <strong>2. Descripción del Proyecto</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="data-label">Objetivo General</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['objetivo_general']) ?></p>
                                </div>

                                <div class="col-12">
                                    <label class="data-label">Objetivos Específicos</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['objetivos_especificos']) ?></p>
                                </div>

                                <div class="col-12">
                                    <label class="data-label">Descripción Breve</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['descripcion_breve']) ?></p>
                                </div>

                                <div class="col-12">
                                    <label class="data-label">Población Meta/Beneficiaria</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['poblacion_meta']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 2.1: Características de la Población -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <strong>2.1 Características de la Población</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-3">
                                    <label class="data-label">Edad</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['edad'] ?? '') ?></p>
                                </div>

                                <div class="col-3">
                                    <label class="data-label">Género</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['genero'] ?? '') ?></p>
                                </div>

                                <div class="col-3">
                                    <label class="data-label">Situacion Económica</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['situacion_economica'] ?? '') ?></p>
                                </div>
                                <div class="col-3">
                                    <label class="data-label">Ubicación</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['ubicacion_poblacion'] ?? '') ?></p>
                                </div>
                                <div class="col-3">
                                    <label class="data-label">Area Prioritaria de Impacto</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['area_prioritaria'] ?? '') ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 3: Justificación del Proyecto -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <strong>3. Justificación del Proyecto</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="data-label">Descripción del Problema</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['descripcion_problema']) ?></p>
                                </div>

                                <div class="col-md-12">
                                    <label class="data-label">Oportunidad Identificada</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['oportunidad']) ?></p>
                                </div>

                                <div class="col-md-12">
                                    <label class="data-label">Impacto Anticipado</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['impacto_anticipado']) ?></p>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 4: Requerimientos del Proyecto -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <strong>4. Requerimientos y Recursos del Proyecto</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="data-label">Presupuesto Estimado (IVA Incluido)</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['presupuesto_estimado']) ?></p>
                                </div>

                                <div class="col-md-6">
                                    <label class="data-label">Componentes Principales</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['componentes_principales']) ?></p>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="section-header mb-3">Fuentes de Financiamiento</h5>
                                <div class="p-3 bg-light rounded">
                                    <?= htmlspecialchars($data['fuente_financiamiento']) ?: 'No se ha definido fuente de financiamiento' ?>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="section-header">Recursos Humanos</h5>
                                <label class="data-label">Personal Requerido</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($data['recursos_humanos']) ?></p>
                            </div>

                            <div class="col-12">
                                <label class="data-label">Infraestructura/Equipamiento</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($data['infraestructura_equipamiento']) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 5: Factibilidad del Proyecto -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <strong>5. Análisis de Factibilidad Inicial</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="data-label">Viabilidad Técnica</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['viabilidad_tecnica']) ?></p>
                                </div>

                                <div class="col-md-6">
                                    <label class="data-label">Viabilidad Económica</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['viabilidad_economica']) ?></p>
                                </div>

                                <div class="col-md-6">
                                    <label class="data-label">Viabilidad Social</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['viabilidad_social']) ?></p>
                                </div>

                                <div class="col-md-6">
                                    <label class="data-label">Viabilidad Ambiental</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['viabilidad_ambiental']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 6: Análisis de Factibilidad -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <strong>6. Análisis de Factibilidad</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="data-label">Viabilidad Técnica</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['viabilidad_tecnica_numero'] ?? '') ?></p>
                                </div>

                                <div class="col-md-6">
                                    <label class="data-label">Viabilidad Económica</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['viabilidad_economica_numero'] ?? '') ?></p>
                                </div>

                                <div class="col-md-6">
                                    <label class="data-label">Impacto Social</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['impacto_social_numero'] ?? '') ?></p>
                                </div>

                                <div class="col-md-6">
                                    <label class="data-label">Alineación con Objetivos</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($data['alineacion_objetivos'] ?? '') ?></p>
                                </div>

                                <div class="col-12">
                                    <label class="data-label">Observaciones y Recomendaciones</label>
                                    <div class="border p-2 bg-light rounded">
                                        <p class="form-control-plaintext"><?= htmlspecialchars($data['observaciones_recomendaciones'] ?? '') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>