<?php
session_start();

if(!isset($_SESSION['user_session']))
{
  header("Location: ../index.php");
}
include_once 'dbconfig.php';
include('conexion.php');
$stmt = $db_con->prepare("SELECT * FROM usuario WHERE Usuario=:uid");
$stmt->execute(array(":uid"=>$_SESSION['user_session']));
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$perfil = $row['Tipo'];
$nombre = $row['Nombre'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$bodega = $_GET['bodega'];
$codigo = $_GET['codigo'];
$barras = '';
$titulo = '';
$autor = '';
$provedor = '';



$reg = mysql_query(
            "SELECT
m.codprod01 as cod,
m.desprod01 as titulo,
m.codbar01 as barras,
d.nomtab as prov,
a.nombres as autor
FROM
maepro AS m
LEFT JOIN destino AS d ON m.infor07 = d.codtab
INNER JOIN autores a ON m.infor01 = a.codigo
WHERE codprod01 = '$codigo'
");

if($row = mysql_fetch_array($reg)){
   //Guardo los datos de la BD en las variables de php
   $barras = $row["barras"];
   $titulo = $row["titulo"];
   $autor = $row["autor"];
   $provedor = $row["prov"];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>MR BOOKS</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" href="../recursos/icono.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">    
    <link rel="stylesheet" href="../css/style.css">    
    <script src="../js/jquery.js"></script>
    <script src="../js/myjava.js"></script>
    <link href="../css/bootstrap.css" rel="stylesheet">
</head>
<body>
<nav role="navigation" class="navbar navbar-default">
        <div class="navbar-header">
            <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="#" class="navbar-brand" id="logo"></a>
        </div>
        <div id="navbarCollapse" class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-left">
          <li>
          <a data-toggle="dropdown" class="dropdown-toggle" href="#">Bienvenido <?php echo $nombre ?><b class="caret"></b></a>
          <ul role="menu" class="dropdown-menu">
              <li><a href="../php/logout.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Cerrar Sesion</a></li>
          </ul>
        </ul>
        <?php 
        $menu_sql = mysql_query("SELECT menusuario.UsuarioTipo_id as perfil,menusuario.Menu_id as id,menu.Nombre as nombre, menu.Url as url FROM menusuario INNER JOIN menu ON menusuario.Menu_id = menu.id WHERE UsuarioTipo_id = '$perfil' and Padre >= '1'");
        if(mysql_num_rows($menu_sql)>0){
            while($menu = mysql_fetch_array($menu_sql)){
              echo '<ul class="nav navbar-nav navbar-right">
                  <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">'.$menu[2].'<b class="caret"></b></a>';
                    $submenu_sql = mysql_query("SELECT menusuario.UsuarioTipo_id as perfil,menu.Submenu as submenu,menusuario.Menu_id as id,menu.Nombre as nombre,menu.Url as url FROM menusuario INNER JOIN menu ON menusuario.Menu_id = menu.id WHERE UsuarioTipo_id = '$perfil' and Padre < '1' AND submenu = '".$menu[1]."'");
                    if(mysql_num_rows($submenu_sql)>0){
                    echo '<ul role="menu" class="dropdown-menu">';
                    while($submenu = mysql_fetch_array($submenu_sql)){                    
                        echo '<li><a href="'.$submenu[4].'">'.$submenu[3].'</a></li>';
                    }
                    echo '</ul>';
                }
                echo '</li>                    
                    </ul>';   
                      }
                    }
              ?>
        </div>
</nav>
  <form class="form-inline" role="form" method="GET">
  <center>
        <div class="form-group">            
            <input type="text" class="form-control" placeholder="Busca Codigo" id="txt-ticket"/>
        </div>
        <div class="form-group">                        
            <select required="required" id="cb-estado" class="form-control">
                <?php
                $query= mysql_query("SELECT id,nombre FROM mrbestados");      
                if(mysql_num_rows($query)>0){
                    while($row = mysql_fetch_array($query))
                    { echo "<option value=".$row['id'].">".$row['nombre']."</option>\n";}
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="pwd">Desde</label>
            <input type="date" class="form-control" id="bd-desde"/>
        </div>
        <div class="form-group">
            <label for="pwd">Hasta</label>
            <input type="date" class="form-control" id="bd-hasta"/>
        </div>        
        <button type="button" id="nuevo-producto" class="btn btn-default">Nuevo</button>
        <button id="bt-ticket" class="btn btn-primary">Buscar</button>
        <a target="_blank" href="javascript:reporteEXCEL();" class="btn btn-success">Excel</a>
      </center>
    </form>      
</div>
</br>
<div class="table-responsive" id="agrega-registros"></div>

<?php 
               $registro = mysql_query(
            "SELECT
    d.TIPOTRA03,
    t.nom_mov,
    d.NOCOMP03,
    DATE_FORMAT(d.FECMOV03,'%Y/%m/%d') AS fecha,
    (CASE WHEN (CODDEST03='0000') THEN c.nomcte01 ELSE des.nomtab END) AS destino,
    (CASE WHEN (TIPOTRA03 IN ('01','02','03','04','06','11','17','20','21','22','23','30','37','40','41','42','42.1','43','46','47','48','49')) THEN d.CANTID03 ELSE '' END) AS 'CNT.ENT',
    (CASE WHEN (TIPOTRA03 IN ('01','02','03','04','06','11','17','20','21','22','23','30','37','40','41','42','42.1','43','46','47','48','49')) THEN d.PU03 ELSE '' END) AS 'COST.ENT',
    (CASE WHEN (TIPOTRA03 IN ('01','02','03','04','06','11','17','20','21','22','23','30','37','40','41','42','42.1','43','46','47','48','49')) THEN d.VALOR03 ELSE '' END) AS 'VAL.ENT',
    (CASE WHEN (TIPOTRA03 IN ('51','52','53','54','55','57','58','61','76','80','81','82','87','88','88.1','89','90','91','92','93','96','97','98')) THEN d.CANTID03 ELSE '' END) AS 'CNT.SAL',
(CASE WHEN (TIPOTRA03 IN ('51','52','53','54','55','57','58','61','76','80','81','82','87','88','88.1','89','90','91','92','93','96','97','98')) THEN d.PU03 ELSE '' END) AS 'COST.SAL',
(CASE WHEN (TIPOTRA03 IN ('51','52','53','54','55','57','58','61','76','80','81','82','87','88','88.1','89','90','91','92','93','96','97','98')) THEN d.VALOR03 ELSE '' END) AS 'VAL.SAL',
d.CANTACT03 AS 'sld.cant',
d.PRECUNI03 AS 'costo.uni',
d.VALACT03 AS 'saldo.valor'
FROM
factura_detalle AS d
LEFT JOIN movimientos_inventario AS t ON d.TIPOTRA03 = t.cod_mov
LEFT JOIN destino des ON d.CODDEST03 = des.codtab
LEFT JOIN clientes c ON d.nomdest03 = c.codcte01
WHERE d.bodega = '$bodega' AND d.CODPROD03 = '$codigo' AND d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
ORDER BY d.FECMOV03 ASC
");

    echo '<table class="table table-striped table-condensed table-hover">
            <tr>
                <th width="200">TIPO</th>
                <th width="250">DOCUMENTO</th>
                <th width="200">FECHA</th>
                <th width="300">DESTINO</th>
                <th width="100">CN.E</th>
                <th width="100">CS.E</th>
                <th width="100">VL.E</th>
                <th width="100">CN.S</th>
                <th width="100">CS.S</th>
                <th width="100">VL.E</th>
                <th width="100">SLD.CNT</th>
                <th width="100">CS.CNT</th>
                <th width="100">SLD.VAL</th>                
            </tr>';
    
     $total_items = 0;
     $total_cant_entrada = 0;
     $total_cost_entrada = 0;
     $total_val_entrada = 0;
     $total_cant_salida = 0;
     $total_cost_salida = 0;
     $total_val_salida = 0;

    if (mysql_num_rows($registro) > 0) {
        while ($registro2 = mysql_fetch_array($registro)) {
            $total_items = $total_items + 1;
            $total_cant_entrada = $total_cant_entrada + $registro2['CNT.ENT'];
            $total_cost_entrada = $total_cost_entrada + $registro2['COST.ENT'];
            $total_val_entrada = $total_val_entrada + $registro2['VAL.ENT'];
            $total_cant_salida = $total_cant_salida + $registro2['CNT.SAL'];
            $total_cost_salida = $total_cost_salida + $registro2['COST.SAL'];
            $total_val_salida = $total_val_salida + $registro2['VAL.SAL'];
            echo '<tr>
                <td><h6>' .$registro2['TIPOTRA03'] ."-".$registro2['nom_mov'] .'</h6></td>
                <td><h6>' . $registro2['NOCOMP03'] . '</h6></td>
                <td><h6>' . $registro2['fecha'] . '</h6></td>
                <td><h6>' . $registro2['destino'] . '</h6></td>                
                <td><h6>' . $registro2['CNT.ENT'] . '</h6></td>
                <td><h6>' . $registro2['COST.ENT'] . '</h6></td>
                <td><h6>' . $registro2['VAL.ENT'] . '</h6></td>
                <td><h6>' . $registro2['CNT.SAL'] . '</h6></td>
                <td><h6>' . $registro2['COST.SAL'] . '</h6></td>
                <td><h6>' . $registro2['VAL.SAL'] . '</h6></td>
                <td><h6>' . $registro2['sld.cant'] . '</h6></td>
                <td><h6>' . $registro2['costo.uni'] . '</h6></td>
                <td><h6>' . $registro2['saldo.valor'] . '</h6></td>
            </tr>';
        }
        echo '<tr>
                  <td colspan="4">Registros = ' . number_format($total_items, 2, '.', ',') . '</td>
                  <td>'.number_format($total_cant_entrada, 0, '.', ',').'</td>
                  <td>'.number_format($total_cost_entrada, 2, '.', ',').'</td>
                  <td>'.number_format($total_val_entrada, 2, '.', ',').'</td>
                  <td>'.number_format($total_cant_salida, 0, '.', ',').'</td>
                  <td>'.number_format($total_cost_salida, 2, '.', ',').'</td>
                  <td>'.number_format($total_val_salida, 2, '.', ',').'</td>                  
              </tr>';
    } else {
        echo '<tr>
                <td colspan="6">No se encontraron resultados</td>
            </tr>';
    }
    echo '</table>';
        ?>

<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>