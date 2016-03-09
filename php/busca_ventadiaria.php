<?php 
session_start(); 
include("conexion.php"); 

$bodega = $_GET['bodega'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];


if ($desde == false){
  echo '<script type="text/javascript">alert("SELECIONE FECHA DESDE");</script>'; 
} else if($hasta == false) {
  echo '<script type="text/javascript">alert("SELECIONE FECHA HASTA");</script>';
} else {
  //INICIO CODIGO
  if ($bodega == 'TODOS')
  { 
$vaciartabla = mysql_query("DELETE FROM tmpventas");
$ventas = mysql_query("INSERT INTO  tmpventas(tipo,bodega,documento,numdoc,numlibros,venta,costo,fecha,grupo) SELECT
d.TIPOTRA03 AS tipo,
d.bodega AS bodega,
d.NOCOMP03 AS docuemnto,
count(distinct d.NOCOMP03) AS FACTURAS,
Sum(d.CANTID03) AS LIBROS,
round(sum(((d.PRECVTA03 - d.DESCVTA03) - d.desctotvta03)),2) AS VENTA,
Sum(d.VALOR03) AS COSTO,
d.FECMOV03 AS fecha,
bodegas.orden as grupo
FROM
factura_detalle AS d
INNER JOIN factura_cabecera ON d.NOCOMP03 = factura_cabecera.nofact31
INNER JOIN bodegas ON d.bodega = bodegas.nombre
where ((d.TIPOTRA03 = '80') and (factura_cabecera.cvanulado31 <> '9')) AND 
d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
group by d.NOCOMP03,d.bodega");
$devolucion = mysql_query("INSERT INTO  tmpventas(tipo,bodega,documento,numdoc,numlibros,venta,costo,fecha,grupo) SELECT
d.TIPOTRA03 AS tipo,
d.bodega AS bodega,
d.NOCOMP03 AS docuemnto,
COUNT(DISTINCT d.NOCOMP03) AS NOTAS,
Sum(d.CANTID03) AS LIBROS,
ROUND((SUM(PRECVTA03-DESCVTA03-desctotvta03)),2) AS VENTA,
Sum(d.VALOR03) AS COSTO,
d.FECMOV03 AS fecha,
bodegas.orden as grupo
FROM
factura_detalle AS d
INNER JOIN bodegas ON d.bodega = bodegas.nombre
WHERE
d.TIPOTRA03 = '22' AND d.cvanulado03 = 'N' AND
d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
group by d.NOCOMP03,d.bodega");

$resul= mysql_query("SELECT
CASE WHEN t.tipo = '80' THEN 'FACTURAS' ELSE 'DEVOLUCION' END AS tipo,
t.bodega as bodega,
sum(t.numdoc) as documentos,
sum(t.numlibros) as libros,
sum(t.venta) as ventas,
sum(t.costo) as costos,
sum(t.venta) - sum(t.costo) as margen,
t.grupo as grupo
FROM
tmpventas t
WHERE t.fecha BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
GROUP BY t.bodega,t.tipo ORDER BY t.grupo,t.tipo DESC"); 

$grupo = '';

echo '<table class="table table-striped table-condensed table-hover">         
        <tr> 
          <th>BODEGA</th>  
          <th>TIPO</th> 
          <th># DOCUEMENTOS</th> 
          <th>LIBROS</th> 
          <th>VENTA</th> 
          <th>COSTO</th> 
          <th>MARGEN</th> 
           </tr>';
           while($row=mysql_fetch_assoc($resul)) { 
                $grupoant=$grupo;         
                $grupo=$row['bodega'];
                if($grupoant != $grupo){
                    echo '<tr><th colspan="7">'.$row["bodega"].'</th></tr>';
                }
                echo '<tr>
                    <td></td>
                    <td>'.$row["tipo"].'</td>
                    <td>'.number_format($row["documentos"]).'</td>
                    <td>'.number_format($row["libros"]).'</td>
                    <td>'.number_format($row["ventas"], 2, '.',',').'</td>
                    <td>'.number_format($row["costos"], 2, '.',',').'</td>
                    <td>'.number_format($row["margen"], 2, '.',',').'</td>
                </tr>';   
           }
           echo '<tr>
                    <th></th>
                    <th>TOTAL</th>
                    <th>TOTAL</th>
                    <th>TOTAL</th>
                    <th>TOTAL</th>
                    <th>TOTAL</th>
                    <th>TOTAL</th>
                </tr>';         
          
}
else {

$vaciartabla = mysql_query("DELETE FROM tmpventas");
$ventas = mysql_query("INSERT INTO  tmpventas(tipo,bodega,documento,numdoc,numlibros,venta,costo,fecha,grupo) SELECT
d.TIPOTRA03 AS tipo,
d.bodega AS bodega,
d.NOCOMP03 AS docuemnto,
count(distinct d.NOCOMP03) AS FACTURAS,
Sum(d.CANTID03) AS LIBROS,
round(sum(((d.PRECVTA03 - d.DESCVTA03) - d.desctotvta03)),2) AS VENTA,
Sum(d.VALOR03) AS COSTO,
d.FECMOV03 AS fecha,
bodegas.orden as grupo
FROM
factura_detalle AS d
INNER JOIN factura_cabecera ON d.NOCOMP03 = factura_cabecera.nofact31
INNER JOIN bodegas ON d.bodega = bodegas.nombre
where ((d.TIPOTRA03 = '80') and (factura_cabecera.cvanulado31 <> '9')) AND 
d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND d.bodega IN ('$bodega')
group by d.NOCOMP03,d.bodega");
$devolucion = mysql_query("INSERT INTO  tmpventas(tipo,bodega,documento,numdoc,numlibros,venta,costo,fecha,grupo) SELECT
d.TIPOTRA03 AS tipo,
d.bodega AS bodega,
d.NOCOMP03 AS docuemnto,
COUNT(DISTINCT d.NOCOMP03) AS NOTAS,
Sum(d.CANTID03) AS LIBROS,
ROUND((SUM(PRECVTA03-DESCVTA03-desctotvta03)),2) AS VENTA,
Sum(d.VALOR03) AS COSTO,
d.FECMOV03 AS fecha,
bodegas.orden as grupo
FROM
factura_detalle AS d
INNER JOIN bodegas ON d.bodega = bodegas.nombre
WHERE
d.TIPOTRA03 = '22' AND d.cvanulado03 = 'N' AND
d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND d.bodega IN ('$bodega')
group by d.NOCOMP03,d.bodega");

$resul= mysql_query("SELECT
CASE WHEN t.tipo = '80' THEN 'FACTURAS' ELSE 'DEVOLUCION' END AS tipo,
t.bodega as bodega,
sum(t.numdoc) as documentos,
sum(t.numlibros) as libros,
sum(t.venta) as ventas,
sum(t.costo) as costos,
sum(t.venta) - sum(t.costo) as margen,
t.grupo as grupo
FROM
tmpventas t
WHERE t.fecha BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND t.bodega IN ('$bodega')
GROUP BY t.bodega,t.tipo ORDER BY t.grupo,t.tipo DESC"); 

$grupo = '';

echo '<table class="table table-striped table-condensed table-hover">         
        <tr> 
          <th>BODEGA</th>  
          <th>TIPO</th> 
          <th># DOCUEMENTOS</th> 
          <th>LIBROS</th> 
          <th>VENTA</th> 
          <th>COSTO</th> 
          <th>MARGEN</th> 
           </tr>';
           while($row=mysql_fetch_assoc($resul)) { 
                $grupoant=$grupo;         
                $grupo=$row['bodega'];
                if($grupoant != $grupo){
                    echo '<tr><th colspan="7">'.$row["bodega"].'</th></tr>';
                }
                echo '<tr>
                    <td></td>
                    <td>'.$row["tipo"].'</td>
                    <td>'.number_format($row["documentos"]).'</td>
                    <td>'.number_format($row["libros"]).'</td>
                    <td>'.number_format($row["ventas"], 2, '.',',').'</td>
                    <td>'.number_format($row["costos"], 2, '.',',').'</td>
                    <td>'.number_format($row["margen"], 2, '.',',').'</td>
                </tr>';   
           }
           echo '<tr>
                    <th></th>
                    <th>TOTAL</th>
                    <th>TOTAL</th>
                    <th>TOTAL</th>
                    <th>TOTAL</th>
                    <th>TOTAL</th>
                    <th>TOTAL</th>
                </tr>';  

  /*echo '<div class="alert alert-danger">
  <strong>NO SE ENCONTRARON RESULTADOS</strong>
</div>';*/
}
}
?> 