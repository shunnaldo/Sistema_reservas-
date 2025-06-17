<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../bd/conexion_test.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

function safe_htmlspecialchars($str)
{
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (!isset($_GET['numero_fip'])) {
    die("Número FIP no especificado.");
}

$numero_fip = $conn->real_escape_string($_GET['numero_fip']);

$sql = "SELECT 
            p.numero_fip,
            p.nombre,
            p.fecha_presentacion,
            p.duracion_valor,
            p.duracion_tipo,
            pr.nombre_completo,
            pr.cargo_rol,
            pr.organizacion,
            pr.correo,
            pr.telefono
        FROM proyecto p
        INNER JOIN proponente pr ON p.id_proponente = pr.id_proponente
        WHERE p.numero_fip = '$numero_fip'
        LIMIT 1";

$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    die("Ficha no encontrada.");
}

$row = $result->fetch_assoc();

$html = '<h2 style="text-align:center; color: #198754;">Ficha N° ' . $row['numero_fip'] . '</h2>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse; font-family: Arial, sans-serif;">';

foreach ($row as $key => $value) {
    $html .= '<tr>
                <th style="background-color:#198754; color: white;">' . safe_htmlspecialchars(ucwords(str_replace('_', ' ', $key))) . '</th>
                <td>' . safe_htmlspecialchars($value) . '</td>
                </tr>';
}

$html .= '</table>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Ficha_{$row['numero_fip']}.pdf", ["Attachment" => true]);

$conn->close();
exit;
