<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario Registro</title>
    <link rel="stylesheet" href="../../css/nuevoForm.css">
</head>
<body>

<div id="navbarStaff-container"></div>

<div class="body">
    <div class="form-container">
        <h2>Registro de Usuario</h2>
        <form id="registroForm" action="../guardarDatosForm.php" method="POST">
            <label for="rut">RUT:</label>
            <input type="text" name="rut" id="rut" required>

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" required>

            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" id="apellido" required>

            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required>

            <label for="petid">PETID:</label>
            <input type="text" name="pet_id" id="petid" required>

            <label for="celular">Celular:</label>
            <input type="text" name="telefono" id="celular" required>

            <label for="correo">Correo Electrónico:</label>
            <input type="email" name="correo" id="correo" required>

            <label for="direccion">Dirección Domicilio:</label>
            <input type="text" name="direccion" id="direccion" required>

            <label class="label" for="genero">Género:</label>
            <select name="genero" id="genero" required>
                <option value="">Seleccione una opción</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
            </select>

            <label for="actividad">Actividad:</label>
            <input type="text" name="actividad" id="actividad" required>
            <input type="hidden" name="ubicacion" id="ubicacion">

            <button class="button" type="submit">Registrar</button>
        </form>
    </div>

</div>


    
    <script src="../../js/sidebarStaff.js"></script>
    <script src="../../js/sidebar.js"></script>
    <script src="../../js/nuevoForm.js"></script>
</body>
</html>