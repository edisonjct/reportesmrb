<?php
$conexion = include('conexion.php');
require_once '../Classes/PHPExcel.php';
$objPHPExcel = new PHPExcel();
$bodega = $_GET['bodega'];

$objPHPExcel->
        getProperties()
        ->setCreator("Edison Chulde")
        ->setLastModifiedBy("Edison Chulde")
        ->setTitle("Exportar Ventas")
        ->setSubject("Ventas")
        ->setDescription("Ventas Mr Books");
//se forman las cabeceras
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
$objPHPExcel->getActiveSheet()->setTitle('Productos Sin CC ' . date("Y-m-d"));
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'REPORTE DE PRODUCTOS SIN CUENTAS CONTABLES');
$objPHPExcel->getActiveSheet()->setAutoFilter("A2:H2");
$objPHPExcel->getActiveSheet()->setCellValue('A2', 'BODEGA');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'CODIGO');
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'TITULO');
$objPHPExcel->getActiveSheet()->setCellValue('D2', 'ACTIVO');
$objPHPExcel->getActiveSheet()->setCellValue('E2', 'VENTA');
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'COSTO');
$objPHPExcel->getActiveSheet()->setCellValue('G2', 'DESCUENTO');
$objPHPExcel->getActiveSheet()->setCellValue('H2', 'TIPO DE PRODUCTO');
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(35);

///////////////////////////////////////////
if ($bodega == 'TODOS') {

    //BUSCA UN CODIGO EN TODAS LAS BODEGAS
    $sql = "SELECT
inv.codbar01 as cod,
inv.desprod01 as titulo,
inv.bodega as bodega,
a.nomcta AS activo,
v.nomcta AS venta,
c.nomcta AS costo,
d.nomcta AS descuento,
t.nomcta AS tipo_pro
FROM
INVENTARIO AS inv
LEFT JOIN cc_activo a ON inv.ctain101 = a.ctamaecon
LEFT JOIN cc_venta v ON inv.ctain201 = v.ctamaecon
LEFT JOIN cc_costo c ON inv.ctain301 = c.ctamaecon
LEFT JOIN cc_descuento d ON inv.ctain401 = d.ctamaecon
LEFT JOIN tipo_producto t ON inv.orden01 = t.ctamaecon
WHERE inv.prodsinsdo01 = 'S' AND 
(inv.ctain101 = '' OR inv.ctain201 = '' OR inv.ctain301 = '' OR inv.ctain401 = '')
ORDER BY inv.bodega";
    
    $resultado = mysql_query($sql) or die(mysql_error());
    $registros = mysql_num_rows($resultado);
    
    if ($registros > 0) {
        //SE IMPRIME LAS COLUMNAS DESEADAS
        $i = 3;
        while ($registro = mysql_fetch_array($resultado)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $registro['bodega'])
                    ->setCellValue('B' . $i, $registro['cod'])
                    ->setCellValue('C' . $i, $registro['titulo'])
                    ->setCellValue('D' . $i, $registro['activo'])
                    ->setCellValue('E' . $i, $registro['venta'])
                    ->setCellValue('F' . $i, $registro['costo'])
                    ->setCellValue('G' . $i, $registro['descuento'])
                    ->setCellValue('H' . $i, $registro['tipo_pro']);
            $i++;
        }
    }
///////////////////////////////////////////////////////////
} else {
    $sql = "SELECT
inv.codbar01 as cod,
inv.desprod01 as titulo,
inv.bodega as bodega,
a.nomcta AS activo,
v.nomcta AS venta,
c.nomcta AS costo,
d.nomcta AS descuento,
t.nomcta AS tipo_pro
FROM
INVENTARIO AS inv
LEFT JOIN cc_activo a ON inv.ctain101 = a.ctamaecon
LEFT JOIN cc_venta v ON inv.ctain201 = v.ctamaecon
LEFT JOIN cc_costo c ON inv.ctain301 = c.ctamaecon
LEFT JOIN cc_descuento d ON inv.ctain401 = d.ctamaecon
LEFT JOIN tipo_producto t ON inv.orden01 = t.ctamaecon
WHERE inv.prodsinsdo01 = 'S' AND inv.bodega = '$bodega' AND
(inv.ctain101 = '' OR inv.ctain201 = '' OR inv.ctain301 = '' OR inv.ctain401 = '')
ORDER BY inv.bodega";
    
    $resultado = mysql_query($sql) or die(mysql_error());
    $registros = mysql_num_rows($resultado);
    
    if ($registros > 0) {
        //SE IMPRIME LAS COLUMNAS DESEADAS
        $i = 3;
        while ($registro = mysql_fetch_array($resultado)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $registro['bodega'])
                    ->setCellValue('B' . $i, $registro['cod'])
                    ->setCellValue('C' . $i, $registro['titulo'])
                    ->setCellValue('D' . $i, $registro['activo'])
                    ->setCellValue('E' . $i, $registro['venta'])
                    ->setCellValue('F' . $i, $registro['costo'])
                    ->setCellValue('G' . $i, $registro['descuento'])
                    ->setCellValue('H' . $i, $registro['tipo_pro']);
            $i++;
        }
    }
}


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Productos Sin CC ' . date("Y-m-d") . '.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>