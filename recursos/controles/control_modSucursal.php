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
	     
	require_once '../clases/ClaseSucursal.php';
	require_once '../clases/Funciones.php';

	try{
		$id_suc = $_POST['id_suc'];
		$nom = $_POST['nom_suc'];
		$dir = $_POST['dir_suc'];
		$fono = $_POST['fono_suc'];
		if(isset($_POST['vig'])){
			$vig = 1; 
		}else{
			$vig = 0; 
			$fun = new funciones();
			$validar_suc = $fun->vigencia_suc($id_suc);
			//var_dump($validar_suc['citas']);
			if ($validar_suc['citas'] > 0) {
				echo"<script type=\"text/javascript\">alert('Error. La sucursal ".$nom.", aun tiene citas agendadas, para quitar la vigencia debe anularlas y reagendarlas en otra sucursal'); window.location='../paginas_usu/sucursales.php'; </script>";
				goto fin;
			}

		}


		
		$dao = new SucursalDAO($id_suc,$nom,$dir, $fono, $vig); 

		$modificar_suc = $dao->modificar_suc();
			
			if (count($modificar_suc)>0){
			echo"<script type=\"text/javascript\">alert('Error de base de datos, comuniquese con el administrador'); window.location='../paginas_usu/sucursales.php';</script>";  
			} else {
				echo"<script type=\"text/javascript\">alert('Sucursal: ".$nom." modificada correctamente.'); 		window.location='../paginas_usu/sucursales.php';</script>"; 
			}
			fin:
	
	} catch (Exception $e) {
		//echo($e);
		echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/sucursales.php';</script>"; 

	}
?>