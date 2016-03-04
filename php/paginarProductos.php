<?php
	include('conexion.php');
	$paginaActual = $_POST['partida'];

   /* $nroProductos = mysql_num_rows(mysql_query("SELECT
m.codbar01,
f.CODPROD03,
m.desprod01,
sum(f.CANTID03),
b.nombre as local
FROM
factura_detalle f
INNER JOIN maepro m ON f.CODPROD03 = m.codprod01
INNER JOIN bodegas b ON f.bodega = b.cod_local
INNER JOIN factura_cabecera fa ON f.NOCOMP03 = fa.nofact31
WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9'
GROUP BY b.nombre,f.CODPROD03"));*/
    $nroLotes = 5000;
    $nroPaginas = ceil($nroProductos/$nroLotes);
    $lista = '';
    $tabla = '';

    if($paginaActual > 1){
        $lista = $lista.'<li><a href="javascript:pagination('.($paginaActual-1).');">Anterior</a></li>';
    }
    for($i=1; $i<=$nroPaginas; $i++){
        if($i == $paginaActual){
            $lista = $lista.'<li class="active"><a href="javascript:pagination('.$i.');">'.$i.'</a></li>';
        }else{
            $lista = $lista.'<li><a href="javascript:pagination('.$i.');">'.$i.'</a></li>';
        }
    }
    if($paginaActual < $nroPaginas){
        $lista = $lista.'<li><a href="javascript:pagination('.($paginaActual+1).');">Siguiente</a></li>';
    }
  
  	if($paginaActual <= 1){
  		$limit = 0;
  	}else{
  		$limit = $nroLotes*($paginaActual-1);
  	}

  	$registro = mysql_query("SELECT
m.codbar01,
f.CODPROD03,
m.desprod01,
sum(f.CANTID03),
b.nombre as local
FROM
factura_detalle f
INNER JOIN maepro m ON f.CODPROD03 = m.codprod01
INNER JOIN bodegas b ON f.bodega = b.cod_local
INNER JOIN factura_cabecera fa ON f.NOCOMP03 = fa.nofact31
WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9'
GROUP BY b.nombre,f.CODPROD03 LIMIT $limit, $nroLotes ");


  	$tabla = $tabla.'<table class="table table-striped table-condensed table-hover">
			            <tr>
			                <th width="100">Codigo</th>
			                 <th width="400">Nombre</th>
                       <th width="80">Cantidad</th>
                       <th width="100">Local</th>
			            </tr>';
				
	while($registro2 = mysql_fetch_array($registro)){
		$tabla = $tabla.'<tr>
							<td>'.$registro2['codbar01'].'</td>
              <td>'.$registro2['desprod01'].'</td>
              <td>'.$registro2['sum(f.CANTID03)'].'</td>
              <td>'.$registro2['local'].'</td>
						  </tr>';		
	}
        

    $tabla = $tabla.'</table>';



    $array = array(0 => $tabla,
    			   1 => $lista);

    echo json_encode($array);
?>