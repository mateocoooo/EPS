<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


session_start();
include("conexion.php");

// Validar sesión y rol
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'recepcionista') {
    header("Location: login.php");
    exit();
}

// Variables auxiliares
$mensaje = "";
$paciente_encontrado = null;

// Buscar paciente
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['buscar'])) {
    $cedula_busqueda = $_POST['cedula_buscar'];
    $sql = "SELECT * FROM pacientes WHERE cedula = '$cedula_busqueda'";
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        $paciente_encontrado = $res->fetch_assoc();
    } else {
        $mensaje = "Paciente no encontrado.";
    }
}

// Agendar cita
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['agendar'])) {
    $id_paciente = $_POST['id_paciente'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $motivo = $_POST['motivo'];
    $copago = $_POST['copago'];

    $sql = "INSERT INTO citas (id_paciente, fecha, hora, motivo, copago, estado)
            VALUES ('$id_paciente', '$fecha', '$hora', '$motivo', '$copago', 'Activa')";
    if ($conn->query($sql) === TRUE) {
        $mensaje = "✅ Cita agendada y activada con éxito.";
    } else {
        $mensaje = "❌ Error al agendar la cita: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recepción de Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">EPS - Recepcionista</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h3 class="text-center text-primary">Recepción de Pacientes</h3>
    <p class="text-center text-muted">Busca al paciente o regístralo, luego agenda y activa su cita.</p>

    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label">Buscar por Cédula:</label>
            <input type="text" name="cedula_buscar" class="form-control" required>
        </div>
        <div class="col-md-6 align-self-end">
            <button type="submit" name="buscar" class="btn btn-primary w-100">Buscar Paciente</button>
        </div>
    </form>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <?php if ($paciente_encontrado): ?>
        <form method="POST" class="card p-4 shadow-sm">
            <input type="hidden" name="id_paciente" value="<?php echo $paciente_encontrado['id']; ?>">
            <h5 class="mb-3">Paciente: <?php echo $paciente_encontrado['nombre']; ?> (<?php echo $paciente_encontrado['cedula']; ?>)</h5>

            <div class="row">
                <div class="col-md-6">
                    <label>Fecha:</label>
                    <input type="date" name="fecha" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Hora:</label>
                    <input type="time" name="hora" class="form-control" required>
                </div>
            </div>

            <div class="mt-3">
                <label>Motivo de la cita:</label>
                <input type="text" name="motivo" class="form-control" required>
            </div>

            <div class="mt-3">
                <label>Valor de Copago ($):</label>
                <input type="number" name="copago" class="form-control" min="0" required>
            </div>

            <button type="submit" name="agendar" class="btn btn-success mt-4">Agendar y Activar Cita</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
