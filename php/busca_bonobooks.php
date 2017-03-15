<?php

include('conexion.php');

$datos = $_GET['datos'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$op1 = $_GET['op1'];
$op2 = $_GET['op2'];
$op3 = $_GET['op3'];

switch ($op1) {
    case 'true':
        $registro = "SELECT m.codbar01 as codigo,
        m.desprod01 as titulo,
        sum(CANTID03) as cantidad
        from factura_detalle d
        INNER JOIN maepro as m ON m.codprod01 = d.CODPROD03 
        WHERE TIPOTRA03 = '80' AND CODPROD03 IN ($datos) 
        AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
        GROUP BY CODPROD03";
        $resultado = mysql_query($registro, $conexion);
        echo '<table class="table table-striped table-condensed table-hover" >
        <tr>
        <th>Codigo</th>
        <th>Descripcions</th>
        <th>Cantidad</th>         
        </tr>';
        if (mysql_num_rows($resultado) > 0) {
            while ($row = mysql_fetch_array($resultado)) {
                echo '<tr> 
                    <td>' . $row['codigo'] . '</td>
                    <td>' . $row['titulo'] . '</td>
                    <td>' . $row['cantidad'] . '</td>                
                </tr>';
            }
        } else {
            echo '<tr>
		<td colspan="3">No se encontraron resultados</td>
	</tr>';
        }
        echo '</table>';
        break;
    case 'false':

        break;
}
switch ($op2) {
    case 'true':
        $registro = "SELECT 
        m.codbar01 as codigo,
        m.desprod01 as titulo,
        b.nombre as bodega,
        YEAR(d.FECMOV03) as anio,
        CASE WHEN MONTH(d.FECMOV03) = 1 THEN 'Enero'
        WHEN MONTH(d.FECMOV03) = 2 THEN 'Febrero'
        WHEN MONTH(d.FECMOV03) = 3 THEN 'Marzo'
        WHEN MONTH(d.FECMOV03) = 4 THEN 'Abril'
        WHEN MONTH(d.FECMOV03) = 5 THEN 'Mayo'
        WHEN MONTH(d.FECMOV03) = 6 THEN 'Junio'
        WHEN MONTH(d.FECMOV03) = 7 THEN 'Julio'
        WHEN MONTH(d.FECMOV03) = 8 THEN 'Agosto'
        WHEN MONTH(d.FECMOV03) = 9 THEN 'Septiembre'
        WHEN MONTH(d.FECMOV03) = 10 THEN 'Octubre'
        WHEN MONTH(d.FECMOV03) = 11 THEN 'Noviembre'
        WHEN MONTH(d.FECMOV03) = 12 THEN 'Diciembre'
        ELSE 'esto no es un mes' END AS MES,
        sum(CANTID03) as cantidad
        from factura_detalle d
        INNER JOIN maepro as m ON m.codprod01 = d.CODPROD03
        INNER JOIN bodegas as b ON b.cod_local = d.bodega
        WHERE TIPOTRA03 = '80' AND CODPROD03 IN ($datos) 
        AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
        GROUP BY d.CODPROD03,d.bodega,YEAR(d.FECMOV03),MONTH(d.FECMOV03)
        ORDER BY d.CODPROD03,d.bodega,d.FECMOV03 ASC";
        $resultado = mysql_query($registro, $conexion);
        echo '<table class="table table-striped table-condensed table-hover" >
        <tr>
        <th>Codigo</th>
        <th>Descripcions</th>
        <th>Bodega</th>  
        <th>Año</th> 
        <th>Mes</th>  
        <th>Cantidad</th>  
        </tr>';
        if (mysql_num_rows($resultado) > 0) {
            while ($row = mysql_fetch_array($resultado)) {
                echo '<tr> 
                    <td>' . $row['codigo'] . '</td>
                    <td>' . $row['titulo'] . '</td>
                    <td>' . $row['bodega'] . '</td>
                    <td>' . $row['anio'] . '</td>
                    <td>' . $row['MES'] . '</td>
                    <td>' . $row['cantidad'] . '</td>
                </tr>';
            }
        } else {
            echo '<tr>
		<td colspan="6">No se encontraron resultados</td>
	</tr>';
        }
        echo '</table>';
        break;
    case 'false':

        break;
}
switch ($op3) {
    case 'true':
        $registro = "SELECT 
        m.codbar01 as codigo,
        m.desprod01 as titulo,
        YEAR(d.FECMOV03) as anio,
        CASE WHEN MONTH(d.FECMOV03) = 1 THEN 'Enero'
        WHEN MONTH(d.FECMOV03) = 2 THEN 'Febrero'
        WHEN MONTH(d.FECMOV03) = 3 THEN 'Marzo'
        WHEN MONTH(d.FECMOV03) = 4 THEN 'Abril'
        WHEN MONTH(d.FECMOV03) = 5 THEN 'Mayo'
        WHEN MONTH(d.FECMOV03) = 6 THEN 'Junio'
        WHEN MONTH(d.FECMOV03) = 7 THEN 'Julio'
        WHEN MONTH(d.FECMOV03) = 8 THEN 'Agosto'
        WHEN MONTH(d.FECMOV03) = 9 THEN 'Septiembre'
        WHEN MONTH(d.FECMOV03) = 10 THEN 'Octubre'
        WHEN MONTH(d.FECMOV03) = 11 THEN 'Noviembre'
        WHEN MONTH(d.FECMOV03) = 12 THEN 'Diciembre'
        ELSE 'esto no es un mes' END AS MES,
        sum(CANTID03) as cantidad
        from factura_detalle d
        INNER JOIN maepro as m ON m.codprod01 = d.CODPROD03 
        WHERE TIPOTRA03 = '80' AND CODPROD03 IN ($datos) 
        AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
        GROUP BY CODPROD03,YEAR(d.FECMOV03),MONTH(d.FECMOV03)
        ORDER BY d.FECMOV03 ASC";
        $resultado = mysql_query($registro, $conexion);
        echo '<table class="table table-striped table-condensed table-hover" >
        <tr>
        <th>Codigo</th>
        <th>Descripcions</th> 
        <th>Año</th> 
        <th>Mes</th>  
        <th>Cantidad</th>  
        </tr>';
        if (mysql_num_rows($resultado) > 0) {
            while ($row = mysql_fetch_array($resultado)) {
                echo '<tr> 
                    <td>' . $row['codigo'] . '</td>
                    <td>' . $row['titulo'] . '</td>
                    <td>' . $row['anio'] . '</td>
                    <td>' . $row['MES'] . '</td>
                    <td>' . $row['cantidad'] . '</td>
                </tr>';
            }
        } else {
            echo '<tr>
		<td colspan="5">No se encontraron resultados</td>
	</tr>';
        }
        echo '</table>';
        break;
    case 'false':

        break;
}




