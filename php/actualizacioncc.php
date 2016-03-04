<?php

include('conexion.php');

$bodega = $_GET['bodega'];

//EJECUTAMOS LA CONSULTA DE BUSQUEDA

if ($bodega == 'TODOS') {
//EJECUTAMOS LA CONSULTA DE BUSQUEDA
    $registro = mysql_query(
            "SELECT
inv.codbar01 as cod,
inv.desprod01 as titulo,
inv.bodega as bodega,
a.nomcta AS activo,
v.nomcta AS venta,
c.nomcta AS costo,
d.nomcta AS descuento,
t.nomcta AS tipo_pro
FROM
INVENTARIO AS inv
LEFT JOIN cc_activo a ON inv.ctain101 = a.ctamaecon
LEFT JOIN cc_venta v ON inv.ctain201 = v.ctamaecon
LEFT JOIN cc_costo c ON inv.ctain301 = c.ctamaecon
LEFT JOIN cc_descuento d ON inv.ctain401 = d.ctamaecon
LEFT JOIN tipo_producto t ON inv.orden01 = t.ctamaecon
WHERE inv.prodsinsdo01 = 'S' AND 
(inv.ctain101 = '' OR inv.ctain201 = '' OR inv.ctain301 = '' OR inv.ctain401 = '')
ORDER BY inv.bodega");

    echo '<table class="table table-striped table-condensed table-hover">
        	<tr>
            	<th width="200">BODEGA</th>
			    <th width="200">CODIGO</th>
			    <th width="200">TITULO</th>
			    <th width="200">ACTIVO</th>
                            <th width="200">VENTA</th>
                            <th width="200">COSTO</th>
                            <th width="200">DESCUENTO</th>
                            <th width="200">TIPO_PRODUCTO</th>
            </tr>';
    
     $total_items = 0;
    if (mysql_num_rows($registro) > 0) {
        while ($registro2 = mysql_fetch_array($registro)) {
            $total_items = $total_items + 1;
            echo '<tr>
				<td><h6>' . $registro2['bodega'] . '</h6></td>
                                <td><h6>' . $registro2['cod'] . '</h6></td>
                                <td><h6>' . $registro2['titulo'] . '</h6></td>
                                <td><h6>' . $registro2['activo'] . '</h6></td>
                                <td><h6>' . $registro2['venta'] . '</h6></td>
                                <td><h6>' . $registro2['costo'] . '</h6></td>
                                <td><h6>' . $registro2['descuento'] . '</h6></td>
                                <td><h6>' . $registro2['tipo_pro'] . '</h6></td>
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

    $registro = mysql_query(
            "SELECT
inv.codbar01 as cod,
inv.desprod01 as titulo,
inv.bodega as bodega,
a.nomcta AS activo,
v.nomcta AS venta,
c.nomcta AS costo,
d.nomcta AS descuento,
t.nomcta AS tipo_pro
FROM
INVENTARIO AS inv
LEFT JOIN cc_activo a ON inv.ctain101 = a.ctamaecon
LEFT JOIN cc_venta v ON inv.ctain201 = v.ctamaecon
LEFT JOIN cc_costo c ON inv.ctain301 = c.ctamaecon
LEFT JOIN cc_descuento d ON inv.ctain401 = d.ctamaecon
LEFT JOIN tipo_producto t ON inv.orden01 = t.ctamaecon
WHERE inv.prodsinsdo01 = 'S' AND inv.bodega = '$bodega' AND
(inv.ctain101 = '' OR inv.ctain201 = '' OR inv.ctain301 = '' OR inv.ctain401 = '')
ORDER BY inv.bodega");

    echo '<table class="table table-striped table-condensed table-hover">
        	<tr>
                            <th width="200">BODEGA</th>
			    <th width="200">CODIGO</th>
			    <th width="200">TITULO</th>
			    <th width="200">ACTIVO</th>
                            <th width="200">VENTA</th>
                            <th width="200">COSTO</th>
                            <th width="200">DESCUENTO</th>
                            <th width="200">TIPO_PRODUCTO</th>
            </tr>';

    $total_items = 0;

    if (mysql_num_rows($registro) > 0) {
        while ($registro2 = mysql_fetch_array($registro)) {
            $total_items = $total_items + 1;
            echo '<tr>                
				<td><h6>' . $registro2['bodega'] . '</h6></td>
                                <td><h6>' . $registro2['cod'] . '</h6></td>
                                <td><h6>' . $registro2['titulo'] . '</h6></td>
                                <td><h6>' . $registro2['activo'] . '</h6></td>
                                <td><h6>' . $registro2['venta'] . '</h6></td>
                                <td><h6>' . $registro2['costo'] . '</h6></td>
                                <td><h6>' . $registro2['descuento'] . '</h6></td>
                                <td><h6>' . $registro2['tipo_pro'] . '</h6></td>
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

//////////////////////////////////////////////////////////
//SE DIBUJA LA SUMATORIA TOTAL
?>