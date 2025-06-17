<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Iniciativa de Proyecto (FIP)</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .section-header {
            background-color: #f8f9fa;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745
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
                    <h1 class="text-center text-success">Formulario de Iniciativa de Proyecto (FIP)</h1>
                    <h2 class="text-center h5 text-success">Suplemento componente PPUP.1C</h2>
                    <p class="text-center fst-italic text-success">Protocolo y Procedimiento de la Unidad de Proyectos COFODEP</p>
                </div>
            </div>

            <form action="procesar_fip.php" method="post" enctype="multipart/form-data">

                <!-- SECCIÓN 1: INFORMACIÓN GENERAL -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <strong>1. Información General del Proyecto</strong>
                    </div>
                    <div class="card-body bg-light">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="nombre_proyecto" class="form-label required-field">Nombre del Proyecto</label>
                                <input type="text" class="form-control" id="nombre_proyecto" name="nombre_proyecto" required>
                            </div>

                            <div class="col-md-6">
                                <label for="fecha_presentacion" class="form-label required-field">Fecha de Presentación</label>
                                <input type="date" class="form-control" id="fecha_presentacion" name="fecha_presentacion" required>
                            </div>

                            <div class="col-md-6">
                                <label for="proponente" class="form-label">Proponente del Proyecto</label>
                                <input type="text" class="form-control" id="proponente" name="proponente">
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="section-header">Datos del Proponente</h5>
                            </div>

                            <div class="col-md-6">
                                <label for="nombre_completo" class="form-label required-field">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
                            </div>

                            <div class="col-md-6">
                                <label for="cargo" class="form-label">Cargo o Rol</label>
                                <input type="text" class="form-control" id="cargo" name="cargo" value="Comunicaciones" readonly>
                            </div>


                            <div class="col-md-6">
                                <label for="organizacion" class="form-label required-field">Organización/Institución</label>
                                <input type="text" class="form-control" id="organizacion" name="organizacion" required>
                            </div>

                            <div class="col-md-6">
                                <label for="direccion" class="form-label required-field">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" required>
                            </div>

                            <div class="col-md-6">
                                <label for="telefono" class="form-label required-field">Teléfono de Contacto</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" required>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label required-field">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="section-header">Sector(es) Estratégico(s)</h5>
                            </div>

                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="infraestructura" name="sectores[]" value="Infraestructura">
                                    <label class="form-check-label" for="infraestructura">Infraestructura</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="desarrollo_social" name="sectores[]" value="Desarrollo Social">
                                    <label class="form-check-label" for="desarrollo_social">Desarrollo Social</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="salud" name="sectores[]" value="Salud">
                                    <label class="form-check-label" for="salud">Salud</label>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="section-header">Ubicación del Proyecto</h5>
                            </div>

                            <div class="col-md-6">
                                <label for="ubicacion" class="form-label">Ubicación</label>
                                <input type="text" class="form-control" id="ubicacion" name="ubicacion">
                            </div>

                            <div class="col-md-6">
                                <label for="region" class="form-label">Región</label>
                                <input type="text" class="form-control" id="region" name="region">
                            </div>

                            <div class="col-md-6">
                                <label for="municipio" class="form-label">Municipio</label>
                                <input type="text" class="form-control" id="municipio" name="municipio">
                            </div>

                            <div class="col-md-6">
                                <label for="sector_comuna" class="form-label">Sector Comuna Unidad Vecinal</label>
                                <select class="form-control" id="sector_comuna" name="sector_comuna">
                                    <option value="">Seleccione una opción</option>
                                    <option value="N/A">N/A</option>
                                    <option value="Total Comuna">Total Comuna</option>
                                    <?php
                                    for ($i = 1; $i <= 30; $i++) {
                                        echo '<option value="UV ' . $i . '">UV ' . $i . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ámbito Territorial</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="territorio_total" name="ambito_territorial" value="Total comunal">
                                    <label class="form-check-label" for="territorio_total">Total comunal</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="territorio_parcial" name="ambito_territorial" value="Parcial">
                                    <label class="form-check-label" for="territorio_parcial">Parcial</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="duracion" class="form-label">Duración Estimada</label>
                                <input type="text" class="form-control" id="duracion" name="duracion" placeholder="Ej: 6 meses / 1 año">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 2: DESCRIPCIÓN DEL PROYECTO -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <strong>2. Descripción del Proyecto</strong>
                    </div>
                    <div class="card-body bg-light">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="objetivo_general" class="form-label required-field">Objetivo General</label>
                                <textarea class="form-control" id="objetivo_general" name="objetivo_general" rows="4" required></textarea>
                            </div>

                            <div class="col-12">
                                <label for="objetivos_especificos" class="form-label required-field">Objetivos Específicos (separar con punto y coma)</label>
                                <textarea class="form-control" id="objetivos_especificos" name="objetivos_especificos" rows="4" required></textarea>
                            </div>

                            <div class="col-12">
                                <label for="descripcion" class="form-label required-field">Descripción Breve</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="6" required></textarea>
                            </div>

                            <div class="col-12">
                                <label for="poblacion_meta" class="form-label required-field">Población Meta/Beneficiaria</label>
                                <textarea class="form-control" id="poblacion_meta" name="poblacion_meta" rows="3" required></textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="section-header">Características de la Población</h5>
                            </div>

                            <div class="col-md-3">
                                <label for="edad" class="form-label">Edad</label>
                                <input type="text" class="form-control" id="edad" name="edad">
                            </div>

                            <div class="col-md-3">
                                <label for="genero" class="form-label">Género</label>
                                <input type="text" class="form-control" id="genero" name="genero">
                            </div>

                            <div class="col-md-3">
                                <label for="situacion_economica" class="form-label">Situación Económica</label>
                                <input type="text" class="form-control" id="situacion_economica" name="situacion_economica">
                            </div>

                            <div class="col-md-3">
                                <label for="ubicacion_poblacion" class="form-label">Ubicación</label>
                                <input type="text" class="form-control" id="ubicacion_poblacion" name="ubicacion_poblacion">
                            </div>

                            <div class="col-12">
                                <label for="areas_impacto" class="form-label">Áreas Prioritarias de Impacto</label>
                                <textarea class="form-control" id="areas_impacto" name="areas_impacto" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 3: JUSTIFICACIÓN -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <strong>3. Justificación del Proyecto</strong>
                    </div>
                    <div class="card-body bg-light">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="problema" class="form-label required-field">Descripción del Problema/Necesidad</label>
                                <textarea class="form-control" id="problema" name="problema" rows="5" required></textarea>
                            </div>

                            <div class="col-12">
                                <label for="oportunidad" class="form-label required-field">Oportunidad Identificada</label>
                                <textarea class="form-control" id="oportunidad" name="oportunidad" rows="5" required></textarea>
                            </div>

                            <div class="col-12">
                                <label for="impacto" class="form-label required-field">Impacto Anticipado</label>
                                <textarea class="form-control" id="impacto" name="impacto" rows="5" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 4: REQUERIMIENTOS -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <strong>4. Requerimientos y Recursos del Proyecto</strong>
                    </div>
                    <div class="card-body bg-light">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="presupuesto" class="form-label">Presupuesto Estimado</label>
                                <input type="text" class="form-control" id="presupuesto" name="presupuesto">
                            </div>

                            <div class="col-md-6">
                                <label for="componentes" class="form-label">Componentes Principales</label>
                                <textarea class="form-control" id="componentes" name="componentes" rows="3"></textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="section-header">Fuentes de Financiamiento</h5>
                            </div>

                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="recursos_propios" name="financiamiento[]" value="Recursos Propios">
                                    <label class="form-check-label" for="recursos_propios">Recursos Propios</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="donaciones" name="financiamiento[]" value="Donaciones">
                                    <label class="form-check-label" for="donaciones">Donaciones</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="prestamos" name="financiamiento[]" value="Préstamos">
                                    <label class="form-check-label" for="prestamos">Préstamos</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="fondos_gubernamentales" name="financiamiento[]" value="Fondos Gubernamentales">
                                    <label class="form-check-label" for="fondos_gubernamentales">Fondos Gubernamentales</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="subvencion" name="financiamiento[]" value="Subvención Municipal">
                                    <label class="form-check-label" for="subvencion">Subvención Municipal</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="otro_financiamiento" name="financiamiento[]" value="Otro">
                                    <label class="form-check-label" for="otro_financiamiento">Otro:</label>
                                    <input type="text" class="form-control form-control-sm mt-1" id="otro_financiamiento_espec" name="otro_financiamiento_espec">
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="section-header">Recursos Humanos</h5>
                            </div>

                            <div class="col-12">
                                <label for="personal" class="form-label">Personal Requerido</label>
                                <textarea class="form-control" id="personal" name="personal" rows="3"></textarea>
                            </div>

                            <div class="col-12">
                                <label for="infraestructura_necesaria" class="form-label">Infraestructura/Equipamiento</label>
                                <textarea class="form-control" id="infraestructura_necesaria" name="infraestructura_necesaria" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 5: FACTIBILIDAD -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <strong>5. Análisis de Factibilidad Inicial</strong>
                    </div>
                    <div class="card-body bg-light">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="viabilidad_tecnica" class="form-label">Viabilidad Técnica</label>
                                <textarea class="form-control" id="viabilidad_tecnica" name="viabilidad_tecnica" rows="3"></textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="viabilidad_economica" class="form-label">Viabilidad Económica</label>
                                <textarea class="form-control" id="viabilidad_economica" name="viabilidad_economica" rows="3"></textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="viabilidad_social" class="form-label">Viabilidad Social</label>
                                <textarea class="form-control" id="viabilidad_social" name="viabilidad_social" rows="3"></textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="viabilidad_ambiental" class="form-label">Viabilidad Ambiental</label>
                                <textarea class="form-control" id="viabilidad_ambiental" name="viabilidad_ambiental" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 6: FIRMAS -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <strong>7. Declaración y Firma del Proponente</strong>
                    </div>
                    <div class="card-body bg-light">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombre_proponente" class="form-label required-field">Nombre del Proponente</label>
                                <input type="text" class="form-control" id="nombre_proponente" name="nombre_proponente" required>
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
                                    <label class="form-check-label" for="declaracion">Declaro que la información proporcionada en este formulario es verídica y que estoy autorizado para presentar esta iniciativa en nombre de la organización o institución representada.</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-5">
                    <button type="reset" class="btn btn-outline-warning me-md-2">Limpiar Formulario</button>
                    <button type="submit" class="btn btn-success">Enviar Formulario</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../js/sidebar.js"></script>
</body>

</html>