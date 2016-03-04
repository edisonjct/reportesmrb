<?php
include('conexion.php');

$id = $_GET['id'];

//OBTENEMOS LOS VALORES DEL PRODUCTO

$valores = mysql_query("SELECT * FROM mrbtickets t WHERE t.id = '$id'");
$valores2 = mysql_fetch_array($valores);

$datos = array(
				0 => $valores2['detalle'],
				1 => $valores2['id_area'],
				2 => $valores2['id_usuario'],
				3 => $valores2['estado'],			
				4 => $valores2['id_tecnico'],
				5 => $valores2['id_grupo'],
				);
echo json_encode($datos);
?>