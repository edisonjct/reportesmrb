<?php

$conexion = include('conexion.php');
require_once '../Classes/PHPExcel.php';
$objPHPExcel = new PHPExcel();
$dato = $_GET['dato'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
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
$objPHPExcel->getActiveSheet()->setTitle('Diario de Ventas ' . date("Y-m-d"));
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'CODIGO');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'TITULO');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'CATEGORIA');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'PROVEDOR');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'PAIS');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'CANTIDAD');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'LOCAL');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'FECHA UC');
$objPHPExcel->getActiveSheet()->setAutoFilter("A1:H1");
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);

///////////////////////////////////////////
if ($dato == true) {
    //BUSCA UN CODIGO EN TODAS LAS BODEGAS
    $sql = "SELECT
                m.codbar01,
                m.desprod01,
                sum(f.CANTID03),
                bodegas.nombre as bodega,
                categorias.desccate,
                provedores.nomcte01,
                (SELECT p.nomtab FROM paises p WHERE provedores.loccte01 = p.codtab) AS PAIS,
                (SELECT max(DATE_FORMAT(uf.FECMOV03,'%Y-%m-%d'))FROM factura_detalle uf WHERE((uf.CODPROD03 = f.CODPROD03)AND(uf.TIPOTRA03 IN ('30', '01', '49','37')))) AS UFECHA 
              FROM
                factura_detalle f
                LEFT JOIN maepro m ON f.CODPROD03 = m.codprod01
                LEFT JOIN factura_cabecera fa ON f.NOCOMP03 = fa.nofact31
                LEFT JOIN categorias ON m.catprod01 = categorias.codcate
                LEFT JOIN provedores ON m.proved101 = provedores.coddest01
                INNER JOIN bodegas ON f.bodega = bodegas.cod_local
              WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND m.codbar01 = '$dato' AND f.bodega IN ($bodega)
              GROUP BY f.bodega,f.CODPROD03 ORDER BY sum(f.CANTID03) DESC";
    $resultado = mysql_query($sql) or die(mysql_error());
    $registros = mysql_num_rows($resultado);
    if ($registros > 0) {
        //SE IMPRIME LAS COLUMNAS DESEADAS
        $i = 2;
        while ($registro = mysql_fetch_array($resultado)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $registro['codbar01'])
                    ->setCellValue('B' . $i, $registro['desprod01'])
                    ->setCellValue('C' . $i, $registro['desccate'])
                    ->setCellValue('D' . $i, $registro['nomcte01'])
                    ->setCellValue('E' . $i, $registro['PAIS'])
                    ->setCellValue('F' . $i, $registro['sum(f.CANTID03)'])
                    ->setCellValue('G' . $i, $registro['bodega'])
                    ->setCellValue('H' . $i, $registro['UFECHA']);
            $i++;
        }
    }
} else {
    //EXPORTA TOOS LOS CODIGO EN TODAS LAS BODEGAS
    $sql = "SELECT
                m.codbar01,
                m.desprod01,
                sum(f.CANTID03),
                bodegas.nombre as bodega,
                categorias.desccate,
                provedores.nomcte01,
                (SELECT p.nomtab FROM paises p WHERE provedores.loccte01 = p.codtab) AS PAIS,
                (SELECT max(DATE_FORMAT(uf.FECMOV03,'%Y-%m-%d'))FROM factura_detalle uf WHERE((uf.CODPROD03 = f.CODPROD03)AND(uf.TIPOTRA03 IN ('30', '01', '49','37')))) AS UFECHA 
              FROM
                factura_detalle f
                LEFT JOIN maepro m ON f.CODPROD03 = m.codprod01
                LEFT JOIN factura_cabecera fa ON f.NOCOMP03 = fa.nofact31
                LEFT JOIN categorias ON m.catprod01 = categorias.codcate
                LEFT JOIN provedores ON m.proved101 = provedores.coddest01
                INNER JOIN bodegas ON f.bodega = bodegas.cod_local
              WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND f.bodega IN ($bodega)
              GROUP BY f.bodega,f.CODPROD03 ORDER BY sum(f.CANTID03) DESC";
    $resultado = mysql_query($sql) or die(mysql_error());
    $registros = mysql_num_rows($resultado);
    if ($registros > 0) {
        //SE IMPRIME LAS COLUMNAS DESEADAS
        $i = 2;
        while ($registro = mysql_fetch_array($resultado)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $registro['codbar01'])
                    ->setCellValue('B' . $i, $registro['desprod01'])
                    ->setCellValue('C' . $i, $registro['desccate'])
                    ->setCellValue('D' . $i, $registro['nomcte01'])
                    ->setCellValue('E' . $i, $registro['PAIS'])
                    ->setCellValue('F' . $i, $registro['sum(f.CANTID03)'])
                    ->setCellValue('G' . $i, $registro['bodega'])
                    ->setCellValue('H' . $i, $registro['UFECHA']);
            $i++;
        }
    }
}


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Diario De Ventas ' . date("Y-m-d") . '.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>