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
$operador = $_GET['operador'];
$tipo = $_GET['tipo'];

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
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:M1');
            $objPHPExcel->getActiveSheet()->setTitle('Reposicion Compras ' . date("Y-m-d"));
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'REPOSRTE DE REPOSICION POR COMPRAS CON BAJA RATACION');
            $objPHPExcel->getActiveSheet()->setAutoFilter("A2:M2");
            $objPHPExcel->getActiveSheet()->setCellValue('A2', 'CODIGO');
            $objPHPExcel->getActiveSheet()->setCellValue('B2', 'TITULO');
            $objPHPExcel->getActiveSheet()->setCellValue('C2', 'CATEGORIA');
            $objPHPExcel->getActiveSheet()->setCellValue('D2', 'AUTOR');
            $objPHPExcel->getActiveSheet()->setCellValue('E2', 'EDITORIAL');
            $objPHPExcel->getActiveSheet()->setCellValue('F2', 'PROVEDOR');            
            $objPHPExcel->getActiveSheet()->setCellValue('G2', 'UBICACION');            
            $objPHPExcel->getActiveSheet()->setCellValue('H2', 'CDI');
            $objPHPExcel->getActiveSheet()->setCellValue('I2', 'COMPRAS');
            $objPHPExcel->getActiveSheet()->setCellValue('J2', 'CANTIDAD');
            $objPHPExcel->getActiveSheet()->setCellValue('K2', 'PROMEDIO');
            $objPHPExcel->getActiveSheet()->setCellValue('L2', 'VENTA');
            $objPHPExcel->getActiveSheet()->setCellValue('M2', 'PORCENTAJE');
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

        $vaciartmpventas = mysql_query("TRUNCATE tmpventastotal");
        $consolidarventas = mysql_query("INSERT INTO tmpventastotal(codpro,total)
        SELECT
        d.CODPROD03,
        sum(d.CANTID03)
        FROM
        factura_detalle AS d
        INNER JOIN factura_cabecera AS c ON d.NOCOMP03 = c.nofact31
        INNER JOIN bodegas as b ON d.bodega = b.cod_local
        WHERE d.TIPOTRA03 = '80' AND c.cvanulado31 <> '9' AND b.estado = '1' AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
        GROUP BY d.CODPROD03
        ORDER BY b.orden,d.CODPROD03");
        $vaciarcompras = mysql_query("TRUNCATE tmpcompras");
        
        switch ($tipo) {
    case '0001':
        
        $importaciones = mysql_query("INSERT INTO tmpcompras(tipodoc,doc,codigo,cantidad,fecha,compra)
        SELECT
        im.TIPOTRA03,
        COUNT(im.NOCOMP03),
        im.CODPROD03,
        SUM(im.CANTID03),
        MAX(im.FECMOV03),
        im.NOFACT03
        from factura_detalle im
        WHERE im.TIPOTRA03 = '30' AND im.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
        GROUP BY im.CODPROD03
	ORDER BY im.CODPROD03");        
        break;
    
     case '0002':
        $nacionales = mysql_query("INSERT INTO tmpcompras(tipodoc,doc,codigo,cantidad,fecha,compra)
        SELECT
        im.TIPOTRA03,
        COUNT(im.NOCOMP03),
        im.CODPROD03,
        SUM(im.CANTID03),
        MAX(im.FECMOV03),
        im.nopedido03
        from factura_detalle im
        WHERE im.TIPOTRA03 = '01' AND im.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
        GROUP BY im.CODPROD03
	ORDER BY im.CODPROD03");
          break;

    case '0003':
        $consignadas = mysql_query("INSERT INTO tmpcompras(tipodoc,doc,codigo,cantidad,fecha,compra)
        SELECT
        im.TIPOTRA03,
        COUNT(im.NOCOMP03),
        im.CODPROD03,
        SUM(im.CANTID03),
        MAX(im.FECMOV03),
        im.nopedido03
        from factura_detalle im
        WHERE im.TIPOTRA03 = '37' AND im.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
        GROUP BY im.CODPROD03
	ORDER BY im.CODPROD03");                
        
        }   
        

    $sql = "SELECT
	m.codprod01 AS interno,
	m.codbar01 AS codigo,
	m.desprod01 AS titulo,
	categorias.desccate AS categoria,
	autores.nombres AS autor,
	editoriales.razon AS editorial,
	p.nomcte01 AS provedor,
	m.infor08 AS ubicacion,
	m.cantact01 AS CDI,
	c.doc as compras,
	c.cantidad as sumcompras,
	c.cantidad / c.doc as prom,
	vt.total as ventas,
	CASE WHEN vt.total >= c.cantidad THEN 100 ELSE ROUND((vt.total * 100)/c.cantidad) END as porcen,
	CASE WHEN vt.total >= c.cantidad THEN 0 ELSE 100 - (ROUND((vt.total * 100)/c.cantidad)) END as dif
	FROM
	maepro AS m
	INNER JOIN provedores AS p ON m.proved101 = p.coddest01
	LEFT JOIN autores ON m.infor01 = autores.codigo
	LEFT JOIN editoriales ON m.infor02 = editoriales.codigo
	INNER JOIN categorias ON m.catprod01 = categorias.codcate
	INNER JOIN tmpcompras AS c ON m.codprod01 = c.codigo
	INNER JOIN tmpventastotal AS vt ON c.codigo = vt.codpro
	WHERE
	m.cantact01 $signo '$stock'
	GROUP BY codprod01
	ORDER BY porcen ASC";

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
                ->setCellValue('G' . $i, $registro['ubicacion'])
                ->setCellValue('H' . $i, $registro['CDI'])
                ->setCellValue('I' . $i, $registro['compras'])
                ->setCellValue('J' . $i, $registro['sumcompras'])
                ->setCellValue('K' . $i, number_format($registro['prom'], 2, '.', ','))
                ->setCellValue('L' . $i, $registro['ventas'])
                ->setCellValue('M' . $i, $registro['porcen'].'%');
        $i++;
    }
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Reposicion por Compras ' . date("Y-m-d") . '.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
