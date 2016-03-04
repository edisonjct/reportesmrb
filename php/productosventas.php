<?php


  $dato = $_GET['dato'];
  $desde = $_GET['desde'];
  $hasta = $_GET['hasta'];

if(strlen($_GET['desde'])>0 and strlen($_GET['hasta'])>0){
	$desde = $_GET['desde'];
	$hasta = $_GET['hasta'];

	$verDesde = date('d/m/Y', strtotime($desde));
	$verHasta = date('d/m/Y', strtotime($hasta));
}else{
	$desde = '1111-01-01';
	$hasta = '9999-12-30';

	$verDesde = '__/__/____';
	$verHasta = '__/__/____';
}

require('../Classes/PHPExcel.php');
require('conexion.php');

$objPHPExcel = new PHPExcel();



$objPHPExcel->getProperties()
        ->setCreator("ingenieroweb.com.co")
        ->setLastModifiedBy("ingenieroweb.com.co")
        ->setTitle("Exportar excel desde mysql")
        ->setSubject("Ejemplo 1")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("ingenieroweb.com.co  con  phpexcel")
        ->setCategory("ciudades");    

$objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'CODIGO');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'TITULO');

$productos = mysql_query("SELECT id_prod,nomb_prod FROM productos WHERE precio_unit = '22' ");    
$i=2;
while($productos2 = mysql_fetch_array($productos)){
	
	$objPHPExcel->setActiveSheetIndex(0)      
      ->setCellValue('A'.$i,$registro['id_prod'])       
      ->setCellValue('B'.$i,$registro['nomb_prod']);
      $i++; 


}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="ejemplo1.xlsx"');
header('Cache-Control: max-age=0');
 
$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save('php://output');

?>