<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$nombre = $_SESSION['nombre'];

// Estadísticas
$total_usuarios = $conn->query("SELECT COUNT(*) AS total FROM usuarios")->fetch_assoc()['total'];
$total_doctores = $conn->query("SELECT COUNT(*) AS total FROM usuarios WHERE rol='doctor'")->fetch_assoc()['total'];
$total_auxiliares = $conn->query("SELECT COUNT(*) AS total FROM usuarios WHERE rol='auxiliar'")->fetch_assoc()['total'];
$total_recepcionistas = $conn->query("SELECT COUNT(*) AS total FROM usuarios WHERE rol='recepcionista'")->fetch_assoc()['total'];
$total_pacientes = $conn->query("SELECT COUNT(*) AS total FROM usuarios WHERE rol='paciente'")->fetch_assoc()['total'];

$total_citas = $conn->query("SELECT COUNT(*) AS total FROM citas")->fetch_assoc()['total'];
$citas_activas = $conn->query("SELECT COUNT(*) AS total FROM citas WHERE estado='Activa'")->fetch_assoc()['total'];
$citas_finalizadas = $conn->query("SELECT COUNT(*) AS total FROM citas WHERE estado='Finalizada'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card:hover {
            transform: scale(1.02);
            transition: 0.3s ease;
        }
        body {
            background: #f5f7fa;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">⚕️ EPS Médica</a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="admin-panel.php">➕ Crear Usuarios</a></li>
                <li class="nav-item"><a class="nav-link" href="ver-citas.php">📅 Ver Citas</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="logout.php">⛔ Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenido -->
<div class="container mt-5">

    <!-- Saludo -->
    <div class="text-center mb-4">
        <h2 class="text-primary fw-semibold">Bienvenido, <?php echo $nombre; ?> 👑</h2>
        <p class="lead text-muted">Desde aquí puedes administrar todo el sistema.</p>
    </div>

    <!-- Accesos rápidos -->
    <div class="row g-4 justify-content-center mb-5">
        <div class="col-md-4">
            <div class="card p-4 shadow-sm border-0 text-center">
                <h5 class="text-primary">👥 Gestionar Usuarios</h5>
                <p class="text-muted">Ver, editar o eliminar usuarios del sistema.</p>
                <a href="admin-usuarios.php" class="btn btn-outline-dark w-100">Entrar</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 shadow-sm border-0 text-center">
                <h5 class="text-success">📋 Ver Citas</h5>
                <p class="text-muted">Consulta todas las citas agendadas.</p>
                <a href="ver-citas.php" class="btn btn-outline-success w-100">Entrar</a>
            </div>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <h4 class="text-secondary mb-3 text-center">📊 Estadísticas Generales</h4>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col">
            <div class="card border-primary shadow text-center">
                <div class="card-body">
                    <h5 class="card-title">👥 Total de Usuarios</h5>
                    <p class="display-6"><?php echo $total_usuarios; ?></p>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-success shadow text-center">
                <div class="card-body">
                    <h5 class="card-title">🧑‍⚕️ Doctores</h5>
                    <p class="display-6"><?php echo $total_doctores; ?></p>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-warning shadow text-center">
                <div class="card-body">
                    <h5 class="card-title">👨‍🔬 Auxiliares</h5>
                    <p class="display-6"><?php echo $total_auxiliares; ?></p>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-info shadow text-center">
                <div class="card-body">
                    <h5 class="card-title">🧑‍💼 Recepcionistas</h5>
                    <p class="display-6"><?php echo $total_recepcionistas; ?></p>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-secondary shadow text-center">
                <div class="card-body">
                    <h5 class="card-title">🧑‍🦽 Pacientes</h5>
                    <p class="display-6"><?php echo $total_pacientes; ?></p>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-dark shadow text-center">
                <div class="card-body">
                    <h5 class="card-title">📅 Citas</h5>
                    <p class="display-6"><?php echo $total_citas; ?></p>
                    <p class="text-success mb-1">Activas: <?php echo $citas_activas; ?></p>
                    <p class="text-secondary">Finalizadas: <?php echo $citas_finalizadas; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
