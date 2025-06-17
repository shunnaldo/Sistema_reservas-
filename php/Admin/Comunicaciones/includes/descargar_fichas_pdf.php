<?php
require_once __DIR__ . '/../bd/conexion_test.php';
require_once __DIR__ . '/../../../vendor/autoload.php'; // Dompdf autoload

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_GET['id_proyecto'])) {
    $id_proyecto = $_GET['id_proyecto'];

    // Consulta principal con todos los joins y campos solicitados
    $sql = "SELECT 
            p.id_proyecto,
            p.nombre,
            p.fecha_presentacion,
            p.duracion_valor,
            p.duracion_tipo,
            p.numero_fip,
            
            -- Información del proponente
            pr.nombre_completo,
            pr.cargo_rol,
            pr.organizacion,
            pr.direccion,
            pr.telefono,
            pr.correo,
            
            -- Ubicación del proyecto
            u.region,
            u.municipio,
            u.sector_unidad_vecinal AS uv,
            u.ambito_territorial,
            
            -- Descripción del proyecto
            dp.objetivo_general,
            dp.objetivos_especificos,
            dp.descripcion_breve,
            dp.poblacion_meta,
            
            -- Justificación
            j.descripcion_problema,
            j.oportunidad,
            j.impacto_anticipado,
            
            -- Población beneficiaria
            po.edad,
            po.genero,
            po.situacion_economica,
            po.ubicacion AS ubicacion_poblacion,
            po.area_prioritaria,
            po.impacto_esperado,
            
            -- Requerimientos de recursos
            rr.presupuesto_estimado,
            rr.componentes_principales,
            rr.fuente_financiamiento,
            rr.recursos_humanos,
            rr.infraestructura_equipamiento,
            
            -- Factibilidad
            fi.viabilidad_tecnica,
            fi.viabilidad_economica,
            fi.viabilidad_social,
            fi.viabilidad_ambiental
            
        FROM proyecto p
        INNER JOIN proponente pr ON p.id_proponente = pr.id_proponente
        INNER JOIN ubicacion_proyecto u ON p.id_ubicacion = u.id_ubicacion
        LEFT JOIN descripcion_proyecto dp ON p.id_proyecto = dp.id_proyecto
        LEFT JOIN justificacion j ON p.id_proyecto = j.id_proyecto
        LEFT JOIN poblacion po ON p.id_proyecto = po.id_proyecto
        LEFT JOIN requerimientos_recursos rr ON p.id_proyecto = rr.id_proyecto
        LEFT JOIN factibilidad_inicial fi ON p.id_proyecto = fi.id_proyecto
        WHERE p.id_proyecto = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en prepare: " . $conn->error);
    }
    $stmt->bind_param("i", $id_proyecto);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        die("No se encontró el proyecto con ID: $id_proyecto");
    }

    $data = $resultado->fetch_assoc();
    $stmt->close();

    // Obtener sectores estratégicos
    $stmt_sectores = $conn->prepare("
        SELECT se.nombre 
        FROM sector_estrategico se
        JOIN proyecto_sector ps ON se.id_sector = ps.id_sector
        WHERE ps.id_proyecto = ?
    ");
    $stmt_sectores->bind_param("i", $id_proyecto);
    $stmt_sectores->execute();
    $sectores = $stmt_sectores->get_result()->fetch_all(MYSQLI_ASSOC);
    $data['sectores_estrategicos'] = array_column($sectores, 'nombre');
    $stmt_sectores->close();

    // Generar HTML para el PDF
    ob_start();
    ?>
    <h1>Ficha del Proyecto</h1>
    <p><strong>Nombre:</strong> <?= htmlspecialchars($data['nombre']) ?></p>
    <p><strong>Fecha de Presentación:</strong> <?= htmlspecialchars($data['fecha_presentacion']) ?></p>
    <p><strong>Duración:</strong> <?= htmlspecialchars($data['duracion_valor'] . ' ' . $data['duracion_tipo']) ?></p>
    <p><strong>Número FIP:</strong> <?= htmlspecialchars($data['numero_fip']) ?></p>

    <h2>Proponente</h2>
    <p><?= htmlspecialchars($data['nombre_completo']) ?> - <?= htmlspecialchars($data['cargo_rol']) ?></p>
    <p><?= htmlspecialchars($data['organizacion']) ?>, <?= htmlspecialchars($data['direccion']) ?></p>
    <p>Tel: <?= htmlspecialchars($data['telefono']) ?>, Email: <?= htmlspecialchars($data['correo']) ?></p>

    <h2>Ubicación</h2>
    <p><?= htmlspecialchars($data['region']) ?>, <?= htmlspecialchars($data['municipio']) ?> (UV <?= htmlspecialchars($data['uv']) ?>)</p>
    <p>Ámbito: <?= htmlspecialchars($data['ambito_territorial']) ?></p>

    <h2>Descripción</h2>
    <p><strong>Objetivo General:</strong> <?= nl2br(htmlspecialchars($data['objetivo_general'])) ?></p>
    <p><strong>Objetivos Específicos:</strong> <?= nl2br(htmlspecialchars($data['objetivos_especificos'])) ?></p>
    <p><strong>Descripción Breve:</strong> <?= nl2br(htmlspecialchars($data['descripcion_breve'])) ?></p>
    <p><strong>Población Meta:</strong> <?= htmlspecialchars($data['poblacion_meta']) ?></p>

    <h2>Justificación</h2>
    <p><strong>Problema:</strong> <?= nl2br(htmlspecialchars($data['descripcion_problema'])) ?></p>
    <p><strong>Oportunidad:</strong> <?= nl2br(htmlspecialchars($data['oportunidad'])) ?></p>
    <p><strong>Impacto Anticipado:</strong> <?= nl2br(htmlspecialchars($data['impacto_anticipado'])) ?></p>

    <h2>Población Beneficiaria</h2>
    <p>Edad: <?= htmlspecialchars($data['edad']) ?>, Género: <?= htmlspecialchars($data['genero']) ?></p>
    <p>Situación Económica: <?= htmlspecialchars($data['situacion_economica']) ?></p>
    <p>Ubicación: <?= htmlspecialchars($data['ubicacion_poblacion']) ?></p>
    <p>Área Prioritaria: <?= htmlspecialchars($data['area_prioritaria']) ?></p>
    <p>Impacto Esperado: <?= htmlspecialchars($data['impacto_esperado']) ?></p>

    <h2>Recursos</h2>
    <p><strong>Presupuesto Estimado:</strong> $<?= number_format($data['presupuesto_estimado'], 2) ?></p>
    <p><strong>Componentes:</strong> <?= nl2br(htmlspecialchars($data['componentes_principales'])) ?></p>
    <p><strong>Financiamiento:</strong> <?= htmlspecialchars($data['fuente_financiamiento']) ?></p>
    <p><strong>Recursos Humanos:</strong> <?= nl2br(htmlspecialchars($data['recursos_humanos'])) ?></p>
    <p><strong>Infraestructura y Equipamiento:</strong> <?= nl2br(htmlspecialchars($data['infraestructura_equipamiento'])) ?></p>

    <h2>Factibilidad</h2>
    <p><strong>Técnica:</strong> <?= htmlspecialchars($data['viabilidad_tecnica']) ?></p>
    <p><strong>Económica:</strong> <?= htmlspecialchars($data['viabilidad_economica']) ?></p>
    <p><strong>Social:</strong> <?= htmlspecialchars($data['viabilidad_social']) ?></p>
    <p><strong>Ambiental:</strong> <?= htmlspecialchars($data['viabilidad_ambiental']) ?></p>

    <h2>Sectores Estratégicos</h2>
    <ul>
        <?php foreach ($data['sectores_estrategicos'] as $sector): ?>
            <li><?= htmlspecialchars($sector) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php
    $html = ob_get_clean();

    // Configurar Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);

    // Tamaño y orientación del papel
    $dompdf->setPaper('A4', 'portrait');

    // Renderizar el PDF
    $dompdf->render();

    // Descargar el archivo
    $dompdf->stream("ficha_proyecto_{$id_proyecto}.pdf", ["Attachment" => true]);
    exit;
} else {
    echo "ID de proyecto no especificado";
}
