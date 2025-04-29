<?php
include_once __DIR__ . "/../conexion.php";
// Consultar los talleres
$sql = "SELECT id, nombre FROM talleres WHERE activo = '1'";
$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error al consultar talleres: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/asistencia.css">

</head>
<body>

<div id="navbarproyecto-container"></div>

<div class="container-fluid">
    <div class="container-center">

  <!-- Nuevo contenedor principal -->
  <div class="main-content">
        <div class="form-container">
            <h2>Asistencia</h2>

            <form action="asistencia.php" method="POST">
                <div class="form-group">
                    <label for="rut">RUT:</label>
                    <input type="text" id="rut" name="rut" placeholder="Ingrese rut sin puntos ni guion" required>
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ingrese su nombre" required>
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" placeholder="Ingrese su apellido" required>
                </div>

                <div class="form-group">
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" placeholder="Ingrese su correo" required>
                </div>

                <div class="form-group">
                    <label for="taller">Seleccione un Taller:</label>
                    <select id="taller" name="taller" required>
                        <option value="">-- Selecciona un taller --</option>
                        <?php while($row = $resultado->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Guardar Asistencia</button>
            </form>
        </div>
    </div>

    </div>
</div>

<script src="../../js/asistenciaAdmin.js"></script>

<script src="../../js/sidebarproyecto.js"></script>
<script src="../../js/sidebar.js"></script>  
    
</body>
</html>