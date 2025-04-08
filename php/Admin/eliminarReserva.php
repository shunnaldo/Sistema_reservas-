<?php
require_once '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = intval($_POST["id"]);
    
    $query = "DELETE FROM Reservas WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conexion->close();
}
?>
