<?php
include('conexion.php');

$id = $_GET['id'];

//OBTENEMOS LOS VALORES DEL PRODUCTO

$valores = mysql_query("SELECT * FROM envio_correos t WHERE t.id = '$id'");
$valores2 = mysql_fetch_array($valores);

$datos = array(
				0 => $valores2['nombre'],
				1 => $valores2['correo'],
				2 => $valores2['idpro'],		
				);
echo json_encode($datos);
?>