<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "pruebaformulario");

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Iniciar sesión y verificar que el usuario esté logueado
session_start();
if (!isset($_SESSION['id_usuario'])) {
    // Si no hay sesión de usuario, redirige a login
    header("Location: login.php?error=sesion_invalida");
    exit();
}

// Recuperar el id_proponente desde la sesión
$id_usuario = $_SESSION['id_usuario'];  // El id_proponente está en la sesión

// Iniciar transacción
$conexion->begin_transaction();

try {
    // ===============================================
    // SECCIÓN 1: INFORMACIÓN GENERAL DEL PROYECTO
    // ===============================================

    // Recibir datos del proyecto
    $nombre_proyecto      = $conexion->real_escape_string($_POST['nombre_proyecto']);
    $fecha_presentacion   = $conexion->real_escape_string($_POST['fecha_presentacion']);
    $nombre_completo      = $conexion->real_escape_string($_POST['nombre_completo']);
    $cargo                = $conexion->real_escape_string($_POST['cargo']);
    $organizacion         = $conexion->real_escape_string($_POST['organizacion']);
    $direccion            = $conexion->real_escape_string($_POST['direccion']);
    $telefono             = $conexion->real_escape_string($_POST['telefono']);
    $email                = $conexion->real_escape_string($_POST['email']);
    $region               = $conexion->real_escape_string($_POST['region']);
    $municipio            = $conexion->real_escape_string($_POST['municipio']);
    $sector_comuna        = $conexion->real_escape_string($_POST['sector_comuna']);
    $ambito_territorial   = isset($_POST['ambito_territorial']) ? $conexion->real_escape_string($_POST['ambito_territorial']) : null;
    $duracion_valor       = isset($_POST['duracion_valor']) ? intval($_POST['duracion_valor']) : null;
    $duracion_tipo        = isset($_POST['duracion_tipo']) ? $_POST['duracion_tipo'] : null;

    // // Verificar si el proponente ya existe en la base de datos
    // $sqlProponenteExistente = "SELECT id_proponente FROM proponente WHERE correo = ?";
    // $stmtProponenteExistente = $conexion->prepare($sqlProponenteExistente);
    // $stmtProponenteExistente->bind_param("s", $email);
    // $stmtProponenteExistente->execute();
    // $resultadoProponente = $stmtProponenteExistente->get_result();

    // // Si el proponente ya existe, usamos su id, si no, lo insertamos
    // if ($resultadoProponente->num_rows > 0) {
    //     $proponente = $resultadoProponente->fetch_assoc();
    //     $id_proponente = $proponente['id_proponente'];  // Obtener el id del proponente existente
    // } else {
    //     // Insertar nuevo proponente
    //     $sqlInsertarProponente = "INSERT INTO proponente (nombre_completo, cargo_rol, organizacion, direccion, telefono, correo)
    //     VALUES ('$nombre_completo', '$cargo', '$organizacion', '$direccion', '$telefono', '$email')";
    //     if (!$conexion->query($sqlInsertarProponente)) {
    //         throw new Exception("Error al insertar proponente: " . $conexion->error);
    //     }
    //     $id_proponente = $conexion->insert_id;  // Obtener el nuevo id_proponente
    // }

    // Generar correlativo número/año actual
    $anio_actual = date("Y");
    $sqlUltimo = "SELECT MAX(CAST(SUBSTRING_INDEX(numero_fip, '/', 1) AS UNSIGNED)) as ultimo_num 
                    FROM proyecto WHERE YEAR(fecha_presentacion) = '$anio_actual'";
    $resultadoUltimo = $conexion->query($sqlUltimo);
    $filaUltimo = $resultadoUltimo->fetch_assoc();
    $ultimo_num = $filaUltimo['ultimo_num'] ?? 0;
    $nuevo_num = $ultimo_num + 1;
    $numero_fip = $nuevo_num . "/" . $anio_actual;

    // Sectores estratégicos (checkbox múltiple)
    $sectores = isset($_POST['sectores']) ? $_POST['sectores'] : [];
    $otro_sector_espec = trim($conexion->real_escape_string($_POST['otro_sector_espec']));

    // Si se marcó "Otro" pero no se especificó, lo eliminamos del array
    if (in_array("Otro", $sectores)) {
        if (!empty($otro_sector_espec)) {
            $key = array_search("Otro", $sectores);
            if ($key !== false) {
                $sectores[$key] = $otro_sector_espec;
            }
        } else {
            $sectores = array_diff($sectores, ["Otro"]);
        }
    }

    // Eliminar duplicados y limpiar sectores vacíos
    $sectores = array_unique(array_filter($sectores));

    // Insertar ubicación
    $sqlUbicacion = "INSERT INTO ubicacion_proyecto (region, municipio, sector_unidad_vecinal, ambito_territorial)
    VALUES ('$region', '$municipio', '$sector_comuna', '$ambito_territorial')";
    if (!$conexion->query($sqlUbicacion)) {
        throw new Exception("Error al insertar ubicación: " . $conexion->error);
    }
    $id_ubicacion = $conexion->insert_id;

    // Insertar proyecto con correlativo
    $sqlProyecto = "INSERT INTO proyecto (numero_fip, nombre, fecha_presentacion, duracion_valor, duracion_tipo, id_usuario, id_ubicacion)
    VALUES ('$numero_fip', '$nombre_proyecto', '$fecha_presentacion', '$duracion_valor', '$duracion_tipo', '$id_usuario', '$id_ubicacion')";
    if (!$conexion->query($sqlProyecto)) {
        throw new Exception("Error al insertar proyecto: " . $conexion->error);
    }
    $id_proyecto = $conexion->insert_id;

    // Estado inicial del proyecto
    $estado_inicial_nombre = 'Realizado';
    $observaciones = 'Formulario enviado correctamente por el usuario.';
    $usuario_modificador = $_SESSION['id_usuario'] ?? 'sistema'; // o el usuario que esté autenticado

    // 1. Obtener el ID del estado desde la tabla estado_fip
    $sqlGetEstado = "SELECT id_estado FROM estado_fip WHERE nombre_estado = ?";
    $stmt = $conexion->prepare($sqlGetEstado);
    $stmt->bind_param("s", $estado_inicial_nombre);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        $id_estado = $fila['id_estado'];

        // 2. Insertar en historial_estado_proyecto
        $sqlEstado = "INSERT INTO historial_estado_proyecto 
        (id_proyecto, id_estado, fecha_cambio, observaciones, usuario_modificador) 
        VALUES (?, ?, NOW(), ?, ?)";

        $stmt2 = $conexion->prepare($sqlEstado);
        $stmt2->bind_param("iiss", $id_proyecto, $id_estado, $observaciones, $usuario_modificador);

        if (!$stmt2->execute()) {
            throw new Exception("Error al insertar estado inicial del proyecto: " . $stmt2->error);
        }

        // 3. Actualizar el estado actual en la tabla proyecto
        $sqlUpdateProyecto = "UPDATE proyecto SET id_estado_actual = ? WHERE id_proyecto = ?";
        $stmt3 = $conexion->prepare($sqlUpdateProyecto);
        $stmt3->bind_param("ii", $id_estado, $id_proyecto);

        if (!$stmt3->execute()) {
            throw new Exception("Error al actualizar el estado actual del proyecto: " . $stmt3->error);
        }
    } else {
        throw new Exception("No se encontró el estado '$estado_inicial_nombre' en la tabla estado_fip.");
    }

    // Insertar sectores estratégicos
    foreach ($sectores as $sector_nombre) {
        $sector_nombre = $conexion->real_escape_string($sector_nombre);

        $buscarSector = "SELECT id_sector FROM sector_estrategico WHERE nombre = '$sector_nombre'";
        $result = $conexion->query($buscarSector);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_sector = $row['id_sector'];
        } else {
            $sqlInsertSector = "INSERT INTO sector_estrategico (nombre) VALUES ('$sector_nombre')";
            if (!$conexion->query($sqlInsertSector)) {
                throw new Exception("Error al insertar sector: " . $conexion->error);
            }
            $id_sector = $conexion->insert_id;
        }

        $verificarRelacion = "SELECT * FROM proyecto_sector WHERE id_proyecto = '$id_proyecto' AND id_sector = '$id_sector'";
        $resultRelacion = $conexion->query($verificarRelacion);

        if ($resultRelacion->num_rows === 0) {
            $sqlInsertProyectoSector = "INSERT INTO proyecto_sector (id_proyecto, id_sector)
            VALUES ('$id_proyecto', '$id_sector')";
            if (!$conexion->query($sqlInsertProyectoSector)) {
                throw new Exception("Error al insertar relación proyecto-sector: " . $conexion->error);
            }
        }
    }

    // ===============================================
    // SECCIÓN 2: DESCRIPCIÓN DEL PROYECTO
    // ===============================================

    $objetivo_general       = $conexion->real_escape_string($_POST['objetivo_general']);
    $objetivos_especificos  = $conexion->real_escape_string($_POST['objetivos_especificos']);
    $descripcion            = $conexion->real_escape_string($_POST['descripcion']);
    $poblacion_meta         = $conexion->real_escape_string($_POST['poblacion_meta']);

    $sqlDescripcion = "INSERT INTO descripcion_proyecto (id_proyecto, objetivo_general, objetivos_especificos, descripcion_breve, poblacion_meta)
    VALUES ('$id_proyecto', '$objetivo_general', '$objetivos_especificos', '$descripcion', '$poblacion_meta')";
    if (!$conexion->query($sqlDescripcion)) {
        throw new Exception("Error al insertar descripción de proyecto: " . $conexion->error);
    }

    // =============================================== 
    // SECCIÓN: CARACTERÍSTICAS DE LA POBLACIÓN
    // ===============================================

    $edad                 = $conexion->real_escape_string($_POST['edad']);
    $genero              = $conexion->real_escape_string($_POST['genero']);
    $situacion_economica = $conexion->real_escape_string($_POST['situacion_economica']);
    $ubicacion = $conexion->real_escape_string($_POST['ubicacion']);
    $area_prioritaria = $conexion->real_escape_string($_POST['areas_impacto'] ?? '');

    // Este campo es opcional, si no se usa puede dejarse como cadena vacía
    $impacto_esperado = ''; // o $_POST['impacto_esperado'] si lo agregaras después

    $sqlPoblacion = "INSERT INTO poblacion (
    id_proyecto,
    edad,
    genero,
    situacion_economica,
    ubicacion,
    area_prioritaria,
    impacto_esperado
) VALUES (
    '$id_proyecto',
    '$edad',
    '$genero',
    '$situacion_economica',
    '$ubicacion',
    '$area_prioritaria',
    '$impacto_esperado'
)";

    if (!$conexion->query($sqlPoblacion)) {
        throw new Exception("Error al insertar características de la población: " . $conexion->error);
    }


    // ===============================================
    // SECCIÓN 3: JUSTIFICACIÓN DEL PROYECTO
    // ===============================================

    $descripcion_problema = $conexion->real_escape_string($_POST['descripcion_problema']);
    $oportunidad = $conexion->real_escape_string($_POST['oportunidad']);
    $impacto_anticipado = $conexion->real_escape_string($_POST['impacto_anticipado']);

    // Insertar la justificación del proyecto en la base de datos
    $sqlJustificacion = "INSERT INTO justificacion (id_proyecto, descripcion_problema, oportunidad, impacto_anticipado)
VALUES ('$id_proyecto', '$descripcion_problema', '$oportunidad', '$impacto_anticipado')";
    if (!$conexion->query($sqlJustificacion)) {
        throw new Exception("Error al insertar justificación del proyecto: " . $conexion->error);
    }

    // ===============================================
    // SECCIÓN 4: Requerimientos
    // ===============================================

    $presupuesto_estimado = floatval($_POST['presupuesto_estimado']);
    $componentes_principales = $conexion->real_escape_string($_POST['componentes_principales']);
    $fuente_financiamiento = isset($_POST['fuente_financiamiento'])
        ? $conexion->real_escape_string(implode(", ", $_POST['fuente_financiamiento']))
        : '';
    $recursos_humanos = $conexion->real_escape_string($_POST['recursos_humanos']);
    $infraestructura_equipamiento = $conexion->real_escape_string($_POST['infraestructura_equipamiento']);

    // Insertar los requerimientos del proyecto en la base de datos
    $sqlRequerimientos = "INSERT INTO requerimientos_recursos (id_proyecto, presupuesto_estimado, componentes_principales, fuente_financiamiento, recursos_humanos, infraestructura_equipamiento)
VALUES ('$id_proyecto', '$presupuesto_estimado', '$componentes_principales', '$fuente_financiamiento', '$recursos_humanos', '$infraestructura_equipamiento')";
    if (!$conexion->query($sqlRequerimientos)) {
        throw new Exception("Error al insertar requerimientos del proyecto: " . $conexion->error);
    }

    // ===============================================
    // SECCIÓN 5 : Factibilidad inicial
    // ===============================================

    $viabilidad_tecnica     = $conexion->real_escape_string($_POST['viabilidad_tecnica']);
    $viabilidad_economica   = $conexion->real_escape_string($_POST['viabilidad_economica']);
    $viabilidad_social      = $conexion->real_escape_string($_POST['viabilidad_social']);
    $viabilidad_ambiental   = $conexion->real_escape_string($_POST['viabilidad_ambiental']);

    // Insertar la factibilidad inicial del proyecto en la base de datos
    $sqlFactibilidad = "INSERT INTO factibilidad_inicial (id_proyecto, viabilidad_tecnica, viabilidad_economica, viabilidad_social, viabilidad_ambiental)
VALUES ('$id_proyecto', '$viabilidad_tecnica', '$viabilidad_economica', '$viabilidad_social', '$viabilidad_ambiental')";
    if (!$conexion->query($sqlFactibilidad)) {
        throw new Exception("Error al insertar factibilidad inicial: " . $conexion->error);
    }

    // ===============================================
    // SECCIÓN 6 : Evaluación inicial
    // ===============================================

    // Obtener y sanitizar los valores del formulario
    $viabilidad_tecnica_numero = intval($_POST['viabilidad_tecnica_numero']);
    $viabilidad_economica_numero = intval($_POST['viabilidad_economica_numero']);
    $impacto_social_numero = intval($_POST['impacto_social_numero']);
    $alineacion_objetivos = intval($_POST['alineacion_objetivos']);
    $observaciones_recomendaciones = $conexion->real_escape_string($_POST['observaciones_recomendaciones'] ?? '');

    // Insertar la evaluación inicial del proyecto en la base de datos
    $sqlEvaluacion = "INSERT INTO evaluacion_inicial (
    id_proyecto,
    viabilidad_tecnica_numero,
    viabilidad_economica_numero,
    impacto_social_numero,
    alineacion_objetivos,
    observaciones_recomendaciones
) VALUES (
    '$id_proyecto',
    '$viabilidad_tecnica_numero',
    '$viabilidad_economica_numero',
    '$impacto_social_numero',
    '$alineacion_objetivos',
    '$observaciones_recomendaciones'
)";

    if (!$conexion->query($sqlEvaluacion)) {
        throw new Exception("Error al insertar evaluación inicial del proyecto: " . $conexion->error);
    }

    if (!$conexion->query($sqlEvaluacion)) {
        throw new Exception("Error al insertar evaluación inicial: " . $conexion->error);
    }



    // ✅ Confirmar transacción
    $conexion->commit();

    // ✅ Todo bien


    echo "<div style='padding:20px;'><h2>✅ Proyecto registrado correctamente.</h2><p>ID del proyecto: $id_proyecto</p><a href='formulario_fip.php'>Volver al formulario</a></div>";

    // Redirigir con JavaScript después de un tiempo (en este caso, 3 segundos)
    echo "<script>setTimeout(function() { window.location.href = 'fichaRequerimientos.php'; }, 3000);</script>";
} catch (Exception $e) {
    // ❌ Algo falló — deshacer cambios
    $conexion->rollback();
    echo "<h3>Error: " . $e->getMessage() . "</h3><a href='formulario_fip.php'>Volver al formulario</a>";
}

// Cerrar conexión
$conexion->close();
