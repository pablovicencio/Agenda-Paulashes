<?php
	session_start();
	//$_SESSION["correo"];
	if( isset($_SESSION['id_usu']) ){
		//Si la sesión esta seteada no hace nada
		$us = $_SESSION['id_usu'];
	}
	else{
		//Si no lo redirige a la pagina index para que inicie la sesion	
		header("location: ../../index.html");
	}          
	
	require_once '../clases/Funciones.php';
	require_once '../clases/ClasePersona.php';

	try{
		$pass = $_POST['pass'];
		$nueva_pass = $_POST['nueva_pass'];
		$nueva_pass1 = $_POST['nueva_pass1'];
		$mail = $_SESSION['correo'];
 		$usuario = $_SESSION['nombre'];
		
		
		$fun = new Funciones();
		if ($pass != ''){

			$old_pass = $fun->old_pass($us);
			
			if (md5($pass) != $old_pass){
			echo"<script type=\"text/javascript\">alert('Error, la contraseña actual no es valida'); window.location='../paginas_usu/cambiar_pass.php';</script>";  
			} else { 
			
			if ($nueva_pass == $nueva_pass1){
			$upd_pass = UsuarioDAO::actualizar_contraseña($mail,md5($nueva_pass));
			$enviar_pass = $fun->correo_upd_pass($mail,$nueva_pass);
			session_destroy();
				echo"<script type=\"text/javascript\">alert('Contraseña actualizada correctamente, favor vuelva a ingresar con su nueva contraseña'); 		window.location='../../index.html';  </script>"; 
				
			}else{
					echo"<script type=\"text/javascript\">alert('Error, las contraseñas nuevas no coinciden, favor vuelta a intentarlo'); window.location='../paginas_usu/cambiar_pass.php';</script>";
					}
	}
		}else{
		echo"Error";
	}	
	} catch (Exception $e) {
		echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/cambiar_pass.php';</script>"; 
		}

?>