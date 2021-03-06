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
$id = $row['id'];
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
  
    </form>      
</div>
<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>