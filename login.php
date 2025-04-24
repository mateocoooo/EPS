<?php
session_start();
include("conexion.php");

// Mostrar errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND contrasena='$contrasena'";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $_SESSION['usuario'] = $fila['usuario'];
        $_SESSION['rol'] = $fila['rol'];
        $_SESSION['nombre'] = $fila['nombre'];

        if ($fila['rol'] === 'admin') {
            header("Location: index-admin.php");
            exit();
        } elseif ($fila['rol'] === 'doctor') {
            header("Location: index-doctor.php");
            exit();
        } elseif ($fila['rol'] === 'paciente') {
            header("Location: index-paciente.php");
            exit();
        } elseif ($fila['rol'] === 'auxiliar') {
            header("Location: index-auxiliar.php");
            exit();
        } elseif ($fila['rol'] === 'recepcionista') {
            header("Location: index-recepcionista.php");
            exit();
        } else {
            $mensaje = "Rol no válido.";
        }
    } else {
        $mensaje = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center text-primary">Iniciar Sesión</h2>
    <p class="text-center text-muted">Accede según tu rol en el sistema</p>

    <?php if ($mensaje): ?>
        <div class="alert alert-danger text-center"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm col-md-6 mx-auto">
        <div class="mb-3">
            <label>Usuario:</label>
            <input type="text" name="usuario" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contraseña:</label>
            <input type="password" name="contrasena" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
    </form>
</div>
</body>
</html>
