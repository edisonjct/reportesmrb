<?php
	session_start();
	require_once 'dbconfig.php';
	if(isset($_POST['btn-login']))
	{
		//$user_name = $_POST['user_name'];
		$user = trim($_POST['user']);
		$user_password = trim($_POST['password']);		
		$password = md5($user_password);		
		try
		{		
			$stmt = $db_con->prepare("SELECT * FROM usuario WHERE Usuario=:Usuario");
			$stmt->execute(array(":Usuario"=>$user));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$count = $stmt->rowCount();			
			if($row['Contrasena']==$password){				
				echo "ok"; // log in
				$_SESSION['user_session'] = $row['Usuario'];
			}
			else{				
				echo "Usuario o contraseña Incorrectas"; // wrong details 
			}				
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

?>