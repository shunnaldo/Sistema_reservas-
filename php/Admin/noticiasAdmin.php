<?php
session_start();
include('../conexion.php');

// Verificar permisos
if (!isset($_SESSION['admin_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: loginAdmin.php?error=No tienes permisos.");
    exit;
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST["titulo"]);
    $cuerpo = trim($_POST["cuerpo"]);
    $link = trim($_POST["link"]);

    // Validar enlace externo (opcional)
    if (!empty($link) && !filter_var($link, FILTER_VALIDATE_URL)) {
        $mensaje = "⚠️ El enlace proporcionado no es válido.";
    } else {
        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
            $nombreImagen = time() . "_" . basename($_FILES["imagen"]["name"]);
            $rutaDestino = "uploads/" . $nombreImagen;

            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
                $sql = "INSERT INTO contenido (tipo, titulo, cuerpo, imagen, link) VALUES ('noticia', ?, ?, ?, ?)";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("ssss", $titulo, $cuerpo, $nombreImagen, $link);

                if ($stmt->execute()) {
                    $mensaje = "✅ Noticia subida con éxito.";
                    header("Location: noticiasAdmin.php");  // Redirige para evitar reenvío del formulario
                    exit();  // Detiene la ejecución del script
                } else {
                    $mensaje = "❌ Error al guardar en la base de datos.";
                }
            } else {
                $mensaje = "❌ Error al subir la imagen.";
            }
        } else {
            $mensaje = "⚠️ Por favor, selecciona una imagen válida.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Noticia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../css/noticiasAdmin.css">
</head>
<body>


    <div id="navbarAdmin-container"></div>

    <div class="container-center ">
        <div class="container-fluid">

            <div class="container">
                <h2 class="mt-4">Subir Nueva Noticia</h2>

                <!-- Mensaje de resultado -->
                <?php if (!empty($mensaje)): ?>
                    <div class="alert alert-info"><?php echo $mensaje; ?></div>
                <?php endif; ?>

                <form action="" method="POST" enctype="multipart/form-data" class="mt-3">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título:</label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="cuerpo" class="form-label">Cuerpo:</label>
                        <textarea name="cuerpo" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen:</label>
                        <input type="file" name="imagen" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="link" class="form-label">Enlace (opcional):</label>
                        <input type="url" name="link" class="form-control" placeholder="https://ejemplo.com">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Subir Noticia
                    </button>
                </form>
            </div>
        </div>

    </div>        
    <script src="../../js/navbarAdmin.js"></script>
    <script src="../../js/sidebar.js"></script>  

</body>
</html>
