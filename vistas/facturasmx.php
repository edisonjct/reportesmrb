<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
<title>MR BOOKS REPORTES</title>
<link href="../css/estilo.css" rel="stylesheet">
<script src="../js/jquery.js"></script>
<script src="../js/myjava.js"></script>
<link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet">
<link href="../bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
</head>
<body>
    <header>CONSOLIDADO DE FACTURAS SUPERMAXI</header>
    <section>
    <table border="0" align="center">
    	<tr>        	                             
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Desde&nbsp;&nbsp;</td>            
            <td><input type="date" id="bd-desde"/></td>
            <td>Hasta&nbsp;&nbsp;</td>
            <td><input type="date" id="bd-hasta"/></td>
            <!-- <td width="50"><button id="bt-buscar" class="btn btn-primary">Buscar</button></td> -->
            <td>&nbsp;&nbsp;</td>            
            <td width="50"><button id="bt-buscar-smx" class="btn btn-primary">Buscar</button></td>
            <td>&nbsp;&nbsp;</td>
            <td width="50"><a target="_blank" href="javascript:reporteEXCEL();" class="btn btn-success">Excel</a></td>
        </tr>
    </table>
    </section>

    <div class="registros" id="agrega-registros"></div>

</body>
</html>
