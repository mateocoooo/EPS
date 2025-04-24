<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center text-primary">Registro de Paciente</h2>

    <form method="POST" action="guardar-usuario.php" class="card p-4 shadow col-md-6 mx-auto">
        <div class="mb-3">
            <label class="form-label">Nombre completo:</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Cédula:</label>
            <input type="text" name="cedula" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Correo electrónico (usuario):</label>
            <input type="email" name="usuario" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono:</label>
            <input type="text" name="telefono" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña:</label>
            <input type="password" name="contrasena" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Registrarse</button>
    </form>
</div>
</body>
</html>
