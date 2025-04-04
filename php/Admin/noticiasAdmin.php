<?php
session_start();
include('../conexion.php');

// Verificar permisos
if (!isset($_SESSION['admin_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: loginAdmin.php?error=No tienes permisos.");
    exit;
}

$mensaje = "";

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST["titulo"]);
    $cuerpo = trim($_POST["cuerpo"]);
    $link = trim($_POST["link"]);
    $usuario_id = $_SESSION['admin_id'];

    if (!empty($link) && !filter_var($link, FILTER_VALIDATE_URL)) {
        $mensaje = "⚠️ El enlace proporcionado no es válido.";
    } else {
        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
            $nombreImagen = time() . "_" . basename($_FILES["imagen"]["name"]);
            $rutaDestino = "uploads/" . $nombreImagen;

            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
                $sql = "INSERT INTO contenido (tipo, titulo, cuerpo, imagen, link, usuario_id) VALUES ('noticia', ?, ?, ?, ?, ?)";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("ssssi", $titulo, $cuerpo, $nombreImagen, $link, $usuario_id);

                if ($stmt->execute()) {
                    header("Location: noticiasAdmin.php?success=1");
                    exit();
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

// Eliminar noticia
if (isset($_GET['eliminar'])) {
    $id_noticia = $_GET['eliminar'];

    // Obtener la imagen para eliminarla del servidor
    $sqlImagen = "SELECT imagen FROM contenido WHERE id = ?";
    $stmt = $conexion->prepare($sqlImagen);
    $stmt->bind_param("i", $id_noticia);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $noticia = $resultado->fetch_assoc();
    $rutaImagen = "uploads/" . $noticia['imagen'];

    if (file_exists($rutaImagen)) {
        unlink($rutaImagen); // Eliminar imagen del servidor
    }

    // Eliminar noticia de la base de datos
    $sqlEliminar = "DELETE FROM contenido WHERE id = ?";
    $stmt = $conexion->prepare($sqlEliminar);
    $stmt->bind_param("i", $id_noticia);
    if ($stmt->execute()) {
        header("Location: noticiasAdmin.php?eliminado=1");
        exit();
    } else {
        $mensaje = "❌ Error al eliminar la noticia.";
    }
}

// Obtener noticias de la base de datos
$sqlNoticias = "SELECT c.*, a.nombre AS autor FROM contenido c 
                JOIN Administradores a ON c.usuario_id = a.id_usuario 
                WHERE c.tipo = 'noticia' 
                ORDER BY c.fecha_creacion DESC";

$resultNoticias = $conexion->query($sqlNoticias);
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
<br>
<div class="container-fluid">
    <div class="container-center">
        <h2>Subir Nueva Noticia</h2>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" required>

        <label for="cuerpo">Cuerpo:</label>
        <textarea name="cuerpo" required></textarea>

        <label for="imagen">Imagen:</label>
        <input type="file" name="imagen" required>

        <label for="link">Enlace (opcional):</label>
        <input type="url" name="link" placeholder="https://ejemplo.com">

        <button type="submit">
            <i class="fas fa-upload"></i> Subir Noticia
        </button>
    </form>

    <!-- Mostrar Noticias -->
    <div class="container-center mt-5">
        <h2>Noticias Publicadas</h2>
    </div>
    
    <div class="row">
        <?php while ($noticia = $resultNoticias->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <img src="uploads/<?php echo $noticia['imagen']; ?>" class="card-img-top" alt="Imagen Noticia">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($noticia['titulo']); ?></h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($noticia['cuerpo'])); ?></p>
                        <p class="text-muted"><i class="bi bi-person-fill"></i> Publicado por: <strong><?php echo htmlspecialchars($noticia['autor']); ?></strong></p>
                        <?php if (!empty($noticia['link'])): ?>
                            <a href="<?php echo htmlspecialchars($noticia['link']); ?>" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fas fa-external-link-alt"></i> Ver más
                            </a>
                        <?php endif; ?>
                        <br>
                        <!-- Botones de Editar y Eliminar -->
                        <a href="editarNoticia.php?id=<?php echo $noticia['id']; ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="?eliminar=<?php echo $noticia['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta noticia?');">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

</div>

<script src="../../js/navbarAdmin.js"></script>
<script src="../../js/sidebar.js"></script>  

</body>
</html>
