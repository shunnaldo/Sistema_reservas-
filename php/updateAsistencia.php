<?php
include('../conexion.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $check_asistencia = $_POST['check_asistencia'];

    $stmt = $conexion->prepare("UPDATE Reservas SET check_asistencia = ? WHERE id = ?");
    $stmt->bind_param("ii", $check_asistencia, $id);
    if ($stmt->execute()) {
        echo "Actualizado correctamente";
    } else {
        echo "Error al actualizar";
    }
    $stmt->close();
}
?>
