<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'paciente') {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];
$mensaje = "";
$paciente = null;

// Buscar paciente por correo
$sql = "SELECT * FROM pacientes WHERE correo = '$usuario'";
$res = $conn->query($sql);

if ($res->num_rows > 0) {
    $paciente = $res->fetch_assoc();
    $id_paciente = $paciente['id'];

    // Consultar datos
    $citas = $conn->query("SELECT * FROM citas WHERE id_paciente = $id_paciente ORDER BY fecha DESC");
    $resultados = $conn->query("SELECT * FROM imagenes_medicas WHERE id_paciente = $id_paciente");
    $historias = $conn->query("SELECT * FROM historias_clinicas WHERE id_paciente = $id_paciente ORDER BY fecha DESC");
} else {
    $mensaje = "❌ Tu perfil no está registrado como paciente en la base de datos.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Portal EPS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Mi Portal EPS</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">

    <h3 class="text-center text-primary">Hola, <?php echo $nombre; ?> 👋</h3>

    <?php if ($paciente): ?>
        <div class="mt-4">
            <h5>📅 Mis Citas:</h5>
            <?php if ($citas->num_rows > 0): ?>
                <ul>
                    <?php while ($c = $citas->fetch_assoc()): ?>
                        <li><?php echo $c['fecha']; ?> a las <?php echo $c['hora']; ?> — Motivo: <?php echo $c['motivo']; ?> — Estado: <?php echo $c['estado']; ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No tienes citas registradas.</p>
            <?php endif; ?>
        </div>

        <div class="mt-4">
            <h5>🧪 Resultados Médicos:</h5>
            <?php if ($resultados->num_rows > 0): ?>
                <ul>
                    <?php while ($r = $resultados->fetch_assoc()): ?>
                        <li><a href="<?php echo $r['ruta_imagen']; ?>" target="_blank"><?php echo $r['nombre_imagen']; ?></a></li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No hay resultados disponibles.</p>
            <?php endif; ?>
        </div>

        <div class="mt-4">
            <h5>🩺 Recomendaciones del Doctor:</h5>
            <?php if ($historias->num_rows > 0): ?>
                <ul>
                    <?php while ($h = $historias->fetch_assoc()): ?>
                        <li><strong><?php echo $h['fecha']; ?></strong> — <?php echo $h['recomendaciones']; ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No tienes recomendaciones registradas.</p>
            <?php endif; ?>
        </div>

        <div class="mt-4 text-center">
        <a href="historia-pdf.php?id=<?php echo $id_paciente; ?>" class="btn btn-outline-primary" target="_blank">📄 Descargar historia clínica (PDF)</a>

        </div>
    <?php else: ?>
        <div class="alert alert-danger text-center mt-4"><?php echo $mensaje; ?></div>
    <?php endif; ?>

</div>
</body>
</html>
