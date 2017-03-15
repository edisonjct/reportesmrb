<?php

session_start();
include("conexion.php");

$bodega = $_GET['bodega'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$subcantidadlibros = 0;
$subcantidaddocumentos = 0;
$subcosto = 0;
$subventa = 0;
$submargen = 0;
$totalcantidadlibros = 0;
$totalcantidaddocumentos = 0;
$totalcosto = 0;
$totalventa = 0;
$totalmargen = 0;

if ($desde == false) {
    echo '<script type="text/javascript">alert("SELECIONE FECHA DESDE");</script>';
} else if ($hasta == false) {
    echo '<script type="text/javascript">alert("SELECIONE FECHA HASTA");</script>';
} else {

    $vaciartabla = mysql_query("DELETE FROM tmpventas");
    $ventas = mysql_query("INSERT INTO  tmpventas(tipo,bodega,documento,numdoc,numlibros,venta,costo,fecha,grupo) 
SELECT
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
INNER JOIN bodegas ON d.bodega = bodegas.cod_local
where ((d.TIPOTRA03 = '80') and (factura_cabecera.cvanulado31 <> '9')) AND 
d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND d.bodega IN ($bodega)
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
INNER JOIN bodegas ON d.bodega = bodegas.cod_local
WHERE
d.TIPOTRA03 = '22' AND d.cvanulado03 = 'N' AND
d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND d.bodega IN ($bodega)
group by d.NOCOMP03,d.bodega");

    $resul = mysql_query("SELECT
CASE WHEN t.tipo = '80' THEN 'FACTURAS' ELSE 'DEVOLUCION' END AS tipo,
bodegas.nombre AS nombodega,
Sum(t.numdoc) AS documentos,
CASE WHEN t.tipo = '80' THEN Sum(t.numlibros) ELSE Sum(t.numlibros) * -1 END AS libros,
CASE WHEN t.tipo = '80' THEN Sum(t.venta) ELSE Sum(t.venta) * -1 END AS ventas,
CASE WHEN t.tipo = '80' THEN Sum(t.costo) ELSE Sum(t.costo) * -1 END AS costos,
CASE WHEN t.tipo = '80' THEN (sum(t.venta) - sum(t.costo)) ELSE (sum(t.venta) - sum(t.costo)) * -1 END AS margen,
t.grupo AS grupo,
t.bodega as codbodega
FROM
tmpventas AS t
INNER JOIN bodegas ON t.bodega = bodegas.cod_local
WHERE t.fecha BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND t.bodega IN ($bodega)
GROUP BY t.bodega,t.tipo
ORDER BY t.grupo,t.tipo DESC");

    $grupo = '';
    $cont = 0;
    while ($row = mysql_fetch_assoc($resul)) {
        $sql_maxGrupos = "SELECT count(DISTINCT(t.tipo)) as max,t.bodega FROM tmpventas AS t WHERE t.fecha BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND t.bodega = '" . $row['codbodega'] . "' GROUP BY t.bodega";
        $result_maxGrupos = mysql_query($sql_maxGrupos);
        $rowsubtotal = mysql_fetch_assoc($result_maxGrupos);
        $maximo_grupos = $rowsubtotal['max'];
        $grupoant = $grupo;
        $grupo = $row['nombodega'];
        $subcantidadlibros = $subcantidadlibros + $row["libros"];
        $subcantidaddocumentos = $subcantidaddocumentos + $row["documentos"];
        $subcosto = $subcosto + $row["costos"];
        $subventa = $subventa + $row["ventas"];
        $submargen = $submargen + $row["margen"];
        $totalcantidadlibros = $totalcantidadlibros + $row["libros"];
        $totalcantidaddocumentos = $totalcantidaddocumentos + $row["documentos"];
        $totalcosto = $totalcosto + $row["costos"];
        $totalventa = $totalventa  + $row["ventas"];
        $totalmargen = $totalmargen + $row["margen"];
        if ($grupoant != $grupo) {
            echo '<table class="table table-striped table-condensed table-hover table-bordered">         
                    <tr> 
                      <th>BODEGA</th>  
                      <th>TIPO</th> 
                      <th># DOCUEMENTOS</th> 
                      <th>LIBROS</th> 
                      <th>VENTA</th> 
                      <th>COSTO</th> 
                      <th>MARGEN</th> 
                    </tr>';
            $cont = 0;
        }
        echo '<tr>
                    <td>' . $row["nombodega"] . '</td>
                    <td>' . $row["tipo"] . '</td>
                    <td>' . number_format($row["documentos"]) . '</td>
                    <td>' . number_format($row["libros"]) . '</td>
                    <td>' . number_format($row["ventas"], 2, '.', ',') . '</td>
                    <td>' . number_format($row["costos"], 2, '.', ',') . '</td>
                    <td>' . number_format($row["margen"], 2, '.', ',') . '</td>
                </tr>';
        $cont = $cont + 1;

        if ($cont == $maximo_grupos) {
            echo '<tr>
                    <th colspan="2">SUBTOTALES</th>
                    <th>' . $subcantidaddocumentos . '</th>
                    <th>' . $subcantidadlibros . '</th>
                    <th>' . number_format($subventa, 2, '.', ',') . '</th>
                    <th>' . number_format($subcosto, 2, '.', ',') . '</th>
                    <th>' . number_format($submargen, 2, '.', ',') . '</th>
                </tr>
                </table>';
            $subcantidadlibros = 0;
            $subcantidaddocumentos = 0;
            $subcosto = 0;
            $subventa = 0;
            $submargen = 0;
        }
       
    }
     echo '<table class="table table-striped table-condensed table-hover table-bordered">         
                    <tr> 
                      <th>BODEGA</th>  
                      <th>TIPO</th> 
                      <th># DOCUEMENTOS</th> 
                      <th>LIBROS</th> 
                      <th>VENTA</th> 
                      <th>COSTO</th> 
                      <th>MARGEN</th> 
                    </tr>';
     echo '<tr>
                    <th colspan="2">TOTALES</th>
                    <th>' . $totalcantidaddocumentos . '</th>
                    <th>' . $totalcantidadlibros . '</th>
                    <th>' . number_format($totalventa, 2, '.', ',') . '</th>
                    <th>' . number_format($totalcosto, 2, '.', ',') . '</th>
                    <th>' . number_format($totalmargen, 2, '.', ',') . '</th>
                </tr></table>';
}