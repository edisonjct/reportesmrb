<?php

include('conexion.php');
$contador = 0;
$banderatrue = 0;
$banderafalse = 0;
/*if ($_FILES['csv']['size'] > 0) {
    $csv = $_FILES['csv']['tmp_name'];
    $handle = fopen($csv, 'r');
    $vaciartabla = "TRUNCATE mrb_ubicacionestmp";
    mysql_query($vaciartabla);
    while ($data = fgetcsv($handle, 1000, ";", "'")) {
        $contador = $contador + 1;
        if ($data[0] != '' && $data[1] != '' && strlen($data[0]) < 14 && strlen($data[1]) < 6) {
            $vercodigo = "select codbar01 from maepro WHERE codbar01='$data[0]'";
            $vercodigo1 = mysql_query($vercodigo);
            $verubicacion = "SELECT cod_ubicacion FROM ubicacionMRB WHERE cod_ubicacion ='$data[1]'";
            $verubicacion1 = mysql_query($verubicacion);
            $vercodigor = "select codigo from mrb_ubicacionestmp WHERE codigo = '$data[0]'";
            $vercodigo2 = mysql_query($vercodigor);
            if (mysql_num_rows($verubicacion1) > 0) {
                if (mysql_num_rows($vercodigo1) > 0) {
                    if (mysql_num_rows($vercodigo2) == false) {
                        echo "<font color='green'>Subio con exito el codigo $data[0] con la ubicacion $data[1] en la linea $contador</font><br>";
                        $sql = "INSERT INTO mrb_ubicacionestmp (codigo, ubicacion) VALUES('" . $data[0] . "','" . $data[1] . "');";
                        mysql_query($sql);
                        $banderatrue = 1;
                    } else {
                        echo "<font color='red'>Codigo Repetido $data[0] verifique el archivo</font><br>";
                        $banderafalse = 2;
                    }
                } else {
                    echo "<font color='red'>No existe el codigo $data[0] en la linea $contador</font><br>";
                    $banderafalse = 2;
                }
            } else {
                echo "<font color='red'>No existe la ubicacion $data[1] en la linea $contador</font><br>";
                $banderafalse = 2;
            }
        } else {
            echo '<font color="red">Linea ' . $contador . ' No se pudo subir verifique que no este vacia o no cumpla con los parametros</font><br>';
            $banderafalse = 2;
        }
    }
    if (($banderatrue + $banderafalse) == 1) {
        $actualiza = "UPDATE maepro SET infor08 = (select mrb_ubicacionestmp.ubicacion from mrb_ubicacionestmp WHERE mrb_ubicacionestmp.codigo = maepro.codbar01) WHERE maepro.codbar01 = (SELECT mrb_ubicacionestmp.codigo FROM mrb_ubicacionestmp WHERE maepro.codbar01 = mrb_ubicacionestmp.codigo)";
        mysql_query($actualiza);
        $vaciartabla = "TRUNCATE mrb_ubicacionestmp";
        mysql_query($vaciartabla);
        echo '<font color="Green" size="15">SE ACTUALIZO CON EXITO</font><br>';
    } else {
        $vaciartabla = "TRUNCATE mrb_ubicacionestmp";
        mysql_query($vaciartabla);
        echo '<font color="red" size="15">NINGUN REGISTRO ACTUALIZADO</font><br>';
    }
}
 
 */
