<?php
require('libs/fpdf/fpdf.php'); // Asegúrate de que la ruta sea correcta

// Crear una instancia de la clase FPDF
$pdf = new FPDF();

// Agregar una página al documento
$pdf->AddPage();

// Establecer la fuente
$pdf->SetFont('Arial', 'B', 16);

// Escribir un texto
$pdf->Cell(40, 10, 'Hola, FPDF funciona!');

// Generar el PDF en el navegador
$pdf->Output();
?>
