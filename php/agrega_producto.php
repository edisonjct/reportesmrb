<?php
include('conexion.php');
$id = $_GET['id'];
$proceso = $_GET['pro'];
$id_area = $_GET['area'];
$id_usuario = $_GET['usuario'];
$estado = $_GET['estado'];
$detalle = $_GET['detalle'];
$id_tecnico = $_GET['tecnico'];
$id_grupo = $_GET['grupo'];
$fechaa = date("Y-m-d");
$fechac = date("Y-m-d");


$ticket = $_GET['id'];
$estado2 = $_GET['estado'];

//VERIFICAMOS EL PROCESO

switch($proceso){
	case 'Registro':
		mysql_query("INSERT INTO mrbtickets (id_area, id_usuario, estado, detalle, id_tecnico, id_grupo, fecha_abierto, fecha_cerrado) VALUES ('$id_area', '$id_usuario', '$estado', '$detalle', '$id_tecnico', '$id_grupo', '$fechaa', '$fechac')");
		$registro = mysql_query("SELECT
t.id as id,
mrbareas.nombre AS area,
usuario.Nombre AS usuario,
mrbestados.nombre AS estado,
t.estado as es,
t.detalle AS detalle,
mrbtecnicos.nombre AS tecnico,
mrbgrupo.nombre AS grupo,
t.fecha_abierto AS fecha
FROM
mrbtickets AS t
INNER JOIN mrbareas ON t.id_area = mrbareas.id
INNER JOIN usuario ON t.id_usuario = usuario.id
INNER JOIN mrbestados ON t.estado = mrbestados.id
INNER JOIN mrbtecnicos ON t.id_tecnico = mrbtecnicos.id
INNER JOIN mrbgrupo ON t.id_grupo = mrbgrupo.id WHERE t.estado = '$estado' ORDER BY id DESC");

//CREAMOS NUESTRA VISTA Y LA DEVOLVEMOS AL AJAX

echo '<table class="table table-striped table-condensed table-hover">
        	<tr>
        		<th width="150"></th>
            	<th width="50">#</th>
                <th width="300">Area</th>
                <th width="300">Usuario</th>
                <th width="150">Estado</th>
                <th width="600">Detalle</th>
                <th width="350">Tecnico</th>
                <th width="100">Grupo</th>
                <th width="300">Fecha</th>                
            </tr>';
	while($registro2 = mysql_fetch_array($registro)){
		echo '<tr>
				<td><a href="javascript:editarProducto('.$registro2['id'].');" class="glyphicon glyphicon-edit"></a> <a href="javascript:eliminarProducto('.$registro2['id'].','.$registro2['es'].');" class="glyphicon glyphicon-remove-circle"></a></td>
				<td>'.$registro2['id'].'</td>
				<td>'.$registro2['area'].'</td>
				<td>'.$registro2['usuario'].'</td>
				<td>'.$registro2['estado'].'</td>
				<td>'.$registro2['detalle'].'</td>
				<td>'.$registro2['tecnico'].'</td>
				<td>'.$registro2['grupo'].'</td>
				<td>'.$registro2['fecha'].'</td>
				</tr>';
	}
echo '</table>';

	break;
	
	case 'Edicion':
		mysql_query("UPDATE mrbtickets SET id_area = '$id_area', id_usuario = '$id_usuario', estado = '$estado', detalle = '$detalle', id_tecnico = '$id_tecnico', id_grupo = '$id_grupo', fecha_abierto = '$fechaa', fecha_cerrado = '$fechac' WHERE id = '$id'");

		$registro = mysql_query("SELECT
t.id as id,
mrbareas.nombre AS area,
usuario.Nombre AS usuario,
mrbestados.nombre AS estado,
t.estado as es,
t.detalle AS detalle,
mrbtecnicos.nombre AS tecnico,
mrbgrupo.nombre AS grupo,
t.fecha_abierto AS fecha
FROM
mrbtickets AS t
INNER JOIN mrbareas ON t.id_area = mrbareas.id
INNER JOIN usuario ON t.id_usuario = usuario.id
INNER JOIN mrbestados ON t.estado = mrbestados.id
INNER JOIN mrbtecnicos ON t.id_tecnico = mrbtecnicos.id
INNER JOIN mrbgrupo ON t.id_grupo = mrbgrupo.id where t.estado = '$estado' ORDER BY id DESC");

//CREAMOS NUESTRA VISTA Y LA DEVOLVEMOS AL AJAX

echo '<table class="table table-striped table-condensed table-hover">
        	<tr>
        		<th width="150"></th>
            	<th width="50">#</th>
                <th width="300">Area</th>
                <th width="300">Usuario</th>
                <th width="150">Estado</th>
                <th width="600">Detalle</th>
                <th width="350">Tecnico</th>
                <th width="100">Grupo</th>
                <th width="300">Fecha</th>                
            </tr>';
	while($registro2 = mysql_fetch_array($registro)){
		echo '<tr>
				<td><a href="javascript:editarProducto('.$registro2['id'].');" class="glyphicon glyphicon-edit"></a> <a href="javascript:eliminarProducto('.$registro2['id'].','.$registro2['es'].');" class="glyphicon glyphicon-remove-circle"></a></td>
				<td>'.$registro2['id'].'</td>
				<td>'.$registro2['area'].'</td>
				<td>'.$registro2['usuario'].'</td>
				<td>'.$registro2['estado'].'</td>
				<td>'.$registro2['detalle'].'</td>
				<td>'.$registro2['tecnico'].'</td>
				<td>'.$registro2['grupo'].'</td>
				<td>'.$registro2['fecha'].'</td>
				</tr>';
	}
echo '</table>';

	break;
}


//ACTUALIZAMOS LOS REGISTROS Y LOS OBTENEMOS


?>