<?php
include('conexion.php');
$id = $_GET['id'];
$proceso = $_GET['pro'];
$idpro = $_GET['programa'];
$nombre = $_GET['nombre'];
$correo = $_GET['correo'];


//VERIFICAMOS EL PROCESO

switch($proceso){
	case 'Registro':
		mysql_query("INSERT INTO envio_correos (nombre,correo,idpro) VALUES ('$nombre', '$correo', '$idpro')");
		$registro = mysql_query("SELECT
e.id AS id,
e.nombre AS nombre, 
e.correo AS correo,
m.Nombre as programa,
m.id as idpro
FROM
  envio_correos e
INNER JOIN menu m ON e.idpro = m.id
WHERE m.id = '$idpro' ORDER BY m.id DESC");

//CREAMOS NUESTRA VISTA Y LA DEVOLVEMOS AL AJAX

echo '<table class="table table-striped table-condensed table-hover">
        	<tr>
        		<th width="150"></th>
                <th width="300">PROGRAMA</th>
                <th width="300">NOMBRE</th>
                <th width="300">CORREO</th>    
            </tr>';
	while($registro2 = mysql_fetch_array($registro)){
		echo '<tr>
				<td><a href="javascript:editarcorreos('.$registro2['id'].');" class="glyphicon glyphicon-edit"></a> <a href="javascript:eliminacorreos('.$registro2['id'].','.$registro2['idpro'].');" class="glyphicon glyphicon-remove-circle"></a></td>        
        <td>'.$registro2['programa'].'</td>
        <td>'.$registro2['nombre'].'</td>
        <td>'.$registro2['correo'].'</td>
				</tr>';
	}
echo '</table>';

	break;
	
	case 'Edicion':
		mysql_query("UPDATE envio_correos SET nombre = '$nombre', correo = '$correo', idpro = '$idpro' WHERE id = '$id'");

		$registro = mysql_query("SELECT
e.id AS id,
e.nombre AS nombre, 
e.correo AS correo,
m.Nombre as programa,
m.id as idpro
FROM
  envio_correos e
INNER JOIN menu m ON e.idpro = m.id
WHERE m.id = '$idpro' ORDER BY m.id DESC");

//CREAMOS NUESTRA VISTA Y LA DEVOLVEMOS AL AJAX

echo '<table class="table table-striped table-condensed table-hover">
        	<tr>
        		<th width="150"></th>
                <th width="300">PROGRAMA</th>
                <th width="300">NOMBRE</th>
                <th width="300">CORREO</th>      
            </tr>';
	while($registro2 = mysql_fetch_array($registro)){
		echo '<tr>
				<td><a href="javascript:editarcorreos('.$registro2['id'].');" class="glyphicon glyphicon-edit"></a> <a href="javascript:eliminacorreos('.$registro2['id'].','.$registro2['idpro'].');" class="glyphicon glyphicon-remove-circle"></a></td>        
        			<td>'.$registro2['programa'].'</td>
        			<td>'.$registro2['nombre'].'</td>
        			<td>'.$registro2['correo'].'</td>
				</tr>';
	}
echo '</table>';

	break;
}


//ACTUALIZAMOS LOS REGISTROS Y LOS OBTENEMOS


?>