<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('tcpdf/tcpdf.php');
include("conexion.php");

if (!isset($_GET['id'])) {
    die("ID no proporcionado");
}

$id_paciente = $_GET['id'];

// Obtener datos del paciente
$p = $conn->query("SELECT * FROM pacientes WHERE id = $id_paciente")->fetch_assoc();

// Obtener historia clínica
$historial = $conn->query("SELECT * FROM historias_clinicas WHERE id_paciente = $id_paciente ORDER BY fecha DESC");

// Crear PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor("EPS System");
$pdf->SetTitle("Historia Clínica");

// Agregar página
$pdf->AddPage();

// Logo (opcional)
if (file_exists('logo_eps.png')) {
    $pdf->Image('logo_eps.png', 15, 10, 25);
    $pdf->SetY(15);
    $pdf->SetX(45);
}

// Título
$pdf->SetFont('helvetica', 'B', 18);
$pdf->Cell(0, 10, 'HISTORIA CLÍNICA', 0, 1, 'C');

$pdf->Ln(10);

// Info del paciente
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 6, "Nombre: " . $p['nombre'], 0, 1);
$pdf->Cell(0, 6, "Cédula: " . $p['cedula'], 0, 1);
$pdf->Cell(0, 6, "Correo: " . $p['correo'], 0, 1);
$pdf->Cell(0, 6, "Teléfono: " . $p['telefono'], 0, 1);

$pdf->Ln(8);

// Línea divisora
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(6);

// Encabezado de historial
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Historial de Consultas', 0, 1, 'L');
$pdf->Ln(2);

// Detalles del historial
$pdf->SetFont('helvetica', '', 11);

if ($historial->num_rows > 0) {
    while ($h = $historial->fetch_assoc()) {
        $pdf->SetFont('', 'B');
        $pdf->Cell(0, 8, '📅 Fecha: ' . $h['fecha'], 0, 1);
        $pdf->SetFont('', '');
        $pdf->MultiCell(0, 6, '🩺 Diagnóstico: ' . $h['diagnostico'], 0, 'L');
        $pdf->MultiCell(0, 6, '📝 Recomendaciones: ' . $h['recomendaciones'], 0, 'L');
        $pdf->Ln(5);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(5);
    }
} else {
    $pdf->MultiCell(0, 6, "No hay historial registrado aún.");
}

$pdf->Output('historia_clinica.pdf', 'I');
