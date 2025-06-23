<?php

// Verificar si el id_proyecto está presente en la URL
if (!isset($_GET['id_proyecto']) || empty($_GET['id_proyecto'])) {
    echo "<p>Proyecto no encontrado o no tienes acceso a este proyecto.</p>";
    exit();
}

$id_proyecto = $_GET['id_proyecto']; // Recuperamos el id del proyecto desde la URL

// Incluir archivo de conexión
require_once __DIR__ . '/../bd/conexion_test.php';

// Inicializamos la variable de datos de la ficha
$data = [];

// Consulta para obtener los detalles del proyecto
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

            pr.telefono,
            pr.direccion,

            ei.viabilidad_tecnica_numero AS viabilidad_tecnica_numero,
            ei.viabilidad_economica_numero AS viabilidad_economica_numero,
            ei.impacto_social_numero AS impacto_social_numero,
            ei.alineacion_objetivos AS alineacion_objetivos,
            ei.observaciones_recomendaciones,

            
            j.oportunidad,
            j.descripcion_problema,
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
        LEFT JOIN proponente pr ON p.id_proponente = pr.id_proponente  -- Asegurarse de incluir los datos del proponente
        LEFT JOIN justificacion j ON p.id_proyecto = j.id_proyecto 
        
        WHERE p.id_proyecto = ? AND p.id_usuario = ?

        GROUP BY p.id_proyecto";


$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error en prepare SQL: " . $conn->error);
}
$stmt->bind_param("ii", $id_proyecto, $_SESSION['id_usuario']); // Enlazamos el id_proyecto y el id_usuario
$stmt->execute();
$result = $stmt->get_result();

// Si encontramos el proyecto, lo asignamos a la variable $data
if ($result->num_rows === 0) {
    die("No se encontró el proyecto con ID: $id_proyecto");
}

$data = $result->fetch_assoc();
$stmt->close();
$conn->close();
