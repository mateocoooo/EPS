<?php
include("conexion.php");

$nombre     = $_POST['nombre'];
$cedula     = $_POST['cedula'];
$correo     = $_POST['usuario'];
$contrasena = $_POST['contrasena'];
$telefono   = $_POST['telefono'];
$rol        = 'paciente'; // Fijamos el rol, no se puede modificar desde el formulario

// Verificar si el correo ya existe
$check = "SELECT * FROM usuarios WHERE usuario = '$correo'";
$result = $conn->query($check);

if ($result->num_rows > 0) {
    echo "❌ Este correo ya está registrado.";
    exit();
}

// Insertar en tabla usuarios
$sql_usuario = "INSERT INTO usuarios (usuario, contrasena, nombre, rol)
                VALUES ('$correo', '$contrasena', '$nombre', '$rol')";

if ($conn->query($sql_usuario) === TRUE) {
    // Insertar en tabla pacientes
    $sql_paciente = "INSERT INTO pacientes (nombre, cedula, correo, telefono)
                     VALUES ('$nombre', '$cedula', '$correo', '$telefono')";

    if ($conn->query($sql_paciente) === TRUE) {
        echo "✅ Paciente registrado correctamente. Ya puedes iniciar sesión.";
    } else {
        echo "❌ Usuario creado, pero error al registrar paciente: " . $conn->error;
    }
} else {
    echo "❌ Error al registrar usuario: " . $conn->error;
}

$conn->close();
