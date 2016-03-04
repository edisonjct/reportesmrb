
<?php
include('conexion.php');

$dato = $_GET['dato'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$bodega = $_GET['bodega'];




if ($bodega == 'TODOS') {	
	if (strlen($dato) > 0) {
	//BUSCA POR CODIGO EN TODOS LOS LOCALES
$registro = mysql_query(
"SELECT
  m.codbar01,
  m.desprod01,
  categorias.desccate,
  provedores.nomcte01,
  (SELECT p.nomtab FROM paises p WHERE provedores.loccte01 = p.codtab) AS PAIS,
  (SELECT max(DATE_FORMAT(uf.FECMOV03,'%Y-%m-%d'))FROM factura_detalle uf WHERE( uf.CODPROD03 = f.CODPROD03 AND uf.TIPOTRA03 IN ('30', '01', '49','37'))) AS UFECHA,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'CDI' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS CDI,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'JARDIN' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS JARDIN,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'CONDADO' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS CONDADO,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'SCALA' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS SCALA,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'SOL' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS SOL,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'VILLAGE' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS VILLAGE,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'QUICENTRO' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS QUICENTRO,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'SAN LUIS' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS SLUIS,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'SAN MARINO' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS SMARINO,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'CUMBAYA' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS CUMBAYA,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'JUAN LEON MERA' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS JLMERA,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'EVENTOS' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS EVENTOS,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'WEB' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS WEB
FROM
  factura_detalle f
  LEFT JOIN maepro m ON f.CODPROD03 = m.codprod01
  LEFT JOIN factura_cabecera fa ON f.NOCOMP03 = fa.nofact31
  LEFT JOIN categorias ON m.catprod01 = categorias.codcate
  LEFT JOIN provedores ON m.proved101 = provedores.codcte01
WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND m.codbar01 = '$dato'
GROUP BY f.CODPROD03 ORDER BY sum(f.CANTID03) DESC"
);
//TOTAL
$sumatoria = mysql_query(
"SELECT	
	sum(f.CANTID03)	
FROM
	factura_detalle f
	LEFT JOIN maepro m ON f.CODPROD03 = m.codprod01							
	LEFT JOIN factura_cabecera fa ON f.NOCOMP03 = fa.nofact31
	LEFT JOIN categorias ON m.catprod01 = categorias.codcate
	LEFT JOIN provedores ON m.proved101 = provedores.codcte01
WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND m.codbar01 = '$dato'");
//CREAMOS NUESTRA VISTA Y LA DEVOLVEMOS AL AJAX
echo '<table class="table table-striped <!--table-condensed--> table-hover table-borderless">
        	<tr>
            	<th width="100">Codigo</th>
			    <th width="400">Nombre</th>
			    <th width="100">Categoria</th>
			    <th width="150">Provedor</th>
			    <th width="80">Pais</th>                
                <th width="80">Fecha_UC</th>
                <th width="100">CDI</th>
                <th width="100">JARDIN</th>
                <th width="100">CONDADO</th>
                <th width="100">SCALA</th>
                <th width="100">SOL</th>
                <th width="100">VILLAGE</th>
                <th width="100">QUICENTRO</th>
                <th width="100">SAN_LUIS</th>
                <th width="100">SAN_MARINO</th>
                <th width="100">CUMBAYA</th>
                <th width="100">JLMERA</th>
                <th width="100">EVENTOS</th>
                <th width="100">WEB</th>
            </tr>';


if(mysql_num_rows($registro)>0){
	while($registro2 = mysql_fetch_array($registro)){
		echo '<tr>
				<td><h6>'.$registro2['codbar01'].'</h6></td>
				<td><h6>'.$registro2['desprod01'].'</h6></td>
				<td><h6>'.$registro2['desccate'].'</h6></td>
				<td><h6>'.$registro2['nomcte01'].'</h6></td>
				<td><h6>'.$registro2['PAIS'].'</h6></td>				
				<td><h6>'.$registro2['UFECHA'].'</h6></td>
				<td><h6>'.$registro2['CDI'].'</h6></td>
				<td><h6>'.$registro2['JARDIN'].'</h6></td>
				<td><h6>'.$registro2['CONDADO'].'</h6></td>
				<td><h6>'.$registro2['SCALA'].'</h6></td>
				<td><h6>'.$registro2['SOL'].'</h6></td>
				<td><h6>'.$registro2['VILLAGE'].'</h6></td>
				<td><h6>'.$registro2['QUICENTRO'].'</h6></td>
				<td><h6>'.$registro2['SLUIS'].'</h6></td>
				<td><h6>'.$registro2['SMARINO'].'</h6></td>
				<td><h6>'.$registro2['CUMBAYA'].'</h6></td>
				<td><h6>'.$registro2['JLMERA'].'</h6></td>
				<td><h6>'.$registro2['EVENTOS'].'</h6></td>
				<td><h6>'.$registro2['WEB'].'</h6></td>

			</tr>';
	}
}else{
	echo '<tr><td colspan="8"><div align="center">No se encontraron resultados</div></td></tr>';
}
echo '</table>';
//SE DIBUJA LA SUMATORIA TOTAL
echo '<table class="table table-striped table-condensed table-hover">';
if(mysql_num_rows($sumatoria)>0){
	while($sumatoria2 = mysql_fetch_array($sumatoria)){
		echo '<tr>
				<td width="150"></td>	
				<td width="100"><h4> SUMATORIA TOTAL :   '.$sumatoria2['sum(f.CANTID03)'].'</h4></td>	
			</tr>';
	}
}else{
	echo '<tr><td colspan="8"><div align="center">No se encontraron resultados</div></td></tr>';
}
echo '</table>';
	} else {
	//BUSCA TODOS LOS CODIGOS EN TODOS LOS LOCALES

$registro = mysql_query(
"SELECT
  m.codbar01,
  m.desprod01,
  categorias.desccate,
  provedores.nomcte01,
  (SELECT p.nomtab FROM paises p WHERE provedores.loccte01 = p.codtab) AS PAIS,
  (SELECT max(DATE_FORMAT(uf.FECMOV03,'%Y-%m-%d'))FROM factura_detalle uf WHERE( uf.CODPROD03 = f.CODPROD03 AND uf.TIPOTRA03 IN ('30', '01', '49','37'))) AS UFECHA,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'CDI' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS CDI,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'JARDIN' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS JARDIN,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'CONDADO' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS CONDADO,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'SCALA' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS SCALA,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'SOL' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS SOL,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'VILLAGE' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS VILLAGE,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'QUICENTRO' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS QUICENTRO,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'SAN LUIS' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS SLUIS,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'SAN MARINO' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS SMARINO,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'CUMBAYA' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS CUMBAYA,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'JUAN LEON MERA' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS JLMERA,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'EVENTOS' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS EVENTOS,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = 'WEB' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS WEB
FROM
  factura_detalle f
  LEFT JOIN maepro m ON f.CODPROD03 = m.codprod01
  LEFT JOIN factura_cabecera fa ON f.NOCOMP03 = fa.nofact31
  LEFT JOIN categorias ON m.catprod01 = categorias.codcate
  LEFT JOIN provedores ON m.proved101 = provedores.codcte01
WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
GROUP BY f.CODPROD03 ORDER BY sum(f.CANTID03) DESC"
);
//TOTALISADOS
$sumatoria = mysql_query(
"SELECT	
	sum(f.CANTID03)	
FROM
	factura_detalle f
	LEFT JOIN maepro m ON f.CODPROD03 = m.codprod01
	LEFT JOIN factura_cabecera fa ON f.NOCOMP03 = fa.nofact31
	LEFT JOIN categorias ON m.catprod01 = categorias.codcate
	LEFT JOIN provedores ON m.proved101 = provedores.codcte01
WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'");
//CREAMOS NUESTRA VISTA Y LA DEVOLVEMOS AL AJAX
echo '<table class="table table-striped <!--table-condensed--> table-hover table-borderless">
        	<tr>
            	<th width="100">Codigo</th>
			    <th width="400">Nombre</th>
			    <th width="100">Categoria</th>
			    <th width="150">Provedor</th>
			    <th width="80">Pais</th>                
                <th width="80">Fecha_UC</th>
                <th width="100">CDI</th>
                <th width="100">JARDIN</th>
                <th width="100">CONDADO</th>
                <th width="100">SCALA</th>
                <th width="100">SOL</th>
                <th width="100">VILLAGE</th>
                <th width="100">QUICENTRO</th>
                <th width="100">SAN_LUIS</th>
                <th width="100">SAN_MARINO</th>
                <th width="100">CUMBAYA</th>
                <th width="100">JLMERA</th>
                <th width="100">EVENTOS</th>
                <th width="100">WEB</th>
            </tr>';


if(mysql_num_rows($registro)>0){
	while($registro2 = mysql_fetch_array($registro)){
		echo '<tr>
				<td><h6>'.$registro2['codbar01'].'</h6></td>
				<td><h6>'.$registro2['desprod01'].'</h6></td>
				<td><h6>'.$registro2['desccate'].'</h6></td>
				<td><h6>'.$registro2['nomcte01'].'</h6></td>
				<td><h6>'.$registro2['PAIS'].'</h6></td>				
				<td><h6>'.$registro2['UFECHA'].'</h6></td>
				<td><h6>'.$registro2['CDI'].'</h6></td>
				<td><h6>'.$registro2['JARDIN'].'</h6></td>
				<td><h6>'.$registro2['CONDADO'].'</h6></td>
				<td><h6>'.$registro2['SCALA'].'</h6></td>
				<td><h6>'.$registro2['SOL'].'</h6></td>
				<td><h6>'.$registro2['VILLAGE'].'</h6></td>
				<td><h6>'.$registro2['QUICENTRO'].'</h6></td>
				<td><h6>'.$registro2['SLUIS'].'</h6></td>
				<td><h6>'.$registro2['SMARINO'].'</h6></td>
				<td><h6>'.$registro2['CUMBAYA'].'</h6></td>
				<td><h6>'.$registro2['JLMERA'].'</h6></td>
				<td><h6>'.$registro2['EVENTOS'].'</h6></td>
				<td><h6>'.$registro2['WEB'].'</h6></td>
			</tr>';
	}
}else{
	echo '<tr><td colspan="6">No se encontraron resultados</td></tr>';
}
echo '</table>';
//SE DIBUJA LA SUMATORIA TOTAL
echo '<table class="table table-striped table-condensed table-hover">';
if(mysql_num_rows($sumatoria)>0){
	while($sumatoria2 = mysql_fetch_array($sumatoria)){
		echo '<tr>
				<td width="150"></td>	
				<td width="100"><h4> SUMATORIA TOTAL :   '.$sumatoria2['sum(f.CANTID03)'].'</h4></td>	
			</tr>';
	}
}else{
	echo '<tr><td colspan="6">No se encontraron resultados</td></tr>';
}
echo '</table>';
/*
echo '<table class="table table-striped table-condensed table-hover">
        	<tr><td width="100"><div align="center"><h3>SELECCIONE BODEGA</h3></div></td></tr>';
echo '</table>';*/
	}
} else {
	if (strlen($dato) > 0){
	//BUSCA POR CODIGO EN LA BODEGA SELECIONADA
$registro = mysql_query(
"SELECT
  m.codbar01,
  m.desprod01,
  categorias.desccate,
  provedores.nomcte01,
  (SELECT p.nomtab FROM paises p WHERE provedores.loccte01 = p.codtab) AS PAIS,
  (SELECT max(DATE_FORMAT(uf.FECMOV03,'%Y-%m-%d'))FROM factura_detalle uf WHERE( uf.CODPROD03 = f.CODPROD03 AND uf.TIPOTRA03 IN ('30', '01', '49','37'))) AS UFECHA,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = '$bodega' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS 'BODEGA'
FROM
  factura_detalle f
  LEFT JOIN maepro m ON f.CODPROD03 = m.codprod01
  LEFT JOIN factura_cabecera fa ON f.NOCOMP03 = fa.nofact31
  LEFT JOIN categorias ON m.catprod01 = categorias.codcate
  LEFT JOIN provedores ON m.proved101 = provedores.codcte01
WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND m.codbar01 = '$dato' AND f.bodega = '$bodega'
GROUP BY f.CODPROD03 ORDER BY sum(f.CANTID03) DESC"
);
//TOTALISADOS
$sumatoria = mysql_query(
"SELECT
	sum(f.CANTID03)
FROM
	factura_detalle f														
	LEFT JOIN factura_cabecera fa ON f.NOCOMP03 = fa.nofact31
	LEFT JOIN maepro m ON f.CODPROD03 = m.codprod01							
WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND m.codbar01 = '$dato' AND f.bodega = ('$bodega')
");
//CREAMOS NUESTRA VISTA Y LA DEVOLVEMOS AL AJAX
echo '<table class="table table-striped <!--table-condensed--> table-hover table-borderless">
        	<tr>
            	<th width="100">Codigo</th>
			    <th width="400">Nombre</th>
			    <th width="100">Categoria</th>
			    <th width="150">Provedor</th>
			    <th width="80">Pais</th>                
                <th width="80">Fecha_UC</th>
                <th width="100">'.$bodega.'</th>                
            </tr>';


if(mysql_num_rows($registro)>0){
	while($registro2 = mysql_fetch_array($registro)){
		echo '<tr>
				<td><h6>'.$registro2['codbar01'].'</h6></td>
				<td><h6>'.$registro2['desprod01'].'</h6></td>
				<td><h6>'.$registro2['desccate'].'</h6></td>
				<td><h6>'.$registro2['nomcte01'].'</h6></td>
				<td><h6>'.$registro2['PAIS'].'</h6></td>	
				<td><h6>'.$registro2['UFECHA'].'</h6></td>				
				<td><h6>'.$registro2['BODEGA'].'</h6></td>
			</tr>';
	}
}else{
	echo '<tr><td colspan="8"><div align="center">No se encontraron resultados</div></td></tr>';
}
echo '</table>';
//SE DIBUJA LA SUMATORIA TOTAL
echo '<table class="table table-striped table-condensed table-hover">';
if(mysql_num_rows($sumatoria)>0){
	while($sumatoria2 = mysql_fetch_array($sumatoria)){
		echo '<tr>
				<td width="150"></td>	
				<td width="100"><h4> SUMATORIA TOTAL :   '.$sumatoria2['sum(f.CANTID03)'].'</h4></td>	
			</tr>';
	}
}else{
	echo '<tr><td colspan="8"><div align="center">No se encontraron resultados</div></td></tr>';
}
echo '</table>';

	//////////////////////////////////////////
	} else {
	//BUSCA TODOS LOS CODIGOS EN LA BODEGA SELECIONADA

$registro = mysql_query(
"SELECT
  m.codbar01,
  m.desprod01,
  categorias.desccate,
  provedores.nomcte01,
  (SELECT p.nomtab FROM paises p WHERE provedores.loccte01 = p.codtab) AS PAIS,
  (SELECT max(DATE_FORMAT(uf.FECMOV03,'%Y-%m-%d'))FROM factura_detalle uf WHERE( uf.CODPROD03 = f.CODPROD03 AND uf.TIPOTRA03 IN ('30', '01', '49','37'))) AS UFECHA,
	(SELECT	sum(v.CANTID03)FROM factura_detalle v LEFT JOIN factura_cabecera fa ON v.NOCOMP03 = fa.nofact31 WHERE v.CODPROD03 = f.CODPROD03 AND v.bodega = '$bodega' AND v.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND v.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' GROUP BY v.CODPROD03) AS 'BODEGA'
FROM
  factura_detalle f
  LEFT JOIN maepro m ON f.CODPROD03 = m.codprod01
  LEFT JOIN factura_cabecera fa ON f.NOCOMP03 = fa.nofact31
  LEFT JOIN categorias ON m.catprod01 = categorias.codcate
  LEFT JOIN provedores ON m.proved101 = provedores.codcte01
WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND f.bodega = '$bodega'
GROUP BY f.CODPROD03 ORDER BY sum(f.CANTID03) DESC"
);
//TOTALISADOS
$sumatoria = mysql_query(
"SELECT
	sum(f.CANTID03)
FROM
	factura_detalle f														
	LEFT JOIN factura_cabecera fa ON f.NOCOMP03 = fa.nofact31
	LEFT JOIN maepro m ON f.CODPROD03 = m.codprod01							
WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND f.bodega = ('$bodega')
");
//CREAMOS NUESTRA VISTA Y LA DEVOLVEMOS AL AJAX
echo '<table class="table table-striped <!--table-condensed--> table-hover table-borderless">
        	<tr>
            	<th width="100">Codigo</th>
			    <th width="400">Nombre</th>
			    <th width="100">Categoria</th>
			    <th width="150">Provedor</th>
			    <th width="80">Pais</th>                
                <th width="80">Fecha_UC</th>
                <th width="100">'.$bodega.'</th>                
            </tr>';
if(mysql_num_rows($registro)>0){
	while($registro2 = mysql_fetch_array($registro)){
		echo '<tr>
				<td><h6>'.$registro2['codbar01'].'</h6></td>
				<td><h6>'.$registro2['desprod01'].'</h6></td>
				<td><h6>'.$registro2['desccate'].'</h6></td>
				<td><h6>'.$registro2['nomcte01'].'</h6></td>
				<td><h6>'.$registro2['PAIS'].'</h6></td>				
				<td><h6>'.$registro2['UFECHA'].'</h6></td>	
				<td><h6>'.$registro2['BODEGA'].'</h6></td>	
			</tr>';
	}
}else{
	echo '<tr><td colspan="8"><div align="center">No se encontraron resultados</div></td></tr>';
}
echo '</table>';
//SE DIBUJA LA SUMATORIA TOTAL
echo '<table class="table table-striped table-condensed table-hover">';
if(mysql_num_rows($sumatoria)>0){
	while($sumatoria2 = mysql_fetch_array($sumatoria)){
		echo '<tr>
				<td width="150"></td>	
				<td width="100"><h4> SUMATORIA TOTAL :   '.$sumatoria2['sum(f.CANTID03)'].'</h4></td>	
			</tr>';
	}
}else{
	echo '<tr><td colspan="8">No se encontraron resultados</td></tr>';
}
echo '</table>';
	}



}


//EJECUTAMOS LA CONSULTA DE BUSQUEDA




?>