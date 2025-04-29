<?php
$host = "15.235.114.116"; 
$usuario = "fomentol_practica";  
$clave = "COFODEP2025**";         
$base_de_datos = "fomentol_tarjetavecino"; 

$conexion = new mysqli($host, $usuario, $clave, $base_de_datos);


$conn = new mysqli($host, $usuario, $clave, $base_de_datos);

$conn->set_charset("utf8");
$conexion->set_charset("utf8");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>