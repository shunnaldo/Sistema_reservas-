<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>
<body>

    <div class="logo-container">
        <img src="img/logo.png" alt="Logo" class="logo">
    </div>

    <div class="form-container">
        <h2 id="reserva">Reservar Hora</h2>
        <form id="reservation-form" method="POST">
            <div class="form-group">
                <label for="rut">RUT:</label>
                <input type="text" id="rut" name="rut" required placeholder="RUT (sin puntos ni guion)" class="input-rut">
            </div>
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required class="input-text">
            </div>
            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" required class="input-text">
            </div>
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" required class="input-text">
            </div>
            <div class="form-group">
                <label for="fecha">Selecciona el día:</label>
                <input type="date" id="fecha" name="fecha" required class="input-text">
            </div>
            <div class="form-group">
                <label for="horaInicio">Hora de inicio:</label>
                <input type="time" id="horaInicio" name="horaInicio" required class="input-text">
            </div>
            <div class="form-group">
                <label for="horaFin">Hora de fin:</label>
                <input type="time" id="horaFin" name="horaFin" required class="input-text">
            </div>
            <button type="submit">Reservar</button>
        </form>

        <div id="success-message" style="display: none; color: green;"></div>
        <div id="error-message" style="display: none; color: red;"></div>
    </div>

    <div class="footer">
    <div class="footer-left">
        <img src="img/logo2.png" alt="Logo" class="footer-logo">
        <div class="social-buttons">
            <a href="https://www.instagram.com/fomentolaflorida/" target="_blank" class="social-btn instagram-btn">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.facebook.com/FomentolaFlorida" target="_blank" class="social-btn facebook-btn">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://www.fomentolf.cl/index.php" target="_blank" class="social-btn website-btn">
                <i class="fas fa-globe"></i>
            </a>
        </div>
    </div>
    <div class="footer-center">
    <h3 id="contact">COFODEP</h3>
        <a href="https://www.fomentolf.cl/quienes-somos.php">Quiénes somos</a>
        <a href="https://www.fomentolf.cl/convenios.php">Convenios</a>
        <a href="https://www.fomentolf.cl/proyectos.php">Proyectos</a>
        <a href="https://www.fomentolf.cl/innovacion.php">Innovación</a>
    </div>
    <div class="footer-right">
        <a href="https://www.fomentolf.cl/contactanos.php" style="text-decoration: none; color: white;">
            <h3 id="contact">Contáctanos</h3>
        </a>
        <p>Enrique Olivares 1003</p>
        <p>La Florida, Chile</p>
        <p>Estadio Bicentenario</p>
    </div>
</div>

    <script src="/js/index.js"></script>

</body>
</html>