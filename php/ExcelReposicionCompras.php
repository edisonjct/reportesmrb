<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExcelReposicionCompras
 *
 * @author DMRBOOKS\echulde
 */

$conexion = include('conexion.php');
require_once '../Classes/PHPExcel.php';
$objPHPExcel = new PHPExcel();

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$bodega = $_GET['bodega'];
$stock = $_GET['stock'];
$operador = $_GET['operador'];

switch ($operador) {
    case '1':
        $signo = '>=';
        break;

    case '2':
        $signo = '=';
        break;

    case '3':
        $signo = '<=';
        break;
}

$objPHPExcel->
        getProperties()
        ->setCreator("Edison Chulde")
        ->setLastModifiedBy("Edison Chulde")
        ->setTitle("Reposicion por Compras")
        ->setSubject("Reposicion por Compras")
        ->setDescription("Reposicion por Compras");
//se forman las cabeceras
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:N1');
$objPHPExcel->getActiveSheet()->setTitle('Reposicion Compras ' . date("Y-m-d"));
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'REPOSRTE DE REPOSICION POR COMPRAS');
$objPHPExcel->getActiveSheet()->setAutoFilter("A2:N2");
$objPHPExcel->getActiveSheet()->setCellValue('A2', 'CODIGO');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'TITULO');
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'CATEGORIA');
$objPHPExcel->getActiveSheet()->setCellValue('D2', 'AUTOR');
$objPHPExcel->getActiveSheet()->setCellValue('E2', 'EDITORIAL');
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'PROVEDOR');
$objPHPExcel->getActiveSheet()->setCellValue('G2', 'IMPORTACION');
$objPHPExcel->getActiveSheet()->setCellValue('H2', 'UBICACION');
$objPHPExcel->getActiveSheet()->setCellValue('I2', 'UFC');
$objPHPExcel->getActiveSheet()->setCellValue('J2', 'UFT');
$objPHPExcel->getActiveSheet()->setCellValue('K2', 'CDI');
$objPHPExcel->getActiveSheet()->setCellValue('L2', $bodega);
$objPHPExcel->getActiveSheet()->setCellValue('M2', 'TRANSFERIDO');
$objPHPExcel->getActiveSheet()->setCellValue('N2', 'PEDIDO');
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(45);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);


///////////////////////////////////////////
if ($bodega == 'TODOS') {
    
} else {

   $vaciartablacompras = mysql_query("TRUNCATE tmpstocklocacompras");
    $compras = mysql_query("INSERT INTO tmpstocklocacompras(codpro,stock,bodega)
        SELECT
        i.codprod01,
        i.cantact01,
        i.bodega
            FROM
        INVENTARIO i WHERE i.bodega = '$bodega'");
    $vaciartablatransferencias = mysql_query("TRUNCATE tmpultimatransferencia");
    $trasnferencias = mysql_query("insert into tmpultimatransferencia(codprod,fecha,cantidad,bodega)
        select i.CODPROD03,max(i.FECMOV03),
(select im.CANTID03 from factura_detalle im WHERE im.CODPROD03=i.CODPROD03 AND im.bodega = '$bodega' AND im.TIPOTRA03 = '11' ORDER BY im.FECMOV03 DESC LIMIT 1) as cantid,
i.bodega from factura_detalle i WHERE i.TIPOTRA03 = '11' AND i.bodega = '$bodega' AND i.CODPROD03 GROUP BY i.CODPROD03");
    $vaciartablaimportaciones = mysql_query("TRUNCATE tmpimportaciones");
    $importaciones = mysql_query("INSERT INTO tmpimportaciones(tipodoc,doc,codigo,cantidad,fecha,importacion)
        SELECT
        im.TIPOTRA03,
        im.NOCOMP03,
        im.CODPROD03,
        im.CANTID03,
        max(im.FECMOV03),
        im.NOFACT03
        from factura_detalle im
        WHERE TIPOTRA03 = '30'
        GROUP BY CODPROD03
");

    $sql = "SELECT
m.codprod01 AS interno,
m.codbar01 AS codigo,
m.desprod01 AS titulo,
categorias.desccate AS categoria,
autores.nombres AS autor,
editoriales.razon AS editorial,
p.nomcte01 AS provedor,
DATE_FORMAT(max(im.fecha),'%Y-%m-%d') AS uf,
im.importacion as codimp,
m.infor08 AS ubicacion,
DATE_FORMAT(utf.fecha,'%Y-%m-%d') as fechaut,
m.cantact01 AS CDI,
st.stock as bodega,
utf.cantidad as cantut,
CASE WHEN CDI > IF (st.stock < utf.cantidad,utf.cantidad-st.stock,0) AND CDI > 0 THEN IF (st.stock < utf.cantidad,utf.cantidad-st.stock,0) ELSE 0 END AS pedido
FROM
maepro AS m
INNER JOIN tmpimportaciones AS im ON im.codigo = m.codprod01
LEFT JOIN tmpstocklocacompras AS st ON st.codpro = m.codprod01
INNER JOIN provedores AS p ON m.proved101 = p.coddest01
LEFT JOIN autores ON m.infor01 = autores.codigo
LEFT JOIN editoriales ON m.infor02 = editoriales.codigo
INNER JOIN categorias ON m.catprod01 = categorias.codcate
INNER JOIN tmpultimatransferencia as utf ON utf.codprod = m.codprod01
WHERE m.cantact01 $signo '$stock' AND im.fecha BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
GROUP BY codprod01
ORDER BY cantact01 DESC";

    $resultado = mysql_query($sql) or die(mysql_error());
    $registros = mysql_num_rows($resultado);

    if ($registros > 0) {
        //SE IMPRIME LAS COLUMNAS DESEADAS
        $i = 3;
        while ($registro = mysql_fetch_array($resultado)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $registro['codigo'])
                    ->setCellValue('B' . $i, $registro['titulo'])
                    ->setCellValue('C' . $i, $registro['categoria'])
                    ->setCellValue('D' . $i, $registro['autor'])
                    ->setCellValue('E' . $i, $registro['editorial'])
                    ->setCellValue('F' . $i, $registro['provedor'])
                    ->setCellValue('G' . $i, $registro['codimp'])
                    ->setCellValue('H' . $i, $registro['ubicacion'])
                    ->setCellValue('I' . $i, $registro['uf'])
                    ->setCellValue('J' . $i, $registro['fechaut'])
                    ->setCellValue('K' . $i, $registro['CDI'])
                    ->setCellValue('L' . $i, $registro['bodega'])
                    ->setCellValue('M' . $i, $registro['cantut'])
                    ->setCellValue('N' . $i, $registro['pedido']);
            $i++;
        }
    }
}


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Reposicion por Compras ' . date("Y-m-d") . '.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');