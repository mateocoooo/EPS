<?php
include("conexion.php");

$nombre = $_POST['nombre'];
$cedula = $_POST['cedula'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$motivo = $_POST['motivo'];

$consulta = "SELECT id FROM pacientes WHERE cedula = '$cedula'";
$resultado = $conn->query($consulta);

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $id_paciente = $fila['id'];
} else {
    $sql_paciente = "INSERT INTO pacientes (nombre, cedula, correo, telefono)
                     VALUES ('$nombre', '$cedula', '$correo', '$telefono')";
    
    if ($conn->query($sql_paciente) === TRUE) {
        $id_paciente = $conn->insert_id;
    } else {
        die("Error al guardar paciente: " . $conn->error);
    }
}

$sql_cita = "INSERT INTO citas (id_paciente, fecha, hora, motivo)
             VALUES ('$id_paciente', '$fecha', '$hora', '$motivo')";

if ($conn->query($sql_cita) === TRUE) {
    echo "✅ Cita agendada correctamente.";
} else {
    echo "❌ Error al agendar la cita: " . $conn->error;
}

$conn->close();
?>
