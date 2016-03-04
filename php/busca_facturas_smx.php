<?php
include('conexion2.php');

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];

//EJECUTAMOS LA CONSULTA DE BUSQUEDA
$registro = mysql_query("SELECT movpro.TIPOTRA03,movpro.NOCOMP03,movpro.CODPROD03,movpro.FECMOV03,movpro.CANTID03 FROM movpro WHERE movpro.TIPOTRA03 = '80' AND movpro.FECMOV03 BETWEEN '$desde' AND '$hasta'");
//CREAMOS NUESTRA VISTA Y LA DEVOLVEMOS AL AJAX
echo '<table class="table table-striped table-condensed table-hover">
        	<tr>
            	<th width="100">Codigo</th>
			    <th width="300">Nombre</th>
			    <th width="100">Categoria</th>
			    <th width="180">Provedor</th>
			    <th width="80">Pais</th>
                <th width="25">Cantidad</th>                
                <th width="80">Local</th>
                <th width="80">U Fecha</th>
            </tr>';
if(mysql_num_rows($registro)>0){
	while($registro2 = mysql_fetch_array($registro)){
		echo '<tr>
				<td><h6>'.$registro2['TIPOTRA03'].'</h6></td>
				<td><h6>'.$registro2['NOCOMP03'].'</h6></td>
				<td><h6>'.$registro2['CODPROD03'].'</h6></td>
				<td><h6>'.$registro2['FECMOV03'].'</h6></td>
				<td><h6>'.$registro2['CANTID03'].'</h6></td>
				<td><h6>'.$registro2['CANTID03'].'</h6></td>
				<td><h6>'.$registro2['CANTID03'].'</h6></td>
				<td><h6>'.$registro2['CANTID03'].'</h6></td>					
			</tr>';
	}
}else{
	echo '<tr>
				<td colspan="6">No se encontraron resultados</td>
			</tr>';
}
echo '</table>';

//////////////////////////////////////////////////////////
//SE DIBUJA LA SUMATORIA TOTAL
?>