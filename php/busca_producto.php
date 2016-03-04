<?php
include('conexiond.php');

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];

//EJECUTAMOS LA CONSULTA DE BUSQUEDA

$registro = mysql_query(
"SELECT
nofact31,
nocte31,
nomcte31,
fecfact31
FROM
maefac
WHERE fecfact31 BETWEEN '$desde 00:00:00' AND '$hasta 00:00:00'
"
);


//$registro = mysql_query("SELECT * FROM productos WHERE nomb_prod LIKE '%$dato%' OR tipo_prod LIKE '%$dato%' ORDER BY id_prod ASC");

//CREAMOS NUESTRA VISTA Y LA DEVOLVEMOS AL AJAX

echo '<table class="table table-striped table-condensed table-hover">
        	<tr>
            	<th width="100">Codigo</th>
			    <th width="400">Nombre</th>
			    <th width="100">Categoria</th>			   		            
            </tr>';
if(mysql_num_rows($registro)>0){
	while($registro2 = mysql_fetch_array($registro)){
		echo '<tr>
				<td><h6>'.$registro2['nofact31'].'</h6></td>
				<td><h6>'.$registro2['nocte31'].'</h6></td>
				<td><h6>'.$registro2['nomcte31'].'</h6></td>			
			</tr>';
	}
}else{
	echo '<tr>
				<td colspan="6">No se encontraron resultados</td>
			</tr>';
}
echo '</table>';

//////////////////////////////////////////////////////////
//SE DIBUJA LA SUMATORIA TOTAL

?>