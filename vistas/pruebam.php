<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Select Dependiente jQuery Ajax</title>
    <meta charset="utf-8">
    <meta name="author" content="Abrahan Apaza" />
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="procesar.js"></script>
    <?php
      include("../php/conexion.php");
      
      $c=mysql_query("select * from zd_country order by name ASC");
    ?>
    <style></style>
  </head>
<body>
  <div class="span5 offset1">
    <form class="form-horizontal" id="formdependiente">
      <div class="control-group">
        <label class="control-label" for="pais">País</label>
          <div class="controls">
            <select name="pais" class="selectdepend" id="pais">
              <option value="">-Seleccione País-</option>
              <?php while($r= mysql_fetch_object($c)){
                echo "<option value=".$r->id.">".$r->name."</option>";
              } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="provincias">Provincias</label>
            <div class="controls">
              <select name="provincias" class="selectdepend" id="provincias" disabled="disabled">
                <option value="">-Seleccione Provincia-</option>
              </select>
            </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="ciudad">Ciudad</label>
            <div class="controls">
              <select name="ciudad" class="selectdepend" id="ciudad" disabled="disabled">
                <option value="">-Seleccione Municipio-</option>
              </select>
            </div>
        </div>
    </form>
  </div>
</body>
</html>