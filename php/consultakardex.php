<?php
include('conexion.php');
$codigo = $_GET['codigo'];
$bodega = $_GET['bodega'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$contador = '';

$sqlcodigo = mysql_query("SELECT codprod01,codbar01,desprod01 FROM maepro WHERE codbar01 = '$codigo'");
if (mysql_num_rows($sqlcodigo) > 0) {
    $rowcodigo = mysql_fetch_assoc($sqlcodigo);
    $codprod = $rowcodigo['codprod01'];
    echo $query = "SELECT
        m.bodega as bodega,
        m.TIPOTRA03 as tipo,
        m.NOCOMP03 as numero,
        m.FECMOV03 as fecha,
        CASE WHEN m.TIPOTRA03 IN(80,23,22,55) THEN m.nomdest03 ELSE m.CODDEST03 END as destino,
        m.CANTID03 as cant,
        m.PU03 as costo,
        m.VALOR03 as costototal,
        m.CANTACT03 as cantidadactual,
        m.VALACT03 as valoractual,
        bodegas.nombre as local,
        movimientos_inventario.nom_mov as nombretipo
        FROM
        factura_detalle AS m
        INNER JOIN bodegas ON m.bodega = bodegas.cod_local
        INNER JOIN movimientos_inventario ON m.TIPOTRA03 = movimientos_inventario.cod_mov
        WHERE m.CODPROD03 = '$codprod' AND (m.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59') AND (m.bodega IN ($bodega))
        ORDER BY m.bodega,m.FECMOV03";
    $result = mysql_query($query);
    $grupo = '';
    $cont = 0;
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
            $sql_maxGrupos = "select count(m.CODPROD03) as max from factura_detalle m WHERE m.CODPROD03 = '$codprod' AND (m.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59') AND (m.bodega = '" . $row['bodega'] . "');";
            $result_maxGrupos = mysql_query($sql_maxGrupos);
            $rowsubtotal = mysql_fetch_assoc($result_maxGrupos);
            $maximo_grupos = $rowsubtotal['max'];
            $grupoant = $grupo;
            if($row['tipo'] == '80' || $row['tipo'] == '22' || $row['tipo'] == '23' ){
                $sqldestino = "SELECT clientes.nomcte01 as destino2 from clientes WHERE clientes.codcte01 = '" . $row['destino'] . "' LIMIT 1";
                $result_sqldestino = mysql_query($sqldestino);
                $rowdestino = mysql_fetch_assoc($result_sqldestino);
            } else {
                $sqldestino = "SELECT nomtab as destino2 from destino WHERE codtab = '" . $row['destino'] . "' LIMIT 1";
                $result_sqldestino = mysql_query($sqldestino);
                $rowdestino = mysql_fetch_assoc($result_sqldestino);
            }                        
            $grupo = $row['bodega'];
            if ($grupoant != $grupo) {
                ?>
                <table class="table table-striped table-condensed table-hover table-bordered">         
                    <tr>
                        <th colspan="13"><?php echo $row['local'].' - '.$rowcodigo['desprod01'];?></th>
                    </tr>
                    <tr>                        
                        <th width="15%">TIPO.TRA</th>
                        <th width="10%">NUMERO</th> 
                        <th width="10%">FECHA</th> 
                        <th width="20%">DESTINO</th> 
                        <th>CNT.E</th>
                        <th>CST.E</th> 
                        <th>VAL.E</th>
                        <th>CNT.S</th>
                        <th>CST.S</th> 
                        <th>VAL.E</th>
                        <th>SLD.CNT</th> 
                        <th>C.UNI</th>
                        <th>SLD.VLR</th>
                    </tr>
                    <?php
                    $cont = 0;
                }
                if ($row['tipo'] != '80' && $row['tipo'] != '61' && $row['tipo'] != '93' && $row['tipo'] != '96' && $row['tipo'] != '89' && $row['tipo'] != '54' && $row['tipo'] != '87') {
                    ?>
                    <tr>
                        <td><?php echo $row['tipo'] . '-' . $row['nombretipo']; ?></td>
                        <td><?php echo $row['numero']; ?></td>
                        <td><?php echo $row['fecha']; ?></td>
                        <td><?php echo $rowdestino['destino2']; ?></td>
                        <td class="bg-success"><?php echo number_format($row['cant']) ?></td>
                        <td class="bg-success"><?php echo number_format($row['costo'], 2, '.', ',') ?></td>
                        <td class="bg-success"><?php echo number_format($row['costototal'], 2, '.', ',') ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?php echo number_format($row['cantidadactual']) ?></td>
                        <td><?php echo number_format($row['costo'], 2, '.', ',') ?></td>
                        <td><?php echo number_format($row['valoractual'], 2, '.', ',') ?></td>
                    </tr>
                <?php } else {
                    ?>
                    <tr>
                        <td><?php echo $row['tipo'] . '-' . $row['nombretipo']; ?></td>
                        <td><?php echo $row['numero']; ?></td>
                        <td><?php echo $row['fecha']; ?></td>
                        <td><?php echo $rowdestino['destino2']; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="bg-danger"><?php echo number_format($row['cant']) ?></td>
                        <td class="bg-danger"><?php echo number_format($row['costo'], 2, '.', ',') ?></td>
                        <td class="bg-danger"><?php echo number_format($row['costototal'], 2, '.', ',') ?></td>
                        <td><?php echo number_format($row['cantidadactual']) ?></td>
                        <td><?php echo number_format($row['costo'], 2, '.', ',') ?></td>                                                
                        <td><?php echo number_format($row['valoractual'], 2, '.', ',') ?></td>
                    </tr>
                    <?php
                }
                $cont = $cont + 1;
                if ($cont == $maximo_grupos) {
                    ?>
<!--                    <tr>
                        <th colspan="4">SUBTOTALES</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>-->
                </table>
                <hr>
                <?php
            }
        }
    } else {
        echo "No hay resultados";
    }
} else {
    echo "No Existe el Codigo";
}


    