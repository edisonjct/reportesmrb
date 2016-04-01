<?php
	$db_host = "100.100.20.102";
	$db_name = "mrbookspac";
	$db_user = "root";
	$db_pass = "mrbooks";
	try{		
		$db_con = new PDO("mysql:host={$db_host};dbname={$db_name}",$db_user,$db_pass);
		$db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
?>