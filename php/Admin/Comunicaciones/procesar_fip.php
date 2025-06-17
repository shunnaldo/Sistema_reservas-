<?php
// Conexión a la base de datos
require_once __DIR__ . '/bd/conexion_test.php';

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
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
$conn->begin_transaction();

try {
    // ===============================================
    // SECCIÓN 1: INFORMACIÓN GENERAL DEL PROYECTO
    // ===============================================

    // Recibir datos del proyecto
    $nombre_proyecto      = $conn->real_escape_string($_POST['nombre_proyecto']);
    $fecha_presentacion   = $conn->real_escape_string($_POST['fecha_presentacion']);
    $nombre_completo      = $conn->real_escape_string($_POST['nombre_completo']);
    $cargo                = $conn->real_escape_string($_POST['cargo']);
    $organizacion         = $conn->real_escape_string($_POST['organizacion']);
    $direccion            = $conn->real_escape_string($_POST['direccion']);
    $telefono             = $conn->real_escape_string($_POST['telefono']);
    $email                = $conn->real_escape_string($_POST['email']);
    $region               = $conn->real_escape_string($_POST['region']);
    $municipio            = $conn->real_escape_string($_POST['municipio']);
    $sector_comuna        = $conn->real_escape_string($_POST['sector_comuna']);
    $ambito_territorial   = isset($_POST['ambito_territorial']) ? $conn->real_escape_string($_POST['ambito_territorial']) : null;
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
    $resultadoUltimo = $conn->query($sqlUltimo);
    $filaUltimo = $resultadoUltimo->fetch_assoc();
    $ultimo_num = $filaUltimo['ultimo_num'] ?? 0;
    $nuevo_num = $ultimo_num + 1;
    $numero_fip = $nuevo_num . "/" . $anio_actual;

    // Sectores estratégicos (checkbox múltiple)
    $sectores = isset($_POST['sectores']) ? $_POST['sectores'] : [];
    $otro_sector_espec = trim($conn->real_escape_string($_POST['otro_sector_espec']));

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
    if (!$conn->query($sqlUbicacion)) {
        throw new Exception("Error al insertar ubicación: " . $conn->error);
    }
    $id_ubicacion = $conn->insert_id;

    // Insertar proyecto con correlativo
    $sqlProyecto = "INSERT INTO proyecto (numero_fip, nombre, fecha_presentacion, duracion_valor, duracion_tipo, id_usuario, id_ubicacion)
    VALUES ('$numero_fip', '$nombre_proyecto', '$fecha_presentacion', '$duracion_valor', '$duracion_tipo', '$id_usuario', '$id_ubicacion')";
    if (!$conn->query($sqlProyecto)) {
        throw new Exception("Error al insertar proyecto: " . $conn->error);
    }
    $id_proyecto = $conn->insert_id;

    // Estado inicial del proyecto
    $estado_inicial_nombre = 'Realizado';
    $observaciones = 'Formulario enviado correctamente por el usuario.';
    $usuario_modificador = $_SESSION['id_usuario'] ?? 'sistema'; // o el usuario que esté autenticado

    // 1. Obtener el ID del estado desde la tabla estado_fip
    $sqlGetEstado = "SELECT id_estado FROM estado_fip WHERE nombre_estado = ?";
    $stmt = $conn->prepare($sqlGetEstado);
    $stmt->bind_param("s", $estado_inicial_nombre);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        $id_estado = $fila['id_estado'];

        // 2. Insertar en historial_estado_proyecto
        $sqlEstado = "INSERT INTO historial_estado_proyecto 
        (id_proyecto, id_estado, fecha_cambio, observaciones, usuario_modificador) 
        VALUES (?, ?, NOW(), ?, ?)";

        $stmt2 = $conn->prepare($sqlEstado);
        $stmt2->bind_param("iiss", $id_proyecto, $id_estado, $observaciones, $usuario_modificador);

        if (!$stmt2->execute()) {
            throw new Exception("Error al insertar estado inicial del proyecto: " . $stmt2->error);
        }

        // 3. Actualizar el estado actual en la tabla proyecto
        $sqlUpdateProyecto = "UPDATE proyecto SET id_estado_actual = ? WHERE id_proyecto = ?";
        $stmt3 = $conn->prepare($sqlUpdateProyecto);
        $stmt3->bind_param("ii", $id_estado, $id_proyecto);

        if (!$stmt3->execute()) {
            throw new Exception("Error al actualizar el estado actual del proyecto: " . $stmt3->error);
        }
    } else {
        throw new Exception("No se encontró el estado '$estado_inicial_nombre' en la tabla estado_fip.");
    }

    // Insertar sectores estratégicos
    foreach ($sectores as $sector_nombre) {
        $sector_nombre = $conn->real_escape_string($sector_nombre);

        $buscarSector = "SELECT id_sector FROM sector_estrategico WHERE nombre = '$sector_nombre'";
        $result = $conn->query($buscarSector);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_sector = $row['id_sector'];
        } else {
            $sqlInsertSector = "INSERT INTO sector_estrategico (nombre) VALUES ('$sector_nombre')";
            if (!$conn->query($sqlInsertSector)) {
                throw new Exception("Error al insertar sector: " . $conn->error);
            }
            $id_sector = $conn->insert_id;
        }

        $verificarRelacion = "SELECT * FROM proyecto_sector WHERE id_proyecto = '$id_proyecto' AND id_sector = '$id_sector'";
        $resultRelacion = $conn->query($verificarRelacion);

        if ($resultRelacion->num_rows === 0) {
            $sqlInsertProyectoSector = "INSERT INTO proyecto_sector (id_proyecto, id_sector)
            VALUES ('$id_proyecto', '$id_sector')";
            if (!$conn->query($sqlInsertProyectoSector)) {
                throw new Exception("Error al insertar relación proyecto-sector: " . $conn->error);
            }
        }
    }

    // ===============================================
    // SECCIÓN 2: DESCRIPCIÓN DEL PROYECTO
    // ===============================================

    $objetivo_general       = $conn->real_escape_string($_POST['objetivo_general']);
    $objetivos_especificos  = $conn->real_escape_string($_POST['objetivos_especificos']);
    $descripcion            = $conn->real_escape_string($_POST['descripcion']);
    $poblacion_meta         = $conn->real_escape_string($_POST['poblacion_meta']);

    $sqlDescripcion = "INSERT INTO descripcion_proyecto (id_proyecto, objetivo_general, objetivos_especificos, descripcion_breve, poblacion_meta)
    VALUES ('$id_proyecto', '$objetivo_general', '$objetivos_especificos', '$descripcion', '$poblacion_meta')";
    if (!$conn->query($sqlDescripcion)) {
        throw new Exception("Error al insertar descripción de proyecto: " . $conn->error);
    }

    // =============================================== 
    // SECCIÓN: CARACTERÍSTICAS DE LA POBLACIÓN
    // ===============================================

    $edad                 = $conn->real_escape_string($_POST['edad']);
    $genero              = $conn->real_escape_string($_POST['genero']);
    $situacion_economica = $conn->real_escape_string($_POST['situacion_economica']);
    $ubicacion = $conn->real_escape_string($_POST['ubicacion']);
    $area_prioritaria = $conn->real_escape_string($_POST['areas_impacto'] ?? '');

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

    if (!$conn->query($sqlPoblacion)) {
        throw new Exception("Error al insertar características de la población: " . $conn->error);
    }


    // ===============================================
    // SECCIÓN 3: JUSTIFICACIÓN DEL PROYECTO
    // ===============================================

    $descripcion_problema = $conn->real_escape_string($_POST['descripcion_problema']);
    $oportunidad = $conn->real_escape_string($_POST['oportunidad']);
    $impacto_anticipado = $conn->real_escape_string($_POST['impacto_anticipado']);

    // Insertar la justificación del proyecto en la base de datos
    $sqlJustificacion = "INSERT INTO justificacion (id_proyecto, descripcion_problema, oportunidad, impacto_anticipado)
VALUES ('$id_proyecto', '$descripcion_problema', '$oportunidad', '$impacto_anticipado')";
    if (!$conn->query($sqlJustificacion)) {
        throw new Exception("Error al insertar justificación del proyecto: " . $conn->error);
    }

    // ===============================================
    // SECCIÓN 4: Requerimientos
    // ===============================================

    $presupuesto_estimado = floatval($_POST['presupuesto_estimado']);
    $componentes_principales = $conn->real_escape_string($_POST['componentes_principales']);
    $fuente_financiamiento = isset($_POST['fuente_financiamiento'])
        ? $conn->real_escape_string(implode(", ", $_POST['fuente_financiamiento']))
        : '';
    $recursos_humanos = $conn->real_escape_string($_POST['recursos_humanos']);
    $infraestructura_equipamiento = $conn->real_escape_string($_POST['infraestructura_equipamiento']);

    // Insertar los requerimientos del proyecto en la base de datos
    $sqlRequerimientos = "INSERT INTO requerimientos_recursos (id_proyecto, presupuesto_estimado, componentes_principales, fuente_financiamiento, recursos_humanos, infraestructura_equipamiento)
VALUES ('$id_proyecto', '$presupuesto_estimado', '$componentes_principales', '$fuente_financiamiento', '$recursos_humanos', '$infraestructura_equipamiento')";
    if (!$conn->query($sqlRequerimientos)) {
        throw new Exception("Error al insertar requerimientos del proyecto: " . $conn->error);
    }

    // ===============================================
    // SECCIÓN 5 : Factibilidad inicial
    // ===============================================

    $viabilidad_tecnica     = $conn->real_escape_string($_POST['viabilidad_tecnica']);
    $viabilidad_economica   = $conn->real_escape_string($_POST['viabilidad_economica']);
    $viabilidad_social      = $conn->real_escape_string($_POST['viabilidad_social']);
    $viabilidad_ambiental   = $conn->real_escape_string($_POST['viabilidad_ambiental']);

    // Insertar la factibilidad inicial del proyecto en la base de datos
    $sqlFactibilidad = "INSERT INTO factibilidad_inicial (id_proyecto, viabilidad_tecnica, viabilidad_economica, viabilidad_social, viabilidad_ambiental)
VALUES ('$id_proyecto', '$viabilidad_tecnica', '$viabilidad_economica', '$viabilidad_social', '$viabilidad_ambiental')";
    if (!$conn->query($sqlFactibilidad)) {
        throw new Exception("Error al insertar factibilidad inicial: " . $conn->error);
    }

    // ===============================================
    // SECCIÓN 6 : Evaluación inicial
    // ===============================================

    // Obtener y sanitizar los valores del formulario
    $viabilidad_tecnica_numero = intval($_POST['viabilidad_tecnica_numero']);
    $viabilidad_economica_numero = intval($_POST['viabilidad_economica_numero']);
    $impacto_social_numero = intval($_POST['impacto_social_numero']);
    $alineacion_objetivos = intval($_POST['alineacion_objetivos']);
    $observaciones_recomendaciones = $conn->real_escape_string($_POST['observaciones_recomendaciones'] ?? '');

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

    if (!$conn->query($sqlEvaluacion)) {
        throw new Exception("Error al insertar evaluación inicial del proyecto: " . $conn->error);
    }

    if (!$conn->query($sqlEvaluacion)) {
        throw new Exception("Error al insertar evaluación inicial: " . $conn->error);
    }



    // ✅ Confirmar transacción
    $conn->commit();


    // ✅ Todo bien
    echo "<div style='padding:20px; text-align:center;'>
        <h2>✅ Proyecto registrado correctamente.</h2>
        <p>ID del proyecto: $id_proyecto</p>
        <div id='progress-bar' style='width: 100%; height: 20px; background-color: #ddd; margin: 20px 0;'>
            <div id='progress' style='height: 100%; width: 0; background-color: #4caf50;'></div>
        </div>
        <p><strong>Redirigiendo a la ficha de requerimientos en <span id='countdown'>3</span> segundos...</strong></p>
        <a href='formulario_fip.php'>Volver al formulario</a>
      </div>";

    // Redirigir con animación de progreso y contador regresivo
    echo "<script>
        let countdown = 3;
        const countdownElement = document.getElementById('countdown');
        const progressElement = document.getElementById('progress');

        function updateProgressBar() {
            if (countdown > 0) {
                countdown--;
                countdownElement.textContent = countdown;
                progressElement.style.width = (100 - (countdown * 33.33)) + '%';
                setTimeout(updateProgressBar, 1000);
            } else {
                window.location.href = 'fichaRequerimientos.php';
            }
        }

        updateProgressBar();
      </script>";
} catch (Exception $e) {
    // ❌ Algo falló — deshacer cambios
    $conn->rollback();
    echo "<h3>Error: " . $e->getMessage() . "</h3><a href='formulario_fip.php'>Volver al formulario</a>";
}

// Cerrar conexión
$conn->close();
