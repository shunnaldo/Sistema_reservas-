<?php
include('php/conexion.php');

// Obtener los días bloqueados
$sql = "SELECT * FROM diasBloqueados ORDER BY fecha ASC";
$result = $conexion->query($sql);
$diasBloqueados = [];
while ($row = $result->fetch_assoc()) {
    $diasBloqueados[] = [
        'fecha' => $row['fecha'],
        'motivo' => $row['motivo']
    ];
}
?>



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
                <label for="cowork">Cowork:</label>
                <select id="cowork" name="cowork" required class="input-text">
                    <option value="oficina">Oficina</option>
                    <option value="cowork_principal">Cowork Principal</option>
                    <option value="cowork_terraza">Cowork Terraza</option>
                </select>
            </div>
            <div class="form-group">
                <label for="telefono">Número de Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" required placeholder="Ej: +56912345678" class="input-text">
            </div>

            <div class="form-group">
                <label for="cantidadPersonas">Cantidad de personas:</label>
                <select id="cantidadPersonas" name="cantidadPersonas" required class="input-text">
            <option value="1">1 </option>
            <option value="2">2 </option>
            <option value="3">3 </option>
            <option value="4">4 </option>
                </select>
            </div>
            <div class="form-group">
                <label for="fecha">Selecciona el día:</label>
                <input type="date" id="fecha" name="fecha" required class="input-text">
            </div>
            <div class="form-group">
            <label for="horaInicio">Hora de inicio:</label>
                <select id="horaInicio" name="horaInicio" required class="input-text">
                    <option value="09:00">09:00</option>
                    <option value="10:00">10:00</option>
                    <option value="11:00">11:00</option>
                    <option value="12:00">12:00</option>
                    <option value="13:00">13:00</option>
                    <option value="14:00">14:00</option>
                    <option value="15:00">15:00</option>
                    <option value="16:00">16:00</option>
                    <option value="17:00">17:00</option>
                </select>

            </div>
            <div class="form-group">
            <label for="horaFin">Hora de fin:</label>
                <select id="horaFin" name="horaFin" required class="input-text">
                    <option value="10:00">10:00</option>
                    <option value="11:00">11:00</option>
                    <option value="12:00">12:00</option>
                    <option value="13:00">13:00</option>
                    <option value="14:00">14:00</option>
                    <option value="15:00">15:00</option>
                    <option value="16:00">16:00</option>
                    <option value="17:00">17:00</option>
                    <option value="18:00">18:00</option>
                </select>
            </div>
            <button type="submit">Reservar</button>
        </form>

        <div id="success-message" style="display: none; color: green;"></div>
        <div id="error-message" style="display: none; color: red;"></div>
    </div>



    <div id="popupModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.5); z-index:1000;">
        <div style="background:white; padding:20px; border-radius:10px; max-width:400px; margin:100px auto; position:relative;">
    <span id="closePopup" style="position:absolute; top:10px; right:15px; cursor:pointer; font-size:18px;">&times;</span>
    <p id="popupMessage"></p>
        </div>
    </div>

 

   
    <div id="footer-container"></div>

    <script src="js/navbar.js"></script>
    <script src="js/index.js"></script>
    <script src="js/footer.js"></script>


    <script>
        // Pasamos los días bloqueados al frontend
        const diasBloqueados = <?php echo json_encode($diasBloqueados); ?>;

        // Función para verificar si la fecha está bloqueada
        function verificarFechaBloqueada(fechaSeleccionada) {
            for (const dia of diasBloqueados) {
                if (dia.fecha === fechaSeleccionada) {
                    return dia.motivo;
                }
            }
            return null;
        }

        // Lógica para el evento de selección de fecha
        document.getElementById('fecha').addEventListener('change', function () {
            const fechaSeleccionada = this.value;
            const motivoBloqueo = verificarFechaBloqueada(fechaSeleccionada);

            if (motivoBloqueo) {
                alert(`Este día está bloqueado por el siguiente motivo: ${motivoBloqueo}`);
                document.getElementById('fecha').value = ''; // Limpiar la fecha seleccionada
            }
        });
    </script>


    </body>
</html>