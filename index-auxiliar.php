<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'auxiliar') {
    header("Location: login.php");
    exit();
}

$mensaje = "";

// Subida de resultado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subir'])) {
    $id_paciente = $_POST['id_paciente'];
    $nombre_imagen = $_FILES['archivo']['name'];
    $ruta = "uploads/" . basename($nombre_imagen);

    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta)) {
        $sql = "INSERT INTO imagenes_medicas (id_paciente, nombre_imagen, ruta_imagen)
                VALUES ('$id_paciente', '$nombre_imagen', '$ruta')";
        if ($conn->query($sql) === TRUE) {
            $mensaje = "✅ Resultado subido exitosamente.";
        } else {
            $mensaje = "❌ Error al guardar en BD: " . $conn->error;
        }
    } else {
        $mensaje = "❌ Error al subir el archivo.";
    }
}

// Cerrar cita
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cerrar_cita'])) {
    $id_paciente = $_POST['id_paciente'];
    $conn->query("UPDATE citas SET estado = 'Finalizada' WHERE id_paciente = $id_paciente AND estado = 'Activa'");
    $mensaje = "✅ La cita ha sido finalizada.";
}

// Agendar seguimiento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agendar'])) {
    $id_paciente = $_POST['id_paciente'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $motivo = $_POST['motivo'];

    $conn->query("INSERT INTO citas (id_paciente, fecha, hora, motivo, estado) 
                  VALUES ('$id_paciente', '$fecha', '$hora', '$motivo', 'Activa')");
    $mensaje = "✅ Nueva cita de seguimiento agendada.";
}

// Pacientes con citas activas
$sql = "SELECT p.id, p.nombre, p.cedula FROM pacientes p
        JOIN citas c ON p.id = c.id_paciente
        WHERE c.estado = 'Activa'
        GROUP BY p.id";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Auxiliar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-info">
    <div class="container">
        <a class="navbar-brand" href="#">EPS - Auxiliar</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h3 class="text-center text-info">📋 Pacientes con Cita Activa</h3>

    <?php if ($mensaje): ?>
        <div class="alert alert-info mt-3 text-center"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <?php if ($resultado->num_rows > 0): ?>
        <?php while ($row = $resultado->fetch_assoc()): ?>
            <?php
                $id_paciente = $row['id'];
                $historial = $conn->query("SELECT * FROM historias_clinicas WHERE id_paciente = $id_paciente ORDER BY fecha DESC");
            ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-info text-white fw-bold">
                    <?php echo $row['nombre']; ?> (<?php echo $row['cedula']; ?>)
                </div>
                <div class="card-body">
                    <!-- Subir archivo -->
                    <form method="POST" enctype="multipart/form-data" class="mb-3">
                        <input type="hidden" name="id_paciente" value="<?php echo $id_paciente; ?>">
                        <label>📎 Subir resultado médico:</label>
                        <input type="file" name="archivo" required>
                        <button type="submit" name="subir" class="btn btn-success btn-sm">Subir</button>
                    </form>

                    <!-- Mostrar historial -->
                    <?php if ($historial->num_rows > 0): ?>
                        <h6 class="text-secondary">📑 Historial Clínico:</h6>
                        <ul class="list-group mb-3">
                            <?php while ($h = $historial->fetch_assoc()): ?>
                                <li class="list-group-item">
                                    <strong><?php echo $h['fecha']; ?>:</strong> 
                                    <?php echo $h['diagnostico']; ?> - 
                                    <em><?php echo $h['recomendaciones']; ?></em>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">Sin historial aún.</p>
                    <?php endif; ?>

                    <!-- Cerrar cita -->
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="id_paciente" value="<?php echo $id_paciente; ?>">
                        <button type="submit" name="cerrar_cita" class="btn btn-outline-danger btn-sm">❌ Finalizar Cita</button>
                    </form>

                    <!-- Agendar nueva cita -->
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#form<?php echo $id_paciente; ?>">
                        ➕ Agendar Seguimiento
                    </button>

                    <div id="form<?php echo $id_paciente; ?>" class="collapse mt-3">
                        <form method="POST">
                            <input type="hidden" name="id_paciente" value="<?php echo $id_paciente; ?>">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <input type="date" name="fecha" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="time" name="hora" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="motivo" placeholder="Motivo" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" name="agendar" class="btn btn-primary btn-sm mt-2">Agendar</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-warning text-center">No hay pacientes con citas activas.</div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS (para collapse) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
