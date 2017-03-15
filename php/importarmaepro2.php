<?php

include('conexion.php');
$bodega = $_GET['bodega'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$contador = 0;
$banderatrue = 0;
$banderafalse = 0;

if ($_FILES['csv']['size'] > 0) {
    $csv = $_FILES['csv']['tmp_name'];
    $handle = fopen($csv, 'r');
    $vaciartabla = "TRUNCATE maepro2";
    mysql_query($vaciartabla);
    while ($data = fgetcsv($handle, 1000, ";", "'")) {
        $contador = $contador + 1;
        if ($data[0] != '') {
            $vercodigo = "select codbar01 from maepro WHERE codbar01='$data[0]'";
            $vercodigo1 = mysql_query($vercodigo);
            $vercodigor = "select codbar01 from maepro2 WHERE codbar01 = '$data[0]'";
            $vercodigo2 = mysql_query($vercodigor);
            if (mysql_num_rows($vercodigo1) > 0) {
                if (mysql_num_rows($vercodigo2) == false) {
                    //echo "<font color='green'>Subio con exito el codigo $data[0] $contador</font><br>";
                    $sql = "INSERT INTO maepro2 (codbar01) VALUES('" . $data[0] . "');";
                    mysql_query($sql);
                    $banderatrue = 1;
                } else {
                    //echo "<font color='red'>Codigo Repetido $data[0] verifique el archivo</font><br>";
                    echo '<div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <a href="#" class="alert-link">Codigo Repetido '.$data[0].' verifique el archivo</a>
          </div>';
                    $banderafalse = 2;
                }
            } else {
                //echo "<font color='red'>No existe el codigo $data[0] en la linea $contador</font><br>";
                 echo '<div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <a href="#" class="alert-link">No existe el codigo '.$data[0].' en la linea '.$contador.'</a>
          </div>';
                $banderafalse = 2;
            }
        } else {
            //echo '<font color="red">Linea ' . $contador . ' No se pudo subir verifique que no este vacia o no cumpla con los parametros</font><br>';
            echo '<div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <a href="#" class="alert-link">Linea ' . $contador . ' No se pudo subir verifique que no este vacia o no cumpla con los parametros</a>
          </div>';
            $banderafalse = 2;
        }
    }
    if (($banderatrue + $banderafalse) == 1) {
        $procesa = "SELECT
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
        sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03-d.desctotfp03) - sum((d.VALOR03/d.CANTID03)) AS utilidad,
        CASE WHEN MONTH(d.FECMOV03) = 1 THEN 'Enero'
        WHEN MONTH(d.FECMOV03) = 2 THEN 'Febrero'
        WHEN MONTH(d.FECMOV03) = 3 THEN 'Marzo'
        WHEN MONTH(d.FECMOV03) = 4 THEN 'Abril'
        WHEN MONTH(d.FECMOV03) = 5 THEN 'Mayo'
        WHEN MONTH(d.FECMOV03) = 6 THEN 'Junio'
        WHEN MONTH(d.FECMOV03) = 7 THEN 'Julio'
        WHEN MONTH(d.FECMOV03) = 8 THEN 'Agosto'
        WHEN MONTH(d.FECMOV03) = 9 THEN 'Septiembre'
        WHEN MONTH(d.FECMOV03) = 10 THEN 'Octubre'
        WHEN MONTH(d.FECMOV03) = 11 THEN 'Noviembre'
        WHEN MONTH(d.FECMOV03) = 12 THEN 'Diciembre'
        ELSE 'esto no es un mes' END AS mes,
        YEAR(d.FECMOV03) AS anio,
        bodegas.nombre AS bodega
        FROM
        factura_detalle AS d
        INNER JOIN factura_cabecera AS c ON d.NOCOMP03 = c.nofact31
        INNER JOIN maepro AS p ON p.codprod01 = d.CODPROD03
        INNER JOIN maepro2 ON p.codbar01 = maepro2.codbar01
        LEFT JOIN autores AS a ON p.infor01 = a.codigo
        LEFT JOIN editoriales AS e ON p.infor02 = e.codigo
        LEFT JOIN categorias_sc AS ct ON p.catprod01 = ct.codigo
        LEFT JOIN provedores AS pr ON p.proved101 = pr.coddest01
        INNER JOIN bodegas ON d.bodega = bodegas.cod_local
        LEFT JOIN idiomas ON p.infor03 = idiomas.codtab
        WHERE d.TIPOTRA03 = '80' AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND d.bodega in ($bodega) AND
                c.cvanulado31 <> '9' AND bodegas.estado = '1'
        GROUP BY d.bodega,YEAR(d.FECMOV03),MONTH(d.FECMOV03),d.CODPROD03
        ORDER BY d.bodega,YEAR(d.FECMOV03),MONTH(d.FECMOV03),p.codbar01
        ";
        //mysql_query($procesa);
        //$vaciartabla = "TRUNCATE mrb_ubicacionestmp";
        //mysql_query($vaciartabla);
        //echo '<font color="Green" size="15">SE ACTUALIZO CON EXITO</font><br>';
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
        $resul = mysql_query($procesa, $conexion);
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
                <td>' . number_format($row['cantidad'], 0, '.', ',') . '</td>
                <td>' . number_format($row['costo'], 2, '.', ',') . '</td>
                <td>' . number_format($row['DESCUENTO'], 2, '.', ',') . '</td>
                <td>' . number_format($row['VENTANET'], 2, '.', ',') . '</td>
                <td>' . number_format($row['utilidad'], 2, '.', ',') . '</td>
                <td>' . $row['mes'] . '</td>
                <td>' . $row['anio'] . '</td>
                <td>' . $row['bodega'] . '</td>
                </tr>';
            }
        } else {
            echo '<tr>
            <td colspan="18"><div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <a href="#" class="alert-link">NO SE ENCONTRARON RESULTADOS</a>
          </div></td>
      </tr>';
        }
        echo '</table>';
        mysql_free_result($resul);
        mysql_close($conexion);
    } else {
        $vaciartabla = "TRUNCATE maepro2";
        mysql_query($vaciartabla);
        echo '<div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <a href="#" class="alert-link">NO SE PUDO PROCESAR EL ARCHIVO</a>
          </div>';
    }
}
