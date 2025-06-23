<?php
session_start(); // Asegúrate de que la sesión esté iniciada

// Verifica si el usuario está logueado y tiene el rol adecuado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'proponente' || $_SESSION['area'] !== 'Comunicaciones') {
    // Si no está logueado o no tiene el rol adecuado, redirige al login
    header("Location: login.php?error=no_autorizado");
    exit();  // Asegúrate de llamar a exit() para que no se siga ejecutando el script
}
// // Recupera el id_proponente desde la sesión
// $id_proponente = $_SESSION['id_proponente'];  // El id_proponente está en la sesión

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Formulario de Iniciativa de Proyecto (FIP)</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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

    <div class="main-content">
        <div class="container-fluid py-4">
            <div class="row mb-4">
                <div class="col">
                    <br />
                    <br />
                    <br />
                    <h1 class="text-center text-success">Formulario de Iniciativa de Proyecto (FIP)</h1>
                    <h2 class="text-center h5 text-muted">Suplemento componente PPUP.1C</h2>
                    <p class="text-center fst-italic text-success">Protocolo y Procedimiento de la Unidad de Proyectos COFODEP</p>
                </div>
            </div>

            <form action="procesar_fip.php" method="post" enctype="multipart/form-data">
                <!-- SECCIÓN 1: INFORMACIÓN GENERAL -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <strong>1. Información General del Proyecto</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="nombre_proyecto" class="form-label required-field">Nombre del Proyecto</label>
                                <input type="text" class="form-control" id="nombre_proyecto" name="nombre_proyecto" required />
                            </div>

                            <div class="col-md-6">
                                <label for="fecha_presentacion" class="form-label required-field">Fecha de Presentación</label>
                                <input type="date" class="form-control" id="fecha_presentacion" name="fecha_presentacion" required />
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="section-header">Datos del Proponente</h5>
                            </div>

                            <div class="col-md-6">
                                <label for="nombre_completo" class="form-label required-field">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required />
                            </div>

                            <div class="col-md-6">
                                <label for="cargo" class="form-label">Cargo o Rol</label>
                                <input type="text" class="form-control" id="cargo" name="cargo" value="Comunicaciones" readonly />
                            </div>

                            <div class="col-md-6">
                                <label for="organizacion" class="form-label required-field">Organización/Institución</label>
                                <input type="text" class="form-control" id="organizacion" name="organizacion" required />
                            </div>

                            <div class="col-md-6">
                                <label for="direccion" class="form-label required-field">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" required />
                            </div>

                            <div class="col-md-6">
                                <label for="telefono" class="form-label required-field">Teléfono de Contacto</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" required />
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label required-field">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required />
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="section-header">Sector(es) Estratégico(s)</h5>
                            </div>

                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="infraestructura" name="sectores[]" value="Infraestructura" />
                                    <label class="form-check-label" for="infraestructura">Infraestructura</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="desarrollo_social" name="sectores[]" value="Desarrollo Social" />
                                    <label class="form-check-label" for="desarrollo_social">Desarrollo Social</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="salud" name="sectores[]" value="Salud" />
                                    <label class="form-check-label" for="salud">Salud</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="energia" name="sectores[]" value="Energía" />
                                    <label class="form-check-label" for="energia">Energía</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="innovacion" name="sectores[]" value="Innovación y Tecnología" />
                                    <label class="form-check-label" for="innovacion">Innovación y Tecnología</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="educacion" name="sectores[]" value="Educación" />
                                    <label class="form-check-label" for="educacion">Educación</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="medio_ambiente" name="sectores[]" value="Medio Ambiente" />
                                    <label class="form-check-label" for="medio_ambiente">Medio Ambiente</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="agroindustria" name="sectores[]" value="Agroindustria" />
                                    <label class="form-check-label" for="agroindustria">Agroindustria</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="otro_sector" name="sectores[]" value="Otro" />
                                    <label class="form-check-label" for="otro_sector">Otro:</label>
                                    <input type="text" class="form-control form-control-sm mt-1" id="otro_sector_espec" name="otro_sector_espec" />
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="section-header">Ubicación del Proyecto</h5>
                            </div>

                            <div class="col-md-6">
                                <label for="ubicacion" class="form-label">Ubicación</label>
                                <input type="text" class="form-control" id="ubicacion" name="ubicacion" />
                            </div>

                            <div class="col-md-6">
                                <label for="region" class="form-label">Región</label>
                                <input type="text" class="form-control" id="region" name="region" />
                            </div>

                            <div class="col-md-6">
                                <label for="municipio" class="form-label">Municipio</label>
                                <input type="text" class="form-control" id="municipio" name="municipio" />
                            </div>

                            <div class="col-md-6">
                                <label for="sector_comuna" class="form-label">Sector Comuna Unidad Vecinal</label>
                                <select class="form-control" id="sector_comuna" name="sector_comuna">
                                    <option value="">Seleccione una opción</option>
                                    <option value="N/A">N/A</option>
                                    <option value="Total Comuna">Total Comuna</option>
                                    <?php
                                    for ($i = 1; $i <= 36; $i++) {
                                        echo '<option value="UV ' . $i . '">UV ' . $i . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ámbito Territorial</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="territorio_total" name="ambito_territorial" value="Total comunal" />
                                    <label class="form-check-label" for="territorio_total">Total comunal</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="territorio_parcial" name="ambito_territorial" value="Parcial" />
                                    <label class="form-check-label" for="territorio_parcial">Parcial</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Duración estimada</label>
                                <div>
                                    <input type="number" name="duracion_valor" placeholder="Duración" required>
                                    <select name="duracion_tipo" required>
                                        <option value="meses">Meses</option>
                                        <option value="semanas">Semanas</option>
                                    </select>
                                </div>
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
                                <textarea class="form-control" id="objetivo_general" name="objetivo_general" rows="4" maxlength="200" required oninput="updateCharacterCount('objetivo_general', 'objetivo_general_count')"></textarea>
                                <small id="objetivo_general_count">200 caracteres restantes</small>
                            </div>

                            <div class="col-12">
                                <label for="objetivos_especificos" class="form-label required-field">Objetivos Específicos (separar con punto y coma)</label>
                                <textarea class="form-control" id="objetivos_especificos" name="objetivos_especificos" rows="4" maxlength="200" required oninput="updateCharacterCount('objetivos_especificos', 'objetivos_especificos_count')"></textarea>
                                <small id="objetivos_especificos_count">200 caracteres restantes</small>
                            </div>

                            <div class="col-12">
                                <label for="descripcion" class="form-label required-field">Descripción Breve</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="6" maxlength="200" required oninput="updateCharacterCount('descripcion', 'descripcion_count')"></textarea>
                                <small id="descripcion_count">200 caracteres restantes</small>
                            </div>

                            <div class="col-12">
                                <label for="poblacion_meta" class="form-label required-field">Población Meta/Beneficiaria</label>
                                <textarea class="form-control" id="poblacion_meta" name="poblacion_meta" rows="3" maxlength="200" required oninput="updateCharacterCount('poblacion_meta', 'poblacion_meta_count')"></textarea>
                                <small id="poblacion_meta_count">200 caracteres restantes</small>
                            </div>


                            <div class="col-12 mt-4">
                                <h5 class="section-header">Características de la Población</h5>
                            </div>

                            <div class="col-md-3">
                                <label for="edad" class="form-label">Edad</label>
                                <select class="form-select" id="edad" name="edad">
                                    <option value="">Seleccione...</option>
                                    <option value="todas">Todas las edades</option>
                                    <option value="menor_edad">Menores de edad (-18 años)</option>
                                    <option value="mayor_edad">Mayores de 18 años (+18)</option>
                                    <option value="adulto_mayor">Adultos mayores (60+ años)</option>
                                </select>
                            </div>


                            <div class="col-md-3">
                                <label for="genero" class="form-label">Género</label>
                                <select class="form-select" id="genero" name="genero">
                                    <option value="">Seleccione...</option>
                                    <option value="masculino">Todos los géneros</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="femenino">Femenino</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>


                            <div class="col-md-3">
                                <label for="situacion_economica" class="form-label">Situación Económica (RSH)</label>
                                <select class="form-select" id="situacion_economica" name="situacion_economica">
                                    <option value="">Seleccione...</option>
                                    <option value="todos">Disponible para todos</option>
                                    <option value="0-40">0% - 40% (Alta vulnerabilidad)</option>
                                    <option value="41-50">41% - 50%</option>
                                    <option value="51-60">51% - 60%</option>
                                    <option value="61-70">61% - 70%</option>
                                    <option value="71-80">71% - 80%</option>
                                    <option value="81-90">81% - 90%</option>
                                    <option value="91-100">91% - 100% (Baja vulnerabilidad)</option>
                                </select>
                            </div>



                            <div class="col-md-3">
                                <label for="ubicacion_poblacion" class="form-label">Ubicación</label>
                                <input type="text" class="form-control" id="ubicacion" name="ubicacion">
                            </div>

                            <div class="col-12">
                                <label for="areas_impacto" class="form-label">Áreas Prioritarias de Impacto</label>
                                <textarea class="form-control" id="areas_impacto" name="areas_impacto" rows="3" maxlength="200" oninput="updateCharacterCount('areas_impacto', 'areas_impacto_count')"></textarea>
                                <small id="areas_impacto_count">200 caracteres restantes</small>
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
                                <label for="descripcion_problema" class="form-label required-field">Descripción del Problema/Necesidad</label>
                                <textarea class="form-control" id="descripcion_problema" name="descripcion_problema" rows="5" maxlength="200" required oninput="updateCharacterCount('descripcion_problema', 'descripcion_problema_count')"></textarea>
                                <small id="descripcion_problema_count">200 caracteres restantes</small>
                            </div>

                            <div class="col-12">
                                <label for="oportunidad" class="form-label required-field">Oportunidad Identificada</label>
                                <textarea class="form-control" id="oportunidad" name="oportunidad" rows="5" maxlength="200" required oninput="updateCharacterCount('oportunidad', 'oportunidad_count')"></textarea>
                                <small id="oportunidad_count">200 caracteres restantes</small>
                            </div>

                            <div class="col-12">
                                <label for="impacto_anticipado" class="form-label required-field">Impacto Anticipado</label>
                                <textarea class="form-control" id="impacto_anticipado" name="impacto_anticipado" rows="5" maxlength="200" required oninput="updateCharacterCount('impacto_anticipado', 'impacto_anticipado_count')"></textarea>
                                <small id="impacto_anticipado_count">200 caracteres restantes</small>
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
                                <label for="presupuesto_estimado" class="form-label">Presupuesto Estimado (IVA incluido)</label>
                                <input type="text" class="form-control" id="presupuesto_estimado" name="presupuesto_estimado">
                            </div>

                            <div class="col-md-6">
                                <label for="componentes_principales" class="form-label">Componentes Principales</label>
                                <textarea class="form-control" id="componentes_principales" name="componentes_principales" rows="3" maxlength="200" oninput="updateCharacterCount('componentes_principales', 'componentes_principales_count')"></textarea>
                                <small id="componentes_principales_count">200 caracteres restantes</small>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="section-header">Fuentes de Financiamiento</h5>
                            </div>

                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="recursos_propios" name="fuente_financiamiento[]" value="Recursos Propios">
                                    <label class="form-check-label" for="recursos_propios">Recursos Propios</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="donaciones" name="fuente_financiamiento[]" value="Donaciones">
                                    <label class="form-check-label" for="donaciones">Donaciones</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="prestamos" name="fuente_financiamiento[]" value="Préstamos">
                                    <label class="form-check-label" for="prestamos">Préstamos</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="fondos_gubernamentales" name="fuente_financiamiento[]" value="Fondos Gubernamentales">
                                    <label class="form-check-label" for="fondos_gubernamentales">Fondos Gubernamentales</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="subvencion" name="fuente_financiamiento[]" value="Subvención Municipal">
                                    <label class="form-check-label" for="subvencion">Subvención Municipal</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="otro_financiamiento" name="fuente_financiamiento[]" value="Otro">
                                    <label class="form-check-label" for="otro_financiamiento">Otro:</label>
                                    <input type="text" class="form-control form-control-sm mt-1" id="otro_financiamiento_espec" name="otro_financiamiento_espec">
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="section-header">Recursos Humanos</h5>
                            </div>

                            <div class="col-12">
                                <label for="recursos_humanos" class="form-label">Personal Requerido</label>
                                <textarea class="form-control" id="recursos_humanos" name="recursos_humanos" rows="3" maxlength="200" oninput="updateCharacterCount('recursos_humanos', 'recursos_humanos_count')"></textarea>
                                <small id="recursos_humanos_count">200 caracteres restantes</small>
                            </div>

                            <div class="col-12">
                                <label for="infraestructura_equipamiento" class="form-label">Infraestructura/Equipamiento</label>
                                <textarea class="form-control" id="infraestructura_equipamiento" name="infraestructura_equipamiento" rows="3" maxlength="200" oninput="updateCharacterCount('infraestructura_equipamiento', 'infraestructura_equipamiento_count')"></textarea>
                                <small id="infraestructura_equipamiento_count">200 caracteres restantes</small>
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
                                <textarea class="form-control" id="viabilidad_tecnica" name="viabilidad_tecnica" rows="3" maxlength="200" oninput="updateCharacterCount('viabilidad_tecnica', 'viabilidad_tecnica_count')"></textarea>
                                <small id="viabilidad_tecnica_count">200 caracteres restantes</small>
                            </div>

                            <div class="col-md-6">
                                <label for="viabilidad_economica" class="form-label">Viabilidad Económica</label>
                                <textarea class="form-control" id="viabilidad_economica" name="viabilidad_economica" rows="3" maxlength="200" oninput="updateCharacterCount('viabilidad_economica', 'viabilidad_economica_count')"></textarea>
                                <small id="viabilidad_economica_count">200 caracteres restantes</small>
                            </div>

                            <div class="col-md-6">
                                <label for="viabilidad_social" class="form-label">Viabilidad Social</label>
                                <textarea class="form-control" id="viabilidad_social" name="viabilidad_social" rows="3" maxlength="200" oninput="updateCharacterCount('viabilidad_social', 'viabilidad_social_count')"></textarea>
                                <small id="viabilidad_social_count">200 caracteres restantes</small>
                            </div>

                            <div class="col-md-6">
                                <label for="viabilidad_ambiental" class="form-label">Viabilidad Ambiental</label>
                                <textarea class="form-control" id="viabilidad_ambiental" name="viabilidad_ambiental" rows="3" maxlength="200" oninput="updateCharacterCount('viabilidad_ambiental', 'viabilidad_ambiental_count')"></textarea>
                                <small id="viabilidad_ambiental_count">200 caracteres restantes</small>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- SECCIÓN 6: Evaluación Inicial -->

                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <strong>6. Evaluación Inicial (Uso Interno COFODEP)</strong>
                    </div>
                    <div class="card-body bg-light">
                        <div class="row g-3">
                            <!-- Viabilidad Técnica -->
                            <div class="col-md-6">
                                <label for="viabilidad_tecnica" class="form-label required-field">Viabilidad Técnica (1-5)</label>
                                <select class="form-select" id="viabilidad_tecnica" name="viabilidad_tecnica_numero" required>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>

                            <!-- Viabilidad Económica -->
                            <div class="col-md-6">
                                <label for="viabilidad_economica" class="form-label required-field">Viabilidad Económica (1-5)</label>
                                <select class="form-select" id="viabilidad_economica" name="viabilidad_economica_numero" required>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>

                            <!-- Impacto Social -->
                            <div class="col-md-6">
                                <label for="impacto_social" class="form-label required-field">Impacto Social (1-5)</label>
                                <select class="form-select" id="impacto_social" name="impacto_social_numero" required>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>

                            </div>

                            <!-- Alineación con los Objetivos -->
                            <div class="col-md-6">
                                <label for="alineacion_objetivos" class="form-label required-field">Alineación con los Objetivos (1-5)</label>
                                <select class="form-select" id="alineacion_objetivos" name="alineacion_objetivos" required>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>

                            </div>

                            <!-- Observaciones y Recomendaciones -->
                            <div class="col-12">
                                <label for="observaciones_recomendaciones" class="form-label required-field">Observaciones y Recomendaciones del Comité Técnico</label>
                                <textarea class="form-control" id="observaciones_recomendaciones" name="observaciones_recomendaciones" rows="5" maxlength="200" required oninput="updateCharacterCount('observaciones_recomendaciones', 'observaciones_recomendaciones_count')"></textarea>
                                <small class="form-text text-muted">Incluya cualquier comentario relevante, como ajustes recomendados o información adicional que el proyecto debe considerar para mejorar.</small>
                                <small id="observaciones_recomendaciones_count">200 caracteres restantes</small>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- SECCIÓN 7: FIRMAS -->
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




                <!-- Botón de envío -->
                <div class="text-end mt-4">
                    <button type="reset" class="btn btn-outline-danger me-md-2">
                        <i class="ri-delete-bin-line"></i>
                        Limpiar Formulario
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="ri-send-plane-line"></i>
                        Enviar Formulario
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

<script>
    // Función para actualizar el contador de caracteres restantes
    function updateCharacterCount(textareaId, countId) {
        var textarea = document.getElementById(textareaId);
        var count = document.getElementById(countId);
        var remaining = 200 - textarea.value.length;
        count.textContent = remaining + " caracteres restantes";
    }
</script>

</html>