<?php

include('conexion.php');

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$bodega = $_GET['bodega'];
$pais = $_GET['pais'];
$stock = $_GET['stock'];
$operador = $_GET['operador'];
$ufc = $_GET['ufc'];
$provedor = $_GET['provedor'];


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

if ($bodega == false) {
    echo '<script type="text/javascript">alert("SELECIONE BODEGA");</script>';
} else if ($ufc == false) {
    echo '<script type="text/javascript">alert("SELECIONE FECHA DE ULTIMA COMPRA");</script>';
} else if ($pais == false) {
    echo '<script type="text/javascript">alert("SELECIONE UN PAIS A BUSCAR");</script>';
} else if ($provedor == false) {
    echo '<script type="text/javascript">alert("SELECIONE PROVEDOR");</script>';
} else if ($desde == false) {
    echo '<script type="text/javascript">alert("SELECIONE FECHA DESDE");</script>';
} else if ($hasta == false) {
    echo '<script type="text/javascript">alert("SELECIONE FECHA HASTA");</script>';
} else {

    echo '<table class="table table-striped table-condensed table-hover table-responsive">
          <tr>
          <th width="10">#</th>
          <th width="100">CODIGO</th>
          <th width="300">TITULO</th>
          <th width="200">CATEGORIA</th>             
          <th width="200">AUTOR</th>   
          <th width="200">EDITORIAL</th>   
          <th width="250">PROVEDOR</th> 
          <th width="200">UFC</th>
          <th width="200">UBICA</th>
          <th width="100">CDI</th>
          <th width="100">LOCAL</th>
          <th width="100">VENTA</th>          
          <th width="100">PEDIDO</th>
            </tr>';


    $vaciarufc = mysql_query("DELETE FROM tmpultimafechacompra");
    $ufcompra = mysql_query("INSERT INTO tmpultimafechacompra (codprod,fecha,cantidad)
    SELECT
    uf.CODPROD03,	
    max(DATE_FORMAT(uf.FECMOV03, '%Y-%m-%d')),
    (select im.CANTID03 from factura_detalle im WHERE im.CODPROD03=uf.CODPROD03 AND im.bodega = '01' AND im.TIPOTRA03 IN ('30', '01', '49', '37') AND im.FECMOV03 BETWEEN '$ufc 00:00:00' AND '$hasta 23:59:59' ORDER BY im.FECMOV03 DESC LIMIT 1) as cantid
    FROM
	factura_detalle uf
    WHERE
    uf.TIPOTRA03 IN ('30', '01', '49', '37') AND uf.FECMOV03 BETWEEN '$ufc 00:00:00' AND '$hasta 23:59:59' AND uf.bodega = '01'
    GROUP BY uf.CODPROD03 ORDER BY uf.CODPROD03");
    $vaciartablaventas = mysql_query("DELETE FROM tmpventascantidadbodega");
    $ventas = mysql_query("INSERT INTO tmpventascantidadbodega(idpro,codbar,bodega,cantidad) SELECT
	m.codprod01,
        m.codbar01,
        f.bodega,
        sum(f.CANTID03)  
        FROM
        factura_detalle f
        LEFT JOIN maepro m ON f.CODPROD03 = m.codprod01
        LEFT JOIN factura_cabecera fa ON f.NOCOMP03 = fa.nofact31    
        WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND f.bodega IN ($bodega)
        GROUP BY f.bodega,f.CODPROD03");
    $vaciartablastock = mysql_query("DELETE FROM tmpstocklocal");
    $stocklocal = mysql_query("INSERT INTO tmpstocklocal(codpro,stock,bodega)
        SELECT
        i.codprod01,
        i.cantact01,
        i.bodega
            FROM
        INVENTARIO i WHERE i.bodega = '$bodega'");
    if ($provedor == 00001) {
        $registro = mysql_query("SELECT
        m.codprod01 AS interno,
        m.codbar01 AS codigo,
        m.desprod01 as titulo,
        categorias.desccate as categoria,
        autores.nombres AS autor,
        editoriales.razon AS editorial,
        p.nomcte01 AS provedor,       
        DATE_FORMAT(ufc.fecha,'%Y-%m-%d') as uf,
        m.infor08 as ubicacion,
        m.cantact01 AS CDI,
        l.stock AS BODEGA,
	p.tipcte01 as tipo,
	p.coddest01 as codprov,
        CASE WHEN v.cantidad > 0 THEN v.cantidad ELSE '0' END AS venta,
        CASE 
            WHEN m.cantact01 > v.cantidad THEN v.cantidad 
            WHEN m.cantact01 < v.cantidad THEN m.cantact01 ELSE '0' END AS pedido
        FROM
        maepro AS m
        INNER JOIN tmpstocklocal AS l ON m.codprod01 = l.codpro
        INNER JOIN tmpventascantidadbodega AS v ON m.codprod01 = v.idpro
        INNER JOIN provedores AS p ON m.proved101 = p.coddest01
        LEFT JOIN autores ON m.infor01 = autores.codigo
        LEFT JOIN editoriales ON m.infor02 = editoriales.codigo
        INNER JOIN categorias ON m.catprod01 = categorias.codcate
	LEFT JOIN tmpultimafechacompra AS ufc ON m.codprod01 = ufc.codprod
        WHERE l.stock $signo '$stock' AND p.loccte01 IN ($pais) AND ufc.fecha >= '$ufc 00:00:00'
        ORDER BY v.cantidad DESC");
    } else {
        $registro = mysql_query("SELECT
        m.codprod01 AS interno,
        m.codbar01 AS codigo,
        m.desprod01 as titulo,
        categorias.desccate as categoria,
        autores.nombres AS autor,
        editoriales.razon AS editorial,
        p.nomcte01 AS provedor,       
        DATE_FORMAT(ufc.fecha,'%Y-%m-%d') as uf,
        m.infor08 as ubicacion,
        m.cantact01 AS CDI,
        l.stock AS BODEGA,
	p.tipcte01 as tipo,
	p.coddest01 as codprov,
        CASE WHEN v.cantidad > 0 THEN v.cantidad ELSE '0' END AS venta,
        CASE 
            WHEN m.cantact01 > v.cantidad THEN v.cantidad 
            WHEN m.cantact01 < v.cantidad THEN m.cantact01 ELSE '0' END AS pedido
        FROM
        maepro AS m
        INNER JOIN tmpstocklocal AS l ON m.codprod01 = l.codpro
        INNER JOIN tmpventascantidadbodega AS v ON m.codprod01 = v.idpro
        INNER JOIN provedores AS p ON m.proved101 = p.coddest01
        LEFT JOIN autores ON m.infor01 = autores.codigo
        LEFT JOIN editoriales ON m.infor02 = editoriales.codigo
        INNER JOIN categorias ON m.catprod01 = categorias.codcate
	LEFT JOIN tmpultimafechacompra AS ufc ON m.codprod01 = ufc.codprod
        WHERE l.stock $signo '$stock' AND p.loccte01 IN ($pais) AND p.coddest01= '$provedor' AND ufc.fecha >= '$ufc 00:00:00'
        ORDER BY v.cantidad DESC");
    }

    $count = '0';
    if (mysql_num_rows($registro) > 0) {
        while ($registro2 = mysql_fetch_array($registro)) {
            $count = $count + 1;
            echo '<tr>
        <td><h6>' . $count . '</h6></td>
        <td><h6>' . $registro2['codigo'] . '</h6></td>
        <td><h6>' . $registro2['titulo'] . '</h6></td>
        <td><h6>' . $registro2['categoria'] . '</h6></td>   
        <td><h6>' . $registro2['autor'] . '</h6></td>
        <td><h6>' . $registro2['editorial'] . '</h6></td>
        <td><h6>' . $registro2['provedor'] . '</h6></td>
        <td><h6>' . $registro2['uf'] . '</h6></td>               
        <td><h6>' . $registro2['ubicacion'] . '</h6></td>   
        <td class="active"><h6>' . number_format($registro2['CDI'], 0, '.', ',') . '</h6></td>
        <td class="success"><h6>' . number_format($registro2['BODEGA'], 0, '.', ',') . '</h6></td>
        <td class="warning"><h6>' . number_format($registro2['venta'], 0, '.', ',') . '</h6></td>         
        <td class="danger"><h6>' . number_format($registro2['pedido'], 0, '.', ',') . '</h6></td>
      </tr>';
        }
    } else {
        echo '<tr><td colspan="13"><div class="alert alert-danger">
            <strong>NO SE ENCONTRARON RESULTADOS</strong>
            </div></td></tr>';
    }
    echo '</table>';
}