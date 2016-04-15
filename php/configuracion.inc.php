<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//por Abrahan Apaza
$bd_host = "100.100.20.102"; // nombre del servidor
$bd_usuario = "root"; //username de la BD
$bd_pwd = "mrbooks"; // password de la BD
$bd_nombre = "mrbookspac"; // nombre de la BD
function conectar($host, $username, $pass, $bd) {
  $link = mysql_connect($host, $username, $pass);
  if ($link) {
    if (!mysql_select_db($bd, $link)) {
      echo "No se pudo establecer la conexion a la Base de Datos <strong>$bd</strong>, revise los datos de conexion";
    }
  } else {
    echo "No se pudo completar la conexion al servidor <strong>$servidor</strong>, revise los datos de conexion";
  }
  return $link;
}