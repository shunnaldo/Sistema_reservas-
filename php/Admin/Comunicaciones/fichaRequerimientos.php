<?php
session_start();

// Verifica si el usuario está logueado y tiene el rol adecuado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'proponente' || $_SESSION['area'] !== 'Comunicaciones') {
    header("Location: login.php?error=no_autorizado");
    exit();
}

// Verifica si el id_proyecto está en la sesión
if (!isset($_SESSION['id_proyecto'])) {
    header("Location: formularioTest.php");
    exit();
}

$id_proyecto = $_SESSION['id_proyecto'];

// Conexión a la base de datos
require_once 'bd/conexion_test.php';

// Recuperamos los datos del proyecto
$sql = "SELECT * FROM proyecto WHERE id_proyecto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_proyecto);
$stmt->execute();
$result = $stmt->get_result();
$proyecto = $result->fetch_assoc();

// Obtener el área y nombre del usuario (encargado del requerimiento)
$sqlUsuario = "SELECT nombre, apellido, area FROM usuarios WHERE id_usuario = ?";
$stmtUsuario = $conn->prepare($sqlUsuario);
$stmtUsuario->bind_param("i", $_SESSION['id_usuario']);
$stmtUsuario->execute();
$resultUsuario = $stmtUsuario->get_result();
$usuario = $resultUsuario->fetch_assoc();
$encargado = $usuario['nombre'] . ' ' . $usuario['apellido'];
$area_requerente = $usuario['area'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Requerimientos</title>
    <!-- Carga de Bootstrap desde CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0-alpha1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .section-header {
            background-color: #f8f9fa;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-left: 4px solid #ffc107;
        }

        .required-field::after {
            content: " *";
            color: red;
        }

        .main-content {
            padding: 20px;
            width: 100%;
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

        .form-select {
            border: 1px solid #ddd;
            /* Borde suave */
            border-radius: 4px;
            /* Esquinas redondeadas */
            padding: 8px 12px;
            /* Espaciado interno */
            font-size: 1rem;
            /* Tamaño de fuente cómodo */
            transition: border-color 0.3s ease;
            /* Transición suave en el borde */
        }

        .form-select:focus {
            border-color: #198754;
            /* Color verde cuando está enfocado */
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
            /* Sombra suave */
        }

        .form-label {
            font-weight: bold;
            color: #333;
            /* Color de la etiqueta */
        }

        .required-field::after {
            content: " *";
            color: red;
        }

        /* Estilo para el campo cuando el usuario no lo ha seleccionado */
        .form-select option {
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
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="container-fluid p-4">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h1 class="text-center">Ficha de Requerimientos</h1>
                            <p class="text-center text-muted">Formulario para solicitud de materiales y servicios</p>
                        </div>
                    </div>

                    <form action="./includes/procesar_requerimientos.php" method="post" enctype="multipart/form-data">

                        <!-- SECCIÓN 1: INFORMACIÓN GENERAL -->
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <strong>1. Información General</strong>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="numero_requerimiento" class="form-label">N° Requerimiento</label>
                                        <input type="text" class="form-control" id="numero_requerimiento" name="numero_requerimiento" readonly value="<?php echo $proyecto['numero_fip']; ?>">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="fecha" class="form-label required-field">Fecha</label>
                                        <input type="date" class="form-control" id="fecha" name="fecha" required value="<?php echo $proyecto['fecha_presentacion']; ?>">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="tipo_requerimiento" class="form-label required-field">Tipo de Requerimiento</label>
                                        <select class="form-select" id="tipo_requerimiento" name="tipo_requerimiento" required>
                                            <option value="COMPRAS_GENERALES">Compras Generales</option>
                                            <option value="MATERIALES">Materiales</option>
                                            <option value="SERVICIOS">Servicios</option>
                                            <option value="OTRO">Otro</option>
                                        </select>
                                    </div>


                                    <div class="col-md-6">
                                        <label for="requerente" class="form-label required-field">Área Requerente</label>
                                        <input type="text" class="form-control" id="requerente" name="requerente" value="<?php echo $area_requerente; ?>" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="encargado" class="form-label required-field">Encargado de Requerimiento</label>
                                        <input type="text" class="form-control" id="encargado" name="encargado" value="<?php echo $encargado; ?>" readonly>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 2: DETALLE DE MATERIALES/SERVICIOS -->
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <strong>2. Detalle de Materiales/Servicios</strong>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered product-table">
                                        <thead>
                                            <tr>
                                                <th width="10%">Cantidad</th>
                                                <th width="25%">Producto/Servicio</th>
                                                <th width="25%">Especificaciones Técnicas</th>
                                                <th width="15%">Unidad</th>
                                                <th width="25%">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="productos-container">
                                            <tr>
                                                <td><input type="number" class="form-control" name="cantidad[]" min="1" required></td>
                                                <td><input type="text" class="form-control" name="producto[]" required></td>
                                                <td><input type="text" class="form-control" name="especificaciones[]"></td>
                                                <td>
                                                    <select class="form-select" name="unidad[]">
                                                        <option value="unidad">Unidad</option>
                                                        <option value="metro">Metro</option>
                                                        <option value="kg">Kilogramo</option>
                                                        <option value="l">Litro</option>
                                                        <option value="paquete">Paquete</option>
                                                        <option value="rollo">Rollo</option>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="observaciones[]"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="agregar-fila">
                                    + Agregar otro ítem
                                </button>
                            </div>
                        </div>

                        <!-- SECCIÓN 3: FIRMAS -->
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <strong>3. Declaración y Firma</strong>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nombre_solicitante" class="form-label required-field">Nombre del Solicitante</label>
                                        <input type="text" class="form-control" id="nombre_solicitante" name="nombre_solicitante" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="fecha_firma" class="form-label required-field">Fecha</label>
                                        <input type="date" class="form-control" id="fecha_firma" name="fecha_firma" required>
                                    </div>

                                    <div class="col-12">
                                        <label for="firma" class="form-label">Firma (subir imagen)</label>
                                        <input class="form-control" type="file" id="firma" name="firma" accept="image/*">
                                    </div>

                                    <div class="col-12 mt-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="declaracion" name="declaracion" required>
                                            <label class="form-check-label" for="declaracion">Declaro que la información proporcionada en este formulario es verídica y que los materiales/servicios solicitados son necesarios para el desarrollo de las actividades institucionales.</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-5">
                            <button type="reset" class="btn btn-outline-warning me-md-2">Limpiar Formulario</button>
                            <button type="submit" class="btn btn-secondary">Enviar Requerimiento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Carga de Bootstrap desde CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</body>
<script>
    document.getElementById('agregar-fila').addEventListener('click', function() {
        const tbody = document.querySelector('#productos-container'); // Selecciona el tbody donde se agregan las filas
        const newRow = document.createElement('tr'); // Crea una nueva fila

        newRow.innerHTML = `
            <td><input type="number" class="form-control" name="cantidad[]" min="1" required></td>
            <td><input type="text" class="form-control" name="producto[]" required></td>
            <td><input type="text" class="form-control" name="especificaciones[]"></td>
            <td>
                <select class="form-select" name="unidad[]">
                    <option value="unidad">Unidad</option>
                    <option value="metro">Metro</option>
                    <option value="kg">Kilogramo</option>
                    <option value="l">Litro</option>
                    <option value="paquete">Paquete</option>
                    <option value="rollo">Rollo</option>
                </select>
            </td>
            <td><input type="text" class="form-control" name="observaciones[]"></td>
        `;

        tbody.appendChild(newRow); // Añade la nueva fila al tbody
    });
</script>

</html>