<?php

include('conexion.php');

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$tipo = $_GET['tipo'];
$grupo = '';

/* switch ($operador) {
  case '1':
  $signo = '>=';
  break;

  case '2':
  $signo = '=';
  break;

  case '3':
  $signo = '<=';
  break;
  } */

$vaciartmpventas = mysql_query("TRUNCATE tmpventastotalprovedor");
$consolidarventas = mysql_query("INSERT INTO tmpventastotalprovedor (codproved,total)
SELECT
provedores.codcte01,
Sum(f.CANTID03)
FROM
factura_detalle AS f
LEFT JOIN maepro AS m ON f.CODPROD03 = m.codprod01
LEFT JOIN factura_cabecera AS fa ON f.NOCOMP03 = fa.nofact31
LEFT JOIN provedores ON m.proved101 = provedores.coddest01
INNER JOIN bodegas ON f.bodega = bodegas.cod_local
WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND bodegas.estado = '1' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
GROUP BY provedores.nomcte01");
$vaciarcompras = mysql_query("TRUNCATE tmpcomprasprovedor");
switch ($tipo) {
    case '0001':
        $importaciones = mysql_query("INSERT INTO tmpcomprasprovedor (tipodoc,compra,fecha,codproved,provedor,cantidad)
        SELECT
        im.TIPOTRA03,
        im.NOFACT03,
        im.FECMOV03,
        provedores.codcte01,
        provedores.nomcte01,
        SUM(im.CANTID03)
        FROM
        factura_detalle AS im
        INNER JOIN maepro ON im.CODPROD03 = maepro.codprod01
        INNER JOIN provedores ON maepro.proved101 = provedores.coddest01
        WHERE im.TIPOTRA03 = '30'  AND im.bodega = '01' AND im.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
        GROUP BY im.NOFACT03,provedores.codcte01
        ORDER BY im.NOFACT03");
        $registro = mysql_query("SELECT
        tmpcomprasprovedor.compra as compra,
        tmpcomprasprovedor.provedor as provedor,
        tmpcomprasprovedor.cantidad as cantidad,
        tmpventastotalprovedor.total as venta,
        CASE WHEN tmpventastotalprovedor.total >= tmpcomprasprovedor.cantidad THEN 100 ELSE ROUND((tmpventastotalprovedor.total * 100)/tmpcomprasprovedor.cantidad) END as porcen,
        CASE WHEN tmpventastotalprovedor.total >= tmpcomprasprovedor.cantidad THEN 0 ELSE 100 - (ROUND((tmpventastotalprovedor.total * 100)/tmpcomprasprovedor.cantidad)) END as dif
        FROM
        tmpcomprasprovedor
        INNER JOIN tmpventastotalprovedor ON tmpventastotalprovedor.codproved = tmpcomprasprovedor.codproved
        WHERE fecha BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'");
        if (mysql_num_rows($registro) > 0) {
            while ($row = mysql_fetch_assoc($registro)) {
                $grupoant = $grupo;
                $grupo = $row['compra'];
                if ($grupoant != $grupo) {
                    echo '<table class="table table-striped table-condensed table-hover table-responsive">';
                    echo '<tr><th colspan="5" class="alert alert-danger">' . $row["compra"] . '</th></tr>';
                    echo '<tr> 
                                <th width="300">PROVEDOR</th>  
                                <th width="100">COMPRA</th> 
                                <th width="100">VENTA</th>
                                <th width="200">%</th>
                              </tr>';
                }
                echo '<tr>                                         
                            <td>' . $row["provedor"] . '</td>
                            <td>' . number_format($row["cantidad"], 0, '.', ',') . '</td>                    
                            <td>' . number_format($row["venta"], 0, '.', ',') . '</td>                
                            <td>
                            <div class="progress">
                            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" style="width:' . number_format($row['porcen'], 0, '.', ',') . '%">
                                    ' . number_format($row['porcen'], 0, '.', ',') . '%
                                </div>        
                                    <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" style="width:' . number_format($row['dif'], 0, '.', ',') . '%">
                                        ' . number_format($row['dif'], 0, '.', ',') . '%
                                    </div>
                                </div>
                            </td>
                    </tr>';
            }
        } else {
            echo '<div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong> No se encontraron Resultados</strong>
                    </div>';
        }
        echo '</table>';
        break;

    case '0002':
        echo "aqui";
        break;

    case '0003':
        echo "aqui 2";
        break;
}      