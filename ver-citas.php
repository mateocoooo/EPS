<?php
session_start();

// Verificar si hay sesión activa y si el rol es válido para ver las citas
if (!isset($_SESSION['usuario']) || ($_SESSION['rol'] !== 'doctor' && $_SESSION['rol'] !== 'admin')) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$sql = "SELECT 
            c.fecha, 
            c.hora, 
            c.motivo,
            p.nombre, 
            p.cedula, 
            p.correo, 
            p.telefono
        FROM citas c
        INNER JOIN pacientes p ON c.id_paciente = p.id";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Citas Agendadas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos/estilos.css">
</head>
<body class="bg-light">

<!-- Navbar dinámica según el rol -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.html">EPS Médica</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <?php if ($_SESSION['rol'] === 'doctor' || $_SESSION['rol'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link active" href="ver-citas.php">Ver Citas</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenido -->
<div class="container mt-5">
    <h2 class="text-center text-success mb-4">Citas Agendadas</h2>

    <table class="table table-striped table-bordered shadow">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $fila["nombre"] . "</td>";
                echo "<td>" . $fila["cedula"] . "</td>";
                echo "<td>" . $fila["correo"] . "</td>";
                echo "<td>" . $fila["telefono"] . "</td>";
                echo "<td>" . $fila["fecha"] . "</td>";
                echo "<td>" . $fila["hora"] . "</td>";
                echo "<td>" . $fila["motivo"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' class='text-center'>No hay citas registradas.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

</body>
</html>
