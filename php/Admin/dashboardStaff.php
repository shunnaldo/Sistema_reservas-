<?php
session_start();


// Verificar si el administrador está logueado
if (!isset($_SESSION['admin_id'])) {
    header("Location: loginAdmin.php");
    exit;
}
// Incluir la conexión a la base de datos
include_once __DIR__ . "/../conexion.php";

// Verificar que la conexión existe
if (!isset($conexion)) {
    die("Error: No se pudo establecer conexión con la base de datos.");
}


// Configurar MySQL para devolver los meses en español
$conexion->query("SET lc_time_names = 'es_ES'");

// Consultas para obtener los datos de reservas
$sql_reservas = "SELECT COUNT(*) AS total_reservas FROM Reservas";
$sql_reservas_mes = "SELECT DATE_FORMAT(fecha, '%M') AS mes, COUNT(*) AS cantidad FROM Reservas GROUP BY mes ORDER BY STR_TO_DATE(mes, '%M')";
$sql_coworks = "SELECT cowork, COUNT(*) AS cantidad_reservas FROM Reservas GROUP BY cowork ORDER BY cantidad_reservas DESC";
$sql_vecinos = "SELECT rut, nombre_vecino, apellido_vecino, COUNT(*) AS total_reservas FROM Reservas GROUP BY rut, nombre_vecino, apellido_vecino ORDER BY total_reservas DESC LIMIT 10";
$sql_horas_reservadas = "SELECT cowork, SUM(TIMESTAMPDIFF(MINUTE, hora_inicio, hora_fin)) AS minutos_reservados FROM Reservas GROUP BY cowork";

// Ejecutar las consultas
$total_reservas = $conexion->query($sql_reservas)->fetch_assoc()['total_reservas'] ?? 0;
$reservas_por_mes = [];
$labels = [];
$colores = [];
$colores_base = ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40", "#C9CBCF"];
$index = 0;

$result_reservas_mes = $conexion->query($sql_reservas_mes);
while ($row = $result_reservas_mes->fetch_assoc()) {
    $labels[] = $row['mes'];
    $reservas_por_mes[] = $row['cantidad'];
    $colores[] = $colores_base[$index++ % count($colores_base)];
}

$coworks = $conexion->query($sql_coworks)->fetch_all(MYSQLI_ASSOC);
$vecinos = $conexion->query($sql_vecinos)->fetch_all(MYSQLI_ASSOC);
$horas_reservadas = $conexion->query($sql_horas_reservadas)->fetch_all(MYSQLI_ASSOC);

// Definir horas disponibles (ejemplo de 8 horas)
$horas_disponibles = 8 * 60; // 8 horas * 60 minutos

// Consulta para calcular el índice de asistencia
$sql_asistencias = "SELECT 
    SUM(CASE WHEN check_asistencia = 1 THEN 1 ELSE 0 END) AS asistencias,
    COUNT(*) AS total
FROM Reservas";

$result_asistencias = $conexion->query($sql_asistencias)->fetch_assoc();
$asistencias = $result_asistencias['asistencias'];
$no_asistencias = $result_asistencias['total'] - $asistencias;


// Calculo de reservas por mes
$sql_reservas_mes_comparativo = "
    SELECT 
        DATE_FORMAT(fecha, '%M') AS mes, 
        COUNT(*) AS cantidad_reservas 
    FROM Reservas 
    GROUP BY mes 
    ORDER BY STR_TO_DATE(mes, '%M')
";

$reservas_por_mes_comparativo = [];
$labels_comparativo = [];
$crecimiento_por_mes = [];

$result_reservas_mes_comparativo = $conexion->query($sql_reservas_mes_comparativo);
$total_acumulado = 0; // Variable para acumular las reservas mes a mes

while ($row = $result_reservas_mes_comparativo->fetch_assoc()) {
    $mes = $row['mes'];
    $cantidad = $row['cantidad_reservas'];
    $labels_comparativo[] = $mes;
    $reservas_por_mes_comparativo[] = $cantidad;

    // Sumar las reservas al total acumulado
    $total_acumulado += $cantidad;

    // Guardar el valor acumulado para cada mes
    $crecimiento_por_mes[] = $total_acumulado;
}

// Calculo de reservas por día en cada mes
$sql_reservas_dia_comparativo = "
    SELECT 
        DATE_FORMAT(fecha, '%d-%m') AS dia,  -- Esto nos da el día y mes en formato 'Día-Mes'
        COUNT(*) AS cantidad_reservas 
    FROM Reservas 
    GROUP BY dia 
    ORDER BY fecha  -- Ahora ordenamos por la fecha completa
";


$reservas_por_dia_comparativo = [];
$labels_dia_comparativo = [];

$result_reservas_dia_comparativo = $conexion->query($sql_reservas_dia_comparativo);
while ($row = $result_reservas_dia_comparativo->fetch_assoc()) {
    $dia = $row['dia'];
    $cantidad = $row['cantidad_reservas'];
    $labels_dia_comparativo[] = $dia;
    $reservas_por_dia_comparativo[] = $cantidad;
}

$sql_reservas_por_hora = "
    SELECT 
        HOUR(hora_inicio) AS hora, 
        COUNT(*) AS cantidad_reservas
    FROM Reservas 
    GROUP BY hora
    ORDER BY hora;
";
$reservas_por_hora = [];
$labels_hora = [];
$result_reservas_por_hora = $conexion->query($sql_reservas_por_hora);

while ($row = $result_reservas_por_hora->fetch_assoc()) {
    $labels_hora[] = $row['hora'] . ':00';  // Etiquetas para las horas
    $reservas_por_hora[] = $row['cantidad_reservas'];  // Cantidad de reservas por hora
}


// Consulta para obtener los datos de coworks por mes
$sql_coworks_por_mes = "SELECT 
    DATE_FORMAT(fecha, '%M') AS mes,
    cowork,
    COUNT(*) AS cantidad_reservas
FROM Reservas
GROUP BY mes, cowork
ORDER BY STR_TO_DATE(mes, '%M'), cantidad_reservas DESC";

$result_coworks_por_mes = $conexion->query($sql_coworks_por_mes);

// Organizar los datos para el gráfico
$coworks_por_mes = [];
while ($row = $result_coworks_por_mes->fetch_assoc()) {
    $mes = $row['mes'];
    if (!isset($coworks_por_mes[$mes])) {
        $coworks_por_mes[$mes] = [];
    }
    $coworks_por_mes[$mes][] = [
        'cowork' => $row['cowork'],
        'cantidad' => $row['cantidad_reservas']
    ];
}

// Consulta para obtener asistencia por mes y cowork
$sql_asistencia_mes_cowork = "SELECT 
    DATE_FORMAT(fecha, '%M') AS mes,
    cowork,
    SUM(CASE WHEN check_asistencia = 1 THEN 1 ELSE 0 END) AS asistencias,
    SUM(CASE WHEN check_asistencia = 0 OR check_asistencia IS NULL THEN 1 ELSE 0 END) AS no_asistencias,
    COUNT(*) AS total
FROM Reservas
GROUP BY mes, cowork
ORDER BY STR_TO_DATE(mes, '%M'), cowork";

$result_asistencia_mes_cowork = $conexion->query($sql_asistencia_mes_cowork);

// Organizar los datos para el gráfico
$asistencia_por_mes_cowork = [];
while ($row = $result_asistencia_mes_cowork->fetch_assoc()) {
    $mes = $row['mes'];
    if (!isset($asistencia_por_mes_cowork[$mes])) {
        $asistencia_por_mes_cowork[$mes] = [];
    }

    $porcentaje_asistencia = ($row['total'] > 0) ? round(($row['asistencias'] / $row['total']) * 100, 2) : 0;

    $asistencia_por_mes_cowork[$mes][] = [
        'cowork' => $row['cowork'],
        'asistencias' => $row['asistencias'],
        'no_asistencias' => $row['no_asistencias'],
        'total' => $row['total'],
        'porcentaje_asistencia' => $porcentaje_asistencia
    ];
}

// Consulta para obtener totales por mes
$sql_totales_mes = "SELECT 
    DATE_FORMAT(fecha, '%M') AS mes,
    SUM(CASE WHEN check_asistencia = 1 THEN 1 ELSE 0 END) AS total_asistencias,
    SUM(CASE WHEN check_asistencia = 0 OR check_asistencia IS NULL THEN 1 ELSE 0 END) AS total_no_asistencias,
    COUNT(*) AS total_reservas
FROM Reservas
GROUP BY mes
ORDER BY STR_TO_DATE(mes, '%M')";

$totales_por_mes = $conexion->query($sql_totales_mes)->fetch_all(MYSQLI_ASSOC);
$totales_mes_data = [];
foreach ($totales_por_mes as $row) {
    $totales_mes_data[$row['mes']] = [
        'total_asistencias' => $row['total_asistencias'],
        'total_no_asistencias' => $row['total_no_asistencias'],
        'total_reservas' => $row['total_reservas'],
        'porcentaje_asistencia' => ($row['total_reservas'] > 0) ?
            round(($row['total_asistencias'] / $row['total_reservas']) * 100, 2) : 0
    ];
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Agregar en tu archivo CSS */
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
            padding: 0.75rem 1.25rem;
        }

        .table-responsive {
            margin-top: 1rem;
        }

        .table th {
            white-space: nowrap;
        }

        .table-bordered th,
        .table-bordered td {
            text-align: center;
        }

        .table-dark {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>

<body>
    <div id="navbarStaff-container"></div>

    <div class="container-fluid">
        <div class="container-center">

            <h2 class="mb-4 mt-5">Dashboard</h2>


            <!-- Estadísticas -->
            <div class="stats-container">
                <div class="stat-box">
                    <i class="fas fa-calendar-check"></i>
                    <p><?php echo $total_reservas; ?></p>
                    <small>Total de Reservas</small>
                </div>

                <div class="stat-box">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-building me-3"></i>
                        <div>
                            <p id="coworkNombre" class="fw-bold"><?php echo $coworks[0]['cowork']; ?></p>
                            <small id="coworkReservas"><?php echo $coworks[0]['cantidad_reservas']; ?> reservas</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="coworkSelect" class="form-label">Selecciona un Cowork:</label>
                        <select id="coworkSelect" class="form-select">
                            <?php foreach ($coworks as $cowork): ?>
                                <option value="<?php echo $cowork['cowork']; ?>"><?php echo $cowork['cowork']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container mt-4">
                        <h4>Reservas por Mes</h4>
                        <canvas id="reservasChart"></canvas>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="chart-container mt-4 text-center">
                        <h4 class="mb-3">Índice de Asistencia</h4>
                        <div style="max-width: 400px; margin: 0 auto;">
                            <canvas id="asistenciaChart"></canvas>
                        </div>
                    </div>
                </div>



            </div>


            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container mt-4">
                        <h4>Crecimiento Diario (Dentro de Cada Mes)</h4>
                        <canvas id="crecimientoDiaChart"></canvas>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="chart-container mt-4">
                        <h4>Reservas por Hora</h4>
                        <canvas id="reservasPorHoraChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="chart-container mt-4">
                        <h4>Uso de Coworks por Mes</h4>
                        <div class="mb-3">
                            <label for="mesSelect" class="form-label">Selecciona un Mes:</label>
                            <select id="mesSelect" class="form-select">
                                <?php foreach (array_keys($coworks_por_mes) as $mes): ?>
                                    <option value="<?php echo $mes; ?>"><?php echo ucfirst($mes); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <canvas id="coworksPorMesChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="chart-container mt-4">
                        <h4>Asistencia por Mes</h4>
                        <div class="mb-3">
                            <label for="mesAsistenciaSelect" class="form-label">Selecciona un Mes:</label>
                            <select id="mesAsistenciaSelect" class="form-select">
                                <?php foreach (array_keys($asistencia_por_mes_cowork) as $mes): ?>
                                    <option value="<?php echo $mes; ?>"><?php echo ucfirst($mes); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div id="asistenciaCoworkContainer">
                            <!-- Aquí se cargarán dinámicamente las tablas por cowork -->
                        </div>

                        <div class="mt-4">
                            <h5>Total del Mes</h5>
                            <div id="totalMesContainer" class="table-responsive">
                                <!-- Aquí se cargarán los totales del mes -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabla de Vecinos -->
            <div class="table-container mt-4">
                <h4>Ranking de Vecinos con más Reservas</h4>
                <table class="table table-striped text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>RUT Vecino</th>
                            <th>Nombre Completo</th>
                            <th>Total de Reservas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $posicion = 1;
                        foreach ($vecinos as $vecino): ?>
                            <tr>
                                <td><?php echo $posicion++; ?></td>
                                <td><?php echo $vecino['rut']; ?></td>
                                <td><?php echo $vecino['nombre_vecino'] . " " . $vecino['apellido_vecino']; ?></td>
                                <td><?php echo $vecino['total_reservas']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script src="../../js/sidebarStaff.js"></script>
    <script src="../../js/sidebar.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Gráfico de Reservas por Mes
            const ctx = document.getElementById('reservasChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode($labels); ?>,
                    datasets: [{
                        label: 'Reservas',
                        data: <?php echo json_encode($reservas_por_mes); ?>,
                        backgroundColor: <?php echo json_encode($colores); ?>,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true
                }
            });

            // Cambiar la información del cowork seleccionado
            const coworksData = <?php echo json_encode($coworks); ?>;
            const selectElement = document.getElementById("coworkSelect");
            const coworkNombre = document.getElementById("coworkNombre");
            const coworkReservas = document.getElementById("coworkReservas");

            selectElement.addEventListener("change", function() {
                const selectedCowork = this.value;
                const coworkInfo = coworksData.find(c => c.cowork === selectedCowork);

                if (coworkInfo) {
                    coworkNombre.textContent = coworkInfo.cowork;
                    coworkReservas.textContent = coworkInfo.cantidad_reservas + " reservas";
                }
            });

            // Gráfico de Horas Reservadas vs Disponibles
            const ctx_horas = document.getElementById('horasChart').getContext('2d');
            const labelsCoworks = <?php echo json_encode(array_column($coworks, 'cowork')); ?>;
            const horasReservadas = <?php echo json_encode(array_column($horas_reservadas, 'minutos_reservados')); ?>;
            const horasDisponibles = new Array(horasReservadas.length).fill(8 * 60); // 8 horas * 60 minutos

            new Chart(ctx_horas, {
                type: 'bar',
                data: {
                    labels: labelsCoworks,
                    datasets: [{
                        label: 'Horas Reservadas',
                        data: horasReservadas,
                        backgroundColor: '#FF6384',
                        borderColor: '#FF6384',
                        borderWidth: 1
                    }, {
                        label: 'Horas Disponibles',
                        data: horasDisponibles,
                        backgroundColor: '#36A2EB',
                        borderColor: '#36A2EB',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 8 * 60
                        }
                    }
                }
            });
        });
    </script>
    <script>
        // Gráfico de Índice de Asistencia
        const ctxAsistencia = document.getElementById('asistenciaChart').getContext('2d');
        new Chart(ctxAsistencia, {
            type: 'doughnut',
            data: {
                labels: ['Asistieron', 'No Asistieron'],
                datasets: [{
                    data: [<?php echo $asistencias; ?>, <?php echo $no_asistencias; ?>],
                    backgroundColor: ['#4BC0C0', '#FF6384'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.raw;
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${context.label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    </script>
    <script>
        // Gráfico de Crecimiento Acumulado Mes a Mes
        const ctxCrecimiento = document.getElementById('crecimientoChart').getContext('2d');
        new Chart(ctxCrecimiento, {
            type: 'line', // Tipo de gráfico de línea
            data: {
                labels: <?php echo json_encode($labels_comparativo); ?>, // Los meses
                datasets: [{
                    label: 'Crecimiento Acumulado de Reservas',
                    data: <?php echo json_encode($crecimiento_por_mes); ?>, // Crecimiento acumulado
                    borderColor: '#FF9F40', // Color de la línea
                    backgroundColor: 'rgba(255, 159, 64, 0.2)', // Fondo de la línea
                    borderWidth: 2,
                    fill: false, // No rellenar debajo de la línea
                    tension: 0.4 // Suaviza la línea
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true, // Asegura que el eje Y empiece desde 0
                        ticks: {
                            callback: function(value) {
                                return value; // No formatear los ticks de y-axis
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Meses'
                        }
                    }
                }
            }
        });
    </script>
    <script>
        // Gráfico de Crecimiento por Día dentro de cada Mes (gráfico lineal)
        const ctxDia = document.getElementById('crecimientoDiaChart').getContext('2d');
        new Chart(ctxDia, {
            type: 'line', // Cambiar a gráfico lineal
            data: {
                labels: <?php echo json_encode($labels_dia_comparativo); ?>, // Los días del mes
                datasets: [{
                    label: 'Reservas por Día',
                    data: <?php echo json_encode($reservas_por_dia_comparativo); ?>, // Reservas por día
                    borderColor: '#FF6384', // Color de la línea
                    backgroundColor: 'rgba(255, 99, 132, 0.2)', // Fondo de la línea (ligeramente transparente)
                    borderWidth: 2,
                    fill: true, // Rellenar debajo de la línea
                    tension: 0.4 // Suaviza la línea
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true, // Asegura que el eje Y empiece desde 0
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Días del Mes'
                        },
                        ticks: {
                            autoSkip: true, // Para evitar que las etiquetas se superpongan
                            maxRotation: 45, // Ángulo de rotación de las etiquetas
                            minRotation: 45
                        }
                    }
                }
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Gráfico de Reservas por Hora
            const ctxHora = document.getElementById('reservasPorHoraChart').getContext('2d');
            new Chart(ctxHora, {
                type: 'bar', // Tipo de gráfico de barras
                data: {
                    labels: <?php echo json_encode($labels_hora); ?>, // Las horas
                    datasets: [{
                        label: 'Reservas por Hora',
                        data: <?php echo json_encode($reservas_por_hora); ?>, // Cantidad de reservas por hora
                        backgroundColor: '#FF6384', // Color de las barras
                        borderColor: '#FF6384',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true, // Asegura que el eje Y empiece desde 0
                        }
                    }
                }
            });
        });
    </script>

    <script>
        // Gráfico de Coworks por Mes - Versión corregida
        document.addEventListener("DOMContentLoaded", function() {
            const ctxCoworksMes = document.getElementById('coworksPorMesChart');
            const coworksPorMesData = <?php echo json_encode($coworks_por_mes); ?>;

            // Verificar si hay datos
            if (Object.keys(coworksPorMesData).length === 0) {
                ctxCoworksMes.innerHTML = '<p class="text-center text-muted">No hay datos disponibles</p>';
                document.getElementById('mesSelect').style.display = 'none';
                return;
            }

            // Función para crear el gráfico
            function createCoworksChart(selectedMes) {
                const dataForMes = coworksPorMesData[selectedMes] || [];
                const labels = dataForMes.map(item => item.cowork);
                const data = dataForMes.map(item => item.cantidad);

                // Colores dinámicos
                const backgroundColors = dataForMes.map((_, index) => {
                    const hue = (index * 360 / Math.max(1, dataForMes.length)) % 360;
                    return `hsl(${hue}, 70%, 50%)`;
                });

                return new Chart(ctxCoworksMes, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Reservas',
                            data: data,
                            backgroundColor: backgroundColors,
                            borderColor: backgroundColors.map(color => color.replace('50%)', '30%)')),
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Cantidad de Reservas'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Coworks'
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.raw} reservas`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Inicializar gráfico
            let coworksChart = null;
            const initialMes = Object.keys(coworksPorMesData)[0];
            if (initialMes) {
                coworksChart = createCoworksChart(initialMes);
            }

            // Manejar cambio de mes
            document.getElementById('mesSelect').addEventListener('change', function() {
                if (coworksChart) {
                    coworksChart.destroy();
                }
                coworksChart = createCoworksChart(this.value);
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const asistenciaData = <?php echo json_encode($asistencia_por_mes_cowork); ?>;
            const totalesData = <?php echo json_encode($totales_mes_data); ?>;
            const mesSelect = document.getElementById('mesAsistenciaSelect');
            const coworkContainer = document.getElementById('asistenciaCoworkContainer');
            const totalContainer = document.getElementById('totalMesContainer');

            function updateAsistenciaData(selectedMes) {
                coworkContainer.innerHTML = '';
                totalContainer.innerHTML = '';

                const mesData = asistenciaData[selectedMes] || [];
                const totalMes = totalesData[selectedMes] || {
                    total_asistencias: 0,
                    total_no_asistencias: 0,
                    total_reservas: 0,
                    porcentaje_asistencia: 0
                };

                mesData.forEach(cowork => {
                    const asistencias = Math.round(cowork.asistencias);
                    const no_asistencias = Math.round(cowork.no_asistencias);
                    const total = Math.round(cowork.total);
                    const porcentaje = Math.round(cowork.porcentaje_asistencia);
                    const porcentaje_no = 100 - porcentaje;

                    const coworkCard = document.createElement('div');
                    coworkCard.className = 'card mb-3';
                    coworkCard.innerHTML = `
                    <div class="card-header">
                        <h5 class="mb-0">${cowork.cowork}</h5>
                    </div>
                    <div class="card-body d-flex flex-wrap align-items-center">
                        <div class="w-50 p-2">
                            <canvas id="chart-${cowork.cowork.replace(/\s+/g, '-')}" height="150"></canvas>
                        </div>
                        <div class="w-50 p-2">
                            <table class="table table-sm">
                                <tr><th>Asistencias:</th><td>${asistencias} (${porcentaje}%)</td></tr>
                                <tr><th>No asistencias:</th><td>${no_asistencias} (${porcentaje_no}%)</td></tr>
                                <tr><th>Total reservas:</th><td>${total}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
                    coworkContainer.appendChild(coworkCard);

                    const ctx = document.getElementById(`chart-${cowork.cowork.replace(/\s+/g, '-')}`).getContext('2d');
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Asistieron', 'No Asistieron'],
                            datasets: [{
                                data: [asistencias, no_asistencias],
                                backgroundColor: ['#4BC0C0', '#FF6384'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = Math.round((context.raw / total) * 100);
                                            return `${context.label}: ${context.raw} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                });

                // Mostrar totales del mes (redondeados también)
                totalContainer.innerHTML = `
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Total Asistencias</th>
                            <th>Total No Asistencias</th>
                            <th>Total Reservas</th>
                            <th>Porcentaje Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>${Math.round(totalMes.total_asistencias)}</td>
                            <td>${Math.round(totalMes.total_no_asistencias)}</td>
                            <td>${Math.round(totalMes.total_reservas)}</td>
                            <td>${Math.round(totalMes.porcentaje_asistencia)}%</td>
                        </tr>
                    </tbody>
                </table>
            `;
            }

            if (mesSelect.options.length > 0) {
                updateAsistenciaData(mesSelect.value);
            }

            mesSelect.addEventListener('change', function() {
                updateAsistenciaData(this.value);
            });
        });
    </script>

</body>

</html>