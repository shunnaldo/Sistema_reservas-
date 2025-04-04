<?php
session_start();
include('../conexion.php');

// Verificar permisos
if (!isset($_SESSION['admin_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: loginAdmin.php?error=No tienes permisos.");
    exit;
}

$mensaje = "";

// Obtener ID de la noticia
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: noticiasAdmin.php?error=ID no válido.");
    exit;
}

$id_noticia = $_GET['id'];

// Obtener datos actuales de la noticia
$sql = "SELECT * FROM contenido WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_noticia);
$stmt->execute();
$result = $stmt->get_result();
$noticia = $result->fetch_assoc();

if (!$noticia) {
    header("Location: noticiasAdmin.php?error=Noticia no encontrada.");
    exit;
}

// Procesar formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST["titulo"]);
    $cuerpo = trim($_POST["cuerpo"]);
    $link = trim($_POST["link"]);
    $nombreImagen = $noticia['imagen']; // Mantener imagen actual si no se cambia

    if (!empty($link) && !filter_var($link, FILTER_VALIDATE_URL)) {
        $mensaje = "⚠️ El enlace proporcionado no es válido.";
    } else {
        // Verificar si se subió una nueva imagen
        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
            $nombreImagen = time() . "_" . basename($_FILES["imagen"]["name"]);
            $rutaDestino = "uploads/" . $nombreImagen;

            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
                // Eliminar la imagen anterior si existe
                $rutaAnterior = "uploads/" . $noticia['imagen'];
                if (file_exists($rutaAnterior)) {
                    unlink($rutaAnterior);
                }
            } else {
                $mensaje = "❌ Error al subir la nueva imagen.";
            }
        }
        
        // Actualizar la noticia en la base de datos
        $sqlUpdate = "UPDATE contenido SET titulo = ?, cuerpo = ?, imagen = ?, link = ? WHERE id = ?";
        $stmt = $conexion->prepare($sqlUpdate);
        $stmt->bind_param("ssssi", $titulo, $cuerpo, $nombreImagen, $link, $id_noticia);
        
        if ($stmt->execute()) {
            header("Location: noticiasAdmin.php?editado=1");
            exit();
        } else {
            $mensaje = "❌ Error al actualizar la noticia.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Noticia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/noticiasAdmin.css">

</head>
<body>
<div id="navbarAdmin-container"></div>
<div class="container-fluid">
    <div class="container-center">

    <h2>Editar Noticia</h2>
    </div>
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-warning"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" value="<?php echo htmlspecialchars($noticia['titulo']); ?>" required>

        <label for="cuerpo">Cuerpo:</label>
        <textarea name="cuerpo" required><?php echo htmlspecialchars($noticia['cuerpo']); ?></textarea>

        <label for="imagen">Imagen Actual:</label>
        <br>
        <img src="uploads/<?php echo $noticia['imagen']; ?>" width="200">
        <br>
        <label for="imagen">Cambiar Imagen (opcional):</label>
        <input type="file" name="imagen">

        <label for="link">Enlace (opcional):</label>
        <input type="url" name="link" value="<?php echo htmlspecialchars($noticia['link']); ?>" placeholder="https://ejemplo.com">


        <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn btn-primary">Actualizar Noticia</button>
            <a href="noticiasAdmin.php" class="btn btn-secondary">Cancelar</a>
        </div>


    </form>
</div>

<script src="../../js/navbarAdmin.js"></script>
<script src="../../js/sidebar.js"></script>  
</body>
</html>
