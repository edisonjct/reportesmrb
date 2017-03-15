<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pedidosgs
 *
 * @author EChulde
 */
include('conexion.php');


$proceso = $_GET['proceso'];

$verid = mysql_query("select max(id) as max from mrb_pedidos_gs");
$verid2 = mysql_fetch_array($verid);
$id = $verid2['max'] + 1;

switch ($proceso) {
    case 1:
        $contador = 0;
        $cliente = $_GET['cliente'];

        if ($_FILES['csv']['size'] > 0) {
            $csv = $_FILES['csv']['tmp_name'];
            $handle = fopen($csv, 'r');
            $ultimoid = "";

            while ($data = fgetcsv($handle, 1000, ";", "'")) {
                $vercodigo = "select cantact01 as codigo,precvta01 as pvpv from maepro WHERE codbar01 = '$data[0]'";
                $vercodigo1 = mysql_query($vercodigo);
                $rowcodigo = mysql_fetch_array($vercodigo1);
                $sql = "INSERT INTO mrb_pedidos_gs (id, cliente, codigo, cantidad, pvp, descuento, orden, estado, cantact,precventa) VALUES ('" . $id . "','" . $cliente . "' ,'" . $data[0] . "', '" . $data[1] . "', '" . $data[2] . "', '" . $data[3] . "', '" . $data[4] . "', '1' , '" . $rowcodigo['codigo'] . "' , '" . $rowcodigo['pvpv'] . "');";
                mysql_query($sql);
            }
        }
        ?>
        <table class="table table-striped table-condensed table-hover">
            <tr>
                <th>#</th>
                <th>CODIGO</th>
                <th>TITULO</th>
                <th>EDITORIAL</th>
                <th>PROVEDOR</th>
                <th>CATEGORIA</th>
                <th>ORDEN</th>
                <th>P-GS</th>
                <th>P-MRB</th>
                <th>P-AF</th>
                <th>IVA</th>
                <th>DESC</th>
                <th>PEDIDO</th>
                <th>STOCK</th>
                <th>RACK</th>
            </tr>
            <?php
            $query = "SELECT 
            p.codigo AS codigo,
            editoriales.razon AS editorial,
            m.desprod01 AS titulo,
            p.cantidad AS cantidad,
            p.pvp AS pvpa,
            p.descuento AS descuentoa,
            p.orden AS orden,
            p.cantact AS stock,
            (((p.precventa*m.porciva01)/100)+p.precventa) AS pvp,
            provedores.nomcte01 AS provedor,
            ((((p.precventa*m.porciva01)/100)+p.precventa) - ((((p.precventa*m.porciva01)/100)+p.precventa) * 0.109)) AS pvpafiliado,
            CONCAT(cat.categoria,'-',categorias.desccate) as categoria,
            m.porciva01 as iva,
            m.infor08 as rack
            FROM
            mrb_pedidos_gs AS p
            LEFT JOIN maepro AS m ON p.codigo = m.codbar01
            LEFT JOIN productos_grandes_superficies AS cat ON p.codigo = cat.codprod AND p.cliente = cat.codcte
            LEFT JOIN editoriales ON m.infor02 = editoriales.codigo
            LEFT JOIN provedores ON m.proved101 = provedores.coddest01
            LEFT JOIN categorias ON cat.categoria = categorias.codcate
            WHERE p.id = '$id' ORDER BY titulo";
            $result = mysql_query($query);
            while ($row = mysql_fetch_array($result)) {
                $contador = $contador + 1;
                ?>
                <?php if ($row['stock'] === 0) { ?>
                    <tr>
                        <td bgcolor="#bd9f9f"><?php echo $contador; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['codigo']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['titulo']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['editorial']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['provedor']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['categoria']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['orden']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['pvpa'], 2, '.', ','); ?></td>
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['pvp'], 2, '.', ','); ?></td>                    
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['pvpafiliado'], 2, '.', ','); ?></td>
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['iva'], 0, '.', ','); ?></td>
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['descuentoa'], 0, '.', ','); ?></td>                    
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['cantidad'], 0, '.', ','); ?></td>
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['stock'], 0, '.', ','); ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['rack']; ?></td>
                    </tr> 
                <?php } else if ($row['pvpa'] != $row['pvp']) { ?>
                    <tr>
                        <td bgcolor="#bd9f9f"><?php echo $contador; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['codigo']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['titulo']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['editorial']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['provedor']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['categoria']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['orden']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['pvpa'], 2, '.', ','); ?></td>
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['pvp'], 2, '.', ','); ?></td>                    
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['pvpafiliado'], 2, '.', ','); ?></td>
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['iva'], 0, '.', ','); ?></td>
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['descuentoa'], 0, '.', ','); ?></td>                    
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['cantidad'], 0, '.', ','); ?></td>
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['stock'], 0, '.', ','); ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['rack']; ?></td>
                    </tr> 
                <?php } else if ($row['categoria'] == '') { ?>
                    <tr>
                        <td bgcolor="#bd9f9f"><?php echo $contador; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['codigo']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['titulo']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['editorial']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['provedor']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['categoria']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['orden']; ?></td>
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['pvpa'], 2, '.', ','); ?></td>
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['pvp'], 2, '.', ','); ?></td>                    
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['pvpafiliado'], 2, '.', ','); ?></td>
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['iva'], 0, '.', ','); ?></td>
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['descuentoa'], 0, '.', ','); ?></td>                    
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['cantidad'], 0, '.', ','); ?></td>
                        <td bgcolor="#bd9f9f"><?php echo number_format($row['stock'], 0, '.', ','); ?></td>
                        <td bgcolor="#bd9f9f"><?php echo $row['rack']; ?></td>
                    </tr> 
                <?php } else { ?>
                    <tr>
                        <td><?php echo $contador; ?></td>
                        <td><?php echo $row['codigo']; ?></td>
                        <td><?php echo $row['titulo']; ?></td>
                        <td><?php echo $row['editorial']; ?></td>
                        <td><?php echo $row['provedor']; ?></td>
                        <td><?php echo $row['categoria']; ?></td>
                        <td><?php echo $row['orden']; ?></td>
                        <td><?php echo number_format($row['pvpa'], 2, '.', ','); ?></td>
                        <td><?php echo number_format($row['pvp'], 2, '.', ','); ?></td>                    
                        <td><?php echo number_format($row['pvpafiliado'], 2, '.', ','); ?></td>
                        <td><?php echo number_format($row['iva'], 0, '.', ','); ?></td>
                        <td><?php echo number_format($row['descuentoa'], 0, '.', ','); ?></td>                    
                        <td><?php echo number_format($row['cantidad'], 0, '.', ','); ?></td>
                        <td><?php echo number_format($row['stock'], 0, '.', ','); ?></td>
                        <td><?php echo $row['rack']; ?></td>
                    </tr>
                <?php } ?>
                <?php
            }
            ?>    
        </table>
        <?php
        break;
    case 2:
        $id = $id - 1;
        $contador = 0;
        ?>
        <table class="table table-striped table-condensed table-hover">
            <tr>
                <th>#</th>
                <th>CODIGO</th>
                <th>TITULO</th>
                <th>EDITORIAL</th>
                <th>PROVEDOR</th>
                <th>CATEGORIA</th>
                <th>ORDEN</th>
                <th>P-GS</th>
                <th>P-MRB</th>
                <th>P-AF</th>
                <th>IVA</th>
                <th>DESC</th>
                <th>PEDIDO</th>
                <th>STOCK</th>
                <th>RACK</th>
                <th>A-FAC</th>
                <th>A-FACT</th>
            </tr>
            <?php
            $query = "SELECT 
            p.codigo AS codigo,
            editoriales.razon AS editorial,
            m.desprod01 AS titulo,
            p.cantidad AS cantidad,
            p.pvp AS pvpa,
            p.descuento AS descuentoa,
            p.orden AS orden,
            p.cantact AS stock,
            (((p.precventa*m.porciva01)/100)+p.precventa) AS pvp,
            provedores.nomcte01 AS provedor,
            ((((p.precventa*m.porciva01)/100)+p.precventa) - ((((p.precventa*m.porciva01)/100)+p.precventa) * 0.109)) AS pvpafiliado,
            CONCAT(cat.categoria,'-',categorias.desccate) as categoria,
            m.porciva01 as iva,
            m.infor08 as rack,
            CASE WHEN p.cantidad <= p.cantact THEN p.cantidad ELSE p.cantact END AS afac,
            CASE WHEN p.cantidad <= p.cantact THEN ((((p.cantidad*p.precventa)-(((p.cantidad*p.precventa)*p.descuento)/100))*m.porciva01)/100)+((p.cantidad*p.precventa)-(((p.cantidad*p.precventa)*p.descuento)/100)) ELSE ((((p.cantact*p.precventa)-(((p.cantact*p.precventa)*p.descuento)/100))*m.porciva01)/100)+((p.cantact*p.precventa)-(((p.cantact*p.precventa)*p.descuento)/100)) END AS afactu
            FROM
            mrb_pedidos_gs AS p
            INNER JOIN maepro AS m ON p.codigo = m.codbar01
            INNER JOIN productos_grandes_superficies AS cat ON p.codigo = cat.codprod AND p.cliente = cat.codcte
            LEFT JOIN editoriales ON m.infor02 = editoriales.codigo
            LEFT JOIN provedores ON m.proved101 = provedores.coddest01
            INNER JOIN categorias ON cat.categoria = categorias.codcate
            WHERE p.id = '$id'
            ";
            $result = mysql_query($query);
            while ($row = mysql_fetch_array($result)) {
                $contador = $contador + 1;
                ?>
                <tr>
                    <td><?php echo $contador; ?></td>
                    <td><?php echo $row['codigo']; ?></td>
                    <td><?php echo $row['titulo']; ?></td>
                    <td><?php echo $row['editorial']; ?></td>
                    <td><?php echo $row['provedor']; ?></td>
                    <td><?php echo $row['categoria']; ?></td>
                    <td><?php echo $row['orden']; ?></td>
                    <td><?php echo number_format($row['pvpa'], 2, '.', ','); ?></td>
                    <td><?php echo number_format($row['pvp'], 2, '.', ','); ?></td>                    
                    <td><?php echo number_format($row['pvpafiliado'], 2, '.', ','); ?></td>
                    <td><?php echo number_format($row['iva'], 0, '.', ','); ?></td>
                    <td><?php echo number_format($row['descuentoa'], 0, '.', ','); ?></td>                    
                    <td><?php echo number_format($row['cantidad'], 0, '.', ','); ?></td>
                    <td><?php echo number_format($row['stock'], 0, '.', ','); ?></td>
                    <td><?php echo $row['rack']; ?></td>
                    <td><?php echo number_format($row['afac'], 0, '.', ','); ?></td>
                    <td><?php echo number_format($row['afactu'], 2, '.', ','); ?></td>
                </tr>
            <?php } ?>
            <?php
            break;
    }

