<?php
// Incluir la conexión a la base de datos
include_once __DIR__ . "/../conexion.php";

// Verificar que la conexión existe
if (!isset($conexion)) {
    die("Error: No se pudo establecer conexión con la base de datos.");
}

// Iniciar la sesión
session_start();

// Verificar si el administrador está logueado
if (!isset($_SESSION['admin_id'])) {
    header("Location: loginAdmin.php");
    exit;
}

// Consulta para obtener el total de reservas
$sql = "SELECT COUNT(*) AS total_reservas FROM Reservas";
$result = $conexion->query($sql);

$total_reservas = 0; // Valor por defecto

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_reservas = $row['total_reservas'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/dashBoardAdmin.css">
</head>
<body>
    <div id="navbarAdmin-container"></div>

    <div class="container-fluid">
        <div class="container-center">

        <div class="container mt-4">
        <h2 class="mb-3">Dashboard</h2>
        
        <div class="stats-container">
            <div class="stat-box">
                <i class="fas fa-calendar-check"></i>
                <p><?php echo $total_reservas; ?></p>
                <small>Total de Reservas</small>
            </div>
            <!-- Aquí puedes agregar más métricas en el futuro -->

        </div>
    </div>



        </div>
    </div>
    <script src="../../js/navbarAdmin.js"></script>
    <script src="../../js/sidebar.js"></script>  
</body>
</html>
