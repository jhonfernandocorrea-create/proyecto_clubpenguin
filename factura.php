<?php
// Check if FPDF is present
if (!file_exists('fpdf.php')) {
    die("Error: La librería FPDF no se encuentra en el directorio raíz. Por favor, descarga 'fpdf.php' desde fpdf.org y colócalo aquí.");
}

require('fpdf.php');
require('conexion.php');

if (!isset($_GET['id'])) {
    die("ID de venta no proporcionado.");
}

$id_venta = $_GET['id'];

// Fetch sale data
$stmt = $pdo->prepare("SELECT * FROM ventas WHERE id = ?");
$stmt->execute([$id_venta]);
$venta = $stmt->fetch();

if (!$venta) {
    die("Venta no encontrada.");
}

// Fetch sale details
$stmtDetail = $pdo->prepare("SELECT dv.*, p.nombre 
                            FROM detalle_ventas dv 
                            JOIN productos p ON dv.producto_id = p.id 
                            WHERE dv.venta_id = ?");
$stmtDetail->execute([$id_venta]);
$detalles = $stmtDetail->fetchAll();

// Generate PDF
class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 20);
        $this->SetTextColor(0, 95, 163); // Primary Blue
        $this->Cell(0, 10, 'CLUB PENGUIN PET SHOP', 0, 1, 'C');
        $this->SetFont('Arial', 'I', 10);
        $this->SetTextColor(100);
        $this->Cell(0, 10, 'Factura de Venta - Mascotas Felices', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Gracias por su compra - Pagina ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Sale Info
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(40, 10, 'No. Factura:', 1, 0, 'L', true);
$pdf->Cell(50, 10, $venta['id'], 1, 0, 'L');
$pdf->Cell(40, 10, 'Fecha:', 1, 0, 'L', true);
$pdf->Cell(60, 10, $venta['fecha'], 1, 1, 'L');
$pdf->Ln(10);

// Table Header
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(0, 159, 227); // Secondary Blue
$pdf->SetTextColor(255);
$pdf->Cell(100, 10, 'Producto', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Cant.', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Precio U.', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Subtotal', 1, 1, 'C', true);

// Table Body
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0);
foreach ($detalles as $d) {
    $subtotal = $d['cantidad'] * $d['precio_unitario'];
    $pdf->Cell(100, 10, utf8_decode($d['nombre']), 1, 0, 'L');
    $pdf->Cell(30, 10, $d['cantidad'], 1, 0, 'C');
    $pdf->Cell(30, 10, '$' . number_format($d['precio_unitario'], 2), 1, 0, 'R');
    $pdf->Cell(30, 10, '$' . number_format($subtotal, 2), 1, 1, 'R');
}

// Total
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(160, 10, 'TOTAL:', 0, 0, 'R');
$pdf->SetTextColor(0, 95, 163);
$pdf->Cell(30, 10, '$' . number_format($venta['total'], 2), 0, 1, 'R');

$pdf->Output('I', 'Factura_' . $id_venta . '.pdf');
?>
