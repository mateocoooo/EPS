<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.html">EPS Médica</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="admin-panel.php">Admin Panel</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center text-primary mb-4">Crear Nuevo Usuario</h2>
    <form action="guardar-usuario.php" method="POST" class="bg-white p-4 shadow rounded" style="max-width: 600px; margin: auto;">
        <div class="mb-3">
            <label class="form-label">Nombre completo:</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Correo:</label>
            <input type="email" name="correo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Usuario:</label>
            <input type="text" name="usuario" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña:</label>
            <input type="password" name="contrasena" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Rol:</label>
            <select name="rol" class="form-select" required>
                <option value="paciente">Paciente</option>
                <option value="doctor">Doctor</option>
                <option value="auxiliar">Auxiliar</option>
                <option value="recepcionista">Recepcionista</option>
                <option value="admin">Administrador</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success w-100">Crear Usuario</button>
    </form>
</div>

</body>
</html>
