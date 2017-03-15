<?php
session_start();

if (!isset($_SESSION['user_session'])) {
    header("Location: ../index.php");
}
include("conexion.php");
header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=importaciones.xls");
header("Pragma: no-cache");
header("Expires: 0");
$doc = $_GET['doc'];
$tipo = $_GET['tipo'];
$grupo = '';
$total = '';
?>
<!DOCTYPE html>
<html lang="en">
    <body>
        <?php
        $sql = "SELECT
                m.codprod01 AS interno,
                m.codbar01 AS codigo,
                m.desprod01 AS titulo,
                a.nombres AS autor,
                e.razon AS editorial,
                c.desccate AS categoria,
                liqimp31.nomcte31 AS provedor,
                liqimp31.codcte31 AS codprove,
                liqimp31.cantped31 AS cantidad,
                liqimp31.precuni31 AS costo,
                liqimp31.cif31 AS valor,
                liqimp31.fob31 AS fob,
                (liqimp31.fob31 / liqimp31.cantped31) as fobu,
                d.FACTORPVP03 as factor,
                m.precvta01 as pvp
                FROM
                maepro AS m
                LEFT JOIN autores AS a ON m.infor01 = a.codigo
                LEFT JOIN editoriales AS e ON m.infor02 = e.codigo
                LEFT JOIN categorias AS c ON m.catprod01 = c.codcate
                INNER JOIN movprocdi AS d ON d.CODPROD03 = m.codprod01
                INNER JOIN liqimp31 ON d.CODPROD03 = liqimp31.codprod31
                WHERE c.tipocate = '02' AND d.TIPOTRA03 = '30' AND d.NOCOMP03 = '$doc' AND liqimp31.tipodoc31 = d.NOFACT03
                ORDER BY m.proved101,d.OCURREN03 ASC
                ";
        $resul = mysql_query($sql, $conexion);

        if (mysql_num_rows($resul) > 0) {
            while ($row = mysql_fetch_array($resul)) {
                $grupoant=$grupo;         
                $grupo=$row['codprove'];
                if($grupoant != $grupo){
                    echo '<table width="100%" height="100px" class="tablec">
                        <tr><th colspan="12">'.$row["provedor"].'</th></tr>
                        <tr>               
                            <th>Codigo</th>
                            <th>Titulo</th>
                            <th>Autor</th>
                            <th>Editorial</th>
                            <th>Categoria</th>
                            <th>Provedor</th>
                            <th>Cantidad</th>
                            <th>Pvp.Actual</th>
                            <th>Costo</th>
                            <th>Costo.T</th>
                            <th>Fob</th>
                            <th>Fob.U</th>
                            <th>Factor</th>
                                                      
                        </tr>';
                }                
                echo '<tr>
                        <td>' . $row['codigo'] . '</td>
                        <td>' . $row['titulo'] . '</td>
                        <td>' . $row['autor'] . '</td>
                        <td>' . $row['editorial'] . '</td>
                        <td>' . $row['categoria'] . '</td>
                        <td>' . $row['provedor'] . '</td>
                        <td style="color: #8B241C;"><b>' . number_format($row['cantidad'], 0, '.',',') . '</b></td>
                        <td>' . number_format($row['pvp'], 2, '.',',') . '</td>
                        <td style="color: #1231F9;">' . number_format($row['costo'], 2, '.',',') . '</td>
                        <td style="color: #1231F9;">' . number_format($row['valor'], 2, '.',',') . '</td>
                        <td>' . number_format($row['fob'], 2, '.',',') . '</td>
                        <td>' . number_format($row['fobu'], 2, '.',',') . '</td>
                        <td style="color: #8B241C;"><b>' . number_format($row['factor'], 2, '.',',') . '</b></td>                       
                    </tr>';  
       
            }
            
        } else {
            echo '<tr>
                <td colspan="3">No se encontraron resultados</td>
              </tr>';
        }
        echo '</table>';

        mysql_close($conexion);
        ?>
    </body>
</html>

