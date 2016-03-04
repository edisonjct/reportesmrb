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
        ->setDescription("Novedades Sin Actualizar");
//se forman las cabeceras
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:I1');
$objPHPExcel->getActiveSheet()->setTitle('Novedades ' . date("Y-m-d"));
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'REPORTE DE PRODUCTOS SIN ACTUALIZAR DATOS');
$objPHPExcel->getActiveSheet()->setAutoFilter("A2:I2");
$objPHPExcel->getActiveSheet()->setCellValue('A2', 'BODEGA');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'CODIGO');
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'TITULO');
$objPHPExcel->getActiveSheet()->setCellValue('D2', 'AUTOR');
$objPHPExcel->getActiveSheet()->setCellValue('E2', 'EDITORIAL');
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'IDIOMA');
$objPHPExcel->getActiveSheet()->setCellValue('G2', 'CATEGORIA');
$objPHPExcel->getActiveSheet()->setCellValue('H2', 'FECHA DE EDICION');
$objPHPExcel->getActiveSheet()->setCellValue('I2', 'UBICACION');
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(35);

///////////////////////////////////////////
if ($bodega == 'TODOS') {

    //BUSCA UN CODIGO EN TODAS LAS BODEGAS
    $sql = "SELECT
inv.codbar01 as cod,
inv.desprod01 as titulo,
a.nombres AS autor,
e.razon AS editorial,
c.desccate as categoria,
id.nomtab as idioma,
infor04 as fedicion,
infor08 as ubicacion,
inv.bodega as bodega
FROM
INVENTARIO AS inv
LEFT JOIN autores AS a ON inv.infor01 = a.codigo
LEFT JOIN editoriales AS e ON inv.infor02 = e.codigo
LEFT JOIN categorias AS c ON inv.catprod01 = c.codcate
LEFT JOIN idiomas  id ON inv.infor03 = id.codtab
WHERE c.tipocate = '02' AND inv.prodsinsdo01 = 'S' AND
(inv.infor01 in ('49743','49884','',NULL) OR inv.infor02 IN ('3245','',NULL) OR inv.infor03 IN ('',NULL) OR inv.infor04 = '0000-00-00')
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
                    ->setCellValue('D' . $i, $registro['autor'])
                    ->setCellValue('E' . $i, $registro['editorial'])
                    ->setCellValue('F' . $i, $registro['categoria'])
                    ->setCellValue('G' . $i, $registro['idioma'])
                    ->setCellValue('H' . $i, $registro['fedicion'])
                    ->setCellValue('I' . $i, $registro['ubicacion']);
            $i++;
        }
    }
///////////////////////////////////////////////////////////
} else {
    $sql = "SELECT
inv.codbar01 as cod,
inv.desprod01 as titulo,
a.nombres AS autor,
e.razon AS editorial,
c.desccate as categoria,
id.nomtab as idioma,
infor04 as fedicion,
infor08 as ubicacion,
inv.bodega as bodega
FROM
INVENTARIO AS inv
LEFT JOIN autores AS a ON inv.infor01 = a.codigo
LEFT JOIN editoriales AS e ON inv.infor02 = e.codigo
LEFT JOIN categorias AS c ON inv.catprod01 = c.codcate
LEFT JOIN idiomas  id ON inv.infor03 = id.codtab
WHERE c.tipocate = '02' AND inv.prodsinsdo01 = 'S' AND inv.bodega = '$bodega' AND
(inv.infor01 in ('49743','49884','',NULL) OR inv.infor02 IN ('3245','',NULL) OR inv.infor03 IN ('',NULL) OR inv.infor04 = '0000-00-00')
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
                    ->setCellValue('D' . $i, $registro['autor'])
                    ->setCellValue('E' . $i, $registro['editorial'])
                    ->setCellValue('F' . $i, $registro['categoria'])
                    ->setCellValue('G' . $i, $registro['idioma'])
                    ->setCellValue('H' . $i, $registro['fedicion'])
                    ->setCellValue('I' . $i, $registro['ubicacion']);
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