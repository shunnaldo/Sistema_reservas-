<?php
include '../conexion.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'];
    $motivo = $_POST['motivo'];

    // Evitar duplicados
    $check = $conn->prepare("SELECT COUNT(*) FROM diasBloqueados WHERE fecha = ?");
    $check->bind_param("s", $fecha);
    $check->execute();
    $check->bind_result($existe);
    $check->fetch();
    $check->close();

    if ($existe > 0) {
        echo "⚠️ La fecha ya está bloqueada.";
    } else {
        $stmt = $conn->prepare("INSERT INTO diasBloqueados (fecha, motivo) VALUES (?, ?)");
        $stmt->bind_param("ss", $fecha, $motivo);
        $stmt->execute();
        echo "✅ Día bloqueado exitosamente.";
    }
}
?>
