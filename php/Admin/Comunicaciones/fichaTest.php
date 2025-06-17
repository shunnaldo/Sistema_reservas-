<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Estado de Solicitudes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <h1 class="text-center">Ficha de Requerimientos</h1>
                    <p class="text-center text-muted">Formulario para solicitud de materiales y servicios</p>
                </div>
            </div>

            <form action="procesar_requerimientos.php" method="post" enctype="multipart/form-data">

                <!-- SECCIÓN 1: INFORMACIÓN GENERAL -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <strong>1. Información General</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="numero_requerimiento" class="form-label">N° Requerimiento</label>
                                <input type="text" class="form-control" id="numero_requerimiento" name="numero_requerimiento" readonly>
                            </div>

                            <div class="col-md-4">
                                <label for="fecha" class="form-label required-field">Fecha</label>
                                <input type="date" class="form-control" id="fecha" name="fecha" required>
                            </div>

                            <div class="col-md-4">
                                <label for="tipo_requerimiento" class="form-label required-field">Tipo de Requerimiento</label>
                                <select class="form-select" id="tipo_requerimiento" name="tipo_requerimiento" required>
                                    <option value="">Seleccione...</option>
                                    <option value="COMPRAS_GENERALES">Compras Generales</option>
                                    <option value="MATERIALES">Materiales</option>
                                    <option value="SERVICIOS">Servicios</option>
                                    <option value="OTRO">Otro</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="requerente" class="form-label required-field">Área Requerente</label>
                                <select class="form-select" id="requerente" name="requerente" required>
                                    <option value="">Seleccione...</option>
                                    <option value="ADM">Administración</option>
                                    <option value="FIT">Finanzas</option>
                                    <option value="PYT">Proyectos</option>
                                    <option value="COM">Comunicaciones</option>
                                    <option value="SE">Servicios</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="encargado" class="form-label required-field">Encargado de Requerimiento</label>
                                <input type="text" class="form-control" id="encargado" name="encargado" required>
                            </div>

                            <div class="col-12">
                                <label for="descripcion" class="form-label required-field">Descripción del Requerimiento</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
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

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../js/sidebar.js"></script>

    <script>
        // Script para agregar filas dinámicas a la tabla
        document.getElementById('agregar-fila').addEventListener('click', function() {
            const tbody = document.querySelector('#productos-container');
            const newRow = document.createElement('tr');

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

            tbody.appendChild(newRow);
        });

        // Generar número de requerimiento automático
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            const year = now.getFullYear();
            const randomNum = Math.floor(Math.random() * 900) + 100; // Número aleatorio entre 100-999
            document.getElementById('numero_requerimiento').value = `${randomNum}/${year}`;
            document.getElementById('fecha').valueAsDate = now;
        });
    </script>
</body>

</html>