<?php
include('conexion.php');

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$bodega = $_GET['bodega'];
$tipo = $_GET['tipo'];

if ($desde == false){
	echo '<script type="text/javascript">alert("SELECIONE FECHA DESDE");</script>';	
} else if($hasta == false) {
	echo '<script type="text/javascript">alert("SELECIONE FECHA HASTA");</script>';
} else {
	//INICIO CODIGO
	if ($tipo == '80')
{
//EJECUTAMOS LA CONSULTA DE BUSQUEDA
        $registro = mysql_query(
        "SELECT
        COUNT(DISTINCT d.NOCOMP03) as FACTURAS,
        SUM(CANTID03) AS LIBROS,
        ROUND((SUM(PRECVTA03-DESCVTA03-desctotvta03)),2) AS VENTA,
        SUM(VALOR03) as COSTO
        FROM
        factura_detalle AS d
        INNER JOIN factura_cabecera ON d.NOCOMP03 = factura_cabecera.nofact31
        WHERE
        d.TIPOTRA03 = '80' AND
        d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND
        d.bodega = '$bodega' AND factura_cabecera.cvanulado31 <> '9'
"
);

echo '<table class="table table-striped table-condensed table-hover">
            <tr>
                <th width="200">FACTURAS</th>
                <th width="200">LIBROS</th>
                <th width="200">VENTA NETA</th>
                <th width="200">COSTO</th>              
            </tr>';
if(mysql_num_rows($registro)>0){
    while($registro2 = mysql_fetch_array($registro)){
        echo '<tr>
                <td><h6>'.number_format($registro2['FACTURAS']).'</h6></td>
                <td><h6>'.number_format($registro2['LIBROS']).'</h6></td>
                <td><h6>'.number_format($registro2['VENTA'], 2, '.',',').'</h6></td>
                <td><h6>'.number_format($registro2['COSTO'], 2, '.',',').'</h6></td>                
            </tr>';
    }
}else{
    echo '<tr><td colspan="6">No se encontraron resultados</td></tr>';
}
echo '</table>';

}
if ($tipo == '22')
{
    $registro = mysql_query(
        "SELECT
        COUNT(DISTINCT d.NOCOMP03) as NOTAS,
        SUM(CANTID03) AS LIBROS,
        ROUND((SUM(PRECVTA03-DESCVTA03-desctotvta03)),2) AS VENTA,
        SUM(VALOR03) as COSTO
        FROM
        factura_detalle AS d
        WHERE
        d.TIPOTRA03 = '22' AND d.cvanulado03 = 'N' AND
        d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59' AND
        d.bodega = '$bodega' 
        "
);

echo '<table class="table table-striped table-condensed table-hover">
            
            <tr>                
                <th width="200">NOTAS DE CREDITO</th>
                <th width="200">LIBROS</th>
                <th width="200">VENTA NETA</th>
                <th width="200">COSTO</th>              
            </tr>';
if(mysql_num_rows($registro)>0){
    while($registro2 = mysql_fetch_array($registro)){
        echo '<tr>
                <td><h6>'.number_format($registro2['NOTAS']).'</h6></td>
                <td><h6>'.number_format($registro2['LIBROS']).'</h6></td>
                <td><h6>'.number_format($registro2['VENTA'], 2, '.',',').'</h6></td>
                <td><h6>'.number_format($registro2['COSTO'], 2, '.',',').'</h6></td>                
            </tr>';
    }
}else{
    echo '<tr>
                <td colspan="6">No se encontraron resultados</td>
            </tr>';
}
echo '</table>';
}
	//FIN CODIGO	
}

?>