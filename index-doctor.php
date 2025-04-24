<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

$mensaje = "";
$paciente = null;
$resultados = [];
$historial = [];

// Ver paciente desde botón "Ver"
if (isset($_GET['paciente_id'])) {
    $id_paciente = $_GET['paciente_id'];
    $res = $conn->query("SELECT * FROM pacientes WHERE id = '$id_paciente'");

    if ($res->num_rows > 0) {
        $paciente = $res->fetch_assoc();
        $resultados = $conn->query("SELECT * FROM imagenes_medicas WHERE id_paciente = $id_paciente");
        $historial = $conn->query("SELECT * FROM historias_clinicas WHERE id_paciente = $id_paciente");
    } else {
        $mensaje = "❌ Paciente no encontrado.";
    }
}

// Guardar historia clínica
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['guardar'])) {
    $id_paciente = $_POST['id_paciente'];
    $fecha = date("Y-m-d");
    $diagnostico = $_POST['diagnostico'];
    $recomendaciones = $_POST['recomendaciones'];
    $creado_por = $_SESSION['usuario'];

    $sql = "INSERT INTO historias_clinicas (id_paciente, fecha, diagnostico, recomendaciones, creado_por)
            VALUES ('$id_paciente', '$fecha', '$diagnostico', '$recomendaciones', '$creado_por')";

    if ($conn->query($sql) === TRUE) {
        $mensaje = "✅ Historia clínica guardada.";
        header("Location: index-doctor.php?paciente_id=$id_paciente&mensaje=guardado");
        exit();
    } else {
        $mensaje = "❌ Error al guardar: " . $conn->error;
    }
}

if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'guardado') {
    $mensaje = "✅ Historia clínica guardada correctamente.";
}

// Obtener pacientes con citas activas
$pacientes_activos = $conn->query("
    SELECT p.id, p.nombre, p.cedula
    FROM pacientes p
    JOIN citas c ON p.id = c.id_paciente
    WHERE c.estado = 'Activa'
    GROUP BY p.id
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand" href="#">EPS - Doctor</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">

    <h3 class="text-success text-center">Pacientes con Citas Activas</h3>

    <?php if ($mensaje): ?>
        <div class="alert alert-info text-center"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <?php if ($pacientes_activos->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead class="table-success">
                <tr>
                    <th>Nombre</th>
                    <th>Cédula</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($p = $pacientes_activos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $p['nombre']; ?></td>
                        <td><?php echo $p['cedula']; ?></td>
                        <td>
                            <a href="index-doctor.php?paciente_id=<?php echo $p['id']; ?>" class="btn btn-sm btn-success">Ver</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning text-center">No hay pacientes con citas activas.</div>
    <?php endif; ?>

    <?php if ($paciente): ?>
        <hr>
        <h4>Consulta de: <?php echo $paciente['nombre']; ?> (<?php echo $paciente['cedula']; ?>)</h4>

        <h6 class="mt-4">Resultados Médicos:</h6>
        <?php if ($resultados->num_rows > 0): ?>
            <ul>
                <?php while ($r = $resultados->fetch_assoc()): ?>
                    <li><a href="<?php echo $r['ruta_imagen']; ?>" target="_blank"><?php echo $r['nombre_imagen']; ?></a></li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No hay resultados cargados.</p>
        <?php endif; ?>

        <form method="POST" class="card p-4 mt-4">
            <input type="hidden" name="id_paciente" value="<?php echo $paciente['id']; ?>">

            <label>Diagnóstico:</label>
            <textarea name="diagnostico" class="form-control" required></textarea>

            <label class="mt-3">Recomendaciones:</label>
            <textarea name="recomendaciones" class="form-control" required></textarea>

            <button type="submit" name="guardar" class="btn btn-primary mt-3">Guardar Historia Clínica</button>
        </form>

        <div class="card mt-4 p-3">
            <h6>Historial Médico:</h6>
            <?php if ($historial->num_rows > 0): ?>
                <ul>
                    <?php while ($h = $historial->fetch_assoc()): ?>
                        <li>
                            <strong><?php echo $h['fecha']; ?>:</strong>
                            <?php echo $h['diagnostico']; ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No hay historial registrado aún.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
