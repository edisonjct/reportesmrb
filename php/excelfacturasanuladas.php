<?php
$conexion = include('conexion.php');
require_once '../Classes/PHPExcel.php';
$objPHPExcel = new PHPExcel();
$bodega = $_GET['bodega'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];

$objPHPExcel->
        getProperties()
        ->setCreator("Edison Chulde")
        ->setLastModifiedBy("Edison Chulde")
        ->setTitle("Facturas Anuladas")
        ->setSubject("Facturas Anuladas")
        ->setDescription("Facturas Anuladas");
//se forman las cabeceras
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:I1');
$objPHPExcel->getActiveSheet()->setTitle('Facturas Anuladas ' . date("Y-m-d"));
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'REPORTE DE FACTURAS ANULADAS');
$objPHPExcel->getActiveSheet()->setAutoFilter("A2:I2");
$objPHPExcel->getActiveSheet()->setCellValue('A2', 'BODEGA');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'FECHA');
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'FACTURA');
$objPHPExcel->getActiveSheet()->setCellValue('D2', 'VENTA BTA');
$objPHPExcel->getActiveSheet()->setCellValue('E2', 'DESCUENTO');
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'VENTA NETA');
$objPHPExcel->getActiveSheet()->setCellValue('G2', 'CEDULA');
$objPHPExcel->getActiveSheet()->setCellValue('H2', 'CLIENTE');
$objPHPExcel->getActiveSheet()->setCellValue('I2', 'CAJERO');
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(50);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(50);


///////////////////////////////////////////
if ($bodega == 'TODOS') {

    //BUSCA UN CODIGO EN TODAS LAS BODEGAS
    $sql = "SELECT
DATE_FORMAT(d.FECMOV03,'%Y/%m/%d') AS FECHA,
d.NOCOMP03 AS FACTURA,
Sum(d.PRECVTA03) AS VENTABTA,
Sum(d.DESCVTA03+d.desctotvta03) AS DESCUENTO,
Sum((((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100)+(d.PRECVTA03-d.DESCVTA03-d.desctotvta03)) AS VENTANETA,
c.ruc31 AS CEDULA,
c.nomcte31 AS NOMBRE,
d.bodega as BODEGA,
usuario.Nombre AS CAJERO
FROM
factura_detalle AS d
LEFT JOIN factura_cabecera AS c ON d.NOCOMP03 = c.nofact31
LEFT JOIN clientes ON c.nocte31 = clientes.codcte01
LEFT JOIN usuario ON c.UID = usuario.id
WHERE
d.TIPOTRA03 = '80' AND
c.cvanulado31 = '9' AND
d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
GROUP BY d.NOCOMP03
ORDER BY d.FECMOV03 DESC";
    
    $resultado = mysql_query($sql) or die(mysql_error());
    $registros = mysql_num_rows($resultado);
    
    if ($registros > 0) {
        //SE IMPRIME LAS COLUMNAS DESEADAS
        $i = 3;
        while ($registro = mysql_fetch_array($resultado)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $registro['BODEGA'])
                    ->setCellValue('B' . $i, $registro['FECHA'])
                    ->setCellValue('C' . $i, $registro['FACTURA'])
                    ->setCellValue('D' . $i, $registro['VENTABTA'])
                    ->setCellValue('E' . $i, $registro['DESCUENTO'])
                    ->setCellValue('F' . $i, $registro['VENTANETA'])
                    ->setCellValue('G' . $i, $registro['CEDULA'])
                    ->setCellValue('H' . $i, $registro['NOMBRE'])
                    ->setCellValue('I' . $i, $registro['CAJERO']);
            $i++;
        }
    }
///////////////////////////////////////////////////////////
} else {
    $sql = "SELECT
DATE_FORMAT(d.FECMOV03,'%Y/%m/%d') AS FECHA,
d.NOCOMP03 AS FACTURA,
Sum(d.PRECVTA03) AS VENTABTA,
Sum(d.DESCVTA03+d.desctotvta03) AS DESCUENTO,
Sum((((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100)+(d.PRECVTA03-d.DESCVTA03-d.desctotvta03)) AS VENTANETA,
c.ruc31 AS CEDULA,
c.nomcte31 AS NOMBRE,
d.bodega as BODEGA,
usuario.Nombre AS CAJERO
FROM
factura_detalle AS d
LEFT JOIN factura_cabecera AS c ON d.NOCOMP03 = c.nofact31
LEFT JOIN clientes ON c.nocte31 = clientes.codcte01
LEFT JOIN usuario ON c.UID = usuario.id
WHERE
d.TIPOTRA03 = '80' AND
c.cvanulado31 = '9' AND
d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND d.bodega = '$bodega'
GROUP BY d.NOCOMP03
ORDER BY d.FECMOV03 DESC";
    
    $resultado = mysql_query($sql) or die(mysql_error());
    $registros = mysql_num_rows($resultado);
    
    if ($registros > 0) {
        //SE IMPRIME LAS COLUMNAS DESEADAS
        $i = 3;
        while ($registro = mysql_fetch_array($resultado)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $registro['BODEGA'])
                    ->setCellValue('B' . $i, $registro['FECHA'])
                    ->setCellValue('C' . $i, $registro['FACTURA'])
                    ->setCellValue('D' . $i, $registro['VENTABTA'])
                    ->setCellValue('E' . $i, $registro['DESCUENTO'])
                    ->setCellValue('F' . $i, $registro['VENTANETA'])
                    ->setCellValue('G' . $i, $registro['CEDULA'])
                    ->setCellValue('H' . $i, $registro['NOMBRE'])
                    ->setCellValue('I' . $i, $registro['CAJERO']);
            $i++;
        }
    }
}


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Facturas Anuladas ' . date("Y-m-d") . '.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>