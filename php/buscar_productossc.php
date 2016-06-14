<?php
session_start();
include("conexion.php");
$bodega = $_GET['bodega'];
$tipo = $_GET['tipo'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];

switch ($tipo) {
    case '80':
        $query = "SELECT
        d.TIPOTRA03 AS tipo,
        p.codbar01 AS codigo,
        p.desprod01 AS titulo,
        e.razon AS editorial,
        a.nombres AS autor,
        ct.categoria AS categoria,
        ct.segmento AS segmento,
        ct.final AS final,
        pr.nomcte01 AS provedor,
        (SELECT pa.nomtab FROM paises pa WHERE pr.loccte01 = pa.codtab) AS PAIS,
        idiomas.nomtab as idioma,
        Sum(d.CANTID03) AS cantidad,
        Sum(d.VALOR03) AS costo,
        Sum(d.desctotvta03+d.DESCVTA03+d.desctotfp03) AS DESCUENTO,
        Sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03-d.desctotfp03) AS VENTANET,
        sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03-d.desctotfp03) - sum(d.PU03) AS utilidad,
        MONTH(d.FECMOV03) AS mes,
        YEAR(d.FECMOV03) AS anio,
        bodegas.nombre AS bodega
        FROM
        factura_detalle AS d
        INNER JOIN factura_cabecera AS c ON d.NOCOMP03 = c.nofact31
        INNER JOIN maepro AS p ON p.codprod01 = d.CODPROD03
        LEFT JOIN autores AS a ON p.infor01 = a.codigo
        LEFT JOIN editoriales AS e ON p.infor02 = e.codigo
        LEFT JOIN categorias_sc AS ct ON p.catprod01 = ct.codigo
        LEFT JOIN provedores AS pr ON p.proved101 = pr.coddest01
        INNER JOIN bodegas ON d.bodega = bodegas.cod_local
        LEFT JOIN idiomas ON p.infor03 = idiomas.codtab
        WHERE d.TIPOTRA03 = '80' AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND d.bodega in ($bodega) AND
        c.cvanulado31 <> '9' AND bodegas.estado = '1'
        GROUP BY d.bodega,YEAR(d.FECMOV03),MONTH(d.FECMOV03),d.CODPROD03
        ORDER BY d.bodega,YEAR(d.FECMOV03),MONTH(d.FECMOV03)";
        echo '<table class="table table-striped table-condensed table-hover">
          <tr>
                <th>CODIGO</th>
                <th>TITULO</th>
                <th>EDITORIAL</th>
                <th>AUTOR</th>
                <th>CATEGORIA</th>
                <th>SEGMENTO</th>
                <th>FINAL</th>
                <th>PROVEDOR</th>  
                <th>PAIS</th>   
                <th>IDIOMA</th>
                <th>CANTIDAD</th>
                <th>COSTO</th>
                <th>DESCUENTO</th>
                <th>VENTA</th>  
                <th>UTILIDAD</th> 
                <th>MES</th>
                <th>AÑO</th>  
                <th>BODEGA</th> 
            </tr>';
        $resul = mysql_query($query, $conexion);
        if (mysql_num_rows($resul) > 0) {
            while ($row = mysql_fetch_array($resul)) {
                echo '<tr>
                <td>' . $row['codigo'] . '</td>
                <td>' . $row['titulo'] . '</td>  
                <td>' . $row['editorial'] . '</td>
                <td>' . $row['autor'] . '</td>
                <td>' . $row['categoria'] . '</td>
                <td>' . $row['segmento'] . '</td>
                <td>' . $row['final'] . '</td>
                <td>' . $row['provedor'] . '</td>
                <td>' . $row['PAIS'] . '</td>
                <td>' . $row['idioma'] . '</td>
                <td>' . number_format($row['cantidad'], 0, '.',',') . '</td>
                <td>' . number_format($row['costo'], 2, '.',',') . '</td>
                <td>' . number_format($row['DESCUENTO'], 2, '.',',') . '</td>
                <td>' . number_format($row['VENTANET'], 2, '.',',') . '</td>
                <td>' . number_format($row['utilidad'], 2, '.',',') . '</td>
                <td>' . $row['mes'] . '</td>
                <td>' . $row['anio'] . '</td>
                <td>' . $row['bodega'] . '</td>
                </tr>';
            }
        } else {
            echo '<tr>
            <td colspan="18">No se encontraron resultados</td>
      </tr>';
        }
        echo '</table>';
        mysql_free_result($resul);
        mysql_close($conexion);
        break;
    case '22':
        $query = "SELECT
        d.TIPOTRA03 AS tipo,
        p.codbar01 AS codigo,
        p.desprod01 AS titulo,
        e.razon AS editorial,
        a.nombres AS autor,
        ct.categoria AS categoria,
        ct.segmento AS segmento,
        ct.final AS final,
        pr.nomcte01 AS provedor,
        (SELECT pa.nomtab FROM paises pa WHERE pr.loccte01 = pa.codtab) AS PAIS,
        idiomas.nomtab as idioma,
        Sum(d.CANTID03) AS cantidad,
        Sum(d.VALOR03) AS costo,
        Sum(d.desctotvta03+d.DESCVTA03+d.desctotfp03) AS DESCUENTO,
        Sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03-d.desctotfp03) AS VENTANET,
        sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03-d.desctotfp03) - sum(d.PU03) AS utilidad,
        MONTH(d.FECMOV03) AS mes,
        YEAR(d.FECMOV03) AS anio,
        bodegas.nombre AS bodega
        FROM
        factura_detalle AS d
        INNER JOIN maepro AS p ON p.codprod01 = d.CODPROD03
        LEFT JOIN autores AS a ON p.infor01 = a.codigo
        LEFT JOIN editoriales AS e ON p.infor02 = e.codigo
        LEFT JOIN categorias_sc AS ct ON p.catprod01 = ct.codigo
        LEFT JOIN provedores AS pr ON p.proved101 = pr.coddest01
        INNER JOIN bodegas ON d.bodega = bodegas.cod_local
        LEFT JOIN idiomas ON p.infor03 = idiomas.codtab
        WHERE
        d.TIPOTRA03 = '22' AND
        d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND
        d.bodega IN ($bodega) AND d.cvanulado03 = 'N' AND
        bodegas.estado = '1'
        GROUP BY d.bodega,YEAR(d.FECMOV03),MONTH(d.FECMOV03),d.CODPROD03
        ORDER BY d.bodega,YEAR(d.FECMOV03),MONTH(d.FECMOV03)";
        echo '<table class="table table-striped table-condensed table-hover">
          <tr>
               <th>CODIGO</th>
                <th>TITULO</th>
                <th>EDITORIAL</th>
                <th>AUTOR</th>
                <th>CATEGORIA</th>
                <th>SEGMENTO</th>
                <th>FINAL</th>
                <th>PROVEDOR</th>  
                <th>PAIS</th>   
                <th>IDIOMA</th>
                <th>CANTIDAD</th>
                <th>COSTO</th>
                <th>DESCUENTO</th>
                <th>VENTA</th>  
                <th>UTILIDAD</th> 
                <th>MES</th>
                <th>AÑO</th>  
                <th>BODEGA</th>
            </tr>';
        $resul = mysql_query($query, $conexion);
        if (mysql_num_rows($resul) > 0) {
            while ($row = mysql_fetch_array($resul)) {
                echo '<tr>
                <td>' . $row['codigo'] . '</td>
                <td>' . $row['titulo'] . '</td>  
                <td>' . $row['editorial'] . '</td>
                <td>' . $row['autor'] . '</td>
                <td>' . $row['categoria'] . '</td>
                <td>' . $row['segmento'] . '</td>
                <td>' . $row['final'] . '</td>
                <td>' . $row['provedor'] . '</td>
                <td>' . $row['PAIS'] . '</td>
                <td>' . $row['idioma'] . '</td>
                <td>' . number_format($row['cantidad'], 0, '.',',') . '</td>
                <td>' . number_format($row['costo'], 2, '.',',') . '</td>
                <td>' . number_format($row['DESCUENTO'], 2, '.',',') . '</td>
                <td>' . number_format($row['VENTANET'], 2, '.',',') . '</td>
                <td>' . number_format($row['utilidad'], 2, '.',',') . '</td>
                <td>' . $row['mes'] . '</td>
                <td>' . $row['anio'] . '</td>
                <td>' . $row['bodega'] . '</td>
                </tr>';
            }
        } else {
            echo '<tr>
            <td colspan="18">No se encontraron resultados</td>
      </tr>';
        }
        echo '</table>';
        mysql_free_result($resul);
        mysql_close($conexion);
        break;
        
    case '30':
        $query = "SELECT
        d.TIPOTRA03 AS tipo,
        p.codbar01 AS codigo,
        p.desprod01 AS titulo,
        e.razon AS editorial,
        a.nombres AS autor,
        ct.categoria AS categoria,
        ct.segmento AS segmento,
        ct.final AS final,
        pr.nomcte01 AS provedor,
        (SELECT pa.nomtab FROM paises pa WHERE pr.loccte01 = pa.codtab) AS PAIS,
        idiomas.nomtab as idioma,
        Sum(d.CANTID03) AS cantidad,
        Sum(d.VALOR03) AS costo,
        Sum(d.desctotvta03+d.DESCVTA03+d.desctotfp03) AS DESCUENTO,
        Sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03-d.desctotfp03) AS VENTANET,
        sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03-d.desctotfp03) - sum(d.PU03) AS utilidad,
        MONTH(d.FECMOV03) AS mes,
        YEAR(d.FECMOV03) AS anio,
        bodegas.nombre AS bodega
        FROM
        factura_detalle AS d
        LEFT JOIN maepro AS p ON p.codprod01 = d.CODPROD03
        LEFT JOIN autores AS a ON p.infor01 = a.codigo
        LEFT JOIN editoriales AS e ON p.infor02 = e.codigo
        LEFT JOIN categorias_sc AS ct ON p.catprod01 = ct.codigo
        LEFT JOIN provedores AS pr ON p.proved101 = pr.coddest01
        INNER JOIN bodegas ON d.bodega = bodegas.cod_local
        LEFT JOIN idiomas ON p.infor03 = idiomas.codtab
        WHERE
        d.TIPOTRA03 = '30' AND
        d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND
        d.bodega IN ($bodega) AND d.cvanulado03 = 'N' AND
        bodegas.estado = '1'
        GROUP BY d.bodega,YEAR(d.FECMOV03),MONTH(d.FECMOV03),d.CODPROD03
        ORDER BY d.bodega,YEAR(d.FECMOV03),MONTH(d.FECMOV03)";
        echo '<table class="table table-striped table-condensed table-hover">
          <tr>
               <th>CODIGO</th>
                <th>TITULO</th>
                <th>EDITORIAL</th>
                <th>AUTOR</th>
                <th>CATEGORIA</th>
                <th>SEGMENTO</th>
                <th>FINAL</th>
                <th>PROVEDOR</th>  
                <th>PAIS</th>   
                <th>IDIOMA</th>
                <th>CANTIDAD</th>
                <th>COSTO</th>
                <th>DESCUENTO</th>
                <th>VENTA</th>  
                <th>UTILIDAD</th> 
                <th>MES</th>
                <th>AÑO</th>  
                <th>BODEGA</th>
            </tr>';
        $resul = mysql_query($query, $conexion);
        if (mysql_num_rows($resul) > 0) {
            while ($row = mysql_fetch_array($resul)) {
                echo '<tr>
                <td>' . $row['codigo'] . '</td>
                <td>' . $row['titulo'] . '</td>  
                <td>' . $row['editorial'] . '</td>
                <td>' . $row['autor'] . '</td>
                <td>' . $row['categoria'] . '</td>
                <td>' . $row['segmento'] . '</td>
                <td>' . $row['final'] . '</td>
                <td>' . $row['provedor'] . '</td>
                <td>' . $row['PAIS'] . '</td>
                <td>' . $row['idioma'] . '</td>
                <td>' . number_format($row['cantidad'], 0, '.',',') . '</td>
                <td>' . number_format($row['costo'], 2, '.',',') . '</td>
                <td>' . number_format($row['DESCUENTO'], 2, '.',',') . '</td>
                <td>' . number_format($row['VENTANET'], 2, '.',',') . '</td>
                <td>' . number_format($row['utilidad'], 2, '.',',') . '</td>
                <td>' . $row['mes'] . '</td>
                <td>' . $row['anio'] . '</td>
                <td>' . $row['bodega'] . '</td>
                </tr>';
            }
        } else {
            echo '<tr>
            <td colspan="18">No se encontraron resultados</td>
      </tr>';
        }
        echo '</table>';
        mysql_free_result($resul);
        mysql_close($conexion);
        break;
}