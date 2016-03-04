<?php
include('conexion.php');

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$categoria = $_GET['categoria'];
$tipo = $_GET['tipo'];
//COMPROBAMOS QUE LAS FECHAS EXISTAN
// BUSQUEDA DE FACTURAS




if ($tipo == '80') {
	if ($categoria == 'TODOS') {
		// BUSQUEDA DE TODAS LAS CATEGORIAS EN FACTURAS
		//echo 'TIPO '.$tipo.' Y CATEGORIAS '.$categoria;
		// INICIO CONSULTA
	$registro = mysql_query(
"SELECT
DATE_FORMAT(d.FECMOV03,'%Y/%m/%d') AS FECHA,
cp.detalle AS TIPO,
(CASE WHEN (clientes.tipcte01 = '0004') THEN 'EMPLEADO'	ELSE 'EX EMPLEADO' END) AS CATEGORIA,
d.NOCOMP03 AS FACTURA,
Sum(d.PRECVTA03) AS IMPORTE,
Sum(d.DESCVTA03+d.desctotvta03) AS DESCUENTO,
Sum((((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100)+(d.PRECVTA03-d.DESCVTA03-d.desctotvta03)) AS TOTAL,
c.ruc31 AS CEDULA,
c.nomcte31 AS NOMBRE,
d.bodega as bodega,
(CASE WHEN (d.bodega = 'CDI') THEN Sum(d.CANTID03)	ELSE '0' END) AS MATRIZ,
(CASE WHEN (d.bodega = 'JARDIN') THEN Sum(d.CANTID03)	ELSE '0' END) AS JARDIN,
(CASE WHEN (d.bodega = 'CONDADO') THEN Sum(d.CANTID03)	ELSE '0' END) AS CONDADO,
(CASE WHEN (d.bodega = 'SCALA') THEN Sum(d.CANTID03)	ELSE '0' END) AS SCALA,
(CASE WHEN (d.bodega = 'SOL') THEN Sum(d.CANTID03)	ELSE '0' END) AS SOL,
(CASE WHEN (d.bodega = 'VILLAGE') THEN Sum(d.CANTID03)	ELSE '0' END) AS VILLAGE,
(CASE WHEN (d.bodega = 'QUICENTRO') THEN Sum(d.CANTID03)	ELSE '0' END) AS QUICENTRO,
(CASE WHEN (d.bodega = 'SAN LUIS') THEN Sum(d.CANTID03)	ELSE '0' END) AS 'SLUIS',
(CASE WHEN (d.bodega = 'SAN MARINO') THEN Sum(d.CANTID03)	ELSE '0' END) AS 'SMARINO',
(CASE WHEN (d.bodega = 'CUMBAYA') THEN Sum(d.CANTID03)	ELSE '0' END) AS CUMBAYA,
(CASE WHEN (d.bodega = 'JUAN LEON MERA') THEN Sum(d.CANTID03)	ELSE '0' END) AS 'JLMERA',
(CASE WHEN (d.bodega = 'WEB') THEN Sum(d.CANTID03)	ELSE '0' END) AS WEB,
(CASE WHEN (d.bodega = 'EVENTOS') THEN Sum(d.CANTID03)	ELSE '0' END) AS EVENTOS
FROM
factura_detalle AS d
INNER JOIN factura_cabecera AS c ON d.NOCOMP03 = c.nofact31
INNER JOIN condicion_pago AS cp ON c.condpag31 = cp.id_tipo_pago
INNER JOIN clientes ON c.nocte31 = clientes.codcte01
WHERE
d.TIPOTRA03 = '$tipo' AND
c.cvanulado31 <> '9' AND
c.condpag31 = '1' AND
clientes.tipcte01 IN ('0004','0008') AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
GROUP BY d.NOCOMP03
ORDER BY d.FECMOV03 DESC
"
);

echo '<table class="table table-striped table-condensed table-hover">
        	<tr>
            	<th width="20">FECHA</th>
            	<th width="20">CATEGORIA</th>
			    <th width="200">FACTURA</th>
			    <th width="200">IMPORTE</th>
			    <th width="200">DESC</th>	
			    <th width="200">TOTAL</th>
			    <th width="200">CEDULA</th>
			    <th width="200">NOMBRE</th>
			    <th width="200">MATRIZ</th>
			    <th width="200">JARDIN</th>
			    <th width="200">CONDADO</th>
			    <th width="200">SCALA</th>
			    <th width="200">SOL</th>
			    <th width="200">VILLAGE</th>
			    <th width="200">QUICENTRO</th>
			    <th width="200">SAN_LUIS</th>
			    <th width="200">SAN_MARINO</th>
			    <th width="200">CUMBAYA</th>		    
			    <th width="200">JLMERA</th>
			    <th width="200">WEB</th>
			    <th width="200">EVENTOS</th>
			    <th width="200"></th>
            </tr>';
if(mysql_num_rows($registro)>0){
	while($registro2 = mysql_fetch_array($registro)){
		echo '<tr>
				<td><h6>'.$registro2['FECHA'].'</h6></td>
				<td><h6>'.$registro2['CATEGORIA'].'</h6></td>
				<td><h6>'.$registro2['FACTURA'].'</h6></td>			
				<td><h6>'.number_format($registro2['IMPORTE'], 2, '.',',').'</h6></td>
				<td><h6>'.number_format($registro2['DESCUENTO'], 2, '.',',').'</h6></td>
				<td><h6>'.number_format($registro2['TOTAL'], 2, '.',',').'</h6></td>
				<td><h6>'.$registro2['CEDULA'].'</h6></td>
				<td><h6>'.$registro2['NOMBRE'].'</h6></td>
				<td><h6>'.number_format($registro2['MATRIZ']).'</h6></td>			
				<td><h6>'.number_format($registro2['JARDIN']).'</h6></td>
				<td><h6>'.number_format($registro2['CONDADO']).'</h6></td>
				<td><h6>'.number_format($registro2['SCALA']).'</h6></td>
				<td><h6>'.number_format($registro2['SOL']).'</h6></td>
				<td><h6>'.number_format($registro2['VILLAGE']).'</h6></td>
				<td><h6>'.number_format($registro2['QUICENTRO']).'</h6></td>
				<td><h6>'.number_format($registro2['SLUIS']).'</h6></td>
				<td><h6>'.number_format($registro2['SMARINO']).'</h6></td>
				<td><h6>'.number_format($registro2['CUMBAYA']).'</h6></td>
				<td><h6>'.number_format($registro2['JLMERA']).'</h6></td>
				<td><h6>'.number_format($registro2['WEB']).'</h6></td>
				<td><h6>'.number_format($registro2['EVENTOS']).'</h6></td>	
				<td width="50"><a href="../php/facturaspdf.php?fac='.$registro2['FACTURA'].'&bodega='.$registro2['bodega'].'" class="btn btn-danger">PDF</a></td>				
			</tr>';
	}
}else{
	echo '<tr>
				<td colspan="22">No se encontraron resultados</td>
			</tr>';
}
echo '</table>';
	// FIN CONSULTA
	} else {
		// BUSQUEDA DE CATEGORIAS SELECIONADA EN FACTURAS
		//echo 'TIPO '.$tipo.' Y CATEGORIAS '.$categoria;
		// INICIO CONSULTA
	$registro = mysql_query(
"SELECT
DATE_FORMAT(d.FECMOV03,'%Y/%m/%d') AS FECHA,
cp.detalle AS TIPO,
(CASE WHEN (clientes.tipcte01 = '0004') THEN 'EMPLEADO'	ELSE 'EX EMPLEADO' END) AS CATEGORIA,
d.NOCOMP03 AS FACTURA,
Sum(d.PRECVTA03) AS IMPORTE,
Sum(d.DESCVTA03+d.desctotvta03) AS DESCUENTO,
Sum((((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100)+(d.PRECVTA03-d.DESCVTA03-d.desctotvta03)) AS TOTAL,
c.ruc31 AS CEDULA,
c.nomcte31 AS NOMBRE,
d.bodega as bodega,
(CASE WHEN (d.bodega = 'CDI') THEN Sum(d.CANTID03)	ELSE '0' END) AS MATRIZ,
(CASE WHEN (d.bodega = 'JARDIN') THEN Sum(d.CANTID03)	ELSE '0' END) AS JARDIN,
(CASE WHEN (d.bodega = 'CONDADO') THEN Sum(d.CANTID03)	ELSE '0' END) AS CONDADO,
(CASE WHEN (d.bodega = 'SCALA') THEN Sum(d.CANTID03)	ELSE '0' END) AS SCALA,
(CASE WHEN (d.bodega = 'SOL') THEN Sum(d.CANTID03)	ELSE '0' END) AS SOL,
(CASE WHEN (d.bodega = 'VILLAGE') THEN Sum(d.CANTID03)	ELSE '0' END) AS VILLAGE,
(CASE WHEN (d.bodega = 'QUICENTRO') THEN Sum(d.CANTID03)	ELSE '0' END) AS QUICENTRO,
(CASE WHEN (d.bodega = 'SAN LUIS') THEN Sum(d.CANTID03)	ELSE '0' END) AS 'SLUIS',
(CASE WHEN (d.bodega = 'SAN MARINO') THEN Sum(d.CANTID03)	ELSE '0' END) AS 'SMARINO',
(CASE WHEN (d.bodega = 'CUMBAYA') THEN Sum(d.CANTID03)	ELSE '0' END) AS CUMBAYA,
(CASE WHEN (d.bodega = 'JUAN LEON MERA') THEN Sum(d.CANTID03)	ELSE '0' END) AS 'JLMERA',
(CASE WHEN (d.bodega = 'WEB') THEN Sum(d.CANTID03)	ELSE '0' END) AS WEB,
(CASE WHEN (d.bodega = 'EVENTOS') THEN Sum(d.CANTID03)	ELSE '0' END) AS EVENTOS
FROM
factura_detalle AS d
INNER JOIN factura_cabecera AS c ON d.NOCOMP03 = c.nofact31
INNER JOIN condicion_pago AS cp ON c.condpag31 = cp.id_tipo_pago
INNER JOIN clientes ON c.nocte31 = clientes.codcte01
WHERE
d.TIPOTRA03 = '$tipo' AND
c.cvanulado31 <> '9' AND
c.condpag31 = '1' AND
clientes.tipcte01 = '$categoria' AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
GROUP BY d.NOCOMP03
ORDER BY d.FECMOV03 DESC
"
);

echo '<table class="table table-striped table-condensed table-hover">
        	<tr>
            	<th width="20">FECHA</th>
            	<th width="20">CATEGORIA</th>
			    <th width="200">FACTURA</th>
			    <th width="200">IMPORTE</th>
			    <th width="200">DESC</th>	
			    <th width="200">TOTAL</th>
			    <th width="200">CEDULA</th>
			    <th width="200">NOMBRE</th>
			    <th width="200">MATRIZ</th>
			    <th width="200">JARDIN</th>
			    <th width="200">CONDADO</th>
			    <th width="200">SCALA</th>
			    <th width="200">SOL</th>
			    <th width="200">VILLAGE</th>
			    <th width="200">QUICENTRO</th>
			    <th width="200">SAN_LUIS</th>
			    <th width="200">SAN_MARINO</th>
			    <th width="200">CUMBAYA</th>		    
			    <th width="200">JLMERA</th>
			    <th width="200">WEB</th>
			    <th width="200">EVENTOS</th>
			    <th width="200"></th>
            </tr>';
if(mysql_num_rows($registro)>0){
	while($registro2 = mysql_fetch_array($registro)){
		echo '<tr>
				<td><h6>'.$registro2['FECHA'].'</h6></td>
				<td><h6>'.$registro2['CATEGORIA'].'</h6></td>
				<td><h6>'.$registro2['FACTURA'].'</h6></td>			
				<td><h6>'.number_format($registro2['IMPORTE'], 2, '.',',').'</h6></td>
				<td><h6>'.number_format($registro2['DESCUENTO'], 2, '.',',').'</h6></td>
				<td><h6>'.number_format($registro2['TOTAL'], 2, '.',',').'</h6></td>
				<td><h6>'.$registro2['CEDULA'].'</h6></td>
				<td><h6>'.$registro2['NOMBRE'].'</h6></td>
				<td><h6>'.number_format($registro2['MATRIZ']).'</h6></td>			
				<td><h6>'.number_format($registro2['JARDIN']).'</h6></td>
				<td><h6>'.number_format($registro2['CONDADO']).'</h6></td>
				<td><h6>'.number_format($registro2['SCALA']).'</h6></td>
				<td><h6>'.number_format($registro2['SOL']).'</h6></td>
				<td><h6>'.number_format($registro2['VILLAGE']).'</h6></td>
				<td><h6>'.number_format($registro2['QUICENTRO']).'</h6></td>
				<td><h6>'.number_format($registro2['SLUIS']).'</h6></td>
				<td><h6>'.number_format($registro2['SMARINO']).'</h6></td>
				<td><h6>'.number_format($registro2['CUMBAYA']).'</h6></td>
				<td><h6>'.number_format($registro2['JLMERA']).'</h6></td>
				<td><h6>'.number_format($registro2['WEB']).'</h6></td>
				<td><h6>'.number_format($registro2['EVENTOS']).'</h6></td>	
				<td width="50"><a href="../php/facturaspdf.php?fac='.$registro2['FACTURA'].'&bodega='.$registro2['bodega'].'" class="btn btn-danger">PDF</a></td>				
			</tr>';
	}
}else{
	echo '<tr>
				<td colspan="22">No se encontraron resultados</td>
			</tr>';
}
echo '</table>';
	// FIN CONSULTA
	}
}

if($tipo == '22') {
	if ($categoria == 'TODOS') {
		// BUSQUEDA DE TODAS LAS CATEGORIAS EN FACTURAS
		//echo 'TIPO '.$tipo.' Y CATEGORIAS '.$categoria;
		// INICIO CONSULTA
	$registro = mysql_query(
"SELECT
DATE_FORMAT(d.FECMOV03,'%Y/%m/%d') AS FECHA,
(CASE WHEN (c.tipcte01 = '0004') THEN 'EMPLEADO' ELSE 'EX EMPLEADO' END) AS CATEGORIA,
d.NOCOMP03 as NCREDITO,
d.NOFACT03 AS FACTURA,
Sum(d.PRECVTA03) AS IMPORTE,
Sum(d.DESCVTA03+d.desctotvta03) AS DESCUENTO,
Sum((((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100)+(d.PRECVTA03-d.DESCVTA03-d.desctotvta03)) AS TOTAL,
c.cascte01 AS CEDULA,
c.nomcte01 AS NOMBRE,
(CASE WHEN (d.bodega = 'CDI') THEN Sum(d.CANTID03)  ELSE '0' END) AS MATRIZ,
(CASE WHEN (d.bodega = 'JARDIN') THEN Sum(d.CANTID03) ELSE '0' END) AS JARDIN,
(CASE WHEN (d.bodega = 'CONDADO') THEN Sum(d.CANTID03)  ELSE '0' END) AS CONDADO,
(CASE WHEN (d.bodega = 'SCALA') THEN Sum(d.CANTID03)  ELSE '0' END) AS SCALA,
(CASE WHEN (d.bodega = 'SOL') THEN Sum(d.CANTID03)  ELSE '0' END) AS SOL,
(CASE WHEN (d.bodega = 'VILLAGE') THEN Sum(d.CANTID03)  ELSE '0' END) AS VILLAGE,
(CASE WHEN (d.bodega = 'QUICENTRO') THEN Sum(d.CANTID03)  ELSE '0' END) AS QUICENTRO,
(CASE WHEN (d.bodega = 'SAN LUIS') THEN Sum(d.CANTID03) ELSE '0' END) AS 'SLUIS',
(CASE WHEN (d.bodega = 'SAN MARINO') THEN Sum(d.CANTID03) ELSE '0' END) AS 'SMARINO',
(CASE WHEN (d.bodega = 'CUMBAYA') THEN Sum(d.CANTID03)  ELSE '0' END) AS CUMBAYA,
(CASE WHEN (d.bodega = 'JUAN LEON MERA') THEN Sum(d.CANTID03) ELSE '0' END) AS 'JLMERA',
(CASE WHEN (d.bodega = 'WEB') THEN Sum(d.CANTID03)  ELSE '0' END) AS WEB,
(CASE WHEN (d.bodega = 'EVENTOS') THEN Sum(d.CANTID03)  ELSE '0' END) AS EVENTOS
FROM
factura_detalle AS d
LEFT JOIN clientes c ON d.nomdest03 = c.codcte01
WHERE d.TIPOTRA03 = '$tipo' AND c.tipcte01 IN ('0004','0008') AND d.cvanulado03 = 'N' AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
GROUP BY d.NOCOMP03
ORDER BY d.FECMOV03 DESC
"
);

echo '<table class="table table-striped table-condensed table-hover">
        	<tr>
            	<th width="20">FECHA</th>
            	<th width="20">CATEGORIA</th>
			    <th width="200">NOTA DE CREDITO</th>
			    <th width="200">FACTURA APLICA</th>
			    <th width="200">IMPORTE</th>
			    <th width="200">DESC</th>	
			    <th width="200">TOTAL</th>
			    <th width="200">CEDULA</th>
			    <th width="200">NOMBRE</th>
			    <th width="200">MATRIZ</th>
			    <th width="200">JARDIN</th>
			    <th width="200">CONDADO</th>
			    <th width="200">SCALA</th>
			    <th width="200">SOL</th>
			    <th width="200">VILLAGE</th>
			    <th width="200">QUICENTRO</th>
			    <th width="200">SAN_LUIS</th>
			    <th width="200">SAN_MARINO</th>
			    <th width="200">CUMBAYA</th>		    
			    <th width="200">JLMERA</th>
			    <th width="200">WEB</th>
			    <th width="200">EVENTOS</th>			    
            </tr>';
if(mysql_num_rows($registro)>0){
	while($registro2 = mysql_fetch_array($registro)){
		echo '<tr>
				<td><h6>'.$registro2['FECHA'].'</h6></td>
				<td><h6>'.$registro2['CATEGORIA'].'</h6></td>
				<td><h6>'.$registro2['NCREDITO'].'</h6></td>			
				<td><h6>'.$registro2['FACTURA'].'</h6></td>	
				<td><h6>'.number_format($registro2['IMPORTE'], 2, '.',',').'</h6></td>
				<td><h6>'.number_format($registro2['DESCUENTO'], 2, '.',',').'</h6></td>
				<td><h6>'.number_format($registro2['TOTAL'], 2, '.',',').'</h6></td>
				<td><h6>'.$registro2['CEDULA'].'</h6></td>
				<td><h6>'.$registro2['NOMBRE'].'</h6></td>
				<td><h6>'.number_format($registro2['MATRIZ']).'</h6></td>			
				<td><h6>'.number_format($registro2['JARDIN']).'</h6></td>
				<td><h6>'.number_format($registro2['CONDADO']).'</h6></td>
				<td><h6>'.number_format($registro2['SCALA']).'</h6></td>
				<td><h6>'.number_format($registro2['SOL']).'</h6></td>
				<td><h6>'.number_format($registro2['VILLAGE']).'</h6></td>
				<td><h6>'.number_format($registro2['QUICENTRO']).'</h6></td>
				<td><h6>'.number_format($registro2['SLUIS']).'</h6></td>
				<td><h6>'.number_format($registro2['SMARINO']).'</h6></td>
				<td><h6>'.number_format($registro2['CUMBAYA']).'</h6></td>
				<td><h6>'.number_format($registro2['JLMERA']).'</h6></td>
				<td><h6>'.number_format($registro2['WEB']).'</h6></td>
				<td><h6>'.number_format($registro2['EVENTOS']).'</h6></td>					
			</tr>';
	}
}else{
	echo '<tr>
				<td colspan="22">No se encontraron resultados</td>
			</tr>';
}
echo '</table>';
	// FIN CONSULTA
	} else {
		// BUSQUEDA DE CATEGORIAS SELECIONADA EN FACTURAS
		//echo 'TIPO '.$tipo.' Y CATEGORIAS '.$categoria;
		// INICIO CONSULTA
	$registro = mysql_query(
"SELECT
DATE_FORMAT(d.FECMOV03,'%Y/%m/%d') AS FECHA,
(CASE WHEN (c.tipcte01 = '0004') THEN 'EMPLEADO' ELSE 'EX EMPLEADO' END) AS CATEGORIA,
d.NOCOMP03 as NCREDITO,
d.NOFACT03 AS FACTURA,
Sum(d.PRECVTA03) AS IMPORTE,
Sum(d.DESCVTA03+d.desctotvta03) AS DESCUENTO,
Sum((((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100)+(d.PRECVTA03-d.DESCVTA03-d.desctotvta03)) AS TOTAL,
c.cascte01 AS CEDULA,
c.nomcte01 AS NOMBRE,
(CASE WHEN (d.bodega = 'CDI') THEN Sum(d.CANTID03)  ELSE '0' END) AS MATRIZ,
(CASE WHEN (d.bodega = 'JARDIN') THEN Sum(d.CANTID03) ELSE '0' END) AS JARDIN,
(CASE WHEN (d.bodega = 'CONDADO') THEN Sum(d.CANTID03)  ELSE '0' END) AS CONDADO,
(CASE WHEN (d.bodega = 'SCALA') THEN Sum(d.CANTID03)  ELSE '0' END) AS SCALA,
(CASE WHEN (d.bodega = 'SOL') THEN Sum(d.CANTID03)  ELSE '0' END) AS SOL,
(CASE WHEN (d.bodega = 'VILLAGE') THEN Sum(d.CANTID03)  ELSE '0' END) AS VILLAGE,
(CASE WHEN (d.bodega = 'QUICENTRO') THEN Sum(d.CANTID03)  ELSE '0' END) AS QUICENTRO,
(CASE WHEN (d.bodega = 'SAN LUIS') THEN Sum(d.CANTID03) ELSE '0' END) AS 'SLUIS',
(CASE WHEN (d.bodega = 'SAN MARINO') THEN Sum(d.CANTID03) ELSE '0' END) AS 'SMARINO',
(CASE WHEN (d.bodega = 'CUMBAYA') THEN Sum(d.CANTID03)  ELSE '0' END) AS CUMBAYA,
(CASE WHEN (d.bodega = 'JUAN LEON MERA') THEN Sum(d.CANTID03) ELSE '0' END) AS 'JLMERA',
(CASE WHEN (d.bodega = 'WEB') THEN Sum(d.CANTID03)  ELSE '0' END) AS WEB,
(CASE WHEN (d.bodega = 'EVENTOS') THEN Sum(d.CANTID03)  ELSE '0' END) AS EVENTOS
FROM
factura_detalle AS d
LEFT JOIN clientes c ON d.nomdest03 = c.codcte01
WHERE d.TIPOTRA03 = '$tipo' AND c.tipcte01 = '$categoria' AND d.cvanulado03 = 'N' AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
GROUP BY d.NOCOMP03
ORDER BY d.FECMOV03 DESC
"
);

echo '<table class="table table-striped table-condensed table-hover">
        	<tr>
            	<th width="20">FECHA</th>
            	<th width="20">CATEGORIA</th>
			    <th width="200">NOTA DE CREDITO</th>
			    <th width="200">FACTURA APLICA</th>
			    <th width="200">IMPORTE</th>
			    <th width="200">DESC</th>	
			    <th width="200">TOTAL</th>
			    <th width="200">CEDULA</th>
			    <th width="200">NOMBRE</th>
			    <th width="200">MATRIZ</th>
			    <th width="200">JARDIN</th>
			    <th width="200">CONDADO</th>
			    <th width="200">SCALA</th>
			    <th width="200">SOL</th>
			    <th width="200">VILLAGE</th>
			    <th width="200">QUICENTRO</th>
			    <th width="200">SAN_LUIS</th>
			    <th width="200">SAN_MARINO</th>
			    <th width="200">CUMBAYA</th>		    
			    <th width="200">JLMERA</th>
			    <th width="200">WEB</th>
			    <th width="200">EVENTOS</th>			    
            </tr>';
if(mysql_num_rows($registro)>0){
	while($registro2 = mysql_fetch_array($registro)){
		echo '<tr>
				<td><h6>'.$registro2['FECHA'].'</h6></td>
				<td><h6>'.$registro2['CATEGORIA'].'</h6></td>
				<td><h6>'.$registro2['NCREDITO'].'</h6></td>			
				<td><h6>'.$registro2['FACTURA'].'</h6></td>	
				<td><h6>'.number_format($registro2['IMPORTE'], 2, '.',',').'</h6></td>
				<td><h6>'.number_format($registro2['DESCUENTO'], 2, '.',',').'</h6></td>
				<td><h6>'.number_format($registro2['TOTAL'], 2, '.',',').'</h6></td>
				<td><h6>'.$registro2['CEDULA'].'</h6></td>
				<td><h6>'.$registro2['NOMBRE'].'</h6></td>
				<td><h6>'.number_format($registro2['MATRIZ']).'</h6></td>			
				<td><h6>'.number_format($registro2['JARDIN']).'</h6></td>
				<td><h6>'.number_format($registro2['CONDADO']).'</h6></td>
				<td><h6>'.number_format($registro2['SCALA']).'</h6></td>
				<td><h6>'.number_format($registro2['SOL']).'</h6></td>
				<td><h6>'.number_format($registro2['VILLAGE']).'</h6></td>
				<td><h6>'.number_format($registro2['QUICENTRO']).'</h6></td>
				<td><h6>'.number_format($registro2['SLUIS']).'</h6></td>
				<td><h6>'.number_format($registro2['SMARINO']).'</h6></td>
				<td><h6>'.number_format($registro2['CUMBAYA']).'</h6></td>
				<td><h6>'.number_format($registro2['JLMERA']).'</h6></td>
				<td><h6>'.number_format($registro2['WEB']).'</h6></td>
				<td><h6>'.number_format($registro2['EVENTOS']).'</h6></td>					
			</tr>';
	}
}else{
	echo '<tr>
				<td colspan="22">No se encontraron resultados</td>
			</tr>';
}
echo '</table>';
	// FIN CONSULTA
	}
}
?>