<?php

include('conexion.php');

$dato = $_GET['dato'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$bodega = $_GET['bodega'];


echo '<table class="table table-striped table-condensed table-hover table-responsive">
          <tr>
                <th width="100">Codigo</th>
                <th width="400">Nombre</th>
                <th width="100">Categoria</th>
                <th width="150">Provedor</th>
                <th width="80">Pais</th>
                <th width="80">Iva</th>
                <th width="80">Cantidad</th>
                <th width="100">Local</th>  
                <th width="80">Fecha UC</th>           
            </tr>';

if (strlen($dato) > 0) {
    $registro = mysql_query(
                "SELECT
                m.codbar01,
                m.desprod01,
                sum(f.CANTID03),
                bodegas.nombre as bodega,
                categorias.desccate,
                provedores.nomcte01,
                (SELECT p.nomtab FROM paises p WHERE provedores.loccte01 = p.codtab) AS PAIS,
                m.porciva01 as iva,
                (SELECT max(DATE_FORMAT(uf.FECMOV03,'%Y-%m-%d'))FROM factura_detalle uf WHERE((uf.CODPROD03 = f.CODPROD03)AND(uf.TIPOTRA03 IN ('30', '01', '49','37')))) AS UFECHA 
              FROM
                factura_detalle f
                LEFT JOIN maepro m ON f.CODPROD03 = m.codprod01
                LEFT JOIN factura_cabecera fa ON f.NOCOMP03 = fa.nofact31
                LEFT JOIN categorias ON m.catprod01 = categorias.codcate
                LEFT JOIN provedores ON m.proved101 = provedores.coddest01
                LEFT JOIN bodegas ON f.bodega = bodegas.cod_local
              WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND m.codbar01 = '$dato' AND f.bodega IN ($bodega)
              GROUP BY f.bodega,f.CODPROD03 ORDER BY sum(f.CANTID03) DESC");
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
WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND m.codbar01 = '$dato' AND f.bodega IN ($bodega)");
//CREAMOS NUESTRA VISTA Y LA DEVOLVEMOS AL AJAX
        if (mysql_num_rows($registro) > 0) {
            while ($registro2 = mysql_fetch_array($registro)) {
                echo '<tr>
        <td><h6>' . $registro2['codbar01'] . '</h6></td>
        <td><h6>' . $registro2['desprod01'] . '</h6></td>
        <td><h6>' . $registro2['desccate'] . '</h6></td>
        <td><h6>' . $registro2['nomcte01'] . '</h6></td>
        <td><h6>' . $registro2['PAIS'] . '</h6></td>
            <td><h6>' . $registro2['iva'] . '</h6></td>
        <td><h6>' . $registro2['sum(f.CANTID03)'] . '</h6></td>
        <td><h6>' . $registro2['bodega'] . '</h6></td>  
        <td><h6>' . $registro2['UFECHA'] . '</h6></td>
      </tr>';
            }
        } else {
            echo '<tr><td colspan="8"><div align="center">No se encontraron resultados</div></td></tr>';
        }
        echo '</table>';
//SE DIBUJA LA SUMATORIA TOTAL
        echo '<table class="table table-striped table-condensed table-hover">';
        if (mysql_num_rows($sumatoria) > 0) {
            while ($sumatoria2 = mysql_fetch_array($sumatoria)) {
                echo '<tr>
        <td width="150"></td> 
        <td width="100"><h4> SUMATORIA TOTAL :   ' . $sumatoria2['sum(f.CANTID03)'] . '</h4></td> 
      </tr>';
            }
        } else {
            echo '<tr><td colspan="8"><div align="center">No se encontraron resultados</div></td></tr>';
        }
        echo '</table>';
} else {
    
    $registro = mysql_query(
                "SELECT
                m.codbar01,
                m.desprod01,
                sum(f.CANTID03),
                bodegas.nombre as bodega,
                categorias.desccate,
                provedores.nomcte01,
                (SELECT p.nomtab FROM paises p WHERE provedores.loccte01 = p.codtab) AS PAIS,
                m.porciva01 as iva,
                (SELECT max(DATE_FORMAT(uf.FECMOV03,'%Y-%m-%d'))FROM factura_detalle uf WHERE((uf.CODPROD03 = f.CODPROD03)AND(uf.TIPOTRA03 IN ('30', '01', '49','37')))) AS UFECHA 
              FROM
                factura_detalle f
                LEFT JOIN maepro m ON f.CODPROD03 = m.codprod01
                LEFT JOIN factura_cabecera fa ON f.NOCOMP03 = fa.nofact31
                LEFT JOIN categorias ON m.catprod01 = categorias.codcate
                LEFT JOIN provedores ON m.proved101 = provedores.coddest01
                LEFT JOIN bodegas ON f.bodega = bodegas.cod_local
              WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND f.bodega IN ($bodega)
              GROUP BY f.bodega,f.CODPROD03 ORDER BY sum(f.CANTID03) DESC");
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
WHERE f.TIPOTRA03 = '80' AND fa.cvanulado31 <> '9' AND f.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND f.bodega IN ($bodega)");
//CREAMOS NUESTRA VISTA Y LA DEVOLVEMOS AL AJAX
        if (mysql_num_rows($registro) > 0) {
            while ($registro2 = mysql_fetch_array($registro)) {
                echo '<tr>
        <td><h6>' . $registro2['codbar01'] . '</h6></td>
        <td><h6>' . $registro2['desprod01'] . '</h6></td>
        <td><h6>' . $registro2['desccate'] . '</h6></td>
        <td><h6>' . $registro2['nomcte01'] . '</h6></td>
        <td><h6>' . $registro2['PAIS'] . '</h6></td>
        <td><h6>' . $registro2['iva'] . '</h6></td>
        <td><h6>' . $registro2['sum(f.CANTID03)'] . '</h6></td>
        <td><h6>' . $registro2['bodega'] . '</h6></td>  
        <td><h6>' . $registro2['UFECHA'] . '</h6></td>
      </tr>';
            }
        } else {
            echo '<tr><td colspan="8"><div align="center">No se encontraron resultados</div></td></tr>';
        }
        echo '</table>';
//SE DIBUJA LA SUMATORIA TOTAL
        echo '<table class="table table-striped table-condensed table-hover">';
        if (mysql_num_rows($sumatoria) > 0) {
            while ($sumatoria2 = mysql_fetch_array($sumatoria)) {
                echo '<tr>
        <td width="150"></td> 
        <td width="100"><h4> SUMATORIA TOTAL :   ' . $sumatoria2['sum(f.CANTID03)'] . '</h4></td> 
      </tr>';
            }
        } else {
            echo '<tr><td colspan="8"><div align="center">No se encontraron resultados</div></td></tr>';
        }
        echo '</table>';
}
