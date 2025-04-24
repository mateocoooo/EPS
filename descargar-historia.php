<?php
include("conexion.php");

if (!isset($_GET['id'])) {
    echo "Falta ID del paciente.";
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM historias_clinicas WHERE id_paciente = $id ORDER BY fecha DESC";
$resultado = $conn->query($sql);

$reporte = "HISTORIA CLÍNICA - PACIENTE\n\n";

while ($fila = $resultado->fetch_assoc()) {
    $reporte .= "Fecha: " . $fila['fecha'] . "\n";
    $reporte .= "Diagnóstico: " . $fila['diagnostico'] . "\n";
    $reporte .= "Recomendaciones: " . $fila['recomendaciones'] . "\n";
    $reporte .= "-------------------------------\n";
}

header("Content-Type: text/plain");
header("Content-Disposition: attachment; filename=historia_clinica.txt");
echo $reporte;
exit();
