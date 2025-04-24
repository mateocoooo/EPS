<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $rol = $_POST['rol'];

    $sql = "UPDATE usuarios SET nombre='$nombre', rol='$rol' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $mensaje = "✅ Usuario actualizado correctamente.";
    } else {
        $mensaje = "❌ Error al actualizar: " . $conn->error;
    }
}

$usuario = $conn->query("SELECT * FROM usuarios WHERE id = $id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="text-center text-warning">✏️ Editar Usuario</h3>

    <?php if ($mensaje): ?>
        <div class="alert alert-info text-center"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 col-md-6 mx-auto shadow">
        <div class="mb-3">
            <label>Correo (usuario):</label>
            <input type="email" class="form-control" value="<?php echo $usuario['usuario']; ?>" readonly>
        </div>

        <div class="mb-3">
            <label>Nombre:</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $usuario['nombre']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Rol:</label>
            <select name="rol" class="form-select" required>
                <option value="admin" <?php if ($usuario['rol'] == 'admin') echo 'selected'; ?>>Administrador</option>
                <option value="doctor" <?php if ($usuario['rol'] == 'doctor') echo 'selected'; ?>>Doctor</option>
                <option value="auxiliar" <?php if ($usuario['rol'] == 'auxiliar') echo 'selected'; ?>>Auxiliar</option>
                <option value="recepcionista" <?php if ($usuario['rol'] == 'recepcionista') echo 'selected'; ?>>Recepcionista</option>
                <option value="paciente" <?php if ($usuario['rol'] == 'paciente') echo 'selected'; ?>>Paciente</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
    </form>
</div>
</body>
</html>
