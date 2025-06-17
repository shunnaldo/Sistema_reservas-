<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Solicitud</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-left: 170px;
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
        
        .detail-section {
            margin-bottom: 2rem;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php include 'sideBardComunicaciones.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid py 4">
            <div class="row mb-4">
                <div class="col">
                    <br>
                    <br>
                    <br>
                    <div class="d-flex justify-content-between align-items-center">
                        <h2>Detalle de Solicitud #<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : 'N/A'; ?></h2>
                        <a href="estadoSolicitud.php" class="btn btn-secondary">Volver</a>
                    </div>
                    <hr>
                </div>
            </div>

            <!-- Datos básicos -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Información General</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nombre del Proyecto:</strong> Construcción de Centro Comunitario</p>
                                    <p><strong>Fecha de Presentación:</strong> 15/11/2023</p>
                                    <p><strong>Proponente:</strong> María González</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Estado:</strong> <span class="badge bg-warning">En Revisión</span></p>
                                    <p><strong>Duración Estimada:</strong> 6 meses</p>
                                    <p><strong>Ubicación:</strong> Región Metropolitana</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descripción del proyecto -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Descripción del Proyecto</h5>
                        </div>
                        <div class="card-body">
                            <h6>Objetivo General</h6>
                            <p>Construir un centro comunitario que sirva como espacio de reunión y desarrollo para los habitantes del sector.</p>
                            
                            <h6 class="mt-3">Objetivos Específicos</h6>
                            <ul>
                                <li>Proveer un espacio físico adecuado para actividades comunitarias</li>
                                <li>Fomentar la participación ciudadana</li>
                                <li>Mejorar la calidad de vida de los residentes</li>
                            </ul>
                            
                            <h6 class="mt-3">Población Beneficiaria</h6>
                            <p>500 familias del sector norte de la comuna.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Requerimientos -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Requerimientos</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Presupuesto Estimado</h6>
                                    <p>$25.000.000</p>
                                    
                                    <h6 class="mt-3">Fuentes de Financiamiento</h6>
                                    <ul>
                                        <li>Fondos municipales</li>
                                        <li>Donaciones privadas</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6>Recursos Humanos</h6>
                                    <ul>
                                        <li>1 Coordinador de proyecto</li>
                                        <li>2 Asistentes sociales</li>
                                        <li>Voluntarios de la comunidad</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documentos adjuntos -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Documentos Adjuntos</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between">
                                        <span>Formulario FIP completo.pdf</span>
                                        <span class="badge bg-primary">Descargar</span>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between">
                                        <span>Presupuesto detallado.xlsx</span>
                                        <span class="badge bg-primary">Descargar</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../js/sidebar.js"></script>
</body>

</html>