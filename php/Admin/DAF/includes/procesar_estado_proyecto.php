<?php
session_start();
// Verificación de la sesión del usuario
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'proponente' || $_SESSION['area'] !== 'DAF') {
    echo "Usuario no autorizado. Redirigiendo al login..."; // Mensaje de depuración
    header("Location: login.php");
    exit();
}

// Depuración: Verificar que los datos están siendo recibidos
$id_proyecto = $_POST['id_proyecto'] ?? null;
$accion = $_POST['accion'] ?? null;
echo "ID del proyecto recibido: " . var_export($id_proyecto, true) . "<br>"; // Depuración
echo "Acción recibida: " . var_export($accion, true) . "<br>"; // Depuración

// Validación de los datos recibidos
if (!$id_proyecto || !$accion) {
    echo "Error: Información inválida."; // Mensaje de depuración
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$fecha_decision = date('Y-m-d H:i:s');

// Solo procesamos la acción de 'aprobar'
if ($accion === 'aprobar') {
    $estado_proyecto = 'Aprobado';
    $id_estado = 4;
    $observaciones = 'Propuesta aceptada por DAF, en espera de aprobación del Director';
    $mensaje = 'aprobado';
} elseif ($accion === 'rechazar') {
    $estado_proyecto = 'Rechazado';
    $id_estado = 7;
    $observaciones = $_POST['motivo_rechazo'] ?? 'Sin motivo especificado.';
    $mensaje = 'rechazado';
} else {
    echo "Acción no válida.";
    exit();
}
// Si observaciones es null, lo convertimos a una cadena vacía
$observaciones = $observaciones ?? "";  // Si es null, le asignamos un valor vacío

// Conectar a la base de datos
include './../../Comunicaciones/bd/conexion_test.php';

// Verificar que la conexión fue exitosa
if ($conn) {
    echo "Conexión a la base de datos exitosa.<br>"; // Depuración
} else {
    echo "Error de conexión a la base de datos.<br>"; // Mensaje de depuración
    exit();
}

// Actualizar el estado del proyecto en la base de datos
$sql_update = "UPDATE proyecto SET id_estado_actual = ? WHERE id_proyecto = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("ii", $id_estado, $id_proyecto);
$stmt_update->execute();

// Depuración: Verificar si la actualización fue exitosa
if ($stmt_update->affected_rows > 0) {
    echo "Estado del proyecto actualizado exitosamente.<br>"; // Depuración
} else {
    echo "No se pudo actualizar el estado del proyecto.<br>"; // Mensaje de depuración
    exit();
}

// Registrar el cambio en la tabla historial_estado_proyecto
$sql_estado = "INSERT INTO historial_estado_proyecto (id_proyecto, id_estado, fecha_cambio, observaciones, usuario_modificador)
               VALUES (?, ?, ?, ?, ?)";
$stmt_estado = $conn->prepare($sql_estado);
$stmt_estado->bind_param("iissi", $id_proyecto, $id_estado, $fecha_decision, $observaciones, $id_usuario);
$stmt_estado->execute();

// Verificar si se registró correctamente
if ($stmt_estado->affected_rows > 0) {
    echo "Cambio de estado registrado correctamente.<br>";
} else {
    echo "No se pudo registrar el cambio de estado.<br>";
    // Puedes salir si lo consideras crítico
    // exit();
}

// Registrar la acción en la tabla historial_aprobaciones
$sql_historial = "INSERT INTO historial_aprobaciones (id_proyecto, nombre_proyecto, id_usuario, fecha_decision, estado_proyecto, observaciones)
                  SELECT id_proyecto, nombre, ?, ?, ?, ? FROM proyecto WHERE id_proyecto = ?";
$stmt_historial = $conn->prepare($sql_historial);
$stmt_historial->bind_param("isssi", $id_usuario, $fecha_decision, $estado_proyecto, $observaciones, $id_proyecto);
$stmt_historial->execute();


// Depuración: Verificar si el historial fue registrado correctamente
if ($stmt_historial->affected_rows > 0) {
    echo "Acción registrada en el historial exitosamente.<br>"; // Depuración
} else {
    echo "No se pudo registrar la acción en el historial.<br>"; // Mensaje de depuración
    exit();
}

// Redirigir a la página de proyectos con éxito
header("Location: ../ver_fichas.php?mensaje=$mensaje");

exit();
