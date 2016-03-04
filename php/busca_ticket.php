<?php
include('conexion.php');

$ticket = $_GET['ticket'];
$estado = $_GET['estado'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];

//EJECUTAMOS LA CONSULTA DE BUSQUEDA

if($desde == false){
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
INNER JOIN mrbgrupo ON t.id_grupo = mrbgrupo.id  WHERE t.estado = '$estado' ORDER BY id DESC");
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
if(mysql_num_rows($registro)>0){
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
}else{
  echo '<tr>
        <td colspan="6">No se encontraron resultados</td>
      </tr>';
}
echo '</table>';


} else {
if($ticket == true){
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
INNER JOIN mrbgrupo ON t.id_grupo = mrbgrupo.id  WHERE t.fecha_abierto BETWEEN '$desde' AND '$hasta' AND t.estado = '$estado' AND t.id = '$ticket' ORDER BY id DESC");
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
if(mysql_num_rows($registro)>0){
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
}else{
  echo '<tr>
        <td colspan="6">No se encontraron resultados</td>
      </tr>';
}
echo '</table>';
} else {
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
INNER JOIN mrbgrupo ON t.id_grupo = mrbgrupo.id  WHERE t.fecha_abierto BETWEEN '$desde' AND '$hasta' AND t.estado = '$estado' ORDER BY id DESC");
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
if(mysql_num_rows($registro)>0){
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
}else{
  echo '<tr>
        <td colspan="6">No se encontraron resultados</td>
      </tr>';
}
echo '</table>';
}
}

?>