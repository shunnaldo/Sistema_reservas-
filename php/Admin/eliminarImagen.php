<?php
session_start();
include('../conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['imagen'])) {
    $imagen = $_POST['imagen'];

    // Buscar la imagen en la base de datos
    $sql = "SELECT imagen FROM contenido WHERE imagen = ? AND tipo = 'carrusel'";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $imagen);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Eliminar la imagen de la base de datos
        $sqlDelete = "DELETE FROM contenido WHERE imagen = ? AND tipo = 'carrusel'";
        $stmtDelete = $conexion->prepare($sqlDelete);
        $stmtDelete->bind_param("s", $imagen);
        $stmtDelete->execute();

        // Eliminar la imagen del servidor
        $rutaImagen = "uploads/" . $imagen;
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }

        echo "<script>alert('Imagen eliminada correctamente'); window.location.href='noticiasAdmin.php';</script>";
    } else {
        echo "<script>alert('No se encontró la imagen en la base de datos'); window.location.href='noticiasAdmin.php';</script>";
    }
    $stmt->close();
}
?>
