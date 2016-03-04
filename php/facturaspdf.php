<?php

  $factura = $_GET['fac'];
  $bodega = $_GET['bodega'];  

require('../fpdf/fpdf.php');
require('conexion.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetTextColor(0,0,0);
$pdf->Line(200, 2, 10, 2);
$pdf->SetFont('Arial', '', 8);
$pdf->Image('../recursos/logoMrBooks.png' , 42 ,13, 60 , 26,'PNG');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(144, 5, '', 0);
$pdf->Cell(15, 5, 'R.U.C: 1791397339001', 0);
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(153, 8, '', 0);
$pdf->Cell(20, 8, 'FACTURA', 0);
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetTextColor(194,8,8);
$pdf->Cell(148, 6, '', 0);
$pdf->Cell(20, 6, 'No '.$factura, 0);
$pdf->Ln(6);
$pdf->SetTextColor(0,0,0);	
$pdf->SetFont('Arial', '', 8);
$pdf->Line(200, 25, 134, 25);
$pdf->Line(200, 35, 200, 25);
$pdf->Line(134, 35, 134, 25);
$pdf->Line(200, 35, 134, 35);
$pdf->Cell(128, 6, '', 0);
$pdf->Cell(60, 12, 'AUTORIZACION SRI No.', 0,0,'C');
$pdf->Ln(3);
$pdf->Cell(128, 6, '', 0);
$pdf->Cell(60, 13, '12345678912345678901234567890123', 0,0,'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(135, 5, '', 0);
$pdf->Cell(15, 5, 'Fecha de autorizacion: ', 0);
$pdf->Ln(4);
$pdf->Cell(146, 5, '', 0);
$pdf->Cell(15, 5, 'Ambiente: Produccion', 0);
$pdf->Ln(2);
$pdf->Cell(148, 5, '', 0);
$pdf->Cell(15, 5, 'Emision: Normal', 0);
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(30, 5, '', 0);
$pdf->Cell(60, 5, 'Razon Social: MISTERBOOKS', 0);
$pdf->SetFont('Arial', '', 6);	
$pdf->Cell(56, 5, '', 0);
$pdf->Cell(15, 5, 'CLAVE DE ACCESO', 0);
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(110, 5, 'Matriz: AV. ELOY ALFARO S/N Y AVIGIRAS - EDIFICIO MEGAKYWI', 0,0,'C');
$pdf->Cell(20, 8, '', 0);
$pdf->Cell(55, 8, '', 1);
$pdf->Ln(3);
$pdf->Cell(110, 5, 'Telefonos: TELEFONOS: 2811066 -2811065 FAX: 2811070', 0,0,'C');
$pdf->Ln(3);
$pdf->Cell(110, 5, 'www.mrbooks.com Quito-Ecuador', 0,0,'C');
$pdf->Ln(3);
$pdf->Cell(110, 5, 'Dir. Sucursal: ELOY ALAFARO Y AVIGIRAS', 0,0,'C');
$pdf->Ln(3);
$pdf->Cell(110, 5, 'Contribuyente Especial Nro 155', 0,0,'C');
$pdf->Ln(3);
$pdf->Cell(110, 5, 'OBLIGADO A LLEVAR CONTABILIDAD: SI', 0,0,'C');
$pdf->Line(200, 71, 10, 71);
$pdf->Line(200, 80, 200, 71);
$pdf->Line(10, 80, 10, 71);
$pdf->Line(200, 80, 10, 80);
$pdf->Ln(17);
$productos = mysql_query("SELECT
m.codbar01 as codigo,
m.desprod01 as titulo,
d.CANTID03 as cantidad,
d.PRECVTA03 as subto,
(d.PRECVTA03 - d.DESCVTA03) as pvpt, 
(d.PRECVTA03/d.CANTID03) as pvpu,
d.DESCVTA03 as descuentop,
d.desctotvta03 as descuentoc,
((d.PRECVTA03 - d.DESCVTA03 - d.desctotvta03) * d.iva03 )/100 AS iva
FROM
factura_detalle d
INNER JOIN maepro m ON d.CODPROD03 = m.codprod01
WHERE d.TIPOTRA03 = '80' AND 
d.bodega = '$bodega' AND 
d.NOCOMP03 = '$factura'");
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25, 4, 'COD. PRINCIPAL', 1,0,'C');
$pdf->Cell(20, 4, 'CANTIDAD', 1,0,'C');
$pdf->Cell(75, 4, 'DESCRIPCION', 1,0,'C');
$pdf->Cell(20, 4, 'P. UNITARIO', 1,0,'C');
$pdf->Cell(25, 4, 'DESCUENTO', 1,0,'C');
$pdf->Cell(25, 4, 'TOTAL DOLARES', 1,0,'C');
$totaluni = 0;
$totaldesp = 0;
$totaldesc = 0;
$totaliva = 0;
$subtotal = 0;

$pdf->Ln(2);
$pdf->SetFont('Arial', '', 7);
while($productos2 = mysql_fetch_array($productos)){	
	$totaluni = $totaluni + $productos2['pvpt'];
	$totaldesp = $totaldesp + $productos2['descuentop'];
	$totaldesc = $totaldesc + $productos2['descuentoc'];
	$totaliva = $totaliva + $productos2['iva'];
	$subtotal = $subtotal + $productos2['subto'];

	$pdf->Cell(25, 8, $productos2['codigo'], 0,0,'C');
	$pdf->Cell(20, 8, number_format($productos2['cantidad'],2,'.',','), 0,0,'R');
	$pdf->Cell(75, 8, substr($productos2['titulo'],0,48), 0);
	$pdf->Cell(20, 8, number_format($productos2['pvpu'],2,'.',','), 0,0,'R');
	$pdf->Cell(25, 8, number_format($productos2['descuentop'],2,'.',','), 0,0,'R');
	$pdf->Cell(25, 8, number_format($productos2['pvpt'],2,'.',','), 0,0,'R');	
	$pdf->Ln(3);	
}

$total = ($subtotal - $totaldesp - $totaldesc) + $totaliva;
$pdf->SetFont('Arial', 'B', 8);
$pdf->Ln(6);
$pdf->Cell(190,0,'',1);
$pdf->Ln(10);
$pdf->Cell(130,0,'',0);
$pdf->Cell(40,0,'Subtotal 1:',0);
$pdf->Cell(30,0,''.number_format($subtotal,2,'.',','),0);
$pdf->Ln(4);
$pdf->Cell(130,0,'',0);
$pdf->Cell(40,0,'Sub Total:',0);
$pdf->Cell(30,0,''.number_format($totaluni,2,'.',','),0);
$pdf->Ln(4);
$pdf->Cell(130,0,'',0);
$pdf->Cell(40,0,'Descuento Producto:',0);
$pdf->Cell(30,0,''.number_format($totaldesp,2,'.',','),0);
$pdf->Ln(4);
$pdf->Cell(130,0,'',0);
$pdf->Cell(40,0,'Descuento Cliente:',0);
$pdf->Cell(30,0,''.number_format($totaldesc,2,'.',','),0);
$pdf->Ln(4);
$pdf->Cell(130,0,'',0);
$pdf->Cell(40,0,'Iva:',0);
$pdf->Cell(30,0,''.number_format($totaliva,2,'.',','),0);
$pdf->Ln(4);
$pdf->Cell(130,0,'',0);
$pdf->Cell(40,0,'TOTAL:',0);
$pdf->Cell(30,0,''.number_format($total,2,'.',','),0);



$pdf->Output(''.$factura.'.pdf','D');

?>