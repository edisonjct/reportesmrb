<?php
header("Content-Type: text/html;charset=utf-8");
include('conexion.php');

$bodega = $_GET['bodega'];

//EJECUTAMOS LA CONSULTA DE BUSQUEDA

if ($bodega == 'TODOS') {
//EJECUTAMOS LA CONSULTA DE BUSQUEDA
    $registro = mysql_query(
            "SELECT
inv.codbar01 as cod,
inv.desprod01 as titulo,
a.nombres AS autor,
e.razon AS editorial,
c.desccate as categoria,
id.nomtab as idioma,
infor04 as fedicion,
infor08 as ubicacion,
inv.bodega as bodega
FROM
INVENTARIO AS inv
LEFT JOIN autores AS a ON inv.infor01 = a.codigo
LEFT JOIN editoriales AS e ON inv.infor02 = e.codigo
LEFT JOIN categorias AS c ON inv.catprod01 = c.codcate
LEFT JOIN idiomas  id ON inv.infor03 = id.codtab
WHERE c.tipocate = '02' AND inv.prodsinsdo01 = 'S' AND
(inv.infor01 in ('49743','49884','',NULL) OR inv.infor02 IN ('3245','',NULL) OR inv.infor03 IN ('',NULL) OR inv.infor04 = '0000-00-00')
ORDER BY inv.bodega 
");

    echo '<table class="table table-striped table-condensed table-hover">
        	<tr>            	
			    <th width="50">BODEGA</th>
			    <th width="60">CODIGO</th>
			    <th width="200">TITULO</th>
                            <th width="100">AUTOR</th>
                            <th width="200">EDITORIAL</th>
                            <th width="150">CATEGORIA</th>
                            <th width="100">IDIOMA</th>
                            <th width="150">FECHA EDICION</th>
                            <th width="50">UBICACION</th>
            </tr>';
    
     $total_items = 0;
    if (mysql_num_rows($registro) > 0) {
        while ($registro2 = mysql_fetch_array($registro)) {
            $total_items = $total_items + 1;
            echo '<tr>
				<td><h6>' . $registro2['bodega'] . '</h6></td>
                                <td><h6>' . $registro2['cod'] . '</h6></td>
                                <td><h6>' . $registro2['titulo'] . '</h6></td>
                                <td><h6>' . $registro2['autor'] . '</h6></td>
                                <td><h6>' . $registro2['editorial'] . '</h6></td>
                                <td><h6>' . $registro2['categoria'] . '</h6></td>
                                <td><h6>' . $registro2['idioma'] . '</h6></td>
                                <td><h6>' . $registro2['fedicion'] . '</h6></td>
                                <td><h6>' . $registro2['ubicacion'] . '</h6></td>
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
a.nombres AS autor,
e.razon AS editorial,
c.desccate as categoria,
id.nomtab as idioma,
infor04 as fedicion,
infor08 as ubicacion,
inv.bodega as bodega
FROM
INVENTARIO AS inv
LEFT JOIN autores AS a ON inv.infor01 = a.codigo
LEFT JOIN editoriales AS e ON inv.infor02 = e.codigo
LEFT JOIN categorias AS c ON inv.catprod01 = c.codcate
LEFT JOIN idiomas  id ON inv.infor03 = id.codtab
WHERE c.tipocate = '02' AND inv.prodsinsdo01 = 'S' AND inv.bodega = '$bodega' AND
(inv.infor01 in ('49743','49884','',NULL) OR inv.infor02 IN ('3245','',NULL) OR inv.infor03 IN ('',NULL) OR inv.infor04 = '0000-00-00')
ORDER BY inv.bodega");

    echo '<table class="table table-striped table-condensed table-hover">
        	<tr>            	
			    <th width="50">BODEGA</th>
			    <th width="60">CODIGO</th>
			    <th width="200">TITULO</th>
                            <th width="100">AUTOR</th>
                            <th width="200">EDITORIAL</th>
                            <th width="150">CATEGORIA</th>
                            <th width="100">IDIOMA</th>
                            <th width="150">FECHA EDICION</th>
                            <th width="50">UBICACION</th>
            </tr>';

    $total_items = 0;

    if (mysql_num_rows($registro) > 0) {
        while ($registro2 = mysql_fetch_array($registro)) {
            $total_items = $total_items + 1;
            echo '<tr>                
				<td><h6>' . $registro2['bodega'] . '</h6></td>
                                <td><h6>' . $registro2['cod'] . '</h6></td>
                                <td><h6>' . $registro2['titulo'] . '</h6></td>
                                <td><h6>' . $registro2['autor'] . '</h6></td>
                                <td><h6>' . $registro2['editorial'] . '</h6></td>
                                <td><h6>' . $registro2['categoria'] . '</h6></td>
                                <td><h6>' . $registro2['idioma'] . '</h6></td>
                                <td><h6>' . $registro2['fedicion'] . '</h6></td>
                                <td><h6>' . $registro2['ubicacion'] . '</h6></td>
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