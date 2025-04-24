<?php
session_start();
include("conexion.php");

$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

// Verificar el usuario en la base de datos
$sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND contrasena='$contrasena'";
$resultado = $conn->query($sql);

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();

    // Guardar en sesión
    $_SESSION['usuario'] = $fila['usuario'];
    $_SESSION['rol'] = $fila['rol'];
    $_SESSION['nombre'] = $fila['nombre'];

    // Redirección según el rol
    if ($fila['rol'] === 'admin') {
        header("Location: index-admin.php");
        exit();
    } elseif ($fila['rol'] === 'doctor') {
        header("Location: ver-citas.php");
        exit();
    } else {
        header("Location: index-paciente.php");
        exit();
    }
} else {
    echo "❌ Usuario o contraseña incorrectos.";
}

$conn->close();
?>
