<?php
session_start(); // Inicia la sesión

// Eliminar todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir al usuario a la página de login
header("Location: /CasaEmprender/Sistema_reservas-/php/Admin/public/login.php?message=sesion_terminada");
exit(); // Asegura que no se ejecute nada más después de la redirección
?>
