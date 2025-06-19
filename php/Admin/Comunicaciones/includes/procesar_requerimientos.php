<?php
// Conexión a la base de datos
require_once __DIR__ . '/../bd/conexion_test.php';

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

// Recuperar el id_usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'];  // El id_usuario está en la sesión
$id_proyecto = $_SESSION['id_proyecto']; // Recuperar el id_proyecto desde la sesión

// Iniciar transacción
$conn->begin_transaction();

// Recuperar los datos del formulario
$fecha = $_POST['fecha'];
$tipo_requerimiento = $_POST['tipo_requerimiento'];
$requerente = $_POST['requerente'];
$encargado = $_POST['encargado'];
// $descripcion = $_POST['descripcion'];
// $justificacion = $_POST['justificacion'];
// $presupuesto = $_POST['presupuesto']; 
// $fecha_requerida = $_POST['fecha_requerida']; 
// $ubicacion = $_POST['ubicacion']; 

// Recuperar los datos de los productos/servicios
$cantidades = $_POST['cantidad'];
$productos = $_POST['producto'];
$especificaciones = $_POST['especificaciones'];
$unidades = $_POST['unidad'];
$observaciones = $_POST['observaciones'];

// Insertar los datos en la tabla ficha_informacion_general
$sqlFicha = "INSERT INTO ficha_informacion_general (id_proyecto, fecha, tipo_requerimiento, area_requerente, encargado_requerimiento)
VALUES ('$id_proyecto', '$fecha', '$tipo_requerimiento', '$requerente', '$encargado')";

if (!$conn->query($sqlFicha)) {
    throw new Exception("Error al insertar ficha de requerimiento: " . $conn->error);
}

// Obtener el id_ficha recién insertado
$id_ficha = $conn->insert_id;

// Insertar los datos en la tabla financiamiento_requerimiento
$sqlFinanciamiento = "INSERT INTO financiamiento_requerimiento (id_ficha, presupuesto_estimado, fecha_requerida, ubicacion)
VALUES ('$id_ficha', '$presupuesto', '$fecha_requerida', '$ubicacion')";

if (!$conn->query($sqlFinanciamiento)) {
    throw new Exception("Error al insertar financiamiento de requerimiento: " . $conn->error);
}

// Insertar los productos/servicios en la tabla detalle_materiales
foreach ($cantidades as $index => $cantidad) {
    $producto_servicio = $productos[$index];
    $especificacion = $especificaciones[$index];
    $unidad = $unidades[$index];
    $observacion = $observaciones[$index];

    $sqlDetalleMateriales = "INSERT INTO detalle_materiales (id_ficha, cantidad, producto_servicio, especificaciones_tecnicas, unidad, observaciones, id_proyecto)
    VALUES ('$id_ficha', '$cantidad', '$producto_servicio', '$especificacion', '$unidad', '$observacion', '$id_proyecto')";

    if (!$conn->query($sqlDetalleMateriales)) {
        throw new Exception("Error al insertar detalle de material/servicio: " . $conn->error);
    }
}

// 1. Obtener el ID del estado "Enviado" desde la tabla estado_fip
$estado_inicial_nombre = 'Enviado';
$usuario_modificador = $_SESSION['id_usuario'] ?? 'sistema'; // o el usuario que esté autenticado
$observaciones = 'Ficha de requerimientos enviada correctamente por el usuario, en espera de revision de DAF.';

$sqlGetEstado = "SELECT id_estado FROM estado_fip WHERE nombre_estado = ?";
$stmtEstado = $conn->prepare($sqlGetEstado);
$stmtEstado->bind_param("s", $estado_inicial_nombre);
$stmtEstado->execute();
$resultadoEstado = $stmtEstado->get_result();

if ($fila = $resultadoEstado->fetch_assoc()) {
    $id_estado = $fila['id_estado'];

    // 2. Insertar el cambio de estado en historial_estado_proyecto
    $sqlEstado = "INSERT INTO historial_estado_proyecto (id_proyecto, id_estado, fecha_cambio, observaciones, usuario_modificador)
    VALUES (?, ?, NOW(), ?, ?)";
    
    $stmtInsertEstado = $conn->prepare($sqlEstado);
    $stmtInsertEstado->bind_param("iiss", $id_proyecto, $id_estado, $observaciones, $usuario_modificador);
    
    if (!$stmtInsertEstado->execute()) {
        throw new Exception("Error al insertar estado inicial en historial_estado_proyecto: " . $stmtInsertEstado->error);
    }

    // 3. Actualizar el estado actual en la tabla proyecto
    $sqlUpdateProyecto = "UPDATE proyecto SET id_estado_actual = ? WHERE id_proyecto = ?";
    $stmtUpdateProyecto = $conn->prepare($sqlUpdateProyecto);
    $stmtUpdateProyecto->bind_param("ii", $id_estado, $id_proyecto);
    
    if (!$stmtUpdateProyecto->execute()) {
        throw new Exception("Error al actualizar el estado del proyecto: " . $stmtUpdateProyecto->error);
    }
    

} else {
    throw new Exception("No se encontró el estado '$estado_inicial_nombre' en la tabla estado_fip.");
}

// Si todo es correcto, confirmamos la transacción
$conn->commit();

// Redirigir a la página de confirmación o éxito
header("Location: ../ver_fichas");
exit();
?>
