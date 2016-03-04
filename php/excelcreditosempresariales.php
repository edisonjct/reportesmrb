<?php 
$conexion = include('conexion.php');
require_once '../Classes/PHPExcel.php';
$objPHPExcel = new PHPExcel();
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$categoria = $_GET['categoria'];
$tipo = $_GET['tipo'];
$objPHPExcel->
    getProperties()
        ->setCreator("Edison Chulde")
        ->setLastModifiedBy("Edison Chulde")
        ->setTitle("Exportar Creditos Empresaliares")
        ->setSubject("Creditos Empresaliares")
        ->setDescription("Creditos Empresaliares Mr Books");

if ($tipo == '80') {
  $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:V1');
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'REPORTE DE CREDITOS EMPRESALIALES DEL '.$desde.' HASTA '.$hasta);
    $objPHPExcel->getActiveSheet()->setTitle('Creditos Emp '. date("Y-m-d"));
    $objPHPExcel->getActiveSheet()->setCellValue('A2', 'FECHA');
    $objPHPExcel->getActiveSheet()->setCellValue('B2', 'CATEGORIA');
    $objPHPExcel->getActiveSheet()->setCellValue('C2', 'TIPO');
    $objPHPExcel->getActiveSheet()->setCellValue('D2', 'FACTURA');
    $objPHPExcel->getActiveSheet()->setCellValue('E2', 'IMPORTE');
    $objPHPExcel->getActiveSheet()->setCellValue('F2', 'DESCUENTO');
    $objPHPExcel->getActiveSheet()->setCellValue('G2', 'TOTAL');
    $objPHPExcel->getActiveSheet()->setCellValue('H2', 'CEDULA');
    $objPHPExcel->getActiveSheet()->setCellValue('I2', 'NOMBRE');
    $objPHPExcel->getActiveSheet()->setCellValue('J2', 'MATRIZ');
    $objPHPExcel->getActiveSheet()->setCellValue('K2', 'JARDIN');
    $objPHPExcel->getActiveSheet()->setCellValue('L2', 'CONDADO');
    $objPHPExcel->getActiveSheet()->setCellValue('M2', 'SCALA');
    $objPHPExcel->getActiveSheet()->setCellValue('N2', 'SOL');
    $objPHPExcel->getActiveSheet()->setCellValue('O2', 'VILLAGE');
    $objPHPExcel->getActiveSheet()->setCellValue('P2', 'QUICENTRO');
    $objPHPExcel->getActiveSheet()->setCellValue('Q2', 'SAN LUIS');
    $objPHPExcel->getActiveSheet()->setCellValue('R2', 'SAN MARINO');
    $objPHPExcel->getActiveSheet()->setCellValue('S2', 'CUMBAYA');
    $objPHPExcel->getActiveSheet()->setCellValue('T2', 'JUAN LEON MERA');
    $objPHPExcel->getActiveSheet()->setCellValue('U2', 'WEB');
    $objPHPExcel->getActiveSheet()->setCellValue('V2', 'EVENTOS');
    $objPHPExcel->getActiveSheet()->setAutoFilter("A2:V2");
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(10);    
    $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(10);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(10);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(10);

  if ($categoria == 'TODOS') {
    $sql = "SELECT
DATE_FORMAT(d.FECMOV03,'%Y/%m/%d') AS FECHA,
(CASE WHEN (clientes.tipcte01 = '0004') THEN 'EMPLEADO' ELSE 'EX EMPLEADO' END) AS CATEGORIA,
cp.detalle AS TIPO,
d.NOCOMP03 AS FACTURA,
Sum(d.PRECVTA03) AS IMPORTE,
Sum(d.DESCVTA03+d.desctotvta03) AS DESCUENTO,
Sum((((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100)+(d.PRECVTA03-d.DESCVTA03-d.desctotvta03)) AS TOTAL,
c.ruc31 AS CEDULA,
c.nomcte31 AS NOMBRE,
(CASE WHEN (d.bodega = 'CDI') THEN Sum(d.CANTID03)  ELSE '0' END) AS MATRIZ,
(CASE WHEN (d.bodega = 'JARDIN') THEN Sum(d.CANTID03) ELSE '0' END) AS JARDIN,
(CASE WHEN (d.bodega = 'CONDADO') THEN Sum(d.CANTID03)  ELSE '0' END) AS CONDADO,
(CASE WHEN (d.bodega = 'SCALA') THEN Sum(d.CANTID03)  ELSE '0' END) AS SCALA,
(CASE WHEN (d.bodega = 'SOL') THEN Sum(d.CANTID03)  ELSE '0' END) AS SOL,
(CASE WHEN (d.bodega = 'VILLAGE') THEN Sum(d.CANTID03)  ELSE '0' END) AS VILLAGE,
(CASE WHEN (d.bodega = 'QUICENTRO') THEN Sum(d.CANTID03)  ELSE '0' END) AS QUICENTRO,
(CASE WHEN (d.bodega = 'SAN LUIS') THEN Sum(d.CANTID03) ELSE '0' END) AS 'SLUIS',
(CASE WHEN (d.bodega = 'SAN MARINO') THEN Sum(d.CANTID03) ELSE '0' END) AS 'SMARINO',
(CASE WHEN (d.bodega = 'CUMBAYA') THEN Sum(d.CANTID03)  ELSE '0' END) AS CUMBAYA,
(CASE WHEN (d.bodega = 'JUAN LEON MERA') THEN Sum(d.CANTID03) ELSE '0' END) AS 'JLMERA',
(CASE WHEN (d.bodega = 'WEB') THEN Sum(d.CANTID03)  ELSE '0' END) AS WEB,
(CASE WHEN (d.bodega = 'EVENTOS') THEN Sum(d.CANTID03)  ELSE '0' END) AS EVENTOS
FROM
factura_detalle AS d
INNER JOIN factura_cabecera AS c ON d.NOCOMP03 = c.nofact31
INNER JOIN condicion_pago AS cp ON c.condpag31 = cp.id_tipo_pago
INNER JOIN clientes ON c.nocte31 = clientes.codcte01
WHERE
d.TIPOTRA03 = '$tipo' AND
c.cvanulado31 <> '9' AND
c.condpag31 = '1' AND
clientes.tipcte01 IN ('0004','0008') AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
GROUP BY d.NOCOMP03
ORDER BY d.FECMOV03 DESC";
 $resultado = mysql_query ($sql) or die (mysql_error ());
 $registros = mysql_num_rows ($resultado);
 if ($registros > 0) {      
    //SE IMPRIME LAS COLUMNAS DESEADAS
   $i = 3;    
   while ($registro = mysql_fetch_array ($resultado)) {        
      $objPHPExcel->setActiveSheetIndex(0)      
      ->setCellValue('A'.$i,$registro['FECHA'])  
      ->setCellValue('B'.$i,$registro['CATEGORIA'])  
      ->setCellValue('C'.$i,$registro['TIPO'])
      ->setCellValue('D'.$i,$registro['FACTURA'])  
      ->setCellValue('E'.$i,$registro['IMPORTE'])  
      ->setCellValue('F'.$i,$registro['DESCUENTO'])  
      ->setCellValue('G'.$i,$registro['TOTAL'])  
      ->setCellValue('H'.$i,$registro['CEDULA'])  
      ->setCellValue('I'.$i,$registro['NOMBRE'])       
      ->setCellValue('J'.$i,$registro['MATRIZ'])
      ->setCellValue('K'.$i,$registro['JARDIN'])  
      ->setCellValue('L'.$i,$registro['CONDADO'])  
      ->setCellValue('M'.$i,$registro['SCALA'])  
      ->setCellValue('N'.$i,$registro['SOL'])  
      ->setCellValue('O'.$i,$registro['VILLAGE']) 
      ->setCellValue('P'.$i,$registro['QUICENTRO'])  
      ->setCellValue('Q'.$i,$registro['SLUIS'])  
      ->setCellValue('R'.$i,$registro['SMARINO'])  
      ->setCellValue('S'.$i,$registro['CUMBAYA']) 
      ->setCellValue('T'.$i,$registro['JLMERA']) 
      ->setCellValue('U'.$i,$registro['WEB']) 
      ->setCellValue('V'.$i,$registro['EVENTOS']);
      $i++;      
   }
}
  } else {
    $sql = "SELECT
DATE_FORMAT(d.FECMOV03,'%Y/%m/%d') AS FECHA,
(CASE WHEN (clientes.tipcte01 = '0004') THEN 'EMPLEADO' ELSE 'EX EMPLEADO' END) AS CATEGORIA,
cp.detalle AS TIPO,
d.NOCOMP03 AS FACTURA,
Sum(d.PRECVTA03) AS IMPORTE,
Sum(d.DESCVTA03+d.desctotvta03) AS DESCUENTO,
Sum((((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100)+(d.PRECVTA03-d.DESCVTA03-d.desctotvta03)) AS TOTAL,
c.ruc31 AS CEDULA,
c.nomcte31 AS NOMBRE,
(CASE WHEN (d.bodega = 'CDI') THEN Sum(d.CANTID03)  ELSE '0' END) AS MATRIZ,
(CASE WHEN (d.bodega = 'JARDIN') THEN Sum(d.CANTID03) ELSE '0' END) AS JARDIN,
(CASE WHEN (d.bodega = 'CONDADO') THEN Sum(d.CANTID03)  ELSE '0' END) AS CONDADO,
(CASE WHEN (d.bodega = 'SCALA') THEN Sum(d.CANTID03)  ELSE '0' END) AS SCALA,
(CASE WHEN (d.bodega = 'SOL') THEN Sum(d.CANTID03)  ELSE '0' END) AS SOL,
(CASE WHEN (d.bodega = 'VILLAGE') THEN Sum(d.CANTID03)  ELSE '0' END) AS VILLAGE,
(CASE WHEN (d.bodega = 'QUICENTRO') THEN Sum(d.CANTID03)  ELSE '0' END) AS QUICENTRO,
(CASE WHEN (d.bodega = 'SAN LUIS') THEN Sum(d.CANTID03) ELSE '0' END) AS 'SLUIS',
(CASE WHEN (d.bodega = 'SAN MARINO') THEN Sum(d.CANTID03) ELSE '0' END) AS 'SMARINO',
(CASE WHEN (d.bodega = 'CUMBAYA') THEN Sum(d.CANTID03)  ELSE '0' END) AS CUMBAYA,
(CASE WHEN (d.bodega = 'JUAN LEON MERA') THEN Sum(d.CANTID03) ELSE '0' END) AS 'JLMERA',
(CASE WHEN (d.bodega = 'WEB') THEN Sum(d.CANTID03)  ELSE '0' END) AS WEB,
(CASE WHEN (d.bodega = 'EVENTOS') THEN Sum(d.CANTID03)  ELSE '0' END) AS EVENTOS
FROM
factura_detalle AS d
INNER JOIN factura_cabecera AS c ON d.NOCOMP03 = c.nofact31
INNER JOIN condicion_pago AS cp ON c.condpag31 = cp.id_tipo_pago
INNER JOIN clientes ON c.nocte31 = clientes.codcte01
WHERE
d.TIPOTRA03 = '$tipo' AND
c.cvanulado31 <> '9' AND
c.condpag31 = '1' AND
clientes.tipcte01 = '$categoria' AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
GROUP BY d.NOCOMP03
ORDER BY d.FECMOV03 DESC";
 $resultado = mysql_query ($sql) or die (mysql_error ());
 $registros = mysql_num_rows ($resultado);
 if ($registros > 0) {      
    //SE IMPRIME LAS COLUMNAS DESEADAS
   $i = 3;    
   while ($registro = mysql_fetch_array ($resultado)) {        
      $objPHPExcel->setActiveSheetIndex(0)      
      ->setCellValue('A'.$i,$registro['FECHA'])  
      ->setCellValue('B'.$i,$registro['CATEGORIA'])  
      ->setCellValue('C'.$i,$registro['TIPO'])
      ->setCellValue('D'.$i,$registro['FACTURA'])  
      ->setCellValue('E'.$i,$registro['IMPORTE'])  
      ->setCellValue('F'.$i,$registro['DESCUENTO'])  
      ->setCellValue('G'.$i,$registro['TOTAL'])  
      ->setCellValue('H'.$i,$registro['CEDULA'])  
      ->setCellValue('I'.$i,$registro['NOMBRE'])       
      ->setCellValue('J'.$i,$registro['MATRIZ'])
      ->setCellValue('K'.$i,$registro['JARDIN'])  
      ->setCellValue('L'.$i,$registro['CONDADO'])  
      ->setCellValue('M'.$i,$registro['SCALA'])  
      ->setCellValue('N'.$i,$registro['SOL'])  
      ->setCellValue('O'.$i,$registro['VILLAGE']) 
      ->setCellValue('P'.$i,$registro['QUICENTRO'])  
      ->setCellValue('Q'.$i,$registro['SLUIS'])  
      ->setCellValue('R'.$i,$registro['SMARINO'])  
      ->setCellValue('S'.$i,$registro['CUMBAYA']) 
      ->setCellValue('T'.$i,$registro['JLMERA']) 
      ->setCellValue('U'.$i,$registro['WEB']) 
      ->setCellValue('V'.$i,$registro['EVENTOS']);
      $i++;      
   }
}

  }
}

if ($tipo == '22') {

  $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:V1');
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'REPORTE DE CREDITOS EMPRESALIALES DEL '.$desde.' HASTA '.$hasta);
    $objPHPExcel->getActiveSheet()->setTitle('Creditos Emp '. date("Y-m-d"));
    $objPHPExcel->getActiveSheet()->setCellValue('A2', 'FECHA');
    $objPHPExcel->getActiveSheet()->setCellValue('B2', 'CATEGORIA');
    $objPHPExcel->getActiveSheet()->setCellValue('C2', 'NOTA DE CREDITO');
    $objPHPExcel->getActiveSheet()->setCellValue('D2', 'FACTURA APLICA');
    $objPHPExcel->getActiveSheet()->setCellValue('E2', 'IMPORTE');
    $objPHPExcel->getActiveSheet()->setCellValue('F2', 'DESCUENTO');
    $objPHPExcel->getActiveSheet()->setCellValue('G2', 'TOTAL');
    $objPHPExcel->getActiveSheet()->setCellValue('H2', 'CEDULA');
    $objPHPExcel->getActiveSheet()->setCellValue('I2', 'NOMBRE');
    $objPHPExcel->getActiveSheet()->setCellValue('J2', 'MATRIZ');
    $objPHPExcel->getActiveSheet()->setCellValue('K2', 'JARDIN');
    $objPHPExcel->getActiveSheet()->setCellValue('L2', 'CONDADO');
    $objPHPExcel->getActiveSheet()->setCellValue('M2', 'SCALA');
    $objPHPExcel->getActiveSheet()->setCellValue('N2', 'SOL');
    $objPHPExcel->getActiveSheet()->setCellValue('O2', 'VILLAGE');
    $objPHPExcel->getActiveSheet()->setCellValue('P2', 'QUICENTRO');
    $objPHPExcel->getActiveSheet()->setCellValue('Q2', 'SAN LUIS');
    $objPHPExcel->getActiveSheet()->setCellValue('R2', 'SAN MARINO');
    $objPHPExcel->getActiveSheet()->setCellValue('S2', 'CUMBAYA');
    $objPHPExcel->getActiveSheet()->setCellValue('T2', 'JUAN LEON MERA');
    $objPHPExcel->getActiveSheet()->setCellValue('U2', 'WEB');
    $objPHPExcel->getActiveSheet()->setCellValue('V2', 'EVENTOS');
    $objPHPExcel->getActiveSheet()->setAutoFilter("A2:V2");
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(10);    
    $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(10);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(10);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(10);

  if ($categoria == 'TODOS') {
    $sql = "SELECT
DATE_FORMAT(d.FECMOV03,'%Y/%m/%d') AS FECHA,
(CASE WHEN (c.tipcte01 = '0004') THEN 'EMPLEADO' ELSE 'EX EMPLEADO' END) AS CATEGORIA,
d.NOCOMP03 as NCREDITO,
d.NOFACT03 AS FACTURA,
Sum(d.PRECVTA03) AS IMPORTE,
Sum(d.DESCVTA03+d.desctotvta03) AS DESCUENTO,
Sum((((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100)+(d.PRECVTA03-d.DESCVTA03-d.desctotvta03)) AS TOTAL,
c.cascte01 AS CEDULA,
c.nomcte01 AS NOMBRE,
(CASE WHEN (d.bodega = 'CDI') THEN Sum(d.CANTID03)  ELSE '0' END) AS MATRIZ,
(CASE WHEN (d.bodega = 'JARDIN') THEN Sum(d.CANTID03) ELSE '0' END) AS JARDIN,
(CASE WHEN (d.bodega = 'CONDADO') THEN Sum(d.CANTID03)  ELSE '0' END) AS CONDADO,
(CASE WHEN (d.bodega = 'SCALA') THEN Sum(d.CANTID03)  ELSE '0' END) AS SCALA,
(CASE WHEN (d.bodega = 'SOL') THEN Sum(d.CANTID03)  ELSE '0' END) AS SOL,
(CASE WHEN (d.bodega = 'VILLAGE') THEN Sum(d.CANTID03)  ELSE '0' END) AS VILLAGE,
(CASE WHEN (d.bodega = 'QUICENTRO') THEN Sum(d.CANTID03)  ELSE '0' END) AS QUICENTRO,
(CASE WHEN (d.bodega = 'SAN LUIS') THEN Sum(d.CANTID03) ELSE '0' END) AS 'SLUIS',
(CASE WHEN (d.bodega = 'SAN MARINO') THEN Sum(d.CANTID03) ELSE '0' END) AS 'SMARINO',
(CASE WHEN (d.bodega = 'CUMBAYA') THEN Sum(d.CANTID03)  ELSE '0' END) AS CUMBAYA,
(CASE WHEN (d.bodega = 'JUAN LEON MERA') THEN Sum(d.CANTID03) ELSE '0' END) AS 'JLMERA',
(CASE WHEN (d.bodega = 'WEB') THEN Sum(d.CANTID03)  ELSE '0' END) AS WEB,
(CASE WHEN (d.bodega = 'EVENTOS') THEN Sum(d.CANTID03)  ELSE '0' END) AS EVENTOS
FROM
factura_detalle AS d
LEFT JOIN clientes c ON d.nomdest03 = c.codcte01
WHERE d.TIPOTRA03 = '$tipo' AND c.tipcte01 IN ('0004','0008') AND d.cvanulado03 = 'N' AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND 'hasta 23:59:59'
GROUP BY d.NOCOMP03
ORDER BY d.FECMOV03 DESC";
 $resultado = mysql_query ($sql) or die (mysql_error ());
 $registros = mysql_num_rows ($resultado);
 if ($registros > 0) {      
    //SE IMPRIME LAS COLUMNAS DESEADAS
   $i = 3;    
   while ($registro = mysql_fetch_array ($resultado)) {        
      $objPHPExcel->setActiveSheetIndex(0)      
      ->setCellValue('A'.$i,$registro['FECHA'])  
      ->setCellValue('B'.$i,$registro['CATEGORIA'])  
      ->setCellValue('C'.$i,$registro['NCREDITO'])
      ->setCellValue('D'.$i,$registro['FACTURA'])  
      ->setCellValue('E'.$i,$registro['IMPORTE'])  
      ->setCellValue('F'.$i,$registro['DESCUENTO'])  
      ->setCellValue('G'.$i,$registro['TOTAL'])  
      ->setCellValue('H'.$i,$registro['CEDULA'])  
      ->setCellValue('I'.$i,$registro['NOMBRE'])       
      ->setCellValue('J'.$i,$registro['MATRIZ'])
      ->setCellValue('K'.$i,$registro['JARDIN'])  
      ->setCellValue('L'.$i,$registro['CONDADO'])  
      ->setCellValue('M'.$i,$registro['SCALA'])  
      ->setCellValue('N'.$i,$registro['SOL'])  
      ->setCellValue('O'.$i,$registro['VILLAGE']) 
      ->setCellValue('P'.$i,$registro['QUICENTRO'])  
      ->setCellValue('Q'.$i,$registro['SLUIS'])  
      ->setCellValue('R'.$i,$registro['SMARINO'])  
      ->setCellValue('S'.$i,$registro['CUMBAYA']) 
      ->setCellValue('T'.$i,$registro['JLMERA']) 
      ->setCellValue('U'.$i,$registro['WEB']) 
      ->setCellValue('V'.$i,$registro['EVENTOS']);
      $i++;      
   }
}

  } else {
    
    $sql = "SELECT
DATE_FORMAT(d.FECMOV03,'%Y/%m/%d') AS FECHA,
(CASE WHEN (c.tipcte01 = '0004') THEN 'EMPLEADO' ELSE 'EX EMPLEADO' END) AS CATEGORIA,
d.NOCOMP03 as NCREDITO,
d.NOFACT03 AS FACTURA,
Sum(d.PRECVTA03) AS IMPORTE,
Sum(d.DESCVTA03+d.desctotvta03) AS DESCUENTO,
Sum((((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100)+(d.PRECVTA03-d.DESCVTA03-d.desctotvta03)) AS TOTAL,
c.cascte01 AS CEDULA,
c.nomcte01 AS NOMBRE,
(CASE WHEN (d.bodega = 'CDI') THEN Sum(d.CANTID03)  ELSE '0' END) AS MATRIZ,
(CASE WHEN (d.bodega = 'JARDIN') THEN Sum(d.CANTID03) ELSE '0' END) AS JARDIN,
(CASE WHEN (d.bodega = 'CONDADO') THEN Sum(d.CANTID03)  ELSE '0' END) AS CONDADO,
(CASE WHEN (d.bodega = 'SCALA') THEN Sum(d.CANTID03)  ELSE '0' END) AS SCALA,
(CASE WHEN (d.bodega = 'SOL') THEN Sum(d.CANTID03)  ELSE '0' END) AS SOL,
(CASE WHEN (d.bodega = 'VILLAGE') THEN Sum(d.CANTID03)  ELSE '0' END) AS VILLAGE,
(CASE WHEN (d.bodega = 'QUICENTRO') THEN Sum(d.CANTID03)  ELSE '0' END) AS QUICENTRO,
(CASE WHEN (d.bodega = 'SAN LUIS') THEN Sum(d.CANTID03) ELSE '0' END) AS 'SLUIS',
(CASE WHEN (d.bodega = 'SAN MARINO') THEN Sum(d.CANTID03) ELSE '0' END) AS 'SMARINO',
(CASE WHEN (d.bodega = 'CUMBAYA') THEN Sum(d.CANTID03)  ELSE '0' END) AS CUMBAYA,
(CASE WHEN (d.bodega = 'JUAN LEON MERA') THEN Sum(d.CANTID03) ELSE '0' END) AS 'JLMERA',
(CASE WHEN (d.bodega = 'WEB') THEN Sum(d.CANTID03)  ELSE '0' END) AS WEB,
(CASE WHEN (d.bodega = 'EVENTOS') THEN Sum(d.CANTID03)  ELSE '0' END) AS EVENTOS
FROM
factura_detalle AS d
LEFT JOIN clientes c ON d.nomdest03 = c.codcte01
WHERE d.TIPOTRA03 = '$tipo' AND c.tipcte01 = '$categoria' AND d.cvanulado03 = 'N' AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
GROUP BY d.NOCOMP03
ORDER BY d.FECMOV03 DESC";
 $resultado = mysql_query ($sql) or die (mysql_error ());
 $registros = mysql_num_rows ($resultado);
 if ($registros > 0) {      
    //SE IMPRIME LAS COLUMNAS DESEADAS
   $i = 3;    
   while ($registro = mysql_fetch_array ($resultado)) {        
      $objPHPExcel->setActiveSheetIndex(0)      
      ->setCellValue('A'.$i,$registro['FECHA'])  
      ->setCellValue('B'.$i,$registro['CATEGORIA'])  
      ->setCellValue('C'.$i,$registro['NCREDITO'])
      ->setCellValue('D'.$i,$registro['FACTURA'])  
      ->setCellValue('E'.$i,$registro['IMPORTE'])  
      ->setCellValue('F'.$i,$registro['DESCUENTO'])  
      ->setCellValue('G'.$i,$registro['TOTAL'])  
      ->setCellValue('H'.$i,$registro['CEDULA'])  
      ->setCellValue('I'.$i,$registro['NOMBRE'])       
      ->setCellValue('J'.$i,$registro['MATRIZ'])
      ->setCellValue('K'.$i,$registro['JARDIN'])  
      ->setCellValue('L'.$i,$registro['CONDADO'])  
      ->setCellValue('M'.$i,$registro['SCALA'])  
      ->setCellValue('N'.$i,$registro['SOL'])  
      ->setCellValue('O'.$i,$registro['VILLAGE']) 
      ->setCellValue('P'.$i,$registro['QUICENTRO'])  
      ->setCellValue('Q'.$i,$registro['SLUIS'])  
      ->setCellValue('R'.$i,$registro['SMARINO'])  
      ->setCellValue('S'.$i,$registro['CUMBAYA']) 
      ->setCellValue('T'.$i,$registro['JLMERA']) 
      ->setCellValue('U'.$i,$registro['WEB']) 
      ->setCellValue('V'.$i,$registro['EVENTOS']);
      $i++;      
   }
}

  }
}



header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Creditos Emp '. date("Y-m-d").'.xlsx"');
header('Cache-Control: max-age=0');
 
$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save('php://output');
?>