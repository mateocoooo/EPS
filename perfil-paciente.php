<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'paciente') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$nombre = $_SESSION['nombre'];

// Buscar la cédula del paciente
$sql_paciente = "SELECT cedula FROM pacientes WHERE nombre = '$nombre' LIMIT 1";
$resultado_paciente = $conn->query($sql_paciente);

if ($resultado_paciente->num_rows > 0) {
    $cedula = $resultado_paciente->fetch_assoc()['cedula'];

    // Obtener citas por cédula
    $sql = "SELECT c.fecha, c.hora, c.motivo
            FROM citas c
            INNER JOIN pacientes p ON c.id_paciente = p.id
            WHERE p.cedula = '$cedula'";
    $resultado = $conn->query($sql);
} else {
    $resultado = null;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand" href="#">EPS Médica</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="perfil-paciente.php">Mi Perfil</a></li>
                <li class="nav-item"><a class="nav-link" href="formulario-cita.html">Agendar Cita</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-primary text-center mb-4">Hola, <?php echo $nombre; ?> 👋</h2>
    <p class="text-center">Este es tu historial de citas médicas.</p>

    <table class="table table-striped table-bordered mt-4 shadow">
        <thead class="table-dark">
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($resultado && $resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $fila['fecha'] . "</td>";
                    echo "<td>" . $fila['hora'] . "</td>";
                    echo "<td>" . $fila['motivo'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3' class='text-center'>No tienes citas agendadas aún.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
