<?php

include('conexion.php');

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$tipo = $_GET['tipo'];
$stock = $_GET['stock'];
$operador = $_GET['operador'];



switch ($operador) {
    case '1':
        $signo = '>=';
        break;

    case '2':
        $signo = '=';
        break;

    case '3':
        $signo = '<=';
        break;
}

$vaciartmpventas = mysql_query("TRUNCATE tmpventastotal");
$consolidarventas = mysql_query("INSERT INTO tmpventastotal(codpro,total)
SELECT
d.CODPROD03,
sum(d.CANTID03)
FROM
factura_detalle AS d
INNER JOIN factura_cabecera AS c ON d.NOCOMP03 = c.nofact31
INNER JOIN bodegas as b ON d.bodega = b.nombre
WHERE d.TIPOTRA03 = '80' AND c.cvanulado31 <> '9' AND b.estado = '1' AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
GROUP BY d.CODPROD03
ORDER BY b.orden,d.CODPROD03");
$vaciarcompras = mysql_query("TRUNCATE tmpcompras");
switch ($tipo) {
    case '0001':
        echo '<table class="table table-striped table-condensed table-hover table-responsive">
          <tr>          
          <th width="100">CODIGO</th>
          <th width="300">TITULO</th>
          <th width="100">CATEGORIA</th>             
          <th width="100">AUTOR</th>   
          <th width="100">EDITORIAL</th>   
          <th width="250">PROVEDOR</th>                   
          <th width="100">UBICA</th>
          <th width="100">CDI</th>
          <th width="100">COMPRAS</th>
          <th width="100">CANTIDAD</th>
          <th width="100">PROM</th>
          <th width="100">VENTAS</th>          
          <th width="400">%</th>
            </tr>';
        $importaciones = mysql_query("INSERT INTO tmpcompras(tipodoc,doc,codigo,cantidad,fecha,compra)
        SELECT
        im.TIPOTRA03,
        COUNT(im.NOCOMP03),
        im.CODPROD03,
        SUM(im.CANTID03),
        MAX(im.FECMOV03),
        im.NOFACT03
        from factura_detalle im
        WHERE im.TIPOTRA03 = '30' AND im.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
        GROUP BY im.CODPROD03
	ORDER BY im.CODPROD03");
        $registro = mysql_query("SELECT
	m.codprod01 AS interno,
	m.codbar01 AS codigo,
	m.desprod01 AS titulo,
	categorias.desccate AS categoria,
	autores.nombres AS autor,
	editoriales.razon AS editorial,
	p.nomcte01 AS provedor,
	m.infor08 AS ubicacion,
	m.cantact01 AS CDI,
	c.doc as compras,
	c.cantidad as sumcompras,
	c.cantidad / c.doc as prom,
	vt.total as ventas,
	CASE WHEN vt.total >= c.cantidad THEN 100 ELSE ROUND((vt.total * 100)/c.cantidad) END as porcen,
	CASE WHEN vt.total >= c.cantidad THEN 0 ELSE 100 - (ROUND((vt.total * 100)/c.cantidad)) END as dif
	FROM
	maepro AS m
	INNER JOIN provedores AS p ON m.proved101 = p.coddest01
	LEFT JOIN autores ON m.infor01 = autores.codigo
	LEFT JOIN editoriales ON m.infor02 = editoriales.codigo
	INNER JOIN categorias ON m.catprod01 = categorias.codcate
	INNER JOIN tmpcompras AS c ON m.codprod01 = c.codigo
	INNER JOIN tmpventastotal AS vt ON c.codigo = vt.codpro
	WHERE
	m.cantact01 $signo '$stock'
	GROUP BY codprod01
	ORDER BY porcen ASC");        
        if (mysql_num_rows($registro) > 0) {
            while ($registro2 = mysql_fetch_array($registro)) {                
                echo '<tr>        
        <td><h6>' . $registro2['codigo'] . '</h6></td>
        <td><h6>' . $registro2['titulo'] . '</h6></td>
        <td><h6>' . $registro2['categoria'] . '</h6></td>   
        <td><h6>' . $registro2['autor'] . '</h6></td>
        <td><h6>' . $registro2['editorial'] . '</h6></td>
        <td><h6>' . $registro2['provedor'] . '</h6></td>
        <td><h6>' . $registro2['ubicacion'] . '</h6></td>
        <td class="active"><h6>' . number_format($registro2['CDI'], 0, '.', ',') . '</h6></td>
        <td class="success"><h6>' . number_format($registro2['compras'], 0, '.', ',') . '</h6></td>
        <td class="warning"><h6>' . number_format($registro2['sumcompras'], 0, '.', ',') . '</h6></td>         
        <td class="danger"><h6>' . number_format($registro2['prom'], 0, '.', ',') . '</h6></td>
        <td class="active"><h6>' . number_format($registro2['ventas'], 0, '.', ',') . '</h6></td>
        <td>
        <div class="progress">
        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" style="width:' . number_format($registro2['porcen'], 0, '.', ',') . '%">
            ' . number_format($registro2['porcen'], 0, '.', ',') . '%
        </div>        
        <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" style="width:' . number_format($registro2['dif'], 0, '.', ',') . '%">
            ' . number_format($registro2['dif'], 0, '.', ',') . '%
        </div>
        </div>
        </td>
      </tr>';
            }
        } else {
            echo '<tr><td colspan="14"><div class="alert alert-danger">
            <strong>NO SE ENCONTRARON RESULTADOS</strong>
            </div></td></tr>';
        }
        echo '</table>';
        break;

    case '0002':
        echo '<table class="table table-striped table-condensed table-hover table-responsive">
          <tr>          
          <th width="100">CODIGO</th>
          <th width="300">TITULO</th>
          <th width="100">CATEGORIA</th>             
          <th width="100">AUTOR</th>   
          <th width="100">EDITORIAL</th>   
          <th width="250">PROVEDOR</th>                   
          <th width="100">UBICA</th>
          <th width="100">CDI</th>
          <th width="100">COMPRAS</th>
          <th width="100">CANTIDAD</th>
          <th width="100">PROM</th>
          <th width="100">VENTAS</th>          
          <th width="400">%</th>
            </tr>';
        $nacionales = mysql_query("INSERT INTO tmpcompras(tipodoc,doc,codigo,cantidad,fecha,compra)
        SELECT
        im.TIPOTRA03,
        COUNT(im.NOCOMP03),
        im.CODPROD03,
        SUM(im.CANTID03),
        MAX(im.FECMOV03),
        im.nopedido03
        from factura_detalle im
        WHERE im.TIPOTRA03 = '01' AND im.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
        GROUP BY im.CODPROD03
	ORDER BY im.CODPROD03");
        $registro = mysql_query("SELECT
	m.codprod01 AS interno,
	m.codbar01 AS codigo,
	m.desprod01 AS titulo,
	categorias.desccate AS categoria,
	autores.nombres AS autor,
	editoriales.razon AS editorial,
	p.nomcte01 AS provedor,
	m.infor08 AS ubicacion,
	m.cantact01 AS CDI,
	c.doc as compras,
	c.cantidad as sumcompras,
	c.cantidad / c.doc as prom,
	vt.total as ventas,
	CASE WHEN vt.total >= c.cantidad THEN 100 ELSE ROUND((vt.total * 100)/c.cantidad) END as porcen,
	CASE WHEN vt.total >= c.cantidad THEN 0 ELSE 100 - (ROUND((vt.total * 100)/c.cantidad)) END as dif
	FROM
	maepro AS m
	INNER JOIN provedores AS p ON m.proved101 = p.coddest01
	LEFT JOIN autores ON m.infor01 = autores.codigo
	LEFT JOIN editoriales ON m.infor02 = editoriales.codigo
	INNER JOIN categorias ON m.catprod01 = categorias.codcate
	INNER JOIN tmpcompras AS c ON m.codprod01 = c.codigo
	INNER JOIN tmpventastotal AS vt ON c.codigo = vt.codpro
	WHERE
	m.cantact01 $signo '$stock'
	GROUP BY codprod01
	ORDER BY porcen ASC");        
        if (mysql_num_rows($registro) > 0) {
            while ($registro2 = mysql_fetch_array($registro)) {                
                echo '<tr>        
        <td><h6>' . $registro2['codigo'] . '</h6></td>
        <td><h6>' . $registro2['titulo'] . '</h6></td>
        <td><h6>' . $registro2['categoria'] . '</h6></td>   
        <td><h6>' . $registro2['autor'] . '</h6></td>
        <td><h6>' . $registro2['editorial'] . '</h6></td>
        <td><h6>' . $registro2['provedor'] . '</h6></td>
        <td><h6>' . $registro2['ubicacion'] . '</h6></td>
        <td class="active"><h6>' . number_format($registro2['CDI'], 0, '.', ',') . '</h6></td>
        <td class="success"><h6>' . number_format($registro2['compras'], 0, '.', ',') . '</h6></td>
        <td class="warning"><h6>' . number_format($registro2['sumcompras'], 0, '.', ',') . '</h6></td>         
        <td class="danger"><h6>' . number_format($registro2['prom'], 0, '.', ',') . '</h6></td>
        <td class="active"><h6>' . number_format($registro2['ventas'], 0, '.', ',') . '</h6></td>
        <td>
        <div class="progress">
        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" style="width:' . number_format($registro2['porcen'], 0, '.', ',') . '%">
            ' . number_format($registro2['porcen'], 0, '.', ',') . '%
        </div>        
        <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" style="width:' . number_format($registro2['dif'], 0, '.', ',') . '%">
            ' . number_format($registro2['dif'], 0, '.', ',') . '%
        </div>
        </div>
        </td>
      </tr>';
            }
        } else {
            echo '<tr><td colspan="14"><div class="alert alert-danger">
            <strong>NO SE ENCONTRARON RESULTADOS</strong>
            </div></td></tr>';
        }
        echo '</table>';
        break;

    case '0003':
        echo '<table class="table table-striped table-condensed table-hover table-responsive">
          <tr>          
          <th width="100">CODIGO</th>
          <th width="300">TITULO</th>
          <th width="100">CATEGORIA</th>             
          <th width="100">AUTOR</th>   
          <th width="100">EDITORIAL</th>   
          <th width="250">PROVEDOR</th>                   
          <th width="100">UBICA</th>
          <th width="100">CDI</th>
          <th width="100">COMPRAS</th>
          <th width="100">CANTIDAD</th>
          <th width="100">PROM</th>
          <th width="100">VENTAS</th>          
          <th width="400">%</th>
            </tr>';
        $consignadas = mysql_query("INSERT INTO tmpcompras(tipodoc,doc,codigo,cantidad,fecha,compra)
        SELECT
        im.TIPOTRA03,
        COUNT(im.NOCOMP03),
        im.CODPROD03,
        SUM(im.CANTID03),
        MAX(im.FECMOV03),
        im.nopedido03
        from factura_detalle im
        WHERE im.TIPOTRA03 = '37' AND im.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
        GROUP BY im.CODPROD03
	ORDER BY im.CODPROD03");
        $registro = mysql_query("SELECT
	m.codprod01 AS interno,
	m.codbar01 AS codigo,
	m.desprod01 AS titulo,
	categorias.desccate AS categoria,
	autores.nombres AS autor,
	editoriales.razon AS editorial,
	p.nomcte01 AS provedor,
	m.infor08 AS ubicacion,
	m.cantact01 AS CDI,
	c.doc as compras,
	c.cantidad as sumcompras,
	c.cantidad / c.doc as prom,
	vt.total as ventas,
	CASE WHEN vt.total >= c.cantidad THEN 100 ELSE ROUND((vt.total * 100)/c.cantidad) END as porcen,
	CASE WHEN vt.total >= c.cantidad THEN 0 ELSE 100 - (ROUND((vt.total * 100)/c.cantidad)) END as dif
	FROM
	maepro AS m
	INNER JOIN provedores AS p ON m.proved101 = p.coddest01
	LEFT JOIN autores ON m.infor01 = autores.codigo
	LEFT JOIN editoriales ON m.infor02 = editoriales.codigo
	INNER JOIN categorias ON m.catprod01 = categorias.codcate
	INNER JOIN tmpcompras AS c ON m.codprod01 = c.codigo
	INNER JOIN tmpventastotal AS vt ON c.codigo = vt.codpro
	WHERE
	m.cantact01 $signo '$stock'
	GROUP BY codprod01
	ORDER BY porcen ASC");        
        if (mysql_num_rows($registro) > 0) {
            while ($registro2 = mysql_fetch_array($registro)) {                
                echo '<tr>        
        <td><h6>' . $registro2['codigo'] . '</h6></td>
        <td><h6>' . $registro2['titulo'] . '</h6></td>
        <td><h6>' . $registro2['categoria'] . '</h6></td>   
        <td><h6>' . $registro2['autor'] . '</h6></td>
        <td><h6>' . $registro2['editorial'] . '</h6></td>
        <td><h6>' . $registro2['provedor'] . '</h6></td>
        <td><h6>' . $registro2['ubicacion'] . '</h6></td>
        <td class="active"><h6>' . number_format($registro2['CDI'], 0, '.', ',') . '</h6></td>
        <td class="success"><h6>' . number_format($registro2['compras'], 0, '.', ',') . '</h6></td>
        <td class="warning"><h6>' . number_format($registro2['sumcompras'], 0, '.', ',') . '</h6></td>         
        <td class="danger"><h6>' . number_format($registro2['prom'], 0, '.', ',') . '</h6></td>
        <td class="active"><h6>' . number_format($registro2['ventas'], 0, '.', ',') . '</h6></td>
        <td>
        <div class="progress">
        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" style="width:' . number_format($registro2['porcen'], 0, '.', ',') . '%">
            ' . number_format($registro2['porcen'], 0, '.', ',') . '%
        </div>        
        <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" style="width:' . number_format($registro2['dif'], 0, '.', ',') . '%">
            ' . number_format($registro2['dif'], 0, '.', ',') . '%
        </div>
        </div>
        </td>
      </tr>';
            }
        } else {
            echo '<tr><td colspan="14"><div class="alert alert-danger">
            <strong>NO SE ENCONTRARON RESULTADOS</strong>
            </div></td></tr>';
        }
        echo '</table>';
        break;
}      