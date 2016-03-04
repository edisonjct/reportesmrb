<?php
include('conexion.php');

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$bodega = $_GET['bodega'];



if ($bodega == 'TODOS') { 

$registro = mysql_query(
            "SELECT
d.CODPROD03 as codint,
DATE_FORMAT(c.fecfact31,'%Y-%m-%d') AS fecha,
c.nofact31 AS factura,
c.nomcte31 as cliente,
maepro.codbar01 AS codbar,
maepro.desprod01 AS titulo,
d.bodega AS bodega,
d.CANTID03 AS cantidad,
(d.VALOR03/d.CANTID03) AS costo_uni,
d.VALOR03 AS costo_total,
(d.PRECVTA03/d.CANTID03) AS ventbta_uni,
d.PRECVTA03 AS ventbta_total,
(d.DESCVTA03/d.CANTID03) AS descuentop_uni,
d.DESCVTA03 AS descuentop_total,
((d.PRECVTA03/d.CANTID03)-(d.DESCVTA03/d.CANTID03)) AS ventanet_uni,
(d.PRECVTA03)-(d.DESCVTA03) AS venta_total,
usuario.Nombre AS cajero
FROM
factura_cabecera AS c
INNER JOIN factura_detalle AS d ON d.NOCOMP03 = c.nofact31
LEFT JOIN usuario ON c.UID = usuario.id
INNER JOIN maepro ON d.CODPROD03 = maepro.codprod01
WHERE
c.cvanulado31 = '9' AND
d.TIPOTRA03 = '80' AND
c.fecfact31 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
ORDER BY d.FECMOV03 ASC
");

    echo '<table class="table table-striped table-condensed table-hover">
          <tr>              
          <th width="100">FECHA</th>
          <th width="400">FACTURA</th>
          <th width="100">CLIENTE</th>
          <th width="150">CODIGO</th>
          <th width="50"></th>
          <th width="80">TITULO</th>
          <th width="80">BODEGA</th>
          <th width="100">CANTIDAD</th>  
          <th width="80">COSTO_U</th>           
          <th width="80">COSTO_T</th>   
          <th width="80">V.BTA_U</th>   
          <th width="80">V.BTA_T</th>   
          <th width="80">DES_U</th>   
          <th width="80">DES_T</th>   
          <th width="80">V.NET_U</th>   
          <th width="80">V.NET_T</th>   
          <th width="80">CAJERO</th>   
            </tr>';
    
     $total_items = 0;
    if (mysql_num_rows($registro) > 0) {
        while ($registro2 = mysql_fetch_array($registro)) {
            $total_items = $total_items + 1;
            echo '<tr>
          <td><h6>'.$registro2['fecha'].'</h6></td>
        <td><h6>'.$registro2['factura'].'</h6></td>
        <td><h6>'.$registro2['cliente'].'</h6></td>
        <td><h6>'.$registro2['codbar'].'</h6></td>
        <td width="50"><a href="../php/kardex2.php?bodega='.$registro2['bodega'].'&codigo='.$registro2['codint'].'&desde='.$desde.'&hasta='.$hasta.'" target="_blank">
        <img src="../recursos/kardex.png" height="20" width="20"></a></td>       
        <td><h6>'.$registro2['titulo'].'</h6></td>
        <td><h6>'.$registro2['bodega'].'</h6></td>
        <td><h6>'.number_format($registro2['cantidad'],0,'.',',').'</h6></td>  
        <td><h6>'.number_format($registro2['costo_uni'],2,'.',',').'</h6></td>
        <td><h6>'.number_format($registro2['costo_total'],2,'.',',').'</h6></td>
        <td><h6>'.number_format($registro2['ventbta_uni'],2,'.',',').'</h6></td>
        <td><h6>'.number_format($registro2['ventbta_total'],2,'.',',').'</h6></td>
        <td><h6>'.number_format($registro2['descuentop_uni'],2,'.',',').'</h6></td>
        <td><h6>'.number_format($registro2['descuentop_total'],2,'.',',').'</h6></td>
        <td><h6>'.number_format($registro2['ventanet_uni'],2,'.',',').'</h6></td>
        <td><h6>'.number_format($registro2['venta_total'],2,'.',',').'</h6></td>  
        <td><h6>'.$registro2['cajero'].'</h6></td>
      </tr>';
        }
        echo '<tr>
                  <td colspan="8"> TOTAL PRODUCTOS = ' . number_format($total_items, 2, '.', ',') . '</td>
              </tr>';
    } else {
        echo '<tr>
        <td colspan="6">No se encontraron resultados</td>
      </tr>';
    }
    echo '</table>';
} else {
  //BUSCA TODOS LOS CODIGOS EN TODOS LOS LOCALES

$registro = mysql_query(
            "SELECT
d.CODPROD03 as codint,
DATE_FORMAT(c.fecfact31,'%Y-%m-%d') AS fecha,
c.nofact31 AS factura,
c.nomcte31 as cliente,
maepro.codbar01 AS codbar,
maepro.desprod01 AS titulo,
d.bodega AS bodega,
d.CANTID03 AS cantidad,
(d.VALOR03/d.CANTID03) AS costo_uni,
d.VALOR03 AS costo_total,
(d.PRECVTA03/d.CANTID03) AS ventbta_uni,
d.PRECVTA03 AS ventbta_total,
(d.DESCVTA03/d.CANTID03) AS descuentop_uni,
d.DESCVTA03 AS descuentop_total,
((d.PRECVTA03/d.CANTID03)-(d.DESCVTA03/d.CANTID03)) AS ventanet_uni,
(d.PRECVTA03)-(d.DESCVTA03) AS venta_total,
usuario.Nombre AS cajero
FROM
factura_cabecera AS c
INNER JOIN factura_detalle AS d ON d.NOCOMP03 = c.nofact31
LEFT JOIN usuario ON c.UID = usuario.id
INNER JOIN maepro ON d.CODPROD03 = maepro.codprod01
WHERE
c.cvanulado31 = '9' AND
d.TIPOTRA03 = '80' AND
c.fecfact31 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND d.bodega = '$bodega'
ORDER BY d.FECMOV03 ASC
");

    echo '<table class="table table-striped table-condensed table-hover">
          <tr>              
         <th width="100">FECHA</th>
          <th width="400">FACTURA</th>
          <th width="100">CLIENTE</th>
          <th width="150">CODIGO</th>
          <th width="50"></th>
          <th width="80">TITULO</th>
          <th width="120">BODEGA</th>
          <th width="100">CANTIDAD</th>  
          <th width="80">COSTO_U</th>           
          <th width="80">COSTO_T</th>   
          <th width="80">V.BTA_U</th>   
          <th width="80">V.BTA_T</th>   
          <th width="80">DES_U</th>   
          <th width="80">DES_T</th>   
          <th width="80">V.NET_U</th>   
          <th width="80">V.NET_T</th>   
          <th width="80">CAJERO</th>   
            </tr>';
    
     $total_items = 0;
    if (mysql_num_rows($registro) > 0) {
        while ($registro2 = mysql_fetch_array($registro)) {
            $total_items = $total_items + 1;
            echo '<tr>
          <td><h6>'.$registro2['fecha'].'</h6></td>
        <td><h6>'.$registro2['factura'].'</h6></td>
        <td><h6>'.$registro2['cliente'].'</h6></td>
        <td><h6>'.$registro2['codbar'].'</h6></td>
        <td width="50"><a href="../php/kardex2.php?bodega='.$registro2['bodega'].'&codigo='.$registro2['codint'].'&desde='.$desde.'&hasta='.$hasta.'" target="_blank">
        <img src="../recursos/kardex.png" height="20" width="20"></a></td> 
        <td><h6>'.$registro2['titulo'].'</h6></td>
        <td><h6>'.$registro2['bodega'].'</h6></td>
        <td><h6>'.number_format($registro2['cantidad'],0,'.',',').'</h6></td>  
        <td><h6>'.number_format($registro2['costo_uni'],2,'.',',').'</h6></td>
        <td><h6>'.number_format($registro2['costo_total'],2,'.',',').'</h6></td>
        <td><h6>'.number_format($registro2['ventbta_uni'],2,'.',',').'</h6></td>
        <td><h6>'.number_format($registro2['ventbta_total'],2,'.',',').'</h6></td>
        <td><h6>'.number_format($registro2['descuentop_uni'],2,'.',',').'</h6></td>
        <td><h6>'.number_format($registro2['descuentop_total'],2,'.',',').'</h6></td>
        <td><h6>'.number_format($registro2['ventanet_uni'],2,'.',',').'</h6></td>
        <td><h6>'.number_format($registro2['venta_total'],2,'.',',').'</h6></td>  
        <td><h6>'.$registro2['cajero'].'</h6></td>
      </tr>';
        }
        echo '<tr>
                  <td colspan="8"> TOTAL PRODUCTOS = ' . number_format($total_items, 2, '.', ',') . '</td>
              </tr>';
    } else {
        echo '<tr>
        <td colspan="6">No se encontraron resultados</td>
      </tr>';
    }
    echo '</table>';
}


?>