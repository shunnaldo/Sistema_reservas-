<?php
session_start();
include('../conexion.php');

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
        $nombreImagen = time() . "_" . basename($_FILES["imagen"]["name"]);
        $rutaDestino = "uploads/" . $nombreImagen;

        // Mover la imagen a la carpeta de destino
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
            // Obtener ID del usuario desde sesión
            $usuario_id = $_SESSION["id_usuario"]; // Asegúrate de que esta variable esté definida

            // Insertar en la base de datos
            $sql = "INSERT INTO contenido (tipo, imagen, usuario_id, fecha_creacion) 
                    VALUES ('carrusel', ?, ?, NOW())";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("si", $nombreImagen, $usuario_id);

            if ($stmt->execute()) {
                echo "<script>alert('Imagen subida correctamente'); window.location.href='noticiasCarrusel.php';</script>";
            } else {
                echo "<script>alert('Error al guardar en la base de datos');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Error al mover la imagen');</script>";
        }
    } else {
        echo "<script>alert('Error en la subida de la imagen');</script>";
    }
}

// Obtener imágenes del carrusel desde la base de datos
$sql = "SELECT imagen FROM contenido WHERE tipo = 'carrusel' ORDER BY fecha_creacion DESC";
$resultado = $conexion->query($sql);

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

        echo "<script>alert('Imagen eliminada correctamente'); window.location.href='noticiasCarrusel.php';</script>";
    } else {
        echo "<script>alert('No se encontró la imagen en la base de datos'); window.location.href='noticiasCarrusel.php';</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Imagen al Carrusel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/noticiasCarrusel.css">
</head>
<body>
<div id="navbarAdmin-container"></div>
<br><br>

<div class="container-fluid">
    <div class="container-center">
    <div class="container ">
            <h2 class="text-center mb-4">Subir Imagen al Carrusel</h2>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="imagen" class="form-label">Seleccionar Imagen</label>
                    <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Subir Imagen</button>
                <a href="noticiasCarrusel.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>


        <div class="container mt-5">
        <h2 class="text-center mb-4">Carrusel de Imágenes</h2>

        <?php if ($resultado->num_rows > 0): ?>
        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                $active = true;
                while ($fila = $resultado->fetch_assoc()):
                ?>
                <div class="carousel-item <?php echo $active ? 'active' : ''; ?>">
                    <img src="uploads/<?php echo $fila['imagen']; ?>" class="d-block w-100" alt="Imagen de carrusel">
                    
                    <!-- Botón de eliminar debajo de cada imagen -->
                    <div class="text-center mt-2">
                        <form action="eliminarImagen.php" method="POST">
                            <input type="hidden" name="imagen" value="<?php echo $fila['imagen']; ?>">
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
                <?php
                $active = false;
                endwhile;
                ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
        <?php else: ?>
        <p class="text-center">No hay imágenes en el carrusel.</p>
        <?php endif; ?>
    </div>
    </div>
   

</div>
        
    <script src="../../js/navbarAdmin.js"></script>
    <script src="../../js/sidebar.js"></script>  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
