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
	require_once '../clases/ClaseSucursal.php';

	try{
		$nom = $_POST['nom_suc'];
		$dir = $_POST['dir_suc'];
		$fono = $_POST['fono_suc'];
		$vig = 1;
		
		$fun = new Funciones(); 
		


		if ($nom != '' and $dir!= ''){
		$val = $fun->validar_suc($nom, $dir);

		if ($val != 0){
			echo"<script type=\"text/javascript\">alert('La Sucursal ya se encuentra en el sistema, favor verificar'); window.location='../paginas_usu/sucursales.php'; </script>";  
			
			
		}else{
			$dao = new SucursalDAO('',$nom, $dir, $fono, $vig); 
			
			$crear_suc = $dao->crear_sucursal();
			
			if (count($crear_suc)>0){
			echo"<script type=\"text/javascript\">alert('Error de base de datos, comuniquese con el administrador'); window.location='../paginas_usu/sucursales.php';</script>";    
			} else {
				echo"<script type=\"text/javascript\">alert('Sucursal ".$nom." agregada correctamente.'); window.location='../paginas_usu/sucursales.php';;		
				</script>"; 
					}
		}}else{
		echo"Error";
	}

	} catch (Exception $e) {
		echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/sucursales.php';</script>"; 



	}
?>