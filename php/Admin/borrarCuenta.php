<?php
// Iniciar sesión
session_start();

// Verificar si el administrador está logueado y tiene permisos
if (!isset($_SESSION['admin_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: loginAdmin.php?error=No tienes permisos para eliminar cuentas.");
    exit;
}

// Incluir el archivo de conexión
include('../conexion.php');

// Verificar si se pasa un ID
if (isset($_GET['id'])) {
    $id_usuario = $_GET['id'];

    // Eliminar la cuenta
    $sql = "DELETE FROM Administradores WHERE id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Cuenta eliminada con éxito.";
    } else {
        $_SESSION['error'] = "Error al eliminar la cuenta.";
    }
}

header("Location: listarCuentas.php");
exit;
