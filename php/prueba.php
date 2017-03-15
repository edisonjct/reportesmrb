<?php

$bd = "test";
$server = "localhost";
$user = "root";
$password = "";

$conexion = @mysqli_connect($server, $user, $password, $bd);

if (!$conexion)
    die("Error de conexion " . mysqli_connect_error());


$sql = "SELECT usuario, nombres FROM usuario limit 50";
$result = mysqli_query($conexion, $sql);
$array = array();
while ($data = mysqli_fetch_assoc($result)) {
    $array[] = $data;
}
print_r(json_encode($array));

header('Content-type: aplication/json');
header("Access-Control-Allow-Origin: *");
?>