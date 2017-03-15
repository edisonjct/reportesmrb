<?php
session_start();
if(!isset($_SESSION['user_session'])){
  header("Location: ../index.php");
}
include("conexion.php");
$doc = $_GET['doc'];
$tipo = $_GET['tipo'];
$grupo = '';
$total = '';
$sqlcabecera = "SELECT
                factura_detalle.NOCOMP03,
                DATE_FORMAT(factura_detalle.FECMOV03,'%b/%d/%Y %h:%i %p'),
                factura_detalle.UID,
                factura_detalle.detalle03,
                usuario.Nombre,
                movimientos_inventario.nom_mov
                FROM
                factura_detalle
                INNER JOIN usuario ON factura_detalle.UID = usuario.id
                INNER JOIN movimientos_inventario ON factura_detalle.TIPOTRA03 = movimientos_inventario.cod_mov
                WHERE TIPOTRA03 = '$tipo' AND NOCOMP03 = '$doc'
                GROUP BY NOCOMP03";
$resulc = mysql_query($sqlcabecera, $conexion);
$rowc = mysql_fetch_array($resulc);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Transaciones Mr Books</title>
        <meta charset="utf-8">
        <link rel="icon" type="image/png" href="../recursos/icono.ico"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">   
        <link rel="stylesheet" href="../css/estilosdoc.css">          
        <script src="../js/jquery.js"></script>
    </head>
    <body>
        <?php
        echo '<table width="100%" height="100px" class="tablec">
            <tr>
                <th rowspan="4"><img src="../recursos/logoMrBooks.png" border="0" width="150" height="65"></th>
                <th colspan="3">T R A N S A C C I O N</th>
                <th>
                    <form id="form1" name="form1" method="post" action="">
                        <a href="../php/buscardocumento01.php?doc=' . $doc . '&tipo=' . $tipo . '"><img src="../recursos/excel.png" width="20" height="20"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="javascript:window.print(); void 0;"><img src="../recursos/printer.png" border="0" width="20" height="20"></a>
                    </form>
                </th>                
            </tr>
            <tr>
                
                <th width="25%">TIPO DE DOCUMENTO:</th>
                <td width="25%">' . $rowc[5] . '</td>
                <th width="25%">NUMERO:</th>
                <td width="25%">' . $rowc[0] . '</td>
            </tr>
            <tr>
                <th width="25%">FECHA:</th>
                <td width="25%">' . $rowc[1] . '</td>
                <th width="25%">USUARIO:</th>
                <td width="25%">' . $rowc[4] . '</td>
            </tr>
            <tr>
                <th width="25%">OBSERVACION:</td>
                <td width="75%" colspan="3">' . $rowc[3] . '</td>
            </tr>
        </table>';

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
                        <tr><th colspan="13">'.$row["provedor"].'</th></tr>
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

        ?>
    </body>
</html>

