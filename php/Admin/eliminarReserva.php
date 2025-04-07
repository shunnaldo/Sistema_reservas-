<?php
session_start();
require_once '../conexion.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "error" => "Acceso denegado."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $stmt = $conexion->prepare("DELETE FROM Reservas WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Error al eliminar."]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Solicitud inválida."]);
}
?>
