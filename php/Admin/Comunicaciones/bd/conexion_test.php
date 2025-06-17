<?php
$host = "15.235.114.116";
$user = "fomentol_practica";
$pass = "CASAEMPRENDER2025**";
$dbname = "fomentol_PROCESO_FIP";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
