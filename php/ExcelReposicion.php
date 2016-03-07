<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$conexion = include('conexion.php');
require_once '../Classes/PHPExcel.php';
$objPHPExcel = new PHPExcel();
$bodega = $_GET['bodega'];
$tipo = $_GET['tipo'];
$stock = $_GET['stock'];


$objPHPExcel->
        getProperties()
        ->setCreator("Edison Chulde")
        ->setLastModifiedBy("Edison Chulde")
        ->setTitle("Exportar Reposicion")
        ->setSubject("Reposiciones")
        ->setDescription("Reposiciones Mr Books");
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:L1');
$objPHPExcel->getActiveSheet()->setTitle('Reposicion '.$bodega.' al '.date("Y-m-d"));
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'REPOSICION DE ' . $bodega);
$objPHPExcel->getActiveSheet()->setAutoFilter("A2:L2");
$objPHPExcel->getActiveSheet()->setCellValue('A2', 'CODIGO');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'TITULO');
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'CATEGORIA');
$objPHPExcel->getActiveSheet()->setCellValue('D2', 'AUTOR');
$objPHPExcel->getActiveSheet()->setCellValue('E2', 'EDITORIAL');
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'PROVEDOR');
$objPHPExcel->getActiveSheet()->setCellValue('G2', 'UF COMPRA');
$objPHPExcel->getActiveSheet()->setCellValue('H2', 'UBICACION');
$objPHPExcel->getActiveSheet()->setCellValue('I2', 'CDI');
$objPHPExcel->getActiveSheet()->setCellValue('J2', $bodega);
$objPHPExcel->getActiveSheet()->setCellValue('K2', 'VENTA');
$objPHPExcel->getActiveSheet()->setCellValue('L2', 'PEDIDO');
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(55);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);

$sql = "SELECT
        m.codprod01 AS interno,
        m.codbar01 AS codigo,
        m.desprod01 as titulo,
        categorias.desccate as categoria,
        autores.nombres AS autor,
        editoriales.razon AS editorial,
        p.nomcte01 AS provedor,       
        DATE_FORMAT(m.fecult01,'%Y-%m-%d') as uf,
        m.infor08 as ubicacion,
        m.cantact01 AS CDI,
        l.stock AS BODEGA,
        CASE WHEN v.cantidad > 0 THEN v.cantidad ELSE '0' END AS venta,
        CASE 
            WHEN m.cantact01 > v.cantidad THEN v.cantidad 
            WHEN m.cantact01 < v.cantidad THEN m.cantact01 ELSE '0' END AS pedido
        FROM
        maepro AS m
        INNER JOIN tmpstocklocal AS l ON m.codprod01 = l.codpro
        LEFT JOIN tmpventascantidadbodega AS v ON m.codprod01 = v.idpro
        INNER JOIN provedores AS p ON m.proved101 = p.coddest01
        LEFT JOIN autores ON m.infor01 = autores.codigo
        LEFT JOIN editoriales ON m.infor02 = editoriales.codigo
        INNER JOIN categorias ON m.catprod01 = categorias.codcate
        WHERE l.stock >= '$stock' AND p.tipcte01 = '$tipo'
        ORDER BY v.cantidad DESC";
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
                    ->setCellValue('G' . $i, $registro['uf'])
                    ->setCellValue('H' . $i, $registro['ubicacion'])
                    ->setCellValue('I' . $i, $registro['CDI'])
                    ->setCellValue('J' . $i, $registro['BODEGA'])
                    ->setCellValue('K' . $i, $registro['venta'])
                    ->setCellValue('L' . $i, $registro['pedido']);
            $i++;
        }
    }
    
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Reposicion '.$bodega. ' al '. date("Y-m-d") . '.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');