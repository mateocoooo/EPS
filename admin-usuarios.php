<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$usuarios = $conn->query("SELECT * FROM usuarios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios Registrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center text-primary mb-4">👥 Usuarios del Sistema</h2>

    <a href="index-admin.php" class="btn btn-secondary mb-3">⬅ Volver al panel</a>

    <table class="table table-bordered table-striped shadow">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($u = $usuarios->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $u['id']; ?></td>
                    <td><?php echo $u['usuario']; ?></td>
                    <td><?php echo $u['nombre']; ?></td>
                    <td><?php echo ucfirst($u['rol']); ?></td>
                    <td>
                        <a href="editar-usuario.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                        <a href="eliminar-usuario.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">🗑️ Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
