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
		$nom = $_POST['nom_usu'];
		$mail = $_POST['mail_usu'];
		$fono = $_POST['fono_usu'];
		$color = $_POST['color_usu'];
		$grupo = $_POST['grupo_usu'];
		$super = 0;
		if(isset($_POST['super_usu'])){
			$super = 1;
		}
		$vig = 1;
		
		$fun = new Funciones(); 
		


		if ($mail != ''){
		$val = $fun->validar_correo($mail);

		if ($val <> ""){
			echo"<script type=\"text/javascript\">alert('El correo ya se encuentra en el sistema, favor utilizar otro correo o restablezca su contraseña'); window.location='../paginas_usu/usuarios.php'; </script>";  
			
			
		}else{
			$nueva_pass = $fun->generaPass();
			$dao = new UsuarioDAO('',$nom, $mail, $fono, md5($nueva_pass),$vig,$super, $color, $grupo); 
		
			$crear_usu = $dao->crear_usuario();
			
			if (count($crear_usu)>0){
			echo"<script type=\"text/javascript\">alert('Error de base de datos, comuniquese con el administrador'); window.location='../paginas_usu/usuarios.php';</script>";    
			} else {
				$enviar_pass = $fun->enviar_correo_pass($nom,$mail,$nueva_pass);
				echo"<script type=\"text/javascript\">alert('Usuario ".$nom." Creado, favor verifique en su correo (Buzon de entrada, correos no deseados o spam) la contraseña para ingresar.'); window.location='../paginas_usu/usuarios.php';		
				</script>"; 
					}
		}}else{
		echo"Error";
	}

	} catch (Exception $e) {
		echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/usuarios.php';</script>"; 



	}
?>