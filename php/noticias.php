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
    <!-- Carrusel de Noticias -->
    <div id="newsCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../img/logo.png" class="d-block w-100" alt="Noticia 1">
                <div class="carousel-caption d-none d-md-block">

                </div>
            </div>
            <div class="carousel-item">
                <img src="../img/Captura de pantalla 2025-03-27 163858.png" class="d-block w-100" alt="Noticia 2">
                <div class="carousel-caption d-none d-md-block">

                </div>
            </div>
            <div class="carousel-item">
                <img src="../img/Captura de pantalla 2025-03-27 163858.png" class="d-block w-100" alt="Noticia 3">
                <div class="carousel-caption d-none d-md-block">

                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#newsCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#newsCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

<br><br><br>
       <!-- Sección de Noticias -->
       <div class="container mt-5">
    <h2 class="mb-4 text-center">Cursos y noticias de la Comunidad</h2>
    <div class="row">
        <!-- Curso 1 -->
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="../img/Tarjetas-1-300x169.png" class="card-img-top" alt="Curso 1">
                <div class="card-body">
                    <h5 class="card-title">Tarjeta vecino</h5>
                    <p class="card-text">La Tarjeta Vive La Florida se puede obtener en la Corporación de Fomento de La Florida. Los vecinos pueden inscribirse a través de su página web o en las oficinas correspondientes para recibir descuentos en servicios, combustibles, salud, y más.</p>
                    <a href="https://www.instagram.com/fomentolaflorida/" class="btn btn-primary">Mas informacion</a>
                </div>
            </div>
        </div>
        <!-- Curso 2 -->
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="../img/Captura de pantalla 2025-03-27 163858.png" class="card-img-top" alt="Curso 2">
                <div class="card-body">
                    <h5 class="card-title">Curso de Diseño Gráfico</h5>
                    <p class="card-text">Domina herramientas como Photoshop y Illustrator para crear diseños profesionales.</p>
                    <a href="#" class="btn btn-primary">Mas informacion</a>
                </div>
            </div>
        </div>
        <!-- Noticia tarjeta - tarjeta vecina -->
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="../img/Captura de pantalla 2025-03-27 163858.png" class="card-img-top" alt="Curso 2">
                <div class="card-body">
                    <h5 class="card-title">Curso de Diseño Gráfico</h5>
                    <p class="card-text">Domina herramientas como Photoshop y Illustrator para crear diseños profesionales.</p>
                    <a href="#" class="btn btn-primary">Mas informacion</a>
                </div>
            </div>
        </div>




    </div>
</div>
<div id="footer-container"></div>




    <script src="../js/navbar.js"></script>
    <script src="../js/footer.js"></script>

</body>
</html>