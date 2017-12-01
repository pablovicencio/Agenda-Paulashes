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
	    
	    if( !empty($_POST['rut_cli'])){
		$rut = $_POST['rut_cli'];
	    }
    	else{
    		$rut = '0-0';
    	}    
		$nom = $_POST['nom_cli'];
		if( !empty($_POST['mail_cli'])){
		    $mail = $_POST['mail_cli'];
	    }
    	else{
    		$mail = '-';
    	} 
		if( !empty($_POST['fono_cli'])){
		    $fono = $_POST['fono_cli'];
	    }
    	else{
    		$fono = '0';
    	} 
		$vig = 1;
		
		
	  $fun = new Funciones(); 
		



		if ($rut != ''){
		$val = $fun->validar_rut($rut);

		if ($val <> ""){
			echo"<script type=\"text/javascript\">alert('El Rut del cliente ya se encuentra en el sistema, favor verificar'); window.location='../paginas_usu/clientes.php'; </script>";  
			
			
		}else{

		$dao = new ClienteDAO('',$nom,$rut, $mail, $fono, $vig); 

		$crear_cli = $dao->crear_cliente();
			
			if (count($crear_cli)>0){
			echo"<script type=\"text/javascript\">alert('Error de base de datos, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/clientes.php';</script>";  
			} else {
				echo"<script type=\"text/javascript\">alert('Cliente: ".$nom." agregado correctamente.'); 		window.location='../paginas_usu/clientes.php';</script>"; 
	}}}else{
		echo"Error";
	}
	
	} catch (Exception $e) {
		echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/clientes.php';</script>"; 

	}
?>