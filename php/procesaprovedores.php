<?php

include('conexion.php');

$tipo = $_GET["tip"];

switch ($tipo) {
    case '01':
        if (isset($_GET["idpais"])) {
            $pais = $_GET["idpais"];
            $query = mysql_query("SELECT codcte01 as id,nomcte01 as modelo FROM provedores WHERE codcte01 <> '10' and loccte01 IN ($pais) AND tipcte01 < '0004' order by nomcte01 ");
            echo '<option value="0" >TODOS</option>';
            if (mysql_num_rows($query) > 0) {
                while ($row = mysql_fetch_array($query)) {
                    echo "<option value='" . $row['id'] . "'>" . $row['modelo'] . "</option>\n";
                }
            }
        }
        break;

    case '02':  
         if (isset($_GET["idpais"])) {
            $pais = $_GET["idpais"];
            $query = mysql_query("SELECT codcte01 as id,nomcte01 as modelo FROM provedores WHERE codcte01 <> '10' and loccte01 IN ($pais) AND tipcte01 < '0004' order by nomcte01 ");
            echo '<option value="0" >TODOS</option>';
            if (mysql_num_rows($query) > 0) {
                while ($row = mysql_fetch_array($query)) {
                    echo "<option value='" . $row['id'] . "'>" . $row['modelo'] . "</option>\n";
                }
            }
        }
        break;   
}


