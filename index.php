<?php
session_start();
if(isset($_SESSION['user_session'])!="") {
	header("Location: vistas/index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SRE3</title>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="recursos/icono.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">    
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> 
    <script src="js/jquery.js"></script>
    <script type="text/javascript" src="js/validation.min.js"></script>
    <link href="css/style.css" rel="stylesheet" type="text/css" media="screen">
    <script type="text/javascript" src="js/script.js"></script>
</head>

<body onload="document.form.user.focus();">    
<div class="signin-form">    
	<div class="container">    
            <form class="form-signin" method="post" id="login-form" name="form">
        <center>
            <img src="recursos/sre.gif" border="0" width="200" height="100">
        <h2 class="form-signin-heading">Ingreso Al Sistema</h2><hr />
        </center>
        <div id="error">
        <!-- error will be shown here ! -->
        </div>
        <div class="form-group">
        <input type="text" class="form-control" placeholder="Usuario" name="user" id="user" />
        <span id="check-e"></span>
        </div>        
        <div class="form-group">
        <input type="password" class="form-control" placeholder="Contraseña" name="password" id="password" />
        </div>
     	<hr />
        <center>
        <div class="form-group">
            <button type="submit" class="btn btn-default" name="btn-login" id="btn-login">
    		<span class="glyphicon glyphicon-log-in"></span> &nbsp; Ingresar
			</button> 
        </div>        
        <p>Todos los Derechos Reservados</p>
        <P>Copyright 2016</p>
        </center>
      </form>
    </div>
</div>    
<script src="js/bootstrap.min.js"></script>
</body>
</html>