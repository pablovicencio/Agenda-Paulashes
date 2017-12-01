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
		$id_cli = $_POST['id_cli'];
		$rut = $_POST['rut_cli'];
		$nom = $_POST['nom_cli'];
		$mail = $_POST['mail_cli'];
		$fono = $_POST['fono_cli'];
		if(isset($_POST['vig'])){
			$vig = 1; 
		}else{
			$vig = 0; 
		}
		
		$dao = new ClienteDAO($id_cli,$nom,$rut, $mail, $fono, $vig); 

		$modificar_cli = $dao->modificar_cli();
			
			if (count($modificar_cli)>0){
			echo"<script type=\"text/javascript\">alert('Error de base de datos, comuniquese con el administrador'); window.location='../paginas_usu/clientes.php';</script>";  
			} else {
				echo"<script type=\"text/javascript\">alert('Cliente: ".$nom." modificado correctamente.'); 		window.location='../paginas_usu/clientes.php';</script>"; 
	}
	
	} catch (Exception $e) {
		//echo($e);
		echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/clientes.php';</script>"; 

	}
?>