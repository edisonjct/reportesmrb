<?php
include('conexion.php');

$bodega = $_GET['bodega'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$egreso = '61';

//EJECUTAMOS LA CONSULTA DE BUSQUEDA
// SI EL CAMPO DE FACTURAS ESTA LLENO
if ($bodega == 'TODOS') {
  //BUSCA LAS TRANSFERENCIAS EN TODAS LAS BODEGAS
  // INICIO CONSULTA  
$registro = mysql_query("SELECT
i.TIPOTRA03 as tipo,
i.NOCOMP03 as ingreso,
i.numero_transferencia_egreso as egreso,
DATE_FORMAT(i.FECMOV03,'%Y/%m/%d') AS FECHA,
i.detalle03 as detalle,
usuario.Usuario as usuario,
destino.nomtab as bodega
FROM
factura_detalle AS i
LEFT JOIN usuario ON i.UID = usuario.id
INNER JOIN destino ON i.CODDEST03 = destino.codtab
WHERE i.detalle03 LIKE 'Detalle: Devolucion Automatica a Bodega por diferencia en Recepcion de Transferencia:%' AND i.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
GROUP BY i.NOCOMP03 ORDER BY i.FECMOV03 DESC
");
echo '<table class="table table-striped table-condensed table-hover">
          <tr>
          <th width="50">FECHA</th>
          <th width="15">TIPO</th>           
          <th width="100">INGRESO</th>
          <th width="20"></th>
          <th width="15">TIPO</th>
          <th width="100">EGRESO</th>        
          <th width="20"></th>          
          <th width="50">USUARIO</th>        
          <th width="50">DESTINO</th>                    
          <th width="350">DETALLE</th>
            </tr>';  
if(mysql_num_rows($registro)>0){
  while($registro2 = mysql_fetch_array($registro)){      
    echo '<tr>        
        <td><h6>'.$registro2['FECHA'].'</h6></td>            
        <td><h6>'.$registro2['tipo'].'</h6></td>
        <td><h6>'.$registro2['ingreso'].'</h6></td> 
        <td width="50"><a href="../php/transferencias.php?bodega='.$registro2['bodega'].'&transferenciai='.$registro2['ingreso'].'&transferenciae='.$registro2['egreso'].'&tipo='.$registro2['tipo'].'" target="_blank">
        <img src="../recursos/entrada.png" height="30" width="30"></a></td>     
        <td><h6>61</h6></td> 
        <td><h6>'.$registro2['egreso'].'</h6></td>     
        <td width="50"><a href="../php/transferencias.php?bodega='.$registro2['bodega'].'&transferenciai='.$registro2['ingreso'].'&transferenciae='.$registro2['egreso'].'&tipo='.$egreso.'" target="_blank">
        <img src="../recursos/salida.png" height="30" width="30"></a></td>
        <td><h6>'.$registro2['usuario'].'</h6></td> 
        <td><h6>'.$registro2['bodega'].'</h6></td>              
        <td><h6>'.$registro2['detalle'].'</h6></td>         
        </tr>';
  } 
}else{
  echo '<tr>
        <td colspan="7">No se encontraron resultados</td>
      </tr>';
}
echo '</table>';
// FIN CONSULTA
    // FIN BUSQUEDA

} 
else {

  // INICIO CONSULTA  
$registro = mysql_query("SELECT
i.TIPOTRA03 as tipo,
i.NOCOMP03 as ingreso,
i.numero_transferencia_egreso as egreso,
DATE_FORMAT(i.FECMOV03,'%Y/%m/%d') AS FECHA,
i.detalle03 as detalle,
usuario.Usuario as usuario,
destino.nomtab as bodega
FROM
factura_detalle AS i
LEFT JOIN usuario ON i.UID = usuario.id
INNER JOIN destino ON i.CODDEST03 = destino.codtab
WHERE i.detalle03 LIKE 'Detalle: Devolucion Automatica a Bodega por diferencia en Recepcion de Transferencia:%' AND i.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND destino.nomtab = '$bodega'
GROUP BY i.NOCOMP03 ORDER BY i.FECMOV03 DESC
");
echo '<table class="table table-striped table-condensed table-hover">
          <tr>
          <th width="50">FECHA</th>
          <th width="15">TIPO</th>           
          <th width="100">INGRESO</th>
          <th width="20"></th>
          <th width="15">TIPO</th>
          <th width="100">EGRESO</th>        
          <th width="20"></th>          
          <th width="50">USUARIO</th>        
          <th width="50">DESTINO</th>                    
          <th width="350">DETALLE</th>
            </tr>';  
if(mysql_num_rows($registro)>0){
  while($registro2 = mysql_fetch_array($registro)){      
    echo '<tr>
        <td><h6>'.$registro2['FECHA'].'</h6></td>            
        <td><h6>'.$registro2['tipo'].'</h6></td>
        <td><h6>'.$registro2['ingreso'].'</h6></td> 
        <td width="50"><a href="../php/transferencias.php?bodega='.$registro2['bodega'].'&transferenciai='.$registro2['ingreso'].'&transferenciae='.$registro2['egreso'].'&tipo='.$registro2['tipo'].'" target="_blank">
        <img src="../recursos/entrada.png" height="30" width="30"></a></td>     
        <td><h6>61</h6></td> 
        <td><h6>'.$registro2['egreso'].'</h6></td>     
        <td width="50"><a href="../php/transferencias.php?bodega='.$registro2['bodega'].'&transferenciai='.$registro2['ingreso'].'&transferenciae='.$registro2['egreso'].'&tipo='.$egreso.'" target="_blank">
        <img src="../recursos/salida.png" height="30" width="30"></a></td>
        <td><h6>'.$registro2['usuario'].'</h6></td> 
        <td><h6>'.$registro2['bodega'].'</h6></td>              
        <td><h6>'.$registro2['detalle'].'</h6></td>         
        </tr>';
  } 
}else{
  echo '<tr>
        <td colspan="7">No se encontraron resultados</td>
      </tr>';
}
echo '</table>';
// FIN CONSULTA

}

?>