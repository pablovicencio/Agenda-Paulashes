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
		$nom = $_POST['nom_usu'];
		$mail = $_POST['mail_usu'];
		$fono = $_POST['fono_usu'];
		$color = $_POST['color_usu'];
		$grupo = $_SESSION['perfil'];
		$super = $_SESSION['super'];
		$vig = 1;
		
		
		$dao = new UsuarioDAO($us,$nom, $mail, $fono,'', $vig,$super,$color, $grupo); 

		$modificar_usuario = $dao->modificar_usuario();
			
			if (count($modificar_usuario)>0){
			echo"<script type=\"text/javascript\">alert('Error de base de datos, comuniquese con el administrador'); window.location='../paginas_usu/actualizar_datos.php';</script>";  
			} else {
				echo"<script type=\"text/javascript\">alert('Estilista: ".$nom." modificado correctamente.'); 		window.location='../paginas_usu/actualizar_datos.php';</script>"; 
	}
	
	} catch (Exception $e) {
		echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/actualizar_datos.php';</script>"; 

	}
?>