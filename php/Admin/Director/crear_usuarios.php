<?php
session_start();

// Verificar si el usuario está logueado y tiene el rol adecuado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'director') {
    header("Location: login.php?error=no_autorizado");
    exit();
}

require_once(__DIR__ . '/../Comunicaciones/bd/conexion_test.php');
include './includes/crear_usuario_logica.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #198754;
            --secondary-color: #146c43;
            --light-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background-color: var(--primary-color);
            color: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 5px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 500;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: white;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .main-content {
            background-color: var(--light-color);
            min-height: 100vh;
        }

        /* Diseño mejorado del formulario */
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }

        .form-title {
            color: var(--primary-color);
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .form-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background-color: var(--primary-color);
            border-radius: 2px;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-radius: 8px 0 0 8px !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .password-container {
            position: relative;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            font-weight: 600;
            border-bottom: 3px solid var(--primary-color);
        }

        .nav-tabs .nav-link {
            color: #6c757d;
            border: none;
            padding: 10px 20px;
        }

        .nav-tabs {
            border-bottom: 1px solid #dee2e6;
        }

        .alert {
            border-radius: 8px;
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
                        <h4>Panel Director</h4>
                        <div class="d-flex align-items-center justify-content-center mt-3">
                            <div class="user-avatar me-2">
                                <?= strtoupper(substr(htmlspecialchars($_SESSION['nombre']), 0, 1)) ?>
                            </div>
                            <span>
                                <?= htmlspecialchars($_SESSION['nombre']) ?>
                                <?= isset($_SESSION['apellido']) ? ' ' . htmlspecialchars($_SESSION['apellido']) : '' ?>
                            </span>
                        </div>
                    </div>

                    <ul class="nav flex-column px-3">
                        <li class="nav-item">
                            <a class="nav-link" href="./index.php">
                                <i class="fas fa-home"></i> Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./ver_fichas.php">
                                <i class="fas fa-file-alt"></i> Ver Propuestas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="./gestionar_usuarios.php">
                                <i class="fas fa-users"></i> Gestionar Usuarios
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
                                <i class="fas fa-user-plus me-2"></i>Crear Nuevo Usuario
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
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <?php if (!empty($success_message)): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i><?= $success_message ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($error_message)): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i><?= $error_message ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <div class="form-container">
                                <h2 class="form-title">Información del Usuario</h2>

                                <form method="POST" action="">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nombre" class="form-label">Nombre</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Ej: Juan">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="apellido" class="form-label">Apellido</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                <input type="text" class="form-control" id="apellido" name="apellido" required placeholder="Ej: Pérez">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="correo" class="form-label">Correo Electrónico</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" class="form-control" id="correo" name="correo" required placeholder="Ej: usuario@ejemplo.com">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="clave" class="form-label">Contraseña</label>
                                        <div class="password-container">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                <input type="password" class="form-control" id="clave" name="clave" required placeholder="Mínimo 8 caracteres">
                                            </div>
                                            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                                        </div>
                                        <small class="text-muted">La contraseña debe contener al menos 8 caracteres</small>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="rol" class="form-label">Rol</label>
                                            <select class="form-select" id="rol" name="rol" required>
                                                <option value="" selected disabled>Seleccione un rol</option>
                                                <option value="director">Director</option>
                                                <option value="proponente">Proponente</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label for="area" class="form-label">Área</label>
                                            <select class="form-select" id="area" name="area" required>
                                                <option value="" selected disabled>Seleccione un área</option>
                                                <option value="Comunicaciones">Comunicaciones</option>
                                                <option value="DAF">DAF</option>
                                                <option value="FIT">FIT</option>
                                                <option value="Director">Director</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Nuevos campos de teléfono y dirección -->
                                    <div class="mb-3">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="tel" class="form-control" id="telefono" name="telefono" required placeholder="Ej: 123-456-7890">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="direccion" class="form-label">Dirección</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                            <input type="text" class="form-control" id="direccion" name="direccion" required placeholder="Ej: Calle Ficticia 123">
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                        <a href="gestionar_usuarios.php" class="btn btn-outline-secondary me-md-2">
                                            <i class="fas fa-arrow-left me-1"></i> Cancelar
                                        </a>
                                        <button type="submit" name="create_user" class="btn btn-primary">
                                            <i class="fas fa-user-plus me-1"></i> Crear Usuario
                                        </button>
                                    </div>
                                </form>
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
        // Mostrar/ocultar contraseña
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('clave');
            const icon = this;

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Validación básica del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('clave').value;

            if (password.length < 8) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 8 caracteres');
            }
        });
    </script>
</body>

</html>