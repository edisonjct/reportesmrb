<?php

$conexion = include('conexion.php');
require_once '../Classes/PHPExcel.php';
$objPHPExcel = new PHPExcel();
$bodega = $_GET['bodega'];
$tipo = $_GET['tipo'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$objPHPExcel->
        getProperties()
        ->setCreator("Edison Chulde")
        ->setLastModifiedBy("Edison Chulde")
        ->setTitle("Exportar Documentoss")
        ->setSubject("Documentos Mr Books")
        ->setDescription("Documentos Mr Books");

    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('Reporte' . date("Y-m-d"));
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'CODIGO');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'TITULO');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', 'EDITORIAL');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', 'AUTOR');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', 'CATEGORIA');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', 'SEGMENTO');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', 'FINAL');
    $objPHPExcel->getActiveSheet()->setCellValue('H1', 'PROVEDOR');
    $objPHPExcel->getActiveSheet()->setCellValue('I1', 'PAIS');
    $objPHPExcel->getActiveSheet()->setCellValue('J1', 'IDIOMA');
    $objPHPExcel->getActiveSheet()->setCellValue('K1', 'CANTIDAD');
    $objPHPExcel->getActiveSheet()->setCellValue('L1', 'COSTO');
    $objPHPExcel->getActiveSheet()->setCellValue('M1', 'DESCUENTO');
    $objPHPExcel->getActiveSheet()->setCellValue('N1', 'VENTA');
    $objPHPExcel->getActiveSheet()->setCellValue('O1', 'UTILIDAD');
    $objPHPExcel->getActiveSheet()->setCellValue('P1', 'MES');
    $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'AÑO');
    $objPHPExcel->getActiveSheet()->setCellValue('R1', 'BODEGA');
    $objPHPExcel->getActiveSheet()->setAutoFilter("A1:R1");
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);

if ($tipo == '80') {
   

    $sql = "SELECT
        d.TIPOTRA03 AS tipo,
        p.codbar01 AS codigo,
        p.desprod01 AS titulo,
        e.razon AS editorial,
        a.nombres AS autor,
        ct.categoria AS categoria,
        ct.segmento AS segmento,
        ct.final AS final,
        pr.nomcte01 AS provedor,
        (SELECT pa.nomtab FROM paises pa WHERE pr.loccte01 = pa.codtab) AS PAIS,
        idiomas.nomtab as idioma,
        Sum(d.CANTID03) AS cantidad,
        Sum(d.VALOR03) AS costo,
        Sum(d.desctotvta03+d.DESCVTA03+d.desctotfp03) AS DESCUENTO,
        Sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03-d.desctotfp03) AS VENTANET,
        sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03-d.desctotfp03) - sum((d.VALOR03/d.CANTID03)) AS utilidad,
        MONTH(d.FECMOV03) AS mes,
        YEAR(d.FECMOV03) AS anio,
        bodegas.nombre AS bodega
        FROM
        factura_detalle AS d
        INNER JOIN factura_cabecera AS c ON d.NOCOMP03 = c.nofact31
        INNER JOIN maepro AS p ON p.codprod01 = d.CODPROD03
        LEFT JOIN autores AS a ON p.infor01 = a.codigo
        LEFT JOIN editoriales AS e ON p.infor02 = e.codigo
        LEFT JOIN categorias_sc AS ct ON p.catprod01 = ct.codigo
        LEFT JOIN provedores AS pr ON p.proved101 = pr.coddest01
        INNER JOIN bodegas ON d.bodega = bodegas.cod_local
        LEFT JOIN idiomas ON p.infor03 = idiomas.codtab
        WHERE d.TIPOTRA03 = '80' AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND d.bodega in ($bodega) AND
        c.cvanulado31 <> '9' AND bodegas.estado = '1'
        GROUP BY d.bodega,YEAR(d.FECMOV03),MONTH(d.FECMOV03),d.CODPROD03
        ORDER BY d.bodega,YEAR(d.FECMOV03),MONTH(d.FECMOV03)";
    $resultado = mysql_query($sql) or die(mysql_error());
    $registros = mysql_num_rows($resultado);
    if ($registros > 0) {
        //SE IMPRIME LAS COLUMNAS DESEADAS
        $i = 2;
        while ($row = mysql_fetch_array($resultado)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $row['codigo'])
                    ->setCellValue('B' . $i, $row['titulo'])
                    ->setCellValue('C' . $i, $row['editorial'])
                    ->setCellValue('D' . $i, $row['autor'])
                    ->setCellValue('E' . $i, $row['categoria'])
                    ->setCellValue('F' . $i, $row['segmento'])
                    ->setCellValue('G' . $i, $row['final'])
                    ->setCellValue('H' . $i, $row['provedor'])
                    ->setCellValue('I' . $i, $row['PAIS'])
                    ->setCellValue('J' . $i, $row['idioma'])
                    ->setCellValue('K' . $i, $row['cantidad'])
                    ->setCellValue('L' . $i, $row['costo'])
                    ->setCellValue('M' . $i, $row['DESCUENTO'])
                    ->setCellValue('N' . $i, $row['VENTANET'])
                    ->setCellValue('O' . $i, $row['utilidad'])
                    ->setCellValue('P' . $i, $row['mes'])
                    ->setCellValue('Q' . $i, $row['anio'])
                    ->setCellValue('R' . $i, $row['bodega'])
            ;
            $i++;
        }
    }
}

if ($tipo == '22') {
   
    $sql = "SELECT
        d.TIPOTRA03 AS tipo,
        p.codbar01 AS codigo,
        p.desprod01 AS titulo,
        e.razon AS editorial,
        a.nombres AS autor,
        ct.categoria AS categoria,
        ct.segmento AS segmento,
        ct.final AS final,
        pr.nomcte01 AS provedor,
        (SELECT pa.nomtab FROM paises pa WHERE pr.loccte01 = pa.codtab) AS PAIS,
        idiomas.nomtab as idioma,
        Sum(d.CANTID03) AS cantidad,
        Sum(d.VALOR03) AS costo,
        Sum(d.desctotvta03+d.DESCVTA03+d.desctotfp03) AS DESCUENTO,
        Sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03-d.desctotfp03) AS VENTANET,
        sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03-d.desctotfp03) - sum((d.VALOR03/d.CANTID03)) AS utilidad,
        MONTH(d.FECMOV03) AS mes,
        YEAR(d.FECMOV03) AS anio,
        bodegas.nombre AS bodega
        FROM
        factura_detalle AS d
        INNER JOIN maepro AS p ON p.codprod01 = d.CODPROD03
        LEFT JOIN autores AS a ON p.infor01 = a.codigo
        LEFT JOIN editoriales AS e ON p.infor02 = e.codigo
        LEFT JOIN categorias_sc AS ct ON p.catprod01 = ct.codigo
        LEFT JOIN provedores AS pr ON p.proved101 = pr.coddest01
        INNER JOIN bodegas ON d.bodega = bodegas.cod_local
        LEFT JOIN idiomas ON p.infor03 = idiomas.codtab
        WHERE
        d.TIPOTRA03 = '22' AND
        d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND
        d.bodega IN ($bodega) AND d.cvanulado03 = 'N' AND
        bodegas.estado = '1'
        GROUP BY d.bodega,YEAR(d.FECMOV03),MONTH(d.FECMOV03),d.CODPROD03
        ORDER BY d.bodega,YEAR(d.FECMOV03),MONTH(d.FECMOV03)";
    $resultado = mysql_query($sql) or die(mysql_error());
    $registros = mysql_num_rows($resultado);
    if ($registros > 0) {
        //SE IMPRIME LAS COLUMNAS DESEADAS
        $i = 2;
        while ($row = mysql_fetch_array($resultado)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $row['codigo'])
                    ->setCellValue('B' . $i, $row['titulo'])
                    ->setCellValue('C' . $i, $row['editorial'])
                    ->setCellValue('D' . $i, $row['autor'])
                    ->setCellValue('E' . $i, $row['categoria'])
                    ->setCellValue('F' . $i, $row['segmento'])
                    ->setCellValue('G' . $i, $row['final'])
                    ->setCellValue('H' . $i, $row['provedor'])
                    ->setCellValue('I' . $i, $row['PAIS'])
                    ->setCellValue('J' . $i, $row['idioma'])
                    ->setCellValue('K' . $i, $row['cantidad'])
                    ->setCellValue('L' . $i, $row['costo'])
                    ->setCellValue('M' . $i, $row['DESCUENTO'])
                    ->setCellValue('N' . $i, $row['VENTANET'])
                    ->setCellValue('O' . $i, $row['utilidad'])
                    ->setCellValue('P' . $i, $row['mes'])
                    ->setCellValue('Q' . $i, $row['anio'])
                    ->setCellValue('R' . $i, $row['bodega'])
            ;
            $i++;
        }
    }
}

if ($tipo == '30') {
   
    $sql = "SELECT
        d.TIPOTRA03 AS tipo,
        p.codbar01 AS codigo,
        p.desprod01 AS titulo,
        e.razon AS editorial,
        a.nombres AS autor,
        ct.categoria AS categoria,
        ct.segmento AS segmento,
        ct.final AS final,
        pr.nomcte01 AS provedor,
        (SELECT pa.nomtab FROM paises pa WHERE pr.loccte01 = pa.codtab) AS PAIS,
        idiomas.nomtab as idioma,
        Sum(d.CANTID03) AS cantidad,
        Sum(d.VALOR03) AS costo,
        Sum(d.desctotvta03+d.DESCVTA03+d.desctotfp03) AS DESCUENTO,
        Sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03-d.desctotfp03) AS VENTANET,
        sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03-d.desctotfp03) - sum((d.VALOR03/d.CANTID03)) AS utilidad,
        MONTH(d.FECMOV03) AS mes,
        YEAR(d.FECMOV03) AS anio,
        bodegas.nombre AS bodega
        FROM
        factura_detalle AS d
        LEFT JOIN maepro AS p ON p.codprod01 = d.CODPROD03
        LEFT JOIN autores AS a ON p.infor01 = a.codigo
        LEFT JOIN editoriales AS e ON p.infor02 = e.codigo
        LEFT JOIN categorias_sc AS ct ON p.catprod01 = ct.codigo
        LEFT JOIN provedores AS pr ON p.proved101 = pr.coddest01
        INNER JOIN bodegas ON d.bodega = bodegas.cod_local
        LEFT JOIN idiomas ON p.infor03 = idiomas.codtab
        WHERE
        d.TIPOTRA03 = '30' AND
        d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND
        d.bodega IN ($bodega) AND d.cvanulado03 = 'N' AND
        bodegas.estado = '1'
        GROUP BY d.bodega,YEAR(d.FECMOV03),MONTH(d.FECMOV03),d.CODPROD03
        ORDER BY d.bodega,YEAR(d.FECMOV03),MONTH(d.FECMOV03)";
    $resultado = mysql_query($sql) or die(mysql_error());
    $registros = mysql_num_rows($resultado);
    if ($registros > 0) {
        //SE IMPRIME LAS COLUMNAS DESEADAS
        $i = 2;
        while ($row = mysql_fetch_array($resultado)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $row['codigo'])
                    ->setCellValue('B' . $i, $row['titulo'])
                    ->setCellValue('C' . $i, $row['editorial'])
                    ->setCellValue('D' . $i, $row['autor'])
                    ->setCellValue('E' . $i, $row['categoria'])
                    ->setCellValue('F' . $i, $row['segmento'])
                    ->setCellValue('G' . $i, $row['final'])
                    ->setCellValue('H' . $i, $row['provedor'])
                    ->setCellValue('I' . $i, $row['PAIS'])
                    ->setCellValue('J' . $i, $row['idioma'])
                    ->setCellValue('K' . $i, $row['cantidad'])
                    ->setCellValue('L' . $i, $row['costo'])
                    ->setCellValue('M' . $i, $row['DESCUENTO'])
                    ->setCellValue('N' . $i, $row['VENTANET'])
                    ->setCellValue('O' . $i, $row['utilidad'])
                    ->setCellValue('P' . $i, $row['mes'])
                    ->setCellValue('Q' . $i, $row['anio'])
                    ->setCellValue('R' . $i, $row['bodega'])
            ;
            $i++;
        }
    }
}

if ($tipo == 'fcli') {
    
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('Reporte' . date("Y-m-d"));
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'FACTURA');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'COD.CLIENTE');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', 'TIPO.CLIENTE');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', 'NOM.CLIENTE');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', 'CODIGO');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', 'TITULO');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', 'EDITORIAL');
    $objPHPExcel->getActiveSheet()->setCellValue('H1', 'AUTOR');
    $objPHPExcel->getActiveSheet()->setCellValue('I1', 'CATEGORIA');
    $objPHPExcel->getActiveSheet()->setCellValue('J1', 'SEGMENTO');
    $objPHPExcel->getActiveSheet()->setCellValue('K1', 'FINAL');
    $objPHPExcel->getActiveSheet()->setCellValue('L1', 'PROVEDOR');
    $objPHPExcel->getActiveSheet()->setCellValue('M1', 'PAIS');
    $objPHPExcel->getActiveSheet()->setCellValue('N1', 'IDIOMA');
    $objPHPExcel->getActiveSheet()->setCellValue('O1', 'CANTIDAD');
    $objPHPExcel->getActiveSheet()->setCellValue('P1', 'COSTO');
    $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'DESCUENTO');
    $objPHPExcel->getActiveSheet()->setCellValue('R1', 'VENTA');
    $objPHPExcel->getActiveSheet()->setCellValue('S1', 'UTILIDAD');
    $objPHPExcel->getActiveSheet()->setCellValue('T1', 'MES');
    $objPHPExcel->getActiveSheet()->setCellValue('U1', 'AÑO');
    $objPHPExcel->getActiveSheet()->setCellValue('V1', 'BODEGA');
    $objPHPExcel->getActiveSheet()->setAutoFilter("A1:V1");
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(15);
       
    $sql = "SELECT
        d.TIPOTRA03 AS tipo,
        p.codbar01 AS codigo,
        c.nofact31 as factura,
        c.ruc31 AS codigocliente,
        CASE WHEN LENGTH(c.ruc31) > 10 THEN 'JURIDICA' ELSE 'NATURAL' END AS tipocli,
        c.nomcte31 AS nombrecliente,
        p.desprod01 AS titulo,
        e.razon AS editorial,
        a.nombres AS autor,
        ct.categoria AS categoria,
        ct.segmento AS segmento,
        ct.final AS final,
        pr.nomcte01 AS provedor,
        (SELECT pa.nomtab FROM paises pa WHERE pr.loccte01 = pa.codtab) AS PAIS,
        idiomas.nomtab AS idioma,
        d.CANTID03 AS cantidad,
        d.VALOR03 AS costo,
        (d.desctotvta03+d.DESCVTA03+d.desctotfp03) AS DESCUENTO,
        (d.PRECVTA03-d.DESCVTA03-d.desctotvta03-d.desctotfp03) AS VENTANET,
        (d.PRECVTA03-d.DESCVTA03-d.desctotvta03-d.desctotfp03) - (d.VALOR03/d.CANTID03) AS utilidad,
        MONTH(d.FECMOV03) AS mes,
        YEAR(d.FECMOV03) AS anio,
        bodegas.nombre AS bodega
        FROM
        factura_detalle AS d
        INNER JOIN factura_cabecera AS c ON d.NOCOMP03 = c.nofact31
        INNER JOIN maepro AS p ON p.codprod01 = d.CODPROD03
        LEFT JOIN autores AS a ON p.infor01 = a.codigo
        LEFT JOIN editoriales AS e ON p.infor02 = e.codigo
        LEFT JOIN categorias_sc AS ct ON p.catprod01 = ct.codigo
        LEFT JOIN provedores AS pr ON p.proved101 = pr.coddest01
        INNER JOIN bodegas ON d.bodega = bodegas.cod_local
        LEFT JOIN idiomas ON p.infor03 = idiomas.codtab
        WHERE d.TIPOTRA03 = '80' AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND d.bodega in ($bodega) AND
        c.cvanulado31 <> '9' AND bodegas.estado = '1'
        ORDER BY d.bodega,YEAR(d.FECMOV03),MONTH(d.FECMOV03)";
    $resultado = mysql_query($sql) or die(mysql_error());
    $registros = mysql_num_rows($resultado);
    if ($registros > 0) {
        //SE IMPRIME LAS COLUMNAS DESEADAS
        $i = 2;
        while ($row = mysql_fetch_array($resultado)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $row['factura'])
                    ->setCellValue('B' . $i, $row['codigocliente'])
                    ->setCellValue('C' . $i, $row['tipocli'])
                    ->setCellValue('D' . $i, $row['nombrecliente'])
                    ->setCellValue('E' . $i, $row['codigo'])
                    ->setCellValue('F' . $i, $row['titulo'])
                    ->setCellValue('G' . $i, $row['editorial'])
                    ->setCellValue('H' . $i, $row['autor'])
                    ->setCellValue('I' . $i, $row['categoria'])
                    ->setCellValue('J' . $i, $row['segmento'])
                    ->setCellValue('K' . $i, $row['final'])
                    ->setCellValue('L' . $i, $row['provedor'])
                    ->setCellValue('M' . $i, $row['PAIS'])
                    ->setCellValue('N' . $i, $row['idioma'])
                    ->setCellValue('O' . $i, $row['cantidad'])
                    ->setCellValue('P' . $i, $row['costo'])
                    ->setCellValue('Q' . $i, $row['DESCUENTO'])
                    ->setCellValue('R' . $i, $row['VENTANET'])
                    ->setCellValue('S' . $i, $row['utilidad'])
                    ->setCellValue('T' . $i, $row['mes'])
                    ->setCellValue('U' . $i, $row['anio'])
                    ->setCellValue('V' . $i, $row['bodega'])
            ;
            $i++;
        }
    }
}

if ($tipo == 'nccli') {
    
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('Reporte' . date("Y-m-d"));
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'NOTADECREDITO');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'COD.CLIENTE');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', 'TIPO.CLIENTE');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', 'NOM.CLIENTE');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', 'CODIGO');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', 'TITULO');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', 'EDITORIAL');
    $objPHPExcel->getActiveSheet()->setCellValue('H1', 'AUTOR');
    $objPHPExcel->getActiveSheet()->setCellValue('I1', 'CATEGORIA');
    $objPHPExcel->getActiveSheet()->setCellValue('J1', 'SEGMENTO');
    $objPHPExcel->getActiveSheet()->setCellValue('K1', 'FINAL');
    $objPHPExcel->getActiveSheet()->setCellValue('L1', 'PROVEDOR');
    $objPHPExcel->getActiveSheet()->setCellValue('M1', 'PAIS');
    $objPHPExcel->getActiveSheet()->setCellValue('N1', 'IDIOMA');
    $objPHPExcel->getActiveSheet()->setCellValue('O1', 'CANTIDAD');
    $objPHPExcel->getActiveSheet()->setCellValue('P1', 'COSTO');
    $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'DESCUENTO');
    $objPHPExcel->getActiveSheet()->setCellValue('R1', 'VENTA');
    $objPHPExcel->getActiveSheet()->setCellValue('S1', 'UTILIDAD');
    $objPHPExcel->getActiveSheet()->setCellValue('T1', 'MES');
    $objPHPExcel->getActiveSheet()->setCellValue('U1', 'AÑO');
    $objPHPExcel->getActiveSheet()->setCellValue('V1', 'BODEGA');
    $objPHPExcel->getActiveSheet()->setAutoFilter("A1:V1");
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(15);
       
    $sql = "SELECT
        d.TIPOTRA03 AS tipo,
        p.codbar01 AS codigo,
        d.NOCOMP03 as notadc,
        c.cascte01 as codigocliente,
        c.nomcte01 as nombrecliente,
        CASE WHEN LENGTH(c.cascte01) > 10 THEN 'JURIDICA' ELSE 'NATURAL' END AS tipocli,
        p.desprod01 AS titulo,
        e.razon AS editorial,
        a.nombres AS autor,
        ct.categoria AS categoria,
        ct.segmento AS segmento,
        ct.final AS final,
        pr.nomcte01 AS provedor,
        (SELECT pa.nomtab FROM paises pa WHERE pr.loccte01 = pa.codtab) AS PAIS,
        idiomas.nomtab AS idioma,
        d.CANTID03 AS cantidad,
        d.VALOR03*-1  AS costo,
        (d.desctotvta03+d.DESCVTA03+d.desctotfp03)*-1  AS DESCUENTO,
        (d.PRECVTA03-d.DESCVTA03-d.desctotvta03-d.desctotfp03)*-1 AS VENTANET,
        ((d.PRECVTA03-d.DESCVTA03-d.desctotvta03-d.desctotfp03) - (d.VALOR03/d.CANTID03))*-1 AS utilidad,
        MONTH(d.FECMOV03) AS mes,
        YEAR(d.FECMOV03) AS anio,
        bodegas.nombre AS bodega
        FROM
                factura_detalle AS d
                LEFT JOIN clientes AS c ON d.nomdest03 = c.codcte01
                INNER JOIN maepro AS p ON p.codprod01 = d.CODPROD03
                LEFT JOIN autores AS a ON p.infor01 = a.codigo
                LEFT JOIN editoriales AS e ON p.infor02 = e.codigo
                LEFT JOIN categorias_sc AS ct ON p.catprod01 = ct.codigo
                LEFT JOIN provedores AS pr ON p.proved101 = pr.coddest01
                INNER JOIN bodegas ON d.bodega = bodegas.cod_local
                LEFT JOIN idiomas ON p.infor03 = idiomas.codtab
        WHERE d.TIPOTRA03 = '22' AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' 
        AND d.bodega in ($bodega) AND bodegas.estado = '1'
        ORDER BY d.bodega,YEAR(d.FECMOV03),MONTH(d.FECMOV03),d.FECMOV03";
    $resultado = mysql_query($sql) or die(mysql_error());
    $registros = mysql_num_rows($resultado);
    if ($registros > 0) {
        //SE IMPRIME LAS COLUMNAS DESEADAS
        $i = 2;
        while ($row = mysql_fetch_array($resultado)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $row['notadc'])
                    ->setCellValue('B' . $i, $row['codigocliente'])
                    ->setCellValue('C' . $i, $row['tipocli'])
                    ->setCellValue('D' . $i, $row['nombrecliente'])
                    ->setCellValue('E' . $i, $row['codigo'])
                    ->setCellValue('F' . $i, $row['titulo'])
                    ->setCellValue('G' . $i, $row['editorial'])
                    ->setCellValue('H' . $i, $row['autor'])
                    ->setCellValue('I' . $i, $row['categoria'])
                    ->setCellValue('J' . $i, $row['segmento'])
                    ->setCellValue('K' . $i, $row['final'])
                    ->setCellValue('L' . $i, $row['provedor'])
                    ->setCellValue('M' . $i, $row['PAIS'])
                    ->setCellValue('N' . $i, $row['idioma'])
                    ->setCellValue('O' . $i, $row['cantidad'])
                    ->setCellValue('P' . $i, $row['costo'])
                    ->setCellValue('Q' . $i, $row['DESCUENTO'])
                    ->setCellValue('R' . $i, $row['VENTANET'])
                    ->setCellValue('S' . $i, $row['utilidad'])
                    ->setCellValue('T' . $i, $row['mes'])
                    ->setCellValue('U' . $i, $row['anio'])
                    ->setCellValue('V' . $i, $row['bodega'])
            ;
            $i++;
        }
    }
}


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Documentos ' . date("Y-m-d") . '.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>