<?php
include('conexion.php');

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$bodega = $_GET['bodega'];

//COMPROBAMOS QUE LAS FECHAS EXISTAN
if(isset($desde)==false){
	$desde = $hasta;
}

if(isset($hasta)==false){
	$hasta = $desde;
}

//EJECUTAMOS LA CONSULTA DE BUSQUEDA
$registro = mysql_query(
"SELECT
	m.codbar01,
	m.desprod01,								
	sum(ROUND(f.CANTID03,0)),
	f.bodega,
	categorias.desccate,
	provedores.nomcte01,
	(SELECT p.nomtab FROM paises p WHERE provedores.loccte01 = p.codtab) AS PAIS,
	(SELECT	max(DATE_FORMAT(uf.FECMOV03,'%Y-%m-%d'))FROM factura_detalle uf	WHERE((uf.CODPROD03 = f.CODPROD03)AND(uf.TIPOTRA03 IN ('30', '01', '49','37')))) AS UFECHA 
FROM
	factura_detalle AS f
	INNER JOIN maepro AS m ON f.CODPROD03 = m.codprod01
	INNER JOIN factura_cabecera AS fa ON f.NOCOMP03 = fa.nofact31
	INNER JOIN categorias ON m.catprod01 = categorias.codcate
	LEFT JOIN provedores ON m.proved101 = provedores.codcte01
	WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND categorias.tipocate = '02' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND f.bodega IN ('$bodega')
	GROUP BY f.bodega,f.CODPROD03
	ORDER BY sum(f.CANTID03) DESC"
);


$sumatoria = mysql_query(
"SELECT
	sum(ROUND(f.CANTID03,0))
FROM
	factura_detalle f														
	INNER JOIN factura_cabecera fa ON f.NOCOMP03 = fa.nofact31
WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND f.bodega IN ('$bodega')"
);

//$registro = mysql_query("SELECT * FROM productos WHERE fecha_reg BETWEEN '$desde' AND '$hasta' ORDER BY id_prod ASC");

//CREAMOS NUESTRA VISTA Y LA DEVOLVEMOS AL AJAX

echo '<table class="table table-striped table-condensed table-hover">
        	<tr>
            	<th width="100">Codigo</th>
			    <th width="300">Nombre</th>
			    <th width="100">Categoria</th>
			    <th width="180">Provedor</th>
			    <th width="80">Pais</th>
                <th width="25">Cantidad</th>                
                <th width="80">Local</th>
                <th width="80">U Fecha</th>
            </tr>';
if(mysql_num_rows($registro)>0){
	while($registro2 = mysql_fetch_array($registro)){
		echo '<tr>
				<td><h6>'.$registro2['codbar01'].'</h6></td>
				<td><h6>'.$registro2['desprod01'].'</h6></td>
				<td><h6>'.$registro2['desccate'].'</h6></td>
				<td><h6>'.$registro2['nomcte01'].'</h6></td>
				<td><h6>'.$registro2['PAIS'].'</h6></td>
				<td><h6>'.$registro2['sum(ROUND(f.CANTID03,0))'].'</h6></td>
				<td><h6>'.$registro2['bodega'].'</h6></td>
				<td><h6>'.$registro2['UFECHA'].'</h6></td>					
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

echo '<table class="table table-striped table-condensed table-hover">
        	';
if(mysql_num_rows($sumatoria)>0){
	while($sumatoria2 = mysql_fetch_array($sumatoria)){
		echo '<tr>
				<td width="150"></td>	
				<td width="100"><h4> SUMATORIA TOTAL :&nbsp;&nbsp;&nbsp;&nbsp;'.$sumatoria2['sum(ROUND(f.CANTID03,0))'].'</h4></td>	
			</tr>';
	}
}else{
	echo '<tr>
				<td colspan="6">No se encontraron resultados</td>
			</tr>';
}
echo '</table>';

?>