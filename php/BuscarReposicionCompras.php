<?php

include('conexion.php');

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$bodega = $_GET['bodega'];
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

echo '<table class="table table-striped table-condensed table-hover table-responsive">
          <tr>
          <th width="10">#</th>
          <th width="100">CODIGO</th>
          <th width="300">TITULO</th>
          <th width="100">CATEGORIA</th>             
          <th width="100">AUTOR</th>   
          <th width="100">EDITORIAL</th>   
          <th width="250">PROVEDOR</th>         
          <th width="250">IMPORTACION</th>
          <th width="100">UBICA</th>
          <th width="200">UFC</th>
          <th width="200">UFT</th>
          <th width="100">CDI</th>
          <th width="100">' . $bodega . '</th>
          <th width="100">C.TRANSF</th>          
          <th width="100">PEDIDO</th>
            </tr>';

if ($bodega == 'TODOS') {
    
} else {
    //BUSCA TODOS LOS CODIGOS EN TODOS LOS LOCALES
    $vaciartablacompras = mysql_query("TRUNCATE tmpstocklocacompras");
    $compras = mysql_query("INSERT INTO tmpstocklocacompras(codpro,stock,bodega)
        SELECT
        i.codprod01,
        i.cantact01,
        i.bodega
            FROM
        INVENTARIO i WHERE i.bodega = '$bodega'");
    $vaciartablatransferencias = mysql_query("TRUNCATE tmpultimatransferencia");
    $trasnferencias = mysql_query("insert into tmpultimatransferencia(codprod,fecha,cantidad,bodega)
        select i.CODPROD03,max(i.FECMOV03),
(select im.CANTID03 from factura_detalle im WHERE im.CODPROD03=i.CODPROD03 AND im.bodega = '$bodega' AND im.TIPOTRA03 = '11' ORDER BY im.FECMOV03 DESC LIMIT 1) as cantid,
i.bodega from factura_detalle i WHERE i.TIPOTRA03 = '11' AND i.bodega = '$bodega' AND i.CODPROD03 GROUP BY i.CODPROD03");
    $vaciartablaimportaciones = mysql_query("TRUNCATE tmpimportaciones");
    $importaciones = mysql_query("INSERT INTO tmpimportaciones(tipodoc,doc,codigo,cantidad,fecha,importacion)
        SELECT
        im.TIPOTRA03,
        im.NOCOMP03,
        im.CODPROD03,
        im.CANTID03,
        max(im.FECMOV03),
        im.NOFACT03
        from factura_detalle im
        WHERE TIPOTRA03 = '30'
        GROUP BY CODPROD03");
    $registro = mysql_query("SELECT
m.codprod01 AS interno,
m.codbar01 AS codigo,
m.desprod01 AS titulo,
categorias.desccate AS categoria,
autores.nombres AS autor,
editoriales.razon AS editorial,
p.nomcte01 AS provedor,
DATE_FORMAT(max(im.fecha),'%Y-%m-%d') AS uf,
im.importacion as codimp,
m.infor08 AS ubicacion,
DATE_FORMAT(utf.fecha,'%Y-%m-%d') as fechaut,
m.cantact01 AS CDI,
st.stock as bodega,
utf.cantidad as cantut,
CASE WHEN CDI > IF (st.stock < utf.cantidad,utf.cantidad-st.stock,0) AND CDI > 0 THEN IF (st.stock < utf.cantidad,utf.cantidad-st.stock,0) ELSE 0 END AS pedido
FROM
maepro AS m
INNER JOIN tmpimportaciones AS im ON im.codigo = m.codprod01
LEFT JOIN tmpstocklocacompras AS st ON st.codpro = m.codprod01
INNER JOIN provedores AS p ON m.proved101 = p.coddest01
LEFT JOIN autores ON m.infor01 = autores.codigo
LEFT JOIN editoriales ON m.infor02 = editoriales.codigo
INNER JOIN categorias ON m.catprod01 = categorias.codcate
INNER JOIN tmpultimatransferencia as utf ON utf.codprod = m.codprod01
WHERE m.cantact01 $signo '$stock' AND im.fecha BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
GROUP BY codprod01
ORDER BY cantact01 DESC"
    );

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
        <td><h6>' . $registro2['codimp'] . '</h6></td>
        <td><h6>' . $registro2['ubicacion'] . '</h6></td>   
        <td><h6>' . $registro2['uf'] . '</h6></td>
        <td><h6>' . $registro2['fechaut'] . '</h6></td>
        <td class="active"><h6>' . number_format($registro2['CDI'], 0, '.', ',') . '</h6></td>
        <td class="success"><h6>' . number_format($registro2['bodega'], 0, '.', ',') . '</h6></td>
        <td class="warning"><h6>' . number_format($registro2['cantut'], 0, '.', ',') . '</h6></td>         
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
?>