<?php 
session_start(); 
include("conexion.php"); 

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$bodega = $_GET['bodega'];


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
WHERE t.fecha BETWEEN '2015-01-01 00:00:00' AND '2016-03-01 23:59:59'
GROUP BY t.bodega,t.tipo ORDER BY t.grupo,t.tipo DESC"); 
$grupo = '';
?> 
   <table>         
        <tr> 
          <th>Id</th>  
          <th>Nombre</th> 
          <th>Grupo</th> 
          <th>Monto</th> 
           </tr>               
        <?php 
         while($row=mysql_fetch_assoc($resul)) { 
          $grupoant=$grupo;         
          $grupo=$row['bodega']; 
          ?> 
        <?php 
        if($grupoant != $grupo){ 
        ?>          
        <tr class="Row"> 
        <td colspan="4"><strong><?php echo $row["bodega"]; ?></strong></td> 
        </tr>         
        <?php  }  ?> 
      <tr class="Row"> 
          <td> 
            <p align="center"><?php echo $row["tipo"]; ?></p></td>  
          <td> 
            <p align="center"><?php echo $row["documentos"]; ?></p></td>  
          <td> 
            <p align="center"><?php echo $row["libros"]; ?></p></td>  
          <td> 
            <p align="center"><?php echo $row["ventas"]; ?></p></td>  
        </tr> 
        <?php  
                } 
         ?> 
</table> <?php
}
else {

}

}



