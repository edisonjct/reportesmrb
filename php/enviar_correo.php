<!DOCTYPE html>
<html lang="en">
<head>
  <title>MR BOOKS</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" href="../recursos/icono.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">    
    <link rel="stylesheet" type="text/css" href="../css/estilostabla.css">
    <script src="../js/jquery.js"></script>
    <script src="../js/myjava.js"></script>
    <link href="../css/bootstrap.css" rel="stylesheet">
</head>
<body>
<?php 
	require_once ("../PHPMailer/class.phpmailer.php");
	include ("../PHPMailer/class.smtp.php");
	include('conexion.php');
	$DestinatarioEmail	=	"edisonjct@gmail.com";
	$DestinatarioNombre	=	"Edison";
	$TAsunto			=	"Facturas Anuladas";	
	$desde = $_GET['desde'];
	$hasta = $_GET['hasta'];	
	$idpro = $_GET['idpro'];	
	$fecha=date("Y/m/d");
	$txtAsunto=$TAsunto.' - '.$fecha;
	$mail = new PHPMailer(true); // TRUE indica que devolvera exception que nos permiten hacer debug
	$smtp = new SMTP();
	$mail->IsSMTP(); // telling the class to use SMTP

	$registro = mysql_query(
"SELECT
DATE_FORMAT(d.FECMOV03,'%Y/%m/%d') AS FECHA,
d.NOCOMP03 AS FACTURA,
Sum(d.PRECVTA03) AS VENTABTA,
Sum(d.DESCVTA03+d.desctotvta03) AS DESCUENTO,
Sum((((d.PRECVTA03-d.DESCVTA03-d.desctotvta03)*d.iva03)/100)+(d.PRECVTA03-d.DESCVTA03-d.desctotvta03)) AS VENTANETA,
c.ruc31 AS CEDULA,
c.nomcte31 AS NOMBRE,
d.bodega as bodega,
usuario.Nombre AS CAJERO
FROM
factura_detalle AS d
LEFT JOIN factura_cabecera AS c ON d.NOCOMP03 = c.nofact31
LEFT JOIN clientes ON c.nocte31 = clientes.codcte01
LEFT JOIN usuario ON c.UID = usuario.id
WHERE
d.TIPOTRA03 = '80' AND
c.cvanulado31 = '9' AND
d.FECMOV03 BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
GROUP BY d.NOCOMP03
ORDER BY d.FECMOV03 DESC
"
);

	try {
		$mail->Host       = "200.105.227.214";	// Servidor SMTP
		$mail->SMTPDebug  = false;					// Habilita  SMTP debug (Parametros 1/2/3)
		$mail->SMTPAuth   = true;					// Servidor SMTP requiere autenticacion
		$mail->Host       = "200.105.227.214";	// Servidor SMTP (GMAIL - smtp.gmail.com)
		$mail->Port       = 25;						// Puerto SMTP (Puerto GMAIL (26) )
		$mail->SMTPSecure = "tlc";					/*SSL o TLC*/   // Protocolo de Seguridad
		$admin = mysql_query("SELECT nombre,correo,clave FROM correo_administrador");
		while ($ad = mysql_fetch_array($admin)) {
			$user = $ad['correo'];
			$clave = $ad['clave'];
			$nombre = $ad['nombre'];
		}
		$mail->Username   = $user;	// SMTP account username
		$mail->Password   = $clave;				// SMTP account password
		$mail->AddReplyTo($user, $nombre); //Direccion a donde se responde
		$correo = mysql_query("SELECT
			e.correo as correo,	
			e.nombre as nombre,
			e.idpro as programa
				FROM
				envio_correos e
				INNER JOIN menu m ON e.idpro = m.id WHERE m.id = '$idpro'");
			if(mysql_num_rows($correo)>0){
				while($correo2 = mysql_fetch_array($correo)){
					$mail->AddAddress($correo2['correo'], $correo2['nombre']);					
				}
			}

		$mail->AddReplyTo($user, $nombre); //Direccion a donde se responde

		// $mail->addCC("cc@dominio.com.ar");		//Enviar CC (Con Copia)
		// $mail->addBCC("bcc@dominio.com.ar");		//Enviar BCC (Copia Oculta)
		$mail->SetFrom($user, $nombre); //Parametros de la cuenta remitente
		$mail->SetFrom($user, $nombre); //Parametros de la cuenta remitente
		$mail->Subject = $txtAsunto;
		$mail->AltBody = 'Mail optimizado para cliente HTML compatible!'; // Opcional-MsgHTML crea uno alternativo
		$mail->IsHTML(false);
		$body  = '<table class="table table-striped table-condensed table-hover">
        	<tr>
            	<th width="20">FECHA</th>
			    <th width="250">FACTURA</th>
			    <th width="150">VENTABTA</th>
			    <th width="150">DESCUENTO</th>	
			    <th width="150">VENTANETA</th>
			    <th width="100">CEDULA</th>
			    <th width="350">NOMBRE</th>
			    <th width="150">BODEGA</th>	
			    <th width="200">CAJERO</th>	
            </tr>';		
			if(mysql_num_rows($registro)>0){
				while($registro2 = mysql_fetch_array($registro)){
					$body .= '<tr>
						<td><h6>'.$registro2['FECHA'].'</h6></td>
						<td><h6>'.$registro2['FACTURA'].'</h6></td>			
						<td><h6>'.number_format($registro2['VENTABTA'], 2, '.',',').'</h6></td>
						<td><h6>'.number_format($registro2['DESCUENTO'], 2, '.',',').'</h6></td>
						<td><h6><b>'.number_format($registro2['VENTANETA'], 2, '.',',').'</b></h6></td>
						<td><h6>'.$registro2['CEDULA'].'</h6></td>
						<td><h6>'.$registro2['NOMBRE'].'</h6></td>
						<td><h6>'.$registro2['bodega'].'</h6></td>							
						<td><h6>'.$registro2['CAJERO'].'</h6></td>					
						</tr>';
				}
			}
		$body .= '</table>';

		$mail->Body = $body;
		$mail->CharSet ="UTF-8";
		//$mail->AddAttachment('images/salida.png');    // Adjunto		
		$mail->Send();
		echo '<table class="table table-striped table-condensed table-hover">
		<tr><td colspan="6">Enviado Correctamente</td><tr>
		</table>';            
	} catch (phpmailerException $e) {
		echo "A: ".$e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
		echo "B: ".$e->getMessage(); //Boring error messages from anything else!
	}
?>
<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>