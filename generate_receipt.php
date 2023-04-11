<?php
require('./fpdf185_2/fpdf.php');

// Create a new PDF document
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Add your receipt content here
$pdf->Cell(40, 10, 'Your Receipt');
$pdf->Ln();

// ... Add more content as needed

// Output the PDF
$pdf->Output();
?>
