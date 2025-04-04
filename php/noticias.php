<?php
session_start();
include('conexion.php');

// Obtener noticias de la base de datos
$sqlNoticias = "SELECT c.*, a.nombre AS autor FROM contenido c 
                JOIN Administradores a ON c.usuario_id = a.id_usuario 
                WHERE c.tipo = 'noticia' 
                ORDER BY c.fecha_creacion DESC";

$resultNoticias = $conexion->query($sqlNoticias);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="../css/noticias.css">
</head>
<body>

    <div id="navbar-container"></div> 

    
    <div class="container mt-5">
    <h2 class="text-center mb-4">Noticias</h2>
    
    <div class="row">
        <?php while ($noticia = $resultNoticias->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <!-- Imagen de la noticia -->
                    <img src="Admin/uploads/<?php echo $noticia['imagen']; ?>" class="card-img-top" alt="Imagen Noticia">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($noticia['titulo']); ?></h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($noticia['cuerpo'])); ?></p>
                        <?php if (!empty($noticia['link'])): ?>
                            <a href="<?php echo htmlspecialchars($noticia['link']); ?>" target="_blank" class="btn btn-primary">
                                <i class="fas fa-external-link-alt"></i> Ver más
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

</div>




    <div id="footer-container"></div>




    <script src="../js/navbar.js"></script>
    <script src="../js/footer.js"></script>

</body>
</html>