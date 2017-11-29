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

	require_once '../db/pauDAO.php';

	try{

		$cita = json_decode($_POST['cita']);
		
		$fec_cita = date("Y-m-d",strtotime($cita[0]));
		$hora_cita = $cita[2];
		$segundos_horaInicial=strtotime($hora_cita);
		$segundos_minutoAnadir=$cita[3]*60;
		$hora_ter=date("H:i",$segundos_horaInicial+$segundos_minutoAnadir);
		$fec_reg =  date("Y-m-d (H:i:s)", time());
		$ubicacion_age = $cita[4];
		$estado_cita = 1;
		$id_cli = $cita[1];
		$id_estilista = $us;
		
		$dao = new pauDAO();
		$control = $dao->control_cita($fec_cita,$hora_cita,$hora_ter,$id_cli,$id_estilista);
		if ((count($control))==0) {
			$ins_cit = $dao->ins_cit($fec_cita,$hora_cita,$hora_ter,$fec_reg,$ubicacion_age,$estado_cita,$id_cli,$id_estilista);
			
			if (count($ins_cit)>0){
					echo"'Error de base de datos, comuniquese con el administrador'";  
			} else {
				echo"Cita Agendada correctamente"; 
			}
		}else{
			echo"Error, estilista o cliente tienen la HORA OCUPADA. Verifique";
		}


		
	
	} catch (Exception $e) {
		//echo($e);
		echo"'Error, verifique los datos'",  $e->getMessage(); 

	}
?>