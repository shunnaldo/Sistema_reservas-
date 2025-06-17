<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Sistema</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-success shadow-lg">
                    <div class="card-header bg-success text-white text-center py-4">
                        <!-- Logo visible usando un icono de Font Awesome -->
                        <i class="fas fa-user-shield fa-4x mb-3"></i>
                        <h2><i class="fas fa-sign-in-alt"></i> Acceso al Sistema</h2>
                    </div>
                    <div class="card-body p-4">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?php echo htmlspecialchars($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="/CasaEmprender/Sistema_reservas-/PHP/Admin/Comunicaciones/includes/procesar_login.php" class="needs-validation" novalidate>


                            <div class="mb-3">
                                <label for="correo" class="form-label">
                                    <i class="fas fa-user text-success me-2"></i>Correo electronico
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="mail" class="form-control" id="correo" name="correo"
                                        placeholder="Ingresa tu correo" required>

                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock text-success me-2"></i>Contraseña
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="contrasena" name="contrasena"
                                        placeholder="Ingresa tu contraseña" required>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Ingresar
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-transparent text-center py-3">
                        <div class="d-flex justify-content-between">
                            <!-- <a href="#" class="text-success text-decoration-none">
                                <i class="fas fa-question-circle me-1"></i>Ayuda
                            </a> -->
                            <a href="#" class="text-success text-decoration-none">
                                <i class="fas fa-key me-1"></i>Recuperar contraseña
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Validación de formulario -->
    <script>
        (function() {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')

            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>

</html>