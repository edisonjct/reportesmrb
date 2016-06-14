<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
include("conexion.php");

$codigo = $_GET['codigo'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];

$sql = "SELECT
d.TIPOTRA03 as tipo,
d.NOCOMP03 AS documento,
DATE_FORMAT(d.FECMOV03,'%Y-%m-%d') AS fecha,
d.NOFACT03 AS importacion
FROM
factura_detalle AS d
INNER JOIN maepro AS m ON m.codprod01 = d.CODPROD03
WHERE d.TIPOTRA03 = 30 AND m.codbar01 = '$codigo' 
AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'";

$resul= mysql_query($sql,$conexion);

echo '<table class="table table-striped table-condensed table-hover" id="imp">         
        <tr> 
          <th>DOCUMENTO</th>  
          <th>FECHA</th> 
          <th>IMPORTACION</th> 
      </tr>';

if (mysql_num_rows($resul) > 0) {
    while ($row = mysql_fetch_array($resul)) {
        echo '<tr>
                <td>' . $row['documento'] . '</td>
                <td>' . $row['fecha'] . '</td>
                <td><a href="../php/buscardocumento.php?doc='.$row['documento'].'&tipo='.$row['tipo'].'" target="_blank">' . $row['importacion'] . '</td>
            </tr>';
    }
} else {
    echo '<tr>
        <td colspan="3">No se encontraron resultados</td>
      </tr>';

}
echo '</table>';

mysql_close($conexion);

