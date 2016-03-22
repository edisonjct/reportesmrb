<?php
session_start();

if (!isset($_SESSION['user_session'])) {
    header("Location: ../index.php");
}
include_once '../php/dbconfig.php';
include('../php/conexion.php');
$stmt = $db_con->prepare("SELECT * FROM usuario WHERE Usuario=:uid");
$stmt->execute(array(":uid" => $_SESSION['user_session']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$perfil = $row['Tipo'];
$nombre = $row['Nombre'];
$id = $row['id'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>REPOSICION</title>        
        <meta charset="utf-8">
        <link rel="icon" type="image/png" href="../recursos/icono.ico"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">    
        <link rel="stylesheet" href="../css/style.css">
        <script src="../js/jquery.js"></script>
        <script src="../js/myjava.js"></script>
        <link href="../css/bootstrap.css" rel="stylesheet">
    </head>
    <body>
        <nav role="navigation" class="navbar navbar-default navbar-fixed-top">
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
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">Bienvenido <?php echo $nombre; ?><b class="caret"></b></a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="../php/logout.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Cerrar Sesion</a></li>
                        </ul>
                </ul>
                <?php
                $menu_sql = mysql_query("SELECT menusuario.UsuarioTipo_id as perfil,menusuario.Menu_id as id,menu.Nombre as nombre, menu.Url as url FROM menusuario INNER JOIN menu ON menusuario.Menu_id = menu.id WHERE UsuarioTipo_id = '$perfil' and Padre >= '1'");
                if (mysql_num_rows($menu_sql) > 0) {
                    while ($menu = mysql_fetch_array($menu_sql)) {
                        echo '<ul class="nav navbar-nav navbar-right">
                  <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">' . $menu[2] . '<b class="caret"></b></a>';
                        $submenu_sql = mysql_query("SELECT menusuario.UsuarioTipo_id as perfil,menu.Submenu as submenu,menusuario.Menu_id as id,menu.Nombre as nombre,menu.Url as url FROM menusuario INNER JOIN menu ON menusuario.Menu_id = menu.id WHERE UsuarioTipo_id = '$perfil' and Padre < '1' AND submenu = '" . $menu[1] . "'");
                        if (mysql_num_rows($submenu_sql) > 0) {
                            echo '<ul role="menu" class="dropdown-menu">';
                            while ($submenu = mysql_fetch_array($submenu_sql)) {
                                echo '<li><a href="' . $submenu[4] . '">' . $submenu[3] . '</a></li>';
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
        </br></br></br>
        <form class="form-inline" role="form" method="GET">
            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#seccion1" aria-controls data-toggle="tab" role="tab">Reposicion Por Ventas</a></li>
                    <li role="presentation" ><a href="#seccion2" aria-controls data-toggle="tab" role="tab">Reposicion Por Compras Internacionales</a></li>                    
                </ul>     
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="seccion1">
                        <br>
                        <center>    
                            <div class="form-group">                        
                                <select required="required" id="cb-bodega" class="form-control" data-toggle="tooltip" title="Selecione Bodegas">                        
                                <option value="CDI">CDI</option>
                                <option value="JARDIN">JARDIN</option>
                                <option value="SOL">SOL</option>
                                <option value="CONDADO">CONDADO</option>
                                <option value="SCALA">SCALA</option>
                                <option value="VILLAGE">VILLAGE</option>
                                <option value="QUICENTRO">QUICENTRO</option>
                                <option value="SAN LUIS">SAN LUIS</option>
                                <option value="SAN MARINO">SAN MARINO</option>
                                <option value="CUMBAYA">CUMBAYA</option>
                                <option value="JUAN LEON MERA">JUAN LEON MERA</option>
                                <option value="EVENTOS">EVENTOS</option>
                                <option value="WEB">WEB</option>                                  
                                </select>
                                <select required="required" id="cb-operador" class="form-control" data-toggle="tooltip" title="Selecione Operador">
                                    <option value='1'>>=</option>
                                    <option value='2'>=</option>
                                    <option value='3'><=</option>
                                </select>
                                <input type="text" class="form-control" value="1" id="txt-stock" data-toggle="tooltip" title="Ingrese Stock a Buscar"/>
                            </div>                            
                            <div class="form-group">                    
                                <input type="date" class="form-control" id="bd-ufc" name="ufc" data-toggle="tooltip" title="Selecione Ultima Fecha de Compra"/>
                            </div>
                            <div class="form-group">    
                                <select required="required" id="cb-tipo" class="form-control" data-toggle="tooltip" title="Tipo de Provedor">
                                    <option value="0002">NACIONAL</option>
                                    <option value="0003">CONSIGNADO</option>
                                    <option value="0001">INTERNACIONAL</option>
                                </select>
                            </div>                                
                            <div class="form-group">                                
                                <input type="date" class="form-control" id="bd-desde" name="desde" data-toggle="tooltip" title="Selecione fecha Desde"/>
                                <input type="date" class="form-control" id="bd-hasta" name="hasta" data-toggle="tooltip" title="Selecione fecha Hasta"/>
                            </div>                            
                            <button id="bt-reposicion" class="btn btn-primary" data-toggle="tooltip" title="Buscar">Buscar</button>        
                            <a data-toggle="tooltip" title="Exportar a Excel" target="_blank" href="javascript:reporteReposicionVentas();" class="btn btn-success">Excel</a>
                        </center> 
                        </br>
                        <div class="table-responsive" id="agrega-registros"></div>
                    </div>   
                    <div role="tabpanel" class="tab-pane" id="seccion2">
                        </br>
                        <center>    
                            <div class="form-group">                        
                                <select required="required" id="cb-bodegacomp" class="form-control" data-toggle="tooltip" title="Selecione Bodegas">                        
                                <option value="CDI">CDI</option>
                                <option value="JARDIN">JARDIN</option>
                                <option value="SOL">SOL</option>
                                <option value="CONDADO">CONDADO</option>
                                <option value="SCALA">SCALA</option>
                                <option value="VILLAGE">VILLAGE</option>
                                <option value="QUICENTRO">QUICENTRO</option>
                                <option value="SAN LUIS">SAN LUIS</option>
                                <option value="SAN MARINO">SAN MARINO</option>
                                <option value="CUMBAYA">CUMBAYA</option>
                                <option value="JUAN LEON MERA">JUAN LEON MERA</option>
                                <option value="EVENTOS">EVENTOS</option>
                                <option value="WEB">WEB</option>                                  
                                </select>
                                <select required="required" id="cb-operadorcomp" class="form-control" data-toggle="tooltip" title="Selecione Operador">
                                    <option value='1'>>=</option>
                                    <option value='2'>=</option>
                                    <option value='3'><=</option>
                                </select>
                                <input type="text" class="form-control" value="1" id="txt-stockcomp" data-toggle="tooltip" title="Ingrese Stock CDI a Buscar"/>
                            </div>                                                        
                            <div class="form-group">                                
                                <input type="date" class="form-control" id="bd-desdecomp" name="desde" data-toggle="tooltip" title="Selecione Fecha de Compra Desde"/>
                                <input type="date" class="form-control" id="bd-hastacomp" name="hasta" data-toggle="tooltip" title="Selecione Fecha de Compra Hasta"/>
                            </div>                            
                            <button id="bt-reposicioncompras" class="btn btn-primary" data-toggle="tooltip" title="Buscar">Buscar</button>        
                            <a data-toggle="tooltip" title="Exportar a Excel" target="_blank" href="javascript:reporteReposicionCompras();" class="btn btn-success">Excel</a>
                        </center> 
                        </br>
                        <div class="table-responsive" id="agrega-registros-comp"></div>
                    </div>  
                </div>                
            </div>                           
        </form>                  
        <script src="../js/jquery.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>