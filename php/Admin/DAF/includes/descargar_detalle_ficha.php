<?php
require_once __DIR__ . '/../../Comunicaciones/bd/conexion_test.php';
require_once __DIR__ . '/../../../vendor/autoload.php'; // Dompdf

use Dompdf\Dompdf;
use Dompdf\Options;

session_start(); // Inicia la sesión

// Verifica que el usuario esté logueado, tenga el rol adecuado y sea de la sección correspondiente
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'proponente' || $_SESSION['area'] !== 'DAF') {
    header("Location: login.php?error=no_autorizado");
    exit();  // Detiene la ejecución si el usuario no tiene permisos
}

// Verificar si el id_proyecto está presente en la URL
if (isset($_GET['id_proyecto']) && is_numeric($_GET['id_proyecto'])) {
    $id_proyecto = intval($_GET['id_proyecto']);

    // Consulta para obtener los detalles del proyecto (sin la restricción de id_usuario)
    $sql = "SELECT 
            p.id_proyecto,
            p.numero_fip,
            p.nombre AS nombre_proyecto,
            p.fecha_presentacion,
            p.duracion_valor,
            p.duracion_tipo,

            u.nombre AS nombre_usuario,
            u.apellido AS apellido_usuario,
            u.correo AS correo_usuario,
            u.rol AS cargo,
            u.area AS organizacion,

            es.nombre_estado AS estado_proyecto,
            es.id_estado AS id_estado,

            dp.objetivo_general, 
            dp.objetivos_especificos, 
            dp.descripcion_breve, 
            dp.poblacion_meta,

            po.edad,
            po.genero,
            po.situacion_economica,
            po.ubicacion AS ubicacion_poblacion,
            po.area_prioritaria,
            po.impacto_esperado,

            up.region, 
            up.municipio, 
            up.sector_unidad_vecinal AS uv, 
            up.ambito_territorial,

            rr.presupuesto_estimado, 
            rr.componentes_principales,
            rr.fuente_financiamiento,
            rr.recursos_humanos,
            rr.infraestructura_equipamiento,
            fi.viabilidad_tecnica,
            fi.viabilidad_economica,
            fi.viabilidad_social,
            fi.viabilidad_ambiental,

            pr.direccion,  
            pr.telefono,

            ei.viabilidad_tecnica_numero AS viabilidad_tecnica_numero,
            ei.viabilidad_economica_numero AS viabilidad_economica_numero,
            ei.impacto_social_numero AS impacto_social_numero,
            ei.alineacion_objetivos AS alineacion_objetivos,
            ei.observaciones_recomendaciones,

            j.descripcion_problema,
            j.oportunidad,
            j.impacto_anticipado,
            
            GROUP_CONCAT(se.nombre SEPARATOR ', ') AS sectores_estrategicos
        FROM proyecto p
        LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario
        LEFT JOIN estado_fip es ON p.id_estado_actual = es.id_estado
        LEFT JOIN descripcion_proyecto dp ON p.id_proyecto = dp.id_proyecto
        LEFT JOIN poblacion po ON p.id_proyecto = po.id_proyecto
        LEFT JOIN ubicacion_proyecto up ON p.id_ubicacion = up.id_ubicacion
        LEFT JOIN proyecto_sector ps ON p.id_proyecto = ps.id_proyecto
        LEFT JOIN sector_estrategico se ON ps.id_sector = se.id_sector
        LEFT JOIN requerimientos_recursos rr ON p.id_proyecto = rr.id_proyecto
        LEFT JOIN factibilidad_inicial fi ON p.id_proyecto = fi.id_proyecto
        LEFT JOIN evaluacion_inicial ei ON p.id_proyecto = ei.id_proyecto
        LEFT JOIN proponente pr ON p.id_proponente = pr.id_proponente
        LEFT JOIN justificacion j ON p.id_proyecto = j.id_proyecto  
        WHERE p.id_proyecto = ?";  // Eliminar la restricción de id_usuario

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_proyecto);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Si no se encuentra el proyecto, mostrar un mensaje y detener el script
    if ($resultado->num_rows === 0) {
        die("No se encontró el proyecto con ID: $id_proyecto");
    }

    // Obtener los datos del proyecto
    $data = $resultado->fetch_assoc();
    $stmt->close();

    // Obtener sectores estratégicos
    $stmt_sectores = $conn->prepare("
        SELECT se.nombre 
        FROM sector_estrategico se
        JOIN proyecto_sector ps ON se.id_sector = ps.id_sector
        WHERE ps.id_proyecto = ?");

    $stmt_sectores->bind_param("i", $id_proyecto);
    $stmt_sectores->execute();
    $sectores = $stmt_sectores->get_result()->fetch_all(MYSQLI_ASSOC);
    $data['sectores_estrategicos'] = array_column($sectores, 'nombre');
    $stmt_sectores->close();

    // Generar el contenido HTML para el PDF
    ob_start();
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style>
            body {
                font-family: 'Arial', sans-serif;
                line-height: 1.6;
                color: #333;
                padding: 20px;
            }

            h1 {
                color: #28a745;
                text-align: center;
                margin-bottom: 30px;
                border-bottom: 2px solid #28a745;
                padding-bottom: 10px;
            }

            h2 {
                color: #28a745;
                margin-top: 25px;
                border-left: 4px solid #28a745;
                padding-left: 10px;
            }

            .card {
                border: 1px solid #d1e7dd;
                border-radius: 5px;
                padding: 15px;
                margin-bottom: 20px;
                background-color: #f8f9fa;
            }

            .card-header {
                background-color: #d4edda;
                color: #155724;
                padding: 10px 15px;
                border-radius: 3px;
                margin-bottom: 10px;
                font-weight: bold;
            }

            .info-item {
                margin-bottom: 8px;
            }

            .info-label {
                font-weight: bold;
                color: #28a745;
            }

            ul {
                padding-left: 20px;
            }

            li {
                margin-bottom: 5px;
            }

            .section-break {
                page-break-after: always;
            }

            .text-success {
                color: #28a745;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin: 15px 0;
            }

            table,
            th,
            td {
                border: 1px solid #ddd;
            }

            th {
                background-color: #d4edda;
                color: #155724;
                padding: 10px;
                text-align: left;
            }

            td {
                padding: 8px 10px;
            }

            .footer {
                margin-top: 30px;
                text-align: center;
                font-size: 12px;
                color: #6c757d;
            }
        </style>
    </head>

    <body>
        <h1 class="text-success">Ficha del Proyecto</h1>

        <!-- Información general -->
        <div class="card">
            <div class="card-header">Información General del Proyecto</div>
            <div class="info-item"><span class="info-label">Nombre:</span> <?= htmlspecialchars($data['nombre_proyecto']) ?></div>
            <div class="info-item"><span class="info-label">Fecha de Presentación:</span> <?= $data['fecha_presentacion'] ?></div>
            <div class="info-item"><span class="info-label">Duración:</span> <?= $data['duracion_valor'] . ' ' . $data['duracion_tipo'] ?></div>
            <div class="info-item"><span class="info-label">Número FIP:</span> <?= $data['numero_fip'] ?></div>
        </div>

        <!-- Datos del Proponente -->
        <div class="card">
            <div class="card-header">Datos Proponente</div>
            <div class="info-item"><span class="info-label">Nombre Completo:</span> <?= $data['nombre_usuario'], ' ', $data['apellido_usuario'] ?></div>
            <div class="info-item"><span class="info-label">Cargo/Rol:</span> <?= $data['cargo'] ?></div>
            <div class="info-item"><span class="info-label">Organización:</span> <?= $data['organizacion'] ?></div>
            <div class="info-item"><span class="info-label">Dirección:</span> <?= $data['direccion'] ?></div>
            <div class="info-item"><span class="info-label">Contacto:</span> Tel: <?= $data['correo_usuario'] ?>, Email: <?= $data['correo_usuario'] ?></div>
        </div>
        <!-- Sectores Estrategicos -->
        <div class="card">
            <div class="card-header">Sectores Estrategicos</div>
            <div class="info-item"><span class="info-label">Sectores Estrategicos:</span> <?= implode(', ', $data['sectores_estrategicos']) ?></div>
        </div>
        <!-- Ubicación -->
        <div class="card">
            <div class="card-header">Ubicación del proyecto</div>
            <div class="info-item"><span class="info-label">Región:</span> <?= $data['region'] ?></div>
            <div class="info-item"><span class="info-label">Municipio:</span> <?= $data['municipio'] ?></div>
            <div class="info-item"><span class="info-label">Unidad Vecinal (UV):</span> <?= $data['uv'] ?></div>
            <div class="info-item"><span class="info-label">Ámbito Territorial:</span> <?= $data['ambito_territorial'] ?></div>
            <div class="info-item"><span class="info-label">Duración Estimada:</span> <?= isset($data['duracion_valor']) ? $data['duracion_tipo'] : 'No disponible' ?></div>

        </div>

        <!-- Descripción del Proyecto -->
        <h2>Descripción del Proyecto</h2>
        <div class="card">
            <div class="info-item"><span class="info-label">Objetivo General:</span><br><?= isset($data['objetivo_general']) ? nl2br($data['objetivo_general']) : 'No disponible' ?></div>
            <div class="info-item"><span class="info-label">Objetivos Específicos:</span><br><?= isset($data['objetivos_especificos']) ? nl2br($data['objetivos_especificos']) : 'No disponible' ?></div>
            <div class="info-item"><span class="info-label">Descripción Breve:</span><br><?= isset($data['descripcion_breve']) ? nl2br($data['descripcion_breve']) : 'No disponible' ?></div>
            <div class="info-item"><span class="info-label">Población Meta:</span> <?= isset($data['poblacion_meta']) ? $data['poblacion_meta'] : 'No disponible' ?></div>
        </div>

        <!-- Caracteristicas de la Población  -->
        <h2>Características de la Población</h2>
        <div class="card">
            <div class="info-item"><span class="info-label">Edad:</span> <?= $data['edad'] ?></div>
            <div class="info-item"><span class="info-label">Género:</span> <?= $data['genero'] ?></div>
            <div class="info-item"><span class="info-label">Situación Económica:</span> <?= $data['situacion_economica'] ?></div>
            <div class="info-item"><span class="info-label">Ubicación:</span> <?= $data['ubicacion_poblacion'] ?></div>
            <div class="info-item"><span class="info-label">Área prioritaria de Impacto:</span> <?= $data['area_prioritaria'] ?></div>

        </div>

        <!-- Justificación -->
        <h2>Justificación</h2>
        <div class="card">
            <div class="info-item"><span class="info-label">Descripcion del Problema:</span><br><?= nl2br($data['descripcion_problema']) ?></div>
            <div class="info-item"><span class="info-label">Oportunidad Identificada:</span><br><?= nl2br($data['oportunidad']) ?></div>
            <div class="info-item"><span class="info-label">Impacto Anticipado:</span><br><?= nl2br($data['impacto_anticipado']) ?></div>
        </div>

        <!-- Recursos y Requerimientos -->
        <h2>Requerimientos y Recursos del Proyecto</h2>
        <div class="card">
            <div class="info-item"><span class="info-label">Presupuesto Estimado (IVA incluido):</span> $<?= number_format($data['presupuesto_estimado'], 2) ?></div>
            <div class="info-item"><span class="info-label">Componentes Principales:</span><br><?= nl2br($data['componentes_principales']) ?></div>
            <div class="info-item"><span class="info-label">Fuente de Financiamiento:</span> <?= $data['fuente_financiamiento'] ?></div>
            <div class="info-item"><span class="info-label">Personal Requerido:</span><br><?= nl2br($data['recursos_humanos']) ?></div>
            <div class="info-item"><span class="info-label">Infraestructura y Equipamiento:</span><br><?= nl2br($data['infraestructura_equipamiento']) ?></div>
        </div>

        <!-- Factibilidad -->
        <h2>Análisis de Factibilidad Inicial</h2>
        <div class="card">
            <div class="info-item"><span class="info-label">Viabilidad Técnica:</span> <?= $data['viabilidad_tecnica'] ?></div>
            <div class="info-item"><span class="info-label">Viabilidad Económica:</span> <?= $data['viabilidad_economica'] ?></div>
            <div class="info-item"><span class="info-label">Viabilidad Social:</span> <?= $data['viabilidad_social'] ?></div>
            <div class="info-item"><span class="info-label">Viabilidad Ambiental:</span> <?= $data['viabilidad_ambiental'] ?></div>
        </div>

        <h2>Análisis de Factibilidad </h2>
        <div class="card">
            <div class="info-item"><span class="info-label">Viabilidad Técnica:</span> <?= $data['viabilidad_tecnica_numero'] ?></div>
            <div class="info-item"><span class="info-label">Viabilidad Económica:</span> <?= $data['viabilidad_economica_numero'] ?></div>
            <div class="info-item"><span class="info-label">Impacto Social:</span> <?= $data['impacto_social_numero'] ?></div>
            <div class="info-item"><span class="info-label">Alineasión con Objetivos:</span> <?= $data['alineacion_objetivos'] ?></div>
            <div class="info-item"><span class="info-label">Observaciones y Recomendaciones:</span> <?= $data['observaciones_recomendaciones'] ?></div>
        </div>

    </body>
<?php
    $html = ob_get_clean();

    // Configurar Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'Arial');

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Descargar el archivo
    $dompdf->stream("ficha_proyecto_{$id_proyecto}.pdf", ["Attachment" => true]);
    exit;
} else {
    echo "No se pasó un ID de proyecto válido.";
}
?>