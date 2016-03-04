<?php
include('conexion.php');

$factura = $_GET['factura'];
$bodega = $_GET['bodega'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];

$total_facturas = 0;
$total_vbta = 0;
$total_descuento = 0;
$total_vnta = 0;
$total_iva = 0;
$total = 0;


//EJECUTAMOS LA CONSULTA DE BUSQUEDA
// SI EL CAMPO DE FACTURAS ESTA LLENO
if ($factura == true) {
  if ($bodega == 'TODOS') {
    // BUSCA EN TODAS LAS BODEGAS LA FACTURA INFRESADA
    // INICIO CONSULTA  
$registro = mysql_query("SELECT
DATE_FORMAT(d.FECMOV03,'%Y/%m/%d') AS FECHA,
d.NOCOMP03 AS FACTURA,
Sum(d.PRECVTA03) AS VENTABTA,
Sum(d.DESCVTA03+d.desctotvta03) AS DESCUENTO,
Sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03) AS VENTANET,
ROUND(Sum(((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100),2) AS IVA,
(Sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03))+(ROUND(Sum(((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100),2)) AS TOTAL,
c.ruc31 AS CEDULA,
c.nomcte31 AS NOMBRE,
d.bodega as bodega,
usuario.Nombre AS CAJERO
FROM
factura_detalle AS d
LEFT JOIN factura_cabecera AS c ON d.NOCOMP03 = c.nofact31
LEFT JOIN clientes ON c.nocte31 = clientes.codcte01
LEFT JOIN usuario ON c.UID = usuario.id
WHERE
d.TIPOTRA03 = '80' AND
c.cvanulado31 <> '9' AND
d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND d.NOCOMP03 = '$factura'
GROUP BY d.NOCOMP03
ORDER BY d.FECMOV03 DESC");
echo '<table class="table table-striped table-condensed table-hover">
          <tr>
         <th width="20">FECHA</th>
          <th width="300">FACTURA</th>
          <th width="150">VENTABTA</th>
          <th width="150">DESCUENTO</th>  
          <th width="150">VENTANETA</th>
          <th width="50">IVA</th>
          <th width="100">TOTAL</th>
          <th width="100">CEDULA</th>
          <th width="350">NOMBRE</th>
          <th width="150">BODEGA</th> 
          <th width="300">CAJERO</th> 
          <th></th>
            </tr>';  
if(mysql_num_rows($registro)>0){
  while($registro2 = mysql_fetch_array($registro)){   
    $total_facturas = $total_facturas + 1;
    $total_vbta = $total_vbta + $registro2['VENTABTA'];
    $total_descuento = $total_descuento + $registro2['DESCUENTO'];
    $total_vnta = $total_vnta + $registro2['VENTANET'];
    $total_iva = $total_iva + $registro2['IVA'];
    $total = $total + $registro2['TOTAL'];
    echo '<tr>
        <td><h6>'.$registro2['FECHA'].'</h6></td>
        <td><h6>'.$registro2['FACTURA'].'</h6></td>     
        <td><h6>'.number_format($registro2['VENTABTA'], 2, '.',',').'</h6></td>
        <td><h6>'.number_format($registro2['DESCUENTO'], 2, '.',',').'</h6></td>
        <td><h6><b>'.number_format($registro2['VENTANET'], 2, '.',',').'</b></h6></td>
        <td><h6><b>'.number_format($registro2['IVA'], 2, '.',',').'</b></h6></td>
        <td><h6><b>'.number_format($registro2['TOTAL'], 2, '.',',').'</b></h6></td>
        <td><h6>'.$registro2['CEDULA'].'</h6></td>
        <td><h6>'.$registro2['NOMBRE'].'</h6></td>
        <td><h6>'.$registro2['bodega'].'</h6></td>              
        <td><h6>'.$registro2['CAJERO'].'</h6></td>  
        <td width="50"><a href="../php/facturaspdf.php?fac='.$registro2['FACTURA'].'&bodega='.$registro2['bodega'].'" class="btn btn-danger">PDF</a></td>       
        </tr>';
  }
  echo '<tr>
        <th>TOTAL</th>
        <th>'.number_format($total_facturas, 0, '.',',').'</th>    
        <th>'.number_format($total_vbta, 2, '.',',').'</th>
        <th>'.number_format($total_descuento, 2, '.',',').'</th>
        <th>'.number_format($total_vnta, 2, '.',',').'</th>
        <th>'.number_format($total_iva, 2, '.',',').'</th>
        <th>'.number_format($total, 2, '.',',').'</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        </tr>';
}else{
  echo '<tr>
        <td colspan="6">No se encontraron resultados</td>
      </tr>';
}
echo '</table>';
// FIN CONSULTA
    // FIN BUSQUEDA
  }
  else
  {
    // BUSCA EN BODEGA SELECIONADA LA FACTURA INGRESADA
    // INICIO CONSULTA  
$registro = mysql_query("SELECT
DATE_FORMAT(d.FECMOV03,'%Y/%m/%d') AS FECHA,
d.NOCOMP03 AS FACTURA,
Sum(d.PRECVTA03) AS VENTABTA,
Sum(d.DESCVTA03+d.desctotvta03) AS DESCUENTO,
Sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03) AS VENTANET,
ROUND(Sum(((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100),2) AS IVA,
(Sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03))+(ROUND(Sum(((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100),2)) AS TOTAL,
c.ruc31 AS CEDULA,
c.nomcte31 AS NOMBRE,
d.bodega as bodega,
usuario.Nombre AS CAJERO
FROM
factura_detalle AS d
LEFT JOIN factura_cabecera AS c ON d.NOCOMP03 = c.nofact31
LEFT JOIN clientes ON c.nocte31 = clientes.codcte01
LEFT JOIN usuario ON c.UID = usuario.id
WHERE
d.TIPOTRA03 = '80' AND
c.cvanulado31 <> '9' AND
d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND d.bodega = '$bodega' AND d.NOCOMP03 = '$factura'
GROUP BY d.NOCOMP03
ORDER BY d.FECMOV03 DESC");
echo '<table class="table table-striped table-condensed table-hover">
          <tr>
          <th width="20">FECHA</th>
          <th width="300">FACTURA</th>
          <th width="150">VENTABTA</th>
          <th width="150">DESCUENTO</th>  
          <th width="150">VENTANETA</th>
          <th width="50">IVA</th>
          <th width="100">TOTAL</th>
          <th width="100">CEDULA</th>
          <th width="350">NOMBRE</th>
          <th width="150">BODEGA</th> 
          <th width="300">CAJERO</th> 
          <th></th>
            </tr>';
if(mysql_num_rows($registro)>0){
 
  while($registro2 = mysql_fetch_array($registro)){
     $total_facturas = $total_facturas + 1;
    $total_vbta = $total_vbta + $registro2['VENTABTA'];
    $total_descuento = $total_descuento + $registro2['DESCUENTO'];
    $total_vnta = $total_vnta + $registro2['VENTANET'];
    $total_iva = $total_iva + $registro2['IVA'];
    $total = $total + $registro2['TOTAL'];
    echo '<tr>
        <td><h6>'.$registro2['FECHA'].'</h6></td>
        <td><h6>'.$registro2['FACTURA'].'</h6></td>     
        <td><h6>'.number_format($registro2['VENTABTA'], 2, '.',',').'</h6></td>
        <td><h6>'.number_format($registro2['DESCUENTO'], 2, '.',',').'</h6></td>
        <td><h6><b>'.number_format($registro2['VENTANET'], 2, '.',',').'</b></h6></td>
        <td><h6><b>'.number_format($registro2['IVA'], 2, '.',',').'</b></h6></td>
        <td><h6><b>'.number_format($registro2['TOTAL'], 2, '.',',').'</b></h6></td>
        <td><h6>'.$registro2['CEDULA'].'</h6></td>
        <td><h6>'.$registro2['NOMBRE'].'</h6></td>
        <td><h6>'.$registro2['bodega'].'</h6></td>              
        <td><h6>'.$registro2['CAJERO'].'</h6></td>  
        <td width="50"><a href="../php/facturaspdf.php?fac='.$registro2['FACTURA'].'&bodega='.$registro2['bodega'].'" class="btn btn-danger">PDF</a></td>       
        </tr>';
  }
  echo '<tr>
        <th>TOTAL</th>
        <th>'.number_format($total_facturas, 0, '.',',').'</th>    
        <th>'.number_format($total_vbta, 2, '.',',').'</th>
        <th>'.number_format($total_descuento, 2, '.',',').'</th>
        <th>'.number_format($total_vnta, 2, '.',',').'</th>
        <th>'.number_format($total_iva, 2, '.',',').'</th>
        <th>'.number_format($total, 2, '.',',').'</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        </tr>';
}else{
  echo '<tr>
        <td colspan="6">No se encontraron resultados</td>
      </tr>';
}
echo '</table>';
// FIN CONSULTA
    // FIN BUSQUEDA
  }
}
else {
  if ($bodega == 'TODOS') {
    // BUSCA TODAS LAS FACTURAS EN TODAS LAS BODEGAS
   // INICIO CONSULTA     
$registro = mysql_query("SELECT
DATE_FORMAT(d.FECMOV03,'%Y/%m/%d') AS FECHA,
d.NOCOMP03 AS FACTURA,
Sum(d.PRECVTA03) AS VENTABTA,
Sum(d.DESCVTA03+d.desctotvta03) AS DESCUENTO,
Sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03) AS VENTANET,
ROUND(Sum(((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100),2) AS IVA,
(Sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03))+(ROUND(Sum(((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100),2)) AS TOTAL,
c.ruc31 AS CEDULA,
c.nomcte31 AS NOMBRE,
d.bodega as bodega,
usuario.Nombre AS CAJERO
FROM
factura_detalle AS d
LEFT JOIN factura_cabecera AS c ON d.NOCOMP03 = c.nofact31
LEFT JOIN clientes ON c.nocte31 = clientes.codcte01
LEFT JOIN usuario ON c.UID = usuario.id
WHERE
d.TIPOTRA03 = '80' AND
c.cvanulado31 <> '9' AND
d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
GROUP BY d.NOCOMP03
ORDER BY d.FECMOV03 DESC");
echo '<table class="table table-striped table-condensed table-hover">
          <tr>
          <th width="20">FECHA</th>
          <th width="300">FACTURA</th>
          <th width="150">VENTABTA</th>
          <th width="150">DESCUENTO</th>  
          <th width="150">VENTANETA</th>
          <th width="50">IVA</th>
          <th width="100">TOTAL</th>
          <th width="100">CEDULA</th>
          <th width="350">NOMBRE</th>
          <th width="150">BODEGA</th> 
          <th width="300">CAJERO</th> 
          <th></th>
            </tr>';
if(mysql_num_rows($registro)>0){  
  while($registro2 = mysql_fetch_array($registro)){
     $total_facturas = $total_facturas + 1;
    $total_vbta = $total_vbta + $registro2['VENTABTA'];
    $total_descuento = $total_descuento + $registro2['DESCUENTO'];
    $total_vnta = $total_vnta + $registro2['VENTANET'];
    $total_iva = $total_iva + $registro2['IVA'];
    $total = $total + $registro2['TOTAL'];
    echo '<tr>
        <td><h6>'.$registro2['FECHA'].'</h6></td>
        <td><h6>'.$registro2['FACTURA'].'</h6></td>     
        <td><h6>'.number_format($registro2['VENTABTA'], 2, '.',',').'</h6></td>
        <td><h6>'.number_format($registro2['DESCUENTO'], 2, '.',',').'</h6></td>
        <td><h6><b>'.number_format($registro2['VENTANET'], 2, '.',',').'</b></h6></td>
        <td><h6><b>'.number_format($registro2['IVA'], 2, '.',',').'</b></h6></td>
        <td><h6><b>'.number_format($registro2['TOTAL'], 2, '.',',').'</b></h6></td>
        <td><h6>'.$registro2['CEDULA'].'</h6></td>
        <td><h6>'.$registro2['NOMBRE'].'</h6></td>
        <td><h6>'.$registro2['bodega'].'</h6></td>              
        <td><h6>'.$registro2['CAJERO'].'</h6></td>  
        <td width="50"><a href="../php/facturaspdf.php?fac='.$registro2['FACTURA'].'&bodega='.$registro2['bodega'].'" class="btn btn-danger">PDF</a></td>       
        </tr>';
  }
  echo '<tr>
        <th>TOTAL</th>
        <th>'.number_format($total_facturas, 0, '.',',').'</th>    
        <th>'.number_format($total_vbta, 2, '.',',').'</th>
        <th>'.number_format($total_descuento, 2, '.',',').'</th>
        <th>'.number_format($total_vnta, 2, '.',',').'</th>
        <th>'.number_format($total_iva, 2, '.',',').'</th>
        <th>'.number_format($total, 2, '.',',').'</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        </tr>';
}else{
  echo '<tr>
        <td colspan="6">No se encontraron resultados</td>
      </tr>';
}
echo '</table>';
// FIN CONSULTA
    // FIN BUSQUEDA
  }
  else
  {
    //BUSCA TODAS LAS FACTURAS EN BODEGA SELECIONADA
    // INICIO CONSULTA  
$registro = mysql_query("SELECT
DATE_FORMAT(d.FECMOV03,'%Y/%m/%d') AS FECHA,
d.NOCOMP03 AS FACTURA,
Sum(d.PRECVTA03) AS VENTABTA,
Sum(d.DESCVTA03+d.desctotvta03) AS DESCUENTO,
Sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03) AS VENTANET,
ROUND(Sum(((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100),2) AS IVA,
(Sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03))+(ROUND(Sum(((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100),2)) AS TOTAL,
c.ruc31 AS CEDULA,
c.nomcte31 AS NOMBRE,
d.bodega as bodega,
usuario.Nombre AS CAJERO
FROM
factura_detalle AS d
LEFT JOIN factura_cabecera AS c ON d.NOCOMP03 = c.nofact31
LEFT JOIN clientes ON c.nocte31 = clientes.codcte01
LEFT JOIN usuario ON c.UID = usuario.id
WHERE
d.TIPOTRA03 = '80' AND
c.cvanulado31 <> '9' AND
d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND d.bodega = '$bodega'
GROUP BY d.NOCOMP03
ORDER BY d.FECMOV03 DESC");
echo '<table class="table table-striped table-condensed table-hover">
          <tr>
         <th width="20">FECHA</th>
          <th width="300">FACTURA</th>
          <th width="150">VENTABTA</th>
          <th width="150">DESCUENTO</th>  
          <th width="150">VENTANETA</th>
          <th width="50">IVA</th>
          <th width="100">TOTAL</th>
          <th width="100">CEDULA</th>
          <th width="350">NOMBRE</th>
          <th width="150">BODEGA</th> 
          <th width="300">CAJERO</th> 
          <th></th>
            </tr>';
if(mysql_num_rows($registro)>0){  
  while($registro2 = mysql_fetch_array($registro)){
     $total_facturas = $total_facturas + 1;
    $total_vbta = $total_vbta + $registro2['VENTABTA'];
    $total_descuento = $total_descuento + $registro2['DESCUENTO'];
    $total_vnta = $total_vnta + $registro2['VENTANET'];
    $total_iva = $total_iva + $registro2['IVA'];
    $total = $total + $registro2['TOTAL'];
    echo '<tr>
        <td><h6>'.$registro2['FECHA'].'</h6></td>
        <td><h6>'.$registro2['FACTURA'].'</h6></td>     
        <td><h6>'.number_format($registro2['VENTABTA'], 2, '.',',').'</h6></td>
        <td><h6>'.number_format($registro2['DESCUENTO'], 2, '.',',').'</h6></td>
        <td><h6><b>'.number_format($registro2['VENTANET'], 2, '.',',').'</b></h6></td>
        <td><h6><b>'.number_format($registro2['IVA'], 2, '.',',').'</b></h6></td>
        <td><h6><b>'.number_format($registro2['TOTAL'], 2, '.',',').'</b></h6></td>
        <td><h6>'.$registro2['CEDULA'].'</h6></td>
        <td><h6>'.$registro2['NOMBRE'].'</h6></td>
        <td><h6>'.$registro2['bodega'].'</h6></td>              
        <td><h6>'.$registro2['CAJERO'].'</h6></td>  
        <td width="50"><a href="../php/facturaspdf.php?fac='.$registro2['FACTURA'].'&bodega='.$registro2['bodega'].'" class="btn btn-danger">PDF</a></td>       
        </tr>';
  }
  echo '<tr>
        <th>TOTAL</th>
        <th>'.number_format($total_facturas, 0, '.',',').'</th>    
        <th>'.number_format($total_vbta, 2, '.',',').'</th>
        <th>'.number_format($total_descuento, 2, '.',',').'</th>
        <th>'.number_format($total_vnta, 2, '.',',').'</th>
        <th>'.number_format($total_iva, 2, '.',',').'</th>
        <th>'.number_format($total, 2, '.',',').'</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        </tr>';
}else{
  echo '<tr>
        <td colspan="6">No se encontraron resultados</td>
      </tr>';
}
echo '</table>';
// FIN CONSULTA
    // FIN BUSQUEDA
  } 
}  
?>