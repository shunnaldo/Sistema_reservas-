<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/navbar.css">

</head>
<body>

<div id="navbar-container"></div> 

<div class="form-container ">


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
                <label for="cowork">Cowork:</label>
                <select id="cowork" name="cowork" required class="input-text">
                    <option value="oficina">Oficina</option>
                    <option value="cowork_principal">Cowork Principal</option>
                    <option value="cowork_terraza">Cowork Terraza</option>
                </select>
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

 

</div>
   
    <div id="footer-container"></div>

    <script src="js/navbar.js"></script>
    <script src="js/index.js"></script>
    <script src="js/footer.js"></script>




    </body>
</html>