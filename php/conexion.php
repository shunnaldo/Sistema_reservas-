<?php
// Datos de conexión
$host = 'b6ohe4ckuuho35k2pmtn-mysql.services.clever-cloud.com';  
$usuario = 'utyczcv4wkxhajqg';    
$contraseña = 'B3q1gDjoA72HLWBhwQSY';     
$baseDeDatos = 'b6ohe4ckuuho35k2pmtn';  

// Establecer la conexión
$conexion = new mysqli($host, $usuario, $contraseña, $baseDeDatos);

// Comprobar conexión
if ($conexion->connect_error) {
    // Registrar el error en un archivo de log
    error_log("Conexión fallida: " . $conexion->connect_error, 3, "errores.log");
    die("Error de conexión: " . $conexion->connect_error);
} else {
    // Puedes mantener el mensaje de conexión exitosa para la depuración si es necesario
    // echo "¡Conexión exitosa a la base de datos!";
}

// No cerramos la conexión aquí
?>