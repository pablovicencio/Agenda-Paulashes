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
	     
	require_once '../clases/ClasePersona.php';

	try{
		$id = $_POST['id_usu'];
		$nom = $_POST['nom_usu'];
		$mail = $_POST['mail_usu'];
		$fono = $_POST['fono_usu'];
		$color = $_POST['color_usu'];
		$grupo = $_SESSION['perfil'];
		if(isset($_POST['vig'])){
			$vig = 1; 
		}else{
			$vig = 0; 
		}
		if(isset($_SESSION['super'])){
			$super = 1; 
		}else{
			$super = 0; 
		}
		
		$dao = new UsuarioDAO($nom, $mail, $fono,'', $vig,$super,$color, $grupo); 

		$modificar_usuario = $dao->modificar_usuario($id);
			
			if (count($modificar_usuario)>0){
			echo"<script type=\"text/javascript\">alert('Error de base de datos, comuniquese con el administrador'); window.location='../paginas_usu/index_usuario.php';</script>";  
			} else {
				echo"<script type=\"text/javascript\">alert('Estilista: ".$nom." modificado correctamente.'); 		window.location='../paginas_usu/index_usuario.php';</script>"; 
	}
	
	} catch (Exception $e) {
		echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/index_usuario.php';</script>"; 

	}
?>