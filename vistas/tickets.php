<?php
session_start();

if(!isset($_SESSION['user_session']))
{
  header("Location: ../index.php");
}
include_once '../php/dbconfig.php';
include('../php/conexion.php');
$stmt = $db_con->prepare("SELECT * FROM usuario WHERE Usuario=:uid");
$stmt->execute(array(":uid"=>$_SESSION['user_session']));
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$perfil = $row['Tipo'];
$nombre = $row['Nombre'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>TICKETERA</title>
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
</br></br>
</br></br>
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
<center>
    <ul class="pagination" id="pagination"></ul>
</center>

<div class="modal fade" id="registra-producto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><a class="btn btn-danger">X</a></button>
              <h4 class="modal-title" id="myModalLabel"><b>Registra o Edita un Ticket</b></h4>
            </div>
            <form id="formulario" class="formulario" onsubmit="return agregaRegistro();">
            <div class="modal-body">
                <table border="0" width="100%">     
                     <tr>
                        <td colspan="2"><input class="hide" type="text" required="required" readonly="readonly" id="id" name="id" /></td>
                        <td><input class="hide" type="text" required="required" readonly="readonly" id="pro" name="pro"/></td>
                     </tr>                
                    <tr>
                        <td>Asunto: </td>                        
                        <td><textarea class="form-control" rows="3" required="required" name="detalle" id="detalle"></textarea></td>
                    </tr>
                    <tr>
                        <td>Area: </td>
                        <td>
                            <select required="required" name="area" id="area" class="form-control">
                            <?php
                            $query= mysql_query("SELECT id,nombre FROM mrbareas");      
                            if(mysql_num_rows($query)>0){
                            while($row = mysql_fetch_array($query))
                                {                                    
                                    echo "<option value=".$row['id'].">".$row['nombre']."</option>\n";
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Solicitante: </td>
                        <td>
                            <select required="required" name="usuario" id="usuario" class="form-control">
                            <?php
                            $query= mysql_query("SELECT id,Nombre,Tipo FROM usuario WHERE Nombre NOT LIKE '%(Provedatos)%' ORDER BY Nombre");      
                            if(mysql_num_rows($query)>0){
                            while($row = mysql_fetch_array($query))
                                {                                    
                                    echo "<option value=".$row['id'].">".$row['Nombre']."</option>\n";
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Grupo: </td>
                        <td>
                            <select required="required" name="grupo" id="grupo" class="form-control">
                            <?php
                            $query= mysql_query("SELECT id,nombre FROM mrbgrupo");      
                            if(mysql_num_rows($query)>0){
                            while($row = mysql_fetch_array($query))
                                {                                    
                                    echo "<option value=".$row['id'].">".$row['nombre']."</option>\n";
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>                    
                    <tr>
                        <td>Estado: </td>
                        <td>
                            <select required="required" name="estado" id="estado" class="form-control">
                            <?php
                            $query= mysql_query("SELECT id,nombre FROM mrbestados");      
                            if(mysql_num_rows($query)>0){
                            while($row = mysql_fetch_array($query))
                                {                                    
                                    echo "<option value=".$row['id'].">".$row['nombre']."</option>\n";
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr> 
                    <tr>
                        <td>Tecnico: </td>
                        <td>
                            <select required="required" name="tecnico" id="tecnico" class="form-control">
                            <?php
                            $query= mysql_query("SELECT id,nombre FROM mrbtecnicos");      
                            if(mysql_num_rows($query)>0){
                            while($row = mysql_fetch_array($query))
                                {                                    
                                    echo "<option value=".$row['id'].">".$row['nombre']."</option>\n";
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr> 
                    <tr>
                        <td colspan="2">
                            <div id="mensaje"></div>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="modal-footer">
                <input type="submit" value="Registrar" class="btn btn-success" id="reg"/>
                <input type="submit" value="Editar" class="btn btn-warning"  id="edi"/>
            </div>
            </form>
          </div>
        </div>
      </div>

<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>