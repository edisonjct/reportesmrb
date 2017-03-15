<?php 
// echo "aqui estoy desde php";
// incluimos la cadena de conexion para que se conecte a la base de datos
include('conexion.php');
//obtenermos los parametros enviados por ajax desde la vista
$bodega = $_GET['bodega'];
$vendedores = $_GET['vendedores'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
//

$sql = "SELECT
YEAR(d.FECMOV03) AS ANIO,
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
ELSE 'Mes Invalido' END AS MES,
t.nomtab AS vendedor,
t.ad8tab as cargo,
b.nombre as bodega,
count(DISTINCT(d.NOCOMP03)) as facturas,
sum(d.CANTID03) as libros,
sum(d.PRECVTA03-d.DESCVTA03-d.desctotvta03)+(ROUND(sum(((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100),2)) as total
FROM
factura_detalle as d
INNER JOIN factura_cabecera as c ON d.NOCOMP03 = c.nofact31
INNER JOIN maetab as t ON c.novend31 = t.codtab
INNER JOIN bodegas as b ON d.bodega = b.cod_local
WHERE (d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59') AND (d.TIPOTRA03 = '80') AND (d.bodega IN ($bodega)) AND
(t.numtab = '73' AND t.codtab != '') AND (c.cvanulado31 != '9') AND (t.codtab IN ($vendedores))
GROUP BY c.novend31,b.nombre,ANIO,MES
ORDER BY ANIO,t.nomtab,MES,b.nombre;";

echo $sql;

// switch (variable) {
// 	case 'value':
// 		# code...
// 		break;
	
// 	default:
// 		# code...
// 		break;
// }



?>